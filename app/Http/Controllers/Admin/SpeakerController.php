<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Models\SpeakerSetting;
use App\Mail\SpeakerApprovalMail;
use App\Services\FeedbackAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SpeakerController extends Controller
{
    public function index(Request $request)
    {
        $query = Speaker::with(['creator', 'approver', 'facultyApprover']);
        
        // Only show speakers that are faculty-approved or created by admin
        // Show all speakers for admin but highlight which ones need admin approval after faculty approval
        
        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } elseif ($sortBy === 'date') {
            $query->orderBy('date', $sortOrder)->orderBy('time', $sortOrder);
        }
        
        $speakers = $query->paginate(15)->appends($request->query());
        
        // Get auto-approve status
        $autoApproveEnabled = SpeakerSetting::isAutoApproveEnabled();
        
        return view('admin.speakers.index', compact('speakers', 'autoApproveEnabled'));
    }

    public function create()
    {
        return view('admin.speakers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'venue' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['approval_status'] = 'pending';
        $validated['faculty_approval_status'] = 'approved'; // Auto-approved for admin-created speakers
        $validated['faculty_approved_by'] = Auth::id();
        $validated['faculty_approved_at'] = now();

        Speaker::create($validated);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker added successfully! Please approve to send feedback email.');
    }

    public function show(Speaker $speaker)
    {
        $speaker->load(['creator', 'approver']);
        return view('admin.speakers.show', compact('speaker'));
    }

    public function approve(Speaker $speaker)
    {
        // Use TemporaryLinkService to generate secure time-limited link
        $linkService = app(\App\Services\TemporaryLinkService::class);
        
        // Generate temporary link (valid for 24 hours by default)
        $linkData = $linkService->generateLink(
            email: $speaker->email,
            type: 'speaker_feedback',
            expiryMinutes: 24 * 60, // 24 hours
            metadata: [
                'speaker_id' => $speaker->id,
                'speaker_name' => $speaker->name,
                'event_date' => $speaker->date->toDateString(),
            ],
            singleUse: true
        );

        // Update speaker approval status
        $speaker->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'feedback_token' => $linkData['token'], // Store token for reference
        ]);

        // Reload the speaker to ensure we have fresh data
        $speaker->refresh();

        // Send approval email with temporary feedback link
        try {
            Mail::to($speaker->email)->send(new SpeakerApprovalMail($speaker, $linkData['url']));
            $message = 'Speaker approved successfully and email sent to ' . $speaker->email . ' (link expires in 24 hours)';
            \Log::info('Email sent successfully to: ' . $speaker->email . ' with temporary link');
        } catch (\Exception $e) {
            $message = 'Speaker approved but email could not be sent. Error: ' . $e->getMessage();
            \Log::error('Email sending failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return redirect()->back()
            ->with('success', $message);
    }

    public function reject(Speaker $speaker)
    {
        $speaker->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Speaker rejected!');
    }

    /**
     * Auto-approve all faculty-approved speakers (bulk action for ease of use)
     */
    public function autoApproveAll()
    {
        // Get all speakers that are faculty-approved but not admin-approved
        $speakers = Speaker::where('faculty_approval_status', 'approved')
            ->where('approval_status', 'pending')
            ->get();

        if ($speakers->isEmpty()) {
            return redirect()->back()
                ->with('info', 'No speakers found that need approval.');
        }

        $linkService = app(\App\Services\TemporaryLinkService::class);
        $approvedCount = 0;
        $emailsSent = 0;
        $emailsFailed = 0;

        foreach ($speakers as $speaker) {
            // Generate temporary link
            $linkData = $linkService->generateLink(
                email: $speaker->email,
                type: 'speaker_feedback',
                expiryMinutes: 24 * 60,
                metadata: [
                    'speaker_id' => $speaker->id,
                    'speaker_name' => $speaker->name,
                    'event_date' => $speaker->date->toDateString(),
                ],
                singleUse: true
            );

            // Update speaker approval status
            $speaker->update([
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'feedback_token' => $linkData['token'],
            ]);

            $approvedCount++;

            // Send approval email
            try {
                Mail::to($speaker->email)->send(new SpeakerApprovalMail($speaker, $linkData['url']));
                $emailsSent++;
            } catch (\Exception $e) {
                $emailsFailed++;
                \Log::error('Auto-approve email failed for ' . $speaker->email . ': ' . $e->getMessage());
            }
        }

        $message = "Auto-approved {$approvedCount} speaker(s). Emails sent: {$emailsSent}, Failed: {$emailsFailed}.";

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Toggle auto-approve setting
     */
    public function toggleAutoApprove()
    {
        $currentStatus = SpeakerSetting::isAutoApproveEnabled();
        $newStatus = !$currentStatus;
        
        SpeakerSetting::set('auto_approve_enabled', $newStatus ? 'true' : 'false');
        
        $message = $newStatus 
            ? 'Auto-approve enabled! Faculty-submitted speakers will be automatically approved.' 
            : 'Auto-approve disabled! Faculty-submitted speakers will require admin approval.';
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * View all feedback responses
     */
    public function feedbackResponses()
    {
        $speakers = Speaker::with(['feedback', 'creator', 'approver'])
            ->where('feedback_submitted', true)
            ->orderBy('approved_at', 'desc')
            ->paginate(20);

        return view('admin.speakers.feedback-responses', compact('speakers'));
    }

    /**
     * View individual speaker feedback
     */
    public function viewFeedback(Speaker $speaker)
    {
        if (!$speaker->feedback_submitted) {
            return redirect()->route('admin.speakers.show', $speaker)
                ->with('error', 'No feedback submitted yet for this speaker.');
        }

        $speaker->load(['feedback', 'creator', 'approver']);

        return view('admin.speakers.feedback-detail', compact('speaker'));
    }

    public function destroy(Speaker $speaker)
    {
        $speaker->delete();

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker deleted successfully!');
    }

    /**
     * Generate NAAC-compliant curriculum feedback analysis report
     */
    public function generateAnalysisReport(FeedbackAnalysisService $analysisService)
    {
        $reportData = $analysisService->generateAnalysisReport();

        if (!$reportData['has_data']) {
            return redirect()->route('admin.speakers.feedback.responses')
                ->with('error', 'No feedback data available for analysis. Please collect feedback first.');
        }

        return view('admin.speakers.analysis-report', $reportData);
    }

    /**
     * Export analysis report as PDF
     */
    public function exportAnalysisReportPdf(FeedbackAnalysisService $analysisService)
    {
        $reportData = $analysisService->generateAnalysisReport();

        if (!$reportData['has_data']) {
            return redirect()->route('admin.speakers.feedback.responses')
                ->with('error', 'No feedback data available for analysis.');
        }

        $pdf = Pdf::loadView('admin.speakers.analysis-report-pdf', $reportData)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $filename = 'Curriculum_Feedback_Analysis_' . 
                   str_replace(['/', '-'], '_', $reportData['title_info']['academic_year']) . 
                   '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
