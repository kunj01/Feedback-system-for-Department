<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackTemplateController extends Controller
{
    /**
     * Display a listing of feedback templates.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        // Mock data for now
        $templates = [
            [
                'id' => 1, 
                'name' => 'Theory Course Feedback', 
                'description' => 'Standard feedback template for theory courses',
                'target_type' => 'Course',
                'question_count' => 8,
                'created_by' => 'Admin',
                'is_active' => true,
            ],
            [
                'id' => 2, 
                'name' => 'Practical Course Feedback', 
                'description' => 'Feedback template for practical/lab courses',
                'target_type' => 'Course',
                'question_count' => 10,
                'created_by' => 'Admin',
                'is_active' => true,
            ],
        ];

        return view('admin.feedback-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $questionTypes = ['Rating 1-5', 'Rating 1-10', 'Text Comment', 'Yes/No'];
        $categories = ['Teaching', 'Course Content', 'Infrastructure', 'Engagement', 'Assessment'];

        return view('admin.feedback-templates.create', compact('questionTypes', 'categories'));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'target_type' => 'required|in:Course,Faculty,Department',
            'questions' => 'required|array|min:5',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|string',
            'questions.*.is_mandatory' => 'boolean',
            'questions.*.category' => 'required|string',
        ]);

        // Store in database
        // FeedbackTemplate::create($validated);

        return redirect()->route('feedback-templates.index')
            ->with('success', 'Feedback template created successfully.');
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit($id)
    {
        $this->authorize('update', User::class);
        
        $template = [
            'id' => $id,
            'name' => 'Theory Course Feedback',
            'description' => 'Standard feedback template for theory courses',
            'target_type' => 'Course',
            'questions' => [
                ['id' => 1, 'question_text' => 'Course content was clear', 'question_type' => 'Rating 1-5', 'is_mandatory' => true, 'category' => 'Course Content'],
                ['id' => 2, 'question_text' => 'Faculty explained concepts well', 'question_type' => 'Rating 1-5', 'is_mandatory' => true, 'category' => 'Teaching'],
            ]
        ];

        $questionTypes = ['Rating 1-5', 'Rating 1-10', 'Text Comment', 'Yes/No'];
        $categories = ['Teaching', 'Course Content', 'Infrastructure', 'Engagement', 'Assessment'];

        return view('admin.feedback-templates.edit', compact('template', 'questionTypes', 'categories'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'target_type' => 'required|in:Course,Faculty,Department',
            'questions' => 'required|array|min:5',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|string',
            'questions.*.is_mandatory' => 'boolean',
            'questions.*.category' => 'required|string',
        ]);

        // Update in database
        // FeedbackTemplate::findOrFail($id)->update($validated);

        return redirect()->route('feedback-templates.index')
            ->with('success', 'Feedback template updated successfully.');
    }

    /**
     * Clone an existing template.
     */
    public function clone($id)
    {
        $this->authorize('create', User::class);

        // Clone template in database
        // FeedbackTemplate::findOrFail($id)->clone();

        return redirect()->route('feedback-templates.index')
            ->with('success', 'Feedback template cloned successfully.');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        // Delete from database
        // FeedbackTemplate::findOrFail($id)->delete();

        return redirect()->route('feedback-templates.index')
            ->with('success', 'Feedback template deleted successfully.');
    }
}
