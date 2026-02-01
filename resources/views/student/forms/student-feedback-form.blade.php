@extends('layouts.app')

@section('title', 'Student Feedback')
@section('page-title', 'Student Feedback Form')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Student Feedback Form</h2>
                    <p class="mt-4 text-sm text-gray-500">
                        Your feedback is valuable and will help us improve the quality of education.
                    </p>
                </div>

                <!-- Error Messages -->
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-red-800 font-medium mb-2">Please correct the following errors:</p>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('forms.submit', $formName ?? 'student-feedback-form') }}" class="space-y-8" id="studentFeedbackForm">
                    @csrf
                    
                    <!-- Hidden field for assignment -->
                    @if(isset($assignment))
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                    @endif

                    <!-- Teacher Selection (for multi-teacher forms) -->
                    @if(isset($allAssignments) && $allAssignments->count() > 1)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Subject and Teacher</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Subject Dropdown -->
                                <div>
                                    <label for="subject_select" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subject <span class="text-red-500">*</span>
                                    </label>
                                    <select id="subject_select" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">-- Select Subject --</option>
                                        @php
                                            $uniqueSubjects = $allAssignments->filter(function($assignment) {
                                                return $assignment->subject_id && $assignment->subject;
                                            })->unique('subject_id');
                                        @endphp
                                        @foreach($uniqueSubjects as $assignment)
                                            <option value="{{ $assignment->subject_id }}">
                                                {{ $assignment->subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Teacher Dropdown -->
                                <div>
                                    <label for="teacher_assignment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teacher <span class="text-red-500">*</span>
                                    </label>
                                    <select name="teacher_assignment_id" id="teacher_assignment_id" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                            disabled>
                                        <option value="">-- Select Teacher --</option>
                                        @foreach($allAssignments as $teacherAssignment)
                                            <option value="{{ $teacherAssignment->id }}"
                                                    data-subject-id="{{ $teacherAssignment->subject_id }}"
                                                    data-status="{{ $teacherAssignment->status }}"
                                                    {{ $teacherAssignment->status === 'completed' ? 'disabled' : '' }}
                                                    class="teacher-option"
                                                    style="color: {{ $teacherAssignment->status === 'completed' ? '#059669' : '#dc2626' }}; font-weight: 500;">
                                                {{ $teacherAssignment->teacher->name ?? 'Unknown' }}
                                                @if($teacherAssignment->status === 'completed')
                                                    - ✓ Submitted
                                                @else
                                                    - ● Remaining
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('teacher_assignment_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const subjectSelect = document.getElementById('subject_select');
                            const teacherSelect = document.getElementById('teacher_assignment_id');
                            const allTeacherOptions = Array.from(teacherSelect.querySelectorAll('.teacher-option'));

                            subjectSelect.addEventListener('change', function() {
                                const selectedSubjectId = this.value;
                                
                                // Reset teacher dropdown
                                teacherSelect.value = '';
                                
                                if (!selectedSubjectId) {
                                    teacherSelect.disabled = true;
                                    allTeacherOptions.forEach(option => option.style.display = 'none');
                                    return;
                                }
                                
                                // Enable teacher dropdown
                                teacherSelect.disabled = false;
                                
                                // Filter teachers by subject
                                allTeacherOptions.forEach(option => {
                                    if (option.dataset.subjectId === selectedSubjectId) {
                                        option.style.display = 'block';
                                        // Auto-select first non-completed teacher
                                        if (!teacherSelect.value && option.dataset.status !== 'completed') {
                                            teacherSelect.value = option.value;
                                        }
                                    } else {
                                        option.style.display = 'none';
                                    }
                                });
                            });
                        });
                        </script>
                    @else
                        <!-- Hidden field for single teacher assignment -->
                        <input type="hidden" name="teacher_assignment_id" value="{{ $assignment->id }}">
                    @endif

                    <!-- Section 1: Your experience as a student in this course -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Your experience as a student in this course</h3>
                        
                        <div class="space-y-6">
                            @php
                            $section1Questions = [
                                ['field' => 'prepare_for_class', 'label' => 'I prepare for class lectures.'],
                                ['field' => 'ask_questions_freely', 'label' => 'I am able to ask questions freely during class.'],
                                ['field' => 'actively_participate', 'label' => 'I actively participate in class.'],
                                ['field' => 'feel_comfortable_sharing', 'label' => 'I feel comfortable sharing my ideas in this course.'],
                                ['field' => 'developing_skills', 'label' => 'I am developing the skills I need in this class.']
                            ];
                            @endphp

                            <!-- Header Row -->
                            <div class="flex flex-row items-center justify-between gap-6 pb-4">
                                <div class="w-1/2"></div>
                                <div class="w-1/2 flex justify-between">
                                    @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $option)
                                    <span class="text-sm font-medium text-gray-700 text-center flex-1">{{ $option }}</span>
                                    @endforeach
                                </div>
                            </div>

                            @foreach($section1Questions as $index => $question)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                <div class="flex flex-row items-center justify-between gap-6">
                                    <!-- Question Text (LEFT) -->
                                    <div class="w-1/2">
                                        <p class="text-base font-medium text-gray-800">
                                            {{ $index + 1 }}. {{ $question['label'] }}
                                            <span class="text-red-500">*</span>
                                        </p>
                                    </div>
                                    
                                    <!-- Radio Options (RIGHT) -->
                                    <div class="w-1/2 flex justify-between">
                                        @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $idx => $option)
                                        <label class="flex items-center justify-center cursor-pointer flex-1">
                                            <input type="radio" 
                                                   name="responses[{{ $question['field'] }}][rating]" 
                                                   value="{{ $option }}" 
                                                   required
                                                   class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 question-radio"
                                                   data-question="{{ $question['field'] }}"
                                                   onchange="handleRatingChange('{{ $question['field'] }}', '{{ $option }}')">
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Reasoning Textbox (Hidden by default, shown only for Strongly Disagree) -->
                                <div id="reasoning_{{ $question['field'] }}" class="mt-4 hidden">
                                    <label class="block text-base font-medium text-gray-700 mb-2">
                                        Please provide reasoning: <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="responses[{{ $question['field'] }}][reasoning]" 
                                              id="reasoning_text_{{ $question['field'] }}"
                                              rows="4"
                                              style="min-height: 100px;"
                                              class="w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white"
                                              placeholder="Explain your rating..."></textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 2: Your experience with the instructor of this course -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Your experience with the instructor of this course</h3>
                        
                        <div class="space-y-6">
                            @php
                            $section2Questions = [
                                ['field' => 'instructor_approachable', 'label' => 'The instructor is approachable/Instructor makes himself/herself available to students in and out of the class.'],
                                ['field' => 'instructor_effective', 'label' => 'Instructor was an effective lecturer/demonstrates knowledge and expertise in the subject matter.'],
                                ['field' => 'presentations_clear', 'label' => 'Presentations of the instructor were clear and organized.'],
                                ['field' => 'instructor_stimulated', 'label' => 'Instructor stimulated student interest/The instructor uses a variety of teaching methods.'],
                                ['field' => 'instructor_used_time', 'label' => 'Instructor effectively used time during class.'],
                                ['field' => 'instructor_introduces_concepts', 'label' => 'The way the instructor introduces new concepts was clear.'],
                                ['field' => 'instructor_positive_environment', 'label' => 'The instructor creates a positive environment in class.'],
                                ['field' => 'instructor_communicates', 'label' => 'The instructor clearly communicate course expectations/requirements and policies.']
                            ];
                            @endphp

                            <!-- Header Row -->
                            <div class="flex flex-row items-center justify-between gap-6 pb-4">
                                <div class="w-1/2"></div>
                                <div class="w-1/2 flex justify-between">
                                    @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $option)
                                    <span class="text-sm font-medium text-gray-700 text-center flex-1">{{ $option }}</span>
                                    @endforeach
                                </div>
                            </div>

                            @foreach($section2Questions as $index => $question)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                <div class="flex flex-row items-center justify-between gap-6">
                                    <!-- Question Text (LEFT) -->
                                    <div class="w-1/2">
                                        <p class="text-base font-medium text-gray-800">
                                            {{ $index + 1 }}. {{ $question['label'] }}
                                            <span class="text-red-500">*</span>
                                        </p>
                                    </div>
                                    
                                    <!-- Radio Options (RIGHT) -->
                                    <div class="w-1/2 flex justify-between">
                                        @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $idx => $option)
                                        <label class="flex items-center justify-center cursor-pointer flex-1">
                                            <input type="radio" 
                                                   name="responses[{{ $question['field'] }}][rating]" 
                                                   value="{{ $option }}" 
                                                   required
                                                   class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 question-radio"
                                                   data-question="{{ $question['field'] }}"
                                                   onchange="handleRatingChange('{{ $question['field'] }}', '{{ $option }}')">
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Reasoning Textbox (Hidden by default, shown only for Strongly Disagree) -->
                                <div id="reasoning_{{ $question['field'] }}" class="mt-4 hidden">
                                    <label class="block text-base font-medium text-gray-700 mb-2">
                                        Please provide reasoning: <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="responses[{{ $question['field'] }}][reasoning]" 
                                              id="reasoning_text_{{ $question['field'] }}"
                                              rows="4"
                                              style="min-height: 100px;"
                                              class="w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white"
                                              placeholder="Explain your rating..."></textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 3: Course content -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Course content</h3>
                        
                        <div class="space-y-6">
                            @php
                            $section3Questions = [
                                ['field' => 'learning_objectives_clear', 'label' => 'Learning objectives were clear.'],
                                ['field' => 'content_organized', 'label' => 'Course content was organized and well presented.'],
                                ['field' => 'opportunities_practice', 'label' => 'There are sufficient opportunities to practice.'],
                                ['field' => 'access_materials', 'label' => 'Able to access all course materials.'],
                                ['field' => 'content_prepares', 'label' => 'Course content prepares you for further studies or your career.'],
                                ['field' => 'teaching_assessments', 'label' => 'Teaching methods and assessments in relation to the learning objectives and outcomes.'],
                                ['field' => 'diverse_perspectives', 'label' => 'The course included diverse perspectives.']
                            ];
                            @endphp

                            <!-- Header Row -->
                            <div class="flex flex-row items-center justify-between gap-6 pb-4">
                                <div class="w-1/2"></div>
                                <div class="w-1/2 flex justify-between">
                                    @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $option)
                                    <span class="text-sm font-medium text-gray-700 text-center flex-1">{{ $option }}</span>
                                    @endforeach
                                </div>
                            </div>

                            @foreach($section3Questions as $index => $question)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                <div class="flex flex-row items-center justify-between gap-6">
                                    <!-- Question Text (LEFT) -->
                                    <div class="w-1/2">
                                        <p class="text-base font-medium text-gray-800">
                                            {{ $index + 1 }}. {{ $question['label'] }}
                                            <span class="text-red-500">*</span>
                                        </p>
                                    </div>
                                    
                                    <!-- Radio Options (RIGHT) -->
                                    <div class="w-1/2 flex justify-between">
                                        @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $idx => $option)
                                        <label class="flex items-center justify-center cursor-pointer flex-1">
                                            <input type="radio" 
                                                   name="responses[{{ $question['field'] }}][rating]" 
                                                   value="{{ $option }}" 
                                                   required
                                                   class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 question-radio"
                                                   data-question="{{ $question['field'] }}"
                                                   onchange="handleRatingChange('{{ $question['field'] }}', '{{ $option }}')">
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Reasoning Textbox (Hidden by default, shown only for Strongly Disagree) -->
                                <div id="reasoning_{{ $question['field'] }}" class="mt-4 hidden">
                                    <label class="block text-base font-medium text-gray-700 mb-2">
                                        Please provide reasoning: <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="responses[{{ $question['field'] }}][reasoning]" 
                                              id="reasoning_text_{{ $question['field'] }}"
                                              rows="4"
                                              style="min-height: 100px;"
                                              class="w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white"
                                              placeholder="Explain your rating..."></textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 4: Open-ended Questions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Additional Feedback</h3>
                        
                        <div class="space-y-6">
                            <!-- Question 1 -->
                            <div>
                                <label class="block text-base font-medium text-gray-800 mb-2">
                                    What aspects of this course were most useful or valuable? <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 mt-1">Write NA if you don't have any suggestion/response</span>
                                </label>
                                <textarea name="responses[most_useful]" 
                                          required
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                          placeholder="Your answer..."></textarea>
                            </div>

                            <!-- Question 2 -->
                            <div>
                                <label class="block text-base font-medium text-gray-800 mb-2">
                                    Were there any topics you felt were missing or needed more emphasis? <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 mt-1">Write NA if you don't have any suggestion/response</span>
                                </label>
                                <textarea name="responses[missing_topics]" 
                                          required
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                          placeholder="Your answer..."></textarea>
                            </div>

                            <!-- Question 3 -->
                            <div>
                                <label class="block text-base font-medium text-gray-800 mb-2">
                                    Give your suggestion to improve this course <span class="text-red-500">*</span>
                                    <span class="block text-xs text-gray-500 mt-1">Write NA if you don't have any suggestion/response</span>
                                </label>
                                <textarea name="responses[improvement_suggestions]" 
                                          required
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                          placeholder="Your answer..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t">
                        <a href="{{ route('forms.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition text-sm">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-medium">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="h-20"></div>

<script>
// Handle rating change - show textarea only for "Strongly Disagree"
function handleRatingChange(questionField, selectedValue) {
    const reasoningDiv = document.getElementById('reasoning_' + questionField);
    const reasoningTextarea = document.getElementById('reasoning_text_' + questionField);
    
    console.log('Rating changed:', questionField, selectedValue);
    console.log('Reasoning div found:', reasoningDiv);
    console.log('Reasoning textarea found:', reasoningTextarea);
    
    if (!reasoningDiv || !reasoningTextarea) {
        console.error('Elements not found!');
        return;
    }
    
    // Hide all other reasoning textareas first
    document.querySelectorAll('[id^="reasoning_"]').forEach(div => {
        if (div.id !== 'reasoning_' + questionField) {
            div.classList.add('hidden');
            div.style.display = 'none';
            const textarea = div.querySelector('textarea');
            if (textarea) {
                textarea.style.display = 'none';
                if (!textarea.value.trim()) {
                    textarea.required = false;
                }
            }
        }
    });
    
    // Only show textarea for "Strongly Disagree"
    if (selectedValue === 'Strongly Disagree') {
        console.log('Showing textarea for:', questionField);
        reasoningDiv.classList.remove('hidden');
        reasoningDiv.style.display = 'block';
        reasoningTextarea.style.display = 'block';
        reasoningTextarea.required = true;
        reasoningTextarea.focus();
    } else {
        // Hide and clear textarea for other options
        console.log('Hiding textarea for:', questionField);
        reasoningDiv.classList.add('hidden');
        reasoningDiv.style.display = 'none';
        reasoningTextarea.style.display = 'none';
        reasoningTextarea.required = false;
        reasoningTextarea.value = '';
    }
}

// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('studentFeedbackForm');
    
    if (!form) {
        console.error('Form element not found!');
        return;
    }
    
    form.addEventListener('submit', function(e) {
        console.log('=== STUDENT FEEDBACK SUBMISSION ===');
        console.log('Timestamp:', new Date().toISOString());
        
        // Get all form data
        const formData = new FormData(this);
        const responses = {};
        
        // Structure the data
        formData.forEach((value, key) => {
            if (key.startsWith('responses[')) {
                // Extract question field and type (rating/reasoning)
                const match = key.match(/responses\[([^\]]+)\](?:\[([^\]]+)\])?/);
                if (match) {
                    const questionField = match[1];
                    const dataType = match[2] || 'value';
                    
                    if (!responses[questionField]) {
                        responses[questionField] = {};
                    }
                    responses[questionField][dataType] = value;
                }
            }
        });
        
        console.log('Structured Responses:', responses);
        console.log('Total Questions Answered:', Object.keys(responses).length);
        
        // Validation check
        const requiredQuestions = 20; // 5 + 8 + 7 = 20 rating questions
        const ratingCount = Object.keys(responses).filter(key => 
            responses[key].rating !== undefined
        ).length;
        
        if (ratingCount < requiredQuestions) {
            console.warn('Warning: Not all questions answered. Expected:', requiredQuestions, 'Got:', ratingCount);
        } else {
            console.log('✓ All rating questions answered');
        }
        
        // Check for empty reasoning textareas when "Strongly Disagree" is selected
        const visibleTextareas = document.querySelectorAll('[id^="reasoning_text_"]');
        let hasEmptyRequired = false;
        let emptyFieldLabel = '';
        
        visibleTextareas.forEach(textarea => {
            if (textarea.required && textarea.style.display !== 'none' && !textarea.classList.contains('hidden')) {
                if (!textarea.value.trim()) {
                    hasEmptyRequired = true;
                    // Highlight the empty textarea
                    textarea.classList.add('border-red-500', '!border-4');
                    textarea.focus();
                    
                    // Find the question label
                    const reasoningDiv = textarea.closest('[id^="reasoning_"]');
                    if (reasoningDiv) {
                        const questionDiv = reasoningDiv.previousElementSibling;
                        if (questionDiv) {
                            const label = questionDiv.querySelector('p');
                            if (label) {
                                emptyFieldLabel = label.textContent.trim();
                            }
                        }
                    }
                } else {
                    textarea.classList.remove('border-red-500', '!border-4');
                }
            }
        });
        
        if (hasEmptyRequired) {
            e.preventDefault();
            alert('Please provide reasoning for your "Strongly Disagree" rating before submitting.\n\nThe reasoning field is required and cannot be left blank.');
            console.log('Submission blocked: Empty reasoning field');
            return false;
        }
        
        // Show confirmation
        if (!confirm('Are you sure you want to submit this feedback? You cannot edit it after submission.')) {
            console.log('User cancelled submission');
            e.preventDefault();
            return false;
        }
        
        console.log('User confirmed - submitting form...');
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Submitting...';
            
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 10000);
        }
        
        return true;
    });
    
    console.log('✓ Student Feedback form initialized');
});
</script>

<style>
/* Responsive radio button layout */
@media (max-width: 768px) {
    .md\:w-1\/2 {
        width: 100%;
    }
}
</style>

@endsection
