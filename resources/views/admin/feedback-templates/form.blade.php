@extends('layouts.app')

@section('title', 'Create Feedback Template - SCFMS')
@section('page-title', {{ isset($template) ? 'Edit Feedback Template' : 'Create Feedback Template' }})

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('feedback-templates.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Templates
    </a>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($template) ? 'Edit Feedback Template' : 'Create New Feedback Template' }}
        </h2>

        <form action="{{ isset($template) ? route('feedback-templates.update', $template['id']) : route('feedback-templates.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($template))
                @method('PUT')
            @endif

            <!-- Basic Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Template Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label required">Template Name</label>
                        <input type="text" name="name" value="{{ old('name', $template['name'] ?? '') }}" placeholder="e.g., Course Feedback Form" class="input-field" required>
                        @error('name')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label required">Target Type</label>
                        <select name="target_type" class="input-field" required>
                            <option value="">Select Type</option>
                            <option value="Course" {{ old('target_type', $template['target_type'] ?? '') === 'Course' ? 'selected' : '' }}>Course</option>
                            <option value="Faculty" {{ old('target_type', $template['target_type'] ?? '') === 'Faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="Department" {{ old('target_type', $template['target_type'] ?? '') === 'Department' ? 'selected' : '' }}>Department</option>
                        </select>
                        @error('target_type')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="label">Description</label>
                    <textarea name="description" rows="3" class="input-field" placeholder="Describe the purpose of this feedback template...">{{ old('description', $template['description'] ?? '') }}</textarea>
                    @error('description')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Questions Section -->
            <div class="border-b pb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Questions (Minimum 5 Required)</h3>
                    <button type="button" onclick="addQuestion()" class="btn-primary text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Question
                    </button>
                </div>

                <div id="questions-container" class="space-y-4">
                    @php
                        $questions = isset($template) ? $template['questions'] ?? [] : [];
                        $questionCount = max(1, count($questions), 5);
                    @endphp

                    @for($i = 0; $i < $questionCount; $i++)
                        <div class="question-item border rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-sm font-semibold text-gray-600">Question {{ $i + 1 }}</span>
                                @if($i > 0)
                                    <button type="button" onclick="removeQuestion(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="label required">Question Text</label>
                                    <input type="text" name="questions[{{ $i }}][text]" value="{{ $questions[$i]['text'] ?? '' }}" placeholder="e.g., How satisfied are you with the course content?" class="input-field" required>
                                </div>

                                <div>
                                    <label class="label required">Question Type</label>
                                    <select name="questions[{{ $i }}][type]" class="input-field question-type" required onchange="updateQuestionType(this)">
                                        <option value="">Select Type</option>
                                        <option value="rating_5" {{ $questions[$i]['type'] ?? '' === 'rating_5' ? 'selected' : '' }}>Rating 1-5</option>
                                        <option value="rating_10" {{ $questions[$i]['type'] ?? '' === 'rating_10' ? 'selected' : '' }}>Rating 1-10</option>
                                        <option value="text" {{ $questions[$i]['type'] ?? '' === 'text' ? 'selected' : '' }}>Text Comment</option>
                                        <option value="yes_no" {{ $questions[$i]['type'] ?? '' === 'yes_no' ? 'selected' : '' }}>Yes/No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label required">Category</label>
                                    <select name="questions[{{ $i }}][category]" class="input-field" required>
                                        <option value="">Select Category</option>
                                        <option value="Teaching" {{ $questions[$i]['category'] ?? '' === 'Teaching' ? 'selected' : '' }}>Teaching</option>
                                        <option value="Course Content" {{ $questions[$i]['category'] ?? '' === 'Course Content' ? 'selected' : '' }}>Course Content</option>
                                        <option value="Infrastructure" {{ $questions[$i]['category'] ?? '' === 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                        <option value="Engagement" {{ $questions[$i]['category'] ?? '' === 'Engagement' ? 'selected' : '' }}>Engagement</option>
                                        <option value="Assessment" {{ $questions[$i]['category'] ?? '' === 'Assessment' ? 'selected' : '' }}>Assessment</option>
                                    </select>
                                </div>

                                <div>
                                    <div class="flex items-center h-full">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" name="questions[{{ $i }}][mandatory]" value="1" {{ ($questions[$i]['mandatory'] ?? 0) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                                            <span class="ml-3 text-sm font-medium text-gray-700">Make Mandatory</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <p class="text-gray-500 text-sm mt-4">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    A template must have at least 5 questions to be used for feedback collection.
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($template) ? 'Update' : 'Create' }} Template
                </button>
                <a href="{{ route('feedback-templates.index') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function addQuestion() {
    const container = document.getElementById('questions-container');
    const count = container.querySelectorAll('.question-item').length;
    const index = count;

    const html = `
        <div class="question-item border rounded-lg p-4 bg-gray-50">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-semibold text-gray-600">Question ${count + 1}</span>
                <button type="button" onclick="removeQuestion(this)" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label required">Question Text</label>
                    <input type="text" name="questions[${index}][text]" placeholder="e.g., How satisfied are you with the course content?" class="input-field" required>
                </div>

                <div>
                    <label class="label required">Question Type</label>
                    <select name="questions[${index}][type]" class="input-field question-type" required onchange="updateQuestionType(this)">
                        <option value="">Select Type</option>
                        <option value="rating_5">Rating 1-5</option>
                        <option value="rating_10">Rating 1-10</option>
                        <option value="text">Text Comment</option>
                        <option value="yes_no">Yes/No</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label required">Category</label>
                    <select name="questions[${index}][category]" class="input-field" required>
                        <option value="">Select Category</option>
                        <option value="Teaching">Teaching</option>
                        <option value="Course Content">Course Content</option>
                        <option value="Infrastructure">Infrastructure</option>
                        <option value="Engagement">Engagement</option>
                        <option value="Assessment">Assessment</option>
                    </select>
                </div>

                <div>
                    <div class="flex items-center h-full">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="questions[${index}][mandatory]" value="1" class="w-5 h-5 text-blue-600 rounded">
                            <span class="ml-3 text-sm font-medium text-gray-700">Make Mandatory</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function removeQuestion(button) {
    button.closest('.question-item').remove();
}

function updateQuestionType(select) {
    // Can add additional logic here if needed for different question types
    console.log('Question type changed to:', select.value);
}
</script>
@endsection
