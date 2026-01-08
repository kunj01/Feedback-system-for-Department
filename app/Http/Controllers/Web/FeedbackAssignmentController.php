<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackAssignmentController extends Controller
{
    /**
     * Display a listing of feedback assignments.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        // Mock data for now
        $assignments = [
            [
                'id' => 1,
                'template_name' => 'Theory Course Feedback',
                'courses' => 'CS101, CS102, CS103',
                'start_date' => '2024-10-01',
                'end_date' => '2024-10-30',
                'status' => 'Active',
                'response_rate' => '65%',
            ],
            [
                'id' => 2,
                'template_name' => 'Practical Course Feedback',
                'courses' => 'CS105, CS106',
                'start_date' => '2024-11-01',
                'end_date' => '2024-11-30',
                'status' => 'Pending',
                'response_rate' => '0%',
            ],
        ];

        return view('admin.feedback-assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $templates = [
            ['id' => 1, 'name' => 'Theory Course Feedback'],
            ['id' => 2, 'name' => 'Practical Course Feedback'],
        ];

        $courses = [
            ['id' => 1, 'code' => 'CS101', 'name' => 'Data Structures'],
            ['id' => 2, 'code' => 'CS102', 'name' => 'Web Development'],
        ];

        return view('admin.feedback-assignments.create', compact('templates', 'courses'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'template_id' => 'required|exists:feedback_templates,id',
            'course_ids' => 'required|array|min:1',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required|date_format:H:i',
            'grace_period' => 'nullable|integer|min:0',
            'send_reminders' => 'boolean',
            'reminder_schedule' => 'nullable|array',
        ]);

        // Store in database
        // FeedbackAssignment::create($validated);

        return redirect()->route('feedback-assignments.index')
            ->with('success', 'Feedback assignment created successfully. Notifications scheduled.');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit($id)
    {
        $this->authorize('update', User::class);
        
        $assignment = [
            'id' => $id,
            'template_id' => 1,
            'course_ids' => [1, 2],
            'start_date' => '2024-10-01',
            'start_time' => '09:00',
            'end_date' => '2024-10-30',
            'end_time' => '23:59',
        ];

        $templates = [['id' => 1, 'name' => 'Theory Course Feedback']];
        $courses = [['id' => 1, 'code' => 'CS101', 'name' => 'Data Structures']];

        return view('admin.feedback-assignments.edit', compact('assignment', 'templates', 'courses'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::class);

        $validated = $request->validate([
            'template_id' => 'required|exists:feedback_templates,id',
            'course_ids' => 'required|array|min:1',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after:start_date',
            'end_time' => 'required|date_format:H:i',
            'grace_period' => 'nullable|integer|min:0',
            'send_reminders' => 'boolean',
            'reminder_schedule' => 'nullable|array',
        ]);

        // Update in database
        // FeedbackAssignment::findOrFail($id)->update($validated);

        return redirect()->route('feedback-assignments.index')
            ->with('success', 'Feedback assignment updated successfully.');
    }

    /**
     * Extend deadline for the specified assignment.
     */
    public function extendDeadline(Request $request, $id)
    {
        $this->authorize('update', User::class);

        $validated = $request->validate([
            'end_date' => 'required|date|after:now',
            'end_time' => 'required|date_format:H:i',
        ]);

        // Update in database
        // FeedbackAssignment::findOrFail($id)->update(['end_date' => $validated['end_date']]);

        return redirect()->route('feedback-assignments.index')
            ->with('success', 'Feedback deadline extended. Notifications will be sent to students.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        // Delete from database
        // FeedbackAssignment::findOrFail($id)->delete();

        return redirect()->route('feedback-assignments.index')
            ->with('success', 'Feedback assignment deleted successfully.');
    }
}
