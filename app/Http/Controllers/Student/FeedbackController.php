<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Show feedback form
     */
    public function showForm($subjectId, $facultyId)
    {
        try {
            return view('feedback.form', [
                'subjectId' => $subjectId,
                'facultyId' => $facultyId
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing feedback form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load feedback form.');
        }
    }

    /**
     * Submit feedback
     */
    public function submit(Request $request)
    {
        try {
            Log::info('=== FEEDBACK SUBMISSION STARTED ===', [
                'timestamp' => now()->toDateTimeString(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_data' => $request->except(['_token'])
            ]);

            // Validate the request
            $validated = $request->validate([
                'subject_id' => 'required|integer',
                'faculty_id' => 'required|integer',
                'q1' => 'required|integer|min:1|max:5',
                'q2' => 'required|integer|min:1|max:5',
                'q3' => 'required|integer|min:1|max:5',
                'q4' => 'required|integer|min:1|max:5',
                'q5' => 'required|integer|min:1|max:5',
                'q6' => 'required|integer|min:1|max:5',
                'q7' => 'required|integer|min:1|max:5',
                'q8' => 'required|integer|min:1|max:5',
                'overall_rating' => 'required|integer|min:1|max:5',
                'comments' => 'nullable|string|max:1000',
            ]);

            Log::info('✓ Validation passed', ['validated_fields' => array_keys($validated)]);

            // Get authenticated user's student ID
            $user = auth()->user();
            
            if (!$user) {
                Log::error('✗ User not authenticated');
                return back()->with('error', 'Please login to submit feedback.');
            }

            Log::info('✓ User authenticated', ['user_id' => $user->id, 'email' => $user->email]);

            $student = $user->student;
            
            if (!$student) {
                Log::error('✗ Student profile not found', ['user_id' => $user->id]);
                return back()->with('error', 'Student profile not found. Please contact administrator.');
            }

            Log::info('✓ Student found', ['student_id' => $student->id, 'name' => $user->name]);

            // Check if feedback already exists
            $existingFeedback = Feedback::where([
                'student_id' => $student->id,
                'subject_id' => $validated['subject_id'],
                'faculty_id' => $validated['faculty_id'],
            ])->first();

            if ($existingFeedback) {
                Log::warning('✗ Duplicate feedback attempt', [
                    'student_id' => $student->id,
                    'subject_id' => $validated['subject_id'],
                    'faculty_id' => $validated['faculty_id'],
                    'existing_feedback_id' => $existingFeedback->id
                ]);
                return back()->with('error', 'You have already submitted feedback for this faculty.');
            }

            Log::info('✓ No duplicate found, proceeding with creation');

            // Prepare responses array
            $responses = [
                'q1' => (int)$validated['q1'],
                'q2' => (int)$validated['q2'],
                'q3' => (int)$validated['q3'],
                'q4' => (int)$validated['q4'],
                'q5' => (int)$validated['q5'],
                'q6' => (int)$validated['q6'],
                'q7' => (int)$validated['q7'],
                'q8' => (int)$validated['q8'],
            ];

            Log::info('Responses prepared', ['responses' => $responses, 'average' => array_sum($responses) / count($responses)]);

            // Create feedback record in database using transaction
            DB::beginTransaction();
            
            try {
                Log::info('Starting database transaction...');
                
                $feedbackData = [
                    'student_id' => $student->id,
                    'subject_id' => $validated['subject_id'],
                    'faculty_id' => $validated['faculty_id'],
                    'responses' => $responses,
                    'overall_rating' => (int)$validated['overall_rating'],
                    'comments' => $validated['comments'] ?? null,
                ];
                
                Log::info('Creating feedback with data:', $feedbackData);
                
                $feedback = Feedback::create($feedbackData);

                Log::info('Feedback created, committing transaction...', ['feedback_id' => $feedback->id]);
                
                DB::commit();
                
                Log::info('✓✓✓ FEEDBACK CREATED SUCCESSFULLY ✓✓✓', [
                    'feedback_id' => $feedback->id,
                    'student_id' => $student->id,
                    'subject_id' => $validated['subject_id'],
                    'faculty_id' => $validated['faculty_id'],
                    'overall_rating' => $validated['overall_rating'],
                    'has_comments' => !empty($validated['comments']),
                    'timestamp' => $feedback->created_at->toDateTimeString()
                ]);

                // Store in session for UI tracking
                $completedFeedbacks = session()->get('completed_feedbacks', []);
                $key = $validated['subject_id'] . '_' . $validated['faculty_id'];
                $completedFeedbacks[$key] = [
                    'subject_id' => $validated['subject_id'],
                    'faculty_id' => $validated['faculty_id'],
                    'submitted_at' => now()->toDateTimeString(),
                    'feedback_id' => $feedback->id,
                ];
                session()->put('completed_feedbacks', $completedFeedbacks);
                
                Log::info('Session updated with completed feedback');
                Log::info('=== FEEDBACK SUBMISSION COMPLETED ===');

                return redirect()->route('dashboard')->with('success', 'Feedback submitted successfully! Thank you for your input.');
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('✗✗✗ DATABASE ERROR DURING FEEDBACK CREATION ✗✗✗', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('✗ Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('✗✗✗ UNEXPECTED ERROR IN FEEDBACK SUBMISSION ✗✗✗', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to submit feedback. Please try again. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Get all feedback for a student (for debugging)
     */
    public function myFeedback()
    {
        try {
            $user = auth()->user();
            
            if (!$user || !$user->student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            $feedbacks = Feedback::where('student_id', $user->student->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'count' => $feedbacks->count(),
                'feedbacks' => $feedbacks
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching student feedback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
