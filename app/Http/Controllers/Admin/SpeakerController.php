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
        $validated['approval_status'] = 'approved';
        $validated['approved_by'] = Auth::id();
        $validated['approved_at'] = now();

        Speaker::create($validated);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker added and approved successfully!');
    }

    public function show(Speaker $speaker)
    {
        $speaker->load(['creator', 'approver']);
        return view('admin.speakers.show', compact('speaker'));
    }

    public function approve(Speaker $speaker)
    {
        // Generate unique feedback token
        $feedbackToken = Str::random(64);
        
        $speaker->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'feedback_token' => $feedbackToken,
        ]);

        // Generate feedback URL
        $feedbackUrl = route('speaker.feedback.show', ['token' => $feedbackToken]);

        // Send approval email with feedback link
        try {
            Mail::to($speaker->email)->send(new SpeakerApprovalMail($speaker, $feedbackUrl));
            $message = 'Speaker approved successfully and email sent!';
        } catch (\Exception $e) {
            $message = 'Speaker approved but email could not be sent. Error: ' . $e->getMessage();
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

    public function destroy(Speaker $speaker)
    {
        $speaker->delete();

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker deleted successfully!');
    }
}
