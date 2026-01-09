<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Models\SpeakerFeedback;
use App\Models\TemporaryLink;
use App\Services\TemporaryLinkService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SpeakerFeedbackController extends Controller
{
    protected TemporaryLinkService $linkService;

    public function __construct(TemporaryLinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    public function show($token)
    {
        // Validate the temporary link
        $temporaryLink = $this->linkService->validateToken($token, 'speaker_feedback');

        if (!$temporaryLink) {
            return view('speaker-feedback.expired', [
                'message' => 'This feedback link has expired or is no longer valid.'
            ]);
        }

        // Get speaker from metadata
        $speakerId = $temporaryLink->metadata['speaker_id'] ?? null;
        
        if (!$speakerId) {
            abort(404, 'Speaker not found');
        }

        $speaker = Speaker::findOrFail($speakerId);

        // Check if feedback already submitted
        if ($speaker->feedback_submitted) {
            return view('speaker-feedback.already-submitted', compact('speaker'));
        }

        return view('speaker-feedback.form', compact('speaker', 'temporaryLink'));
    }

    public function store(Request $request, $token)
    {
        // Validate the temporary link
        $temporaryLink = $this->linkService->validateToken($token, 'speaker_feedback');

        if (!$temporaryLink) {
            return redirect()->back()
                ->with('error', 'This feedback link has expired or is no longer valid.');
        }

        // Get speaker from metadata
        $speakerId = $temporaryLink->metadata['speaker_id'] ?? null;
        $speaker = Speaker::findOrFail($speakerId);

        // Check if feedback already submitted
        if ($speaker->feedback_submitted) {
            return redirect()->route('speaker.feedback.show', $token)
                ->with('error', 'Feedback already submitted!');
        }

        // Validate the 10 curriculum questions
        $validated = $request->validate([
            'q1_content_of_syllabus' => 'required|integer|min:1|max:5',
            'q2_relevance_to_industry' => 'required|integer|min:1|max:5',
            'q3_course_outcomes' => 'required|integer|min:1|max:5',
            'q4_reading_materials' => 'required|integer|min:1|max:5',
            'q5_advanced_topics' => 'required|integer|min:1|max:5',
            'q6_pedagogy' => 'required|integer|min:1|max:5',
            'q7_theory_practical_balance' => 'required|integer|min:1|max:5',
            'q8_assessment_methods' => 'required|integer|min:1|max:5',
            'q9_project_component' => 'required|integer|min:1|max:5',
            'q10_industrial_training' => 'required|integer|min:1|max:5',
            'additional_comments' => 'nullable|string|max:2000',
        ]);

        $validated['speaker_id'] = $speaker->id;

        // Store feedback
        SpeakerFeedback::create($validated);

        // Mark speaker feedback as submitted
        $speaker->update(['feedback_submitted' => true]);

        // Mark temporary link as used
        $this->linkService->markAsUsed($temporaryLink);

        return view('speaker-feedback.thank-you', compact('speaker'));
    }
}
