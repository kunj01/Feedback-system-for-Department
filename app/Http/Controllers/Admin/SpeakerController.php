<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Mail\SpeakerApprovalMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::with(['creator', 'approver'])
            ->orderBy('approval_status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.speakers.index', compact('speakers'));
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
}
