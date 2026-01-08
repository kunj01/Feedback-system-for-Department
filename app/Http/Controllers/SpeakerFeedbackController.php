<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Models\SpeakerFeedback;
use Illuminate\Http\Request;

class SpeakerFeedbackController extends Controller
{
    public function show($token)
    {
        $speaker = Speaker::where('feedback_token', $token)->firstOrFail();

        if ($speaker->feedback_submitted) {
            return view('speaker-feedback.already-submitted', compact('speaker'));
        }

        return view('speaker-feedback.form', compact('speaker'));
    }

    public function store(Request $request, $token)
    {
        $speaker = Speaker::where('feedback_token', $token)->firstOrFail();

        if ($speaker->feedback_submitted) {
            return redirect()->route('speaker.feedback.show', $token)
                ->with('error', 'Feedback already submitted!');
        }

        $validated = $request->validate([
            'event_quality' => 'required|string',
            'venue_facilities' => 'required|string',
            'hospitality' => 'required|string',
            'overall_experience' => 'required|string',
            'suggestions' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['speaker_id'] = $speaker->id;

        SpeakerFeedback::create($validated);

        $speaker->update(['feedback_submitted' => true]);

        return view('speaker-feedback.thank-you', compact('speaker'));
    }
}
