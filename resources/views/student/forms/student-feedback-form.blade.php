@extends('layouts.app')

@section('title', 'Student Feedback')
@section('page-title', 'Student Feedback Form')

@section('content')
<style>
/* Smooth transitions for reasoning textareas */
[id^="reasoning_"] {
    transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                margin-top 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                padding 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

[id^="reasoning_"].show {
    max-height: 280px !important;
    opacity: 1 !important;
    margin-top: 1rem !important;
    padding-top: 0.5rem !important;
}

[id^="reasoning_"].hidden {
    max-height: 0 !important;
    opacity: 0 !important;
    margin-top: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

[id^="reasoning_"] textarea {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                box-shadow 0.3s ease-in-out;
}

[id^="reasoning_"].show textarea {
    transform: translateY(0) scale(1);
    animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

[id^="reasoning_"].hidden textarea {
    transform: translateY(-10px) scale(0.98);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Focus effect */
[id^="reasoning_"] textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    transform: translateY(0) scale(1.01);
}
</style>

<div class="max-w-6xl mx-auto py-3 px-3 sm:px-4">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-4 sm:px-6 bg-white border-b border-gray-200">
            <div class="mt-4">
                <div class="text-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Student Feedback Form</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Your feedback is valuable and will help us improve the quality of education.
                    </p>
                </div>

                <!-- Progress Bar -->
                @if(isset($totalAssignments) && $totalAssignments > 0)
                    @php
                        $progressPercentage = round(($completedAssignments / $totalAssignments) * 100);
                        $progressColor = $progressPercentage >= 75 ? 'bg-green-500' : ($progressPercentage >= 50 ? 'bg-blue-500' : ($progressPercentage >= 25 ? 'bg-yellow-500' : 'bg-red-500'));
                        $progressTextColor = $progressPercentage >= 75 ? 'text-green-600' : ($progressPercentage >= 50 ? 'text-blue-600' : ($progressPercentage >= 25 ? 'text-yellow-600' : 'text-red-600'));
                    @endphp
                    
                    <div class="mb-6 bg-gradient-to-r from-indigo-50 via-purple-50 to-blue-50 border-2 border-indigo-300 rounded-lg p-4 shadow-md">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800">Your Progress</h3>
                                    <p class="text-xs text-gray-600">{{ $completedAssignments }} of {{ $totalAssignments }} forms completed</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-extrabold {{ $progressTextColor }}">{{ $progressPercentage }}%</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="relative mb-3">
                            <div class="overflow-hidden h-3 text-xs flex rounded-full bg-gray-300 shadow-inner border border-gray-400">
                                <div 
                                    style="width: {{ $progressPercentage }}%;" 
                                    class="shadow-sm flex flex-col text-center whitespace-nowrap text-white justify-center {{ $progressColor }} transition-all duration-700 ease-out"
                                ></div>
                            </div>
                        </div>
                        
                        <!-- Mini Stats -->
                        <div class="flex justify-between text-xs">
                            <span class="text-green-700 font-semibold">
                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $completedAssignments }} Completed
                            </span>
                            <span class="text-red-700 font-semibold">
                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $pendingAssignments }} Remaining
                            </span>
                        </div>
                    </div>
                @endif

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

                <form method="POST" action="{{ route('forms.submit', $formName ?? 'student-feedback-form') }}" class="space-y-5" id="studentFeedbackForm">
                    @csrf
                    
                    <!-- Hidden field for assignment -->
                    @if(isset($assignment))
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                    @endif

                    <!-- Teacher Selection (for multi-teacher forms) -->
                    @if(isset($allAssignments) && $allAssignments->count() > 1)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Select Subject and Teacher</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your experience as a student in this course</h3>
                        
                        <div class="space-y-4">
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
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
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
                                              class="w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:outline-none focus:border-indigo-500 bg-white resize-none box-border"
                                              placeholder="Explain your rating..."></textarea>
                                    <p id="error_msg_{{ $question['field'] }}" class="mt-2 text-sm text-red-600 font-medium hidden">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        This field is required. Please explain your reasoning before submitting.
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 2: Your experience with the instructor of this course -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your experience with the instructor of this course</h3>
                        
                        <div class="space-y-4">
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
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
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
                                              class="w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:outline-none focus:border-indigo-500 bg-white resize-none box-border"
                                              placeholder="Explain your rating..."></textarea>
                                    <p id="error_msg_{{ $question['field'] }}" class="mt-2 text-sm text-red-600 font-medium hidden">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        This field is required. Please explain your reasoning before submitting.
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 3: Course content -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course content</h3>
                        
                        <div class="space-y-4">
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
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
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
                                              class="w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:outline-none focus:border-indigo-500 bg-white resize-none box-border"
                                              placeholder="Explain your rating..."></textarea>
                                    <p id="error_msg_{{ $question['field'] }}" class="mt-2 text-sm text-red-600 font-medium hidden">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        This field is required. Please explain your reasoning before submitting.
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 4: Open-ended Questions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Feedback</h3>
                        
                        <div class="space-y-4">
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
                    <div class="flex items-center justify-end gap-3 pt-4 border-t">
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

<div class="h-8"></div>

<script>
// Toggle instructions section with animation
function toggleInstructions() {
    const content = document.getElementById('instructionsContent');
    const chevron = document.getElementById('instructionsChevron');
    
    if (content.classList.contains('hidden')) {
        // Show with animation
        content.classList.remove('hidden');
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px)';
        
        // Trigger animation
        requestAnimationFrame(() => {
            content.style.transition = 'all 0.4s ease-out';
            content.style.maxHeight = '600px';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        });
        
        chevron.classList.add('rotate-180');
        
        // Animate instruction items one by one
        const items = content.querySelectorAll('.flex.items-start');
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                item.style.transition = 'all 0.3s ease-out';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 100 + (index * 100)); // Stagger animation
        });
    } else {
        // Hide with animation
        content.style.transition = 'all 0.3s ease-in';
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px)';
        
        chevron.classList.remove('rotate-180');
        
        setTimeout(() => {
            content.classList.add('hidden');
        }, 300);
    }
}

// Handle rating change - show textarea only for "Strongly Disagree"
function handleRatingChange(questionField, selectedValue) {
    const reasoningDiv = document.getElementById('reasoning_' + questionField);
    const reasoningTextarea = document.getElementById('reasoning_text_' + questionField);
    
    console.log('Rating changed:', questionField, selectedValue);
    
    if (!reasoningDiv || !reasoningTextarea) {
        console.error('Elements not found for:', questionField);
        return;
    }
    
    // Hide all other reasoning textareas that have text (already answered) with smooth transition
    document.querySelectorAll('[id^="reasoning_"]').forEach(div => {
        if (div.id !== 'reasoning_' + questionField) {
            const textarea = div.querySelector('textarea');
            if (textarea && textarea.value.trim()) {
                // This textarea has text, hide it smoothly
                div.classList.remove('show');
                div.classList.add('hidden');
                
                // Hide error message too
                const fieldName = textarea.id.replace('reasoning_text_', '');
                const errorMsg = document.getElementById('error_msg_' + fieldName);
                if (errorMsg) {
                    errorMsg.classList.add('hidden');
                }
                
                console.log('✓ Hiding filled textarea:', textarea.id);
            }
        }
    });
    
    // Clear any previous error styling for current textarea
    reasoningTextarea.classList.remove('border-red-500', '!border-4', 'shake');
    const currentErrorMsg = document.getElementById('error_msg_' + questionField);
    if (currentErrorMsg) {
        currentErrorMsg.classList.add('hidden');
    }
    
    // Only show textarea for "Strongly Disagree"
    if (selectedValue === 'Strongly Disagree') {
        console.log('✓ Showing textarea for:', questionField, '(Strongly Disagree selected)');
        
        // Show the textarea with smooth transition
        reasoningDiv.classList.remove('hidden');
        reasoningDiv.classList.add('show');
        reasoningDiv.style.display = 'block';
        
        // Make it required
        reasoningTextarea.required = true;
        reasoningTextarea.setAttribute('required', 'required');
        
        // Focus on it after transition
        setTimeout(() => {
            reasoningTextarea.focus();
        }, 300);
        
        console.log('Textarea status:', {
            id: reasoningTextarea.id,
            required: reasoningTextarea.required,
            visible: !reasoningDiv.classList.contains('hidden'),
            parentVisible: reasoningDiv.style.display !== 'none'
        });
    } else {
        // Hide and clear textarea for other options with smooth transition
        console.log('✓ Hiding textarea for:', questionField, '(' + selectedValue + ' selected)');
        
        reasoningDiv.classList.remove('show');
        reasoningDiv.classList.add('hidden');
        
        // After transition completes, set display none
        setTimeout(() => {
            if (reasoningDiv.classList.contains('hidden')) {
                reasoningDiv.style.display = 'none';
            }
        }, 400);
        
        // Remove required attribute
        reasoningTextarea.required = false;
        reasoningTextarea.removeAttribute('required');
        
        // Clear the value
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
        const allTextareas = document.querySelectorAll('[id^="reasoning_text_"]');
        let hasEmptyRequired = false;
        let emptyFields = [];
        
        // First, clear all previous error styling and error messages
        allTextareas.forEach(textarea => {
            textarea.classList.remove('border-red-500', '!border-4', 'shake');
            
            // Hide error message for this textarea
            const fieldName = textarea.id.replace('reasoning_text_', '');
            const errorMsg = document.getElementById('error_msg_' + fieldName);
            if (errorMsg) {
                errorMsg.classList.add('hidden');
            }
        });
        
        // Now check each textarea
        allTextareas.forEach(textarea => {
            const reasoningDiv = textarea.closest('[id^="reasoning_"]');
            
            // Check if this textarea is visible and required
            const isVisible = reasoningDiv && 
                             !reasoningDiv.classList.contains('hidden') && 
                             reasoningDiv.style.display !== 'none' &&
                             textarea.style.display !== 'none';
            
            const isRequired = textarea.required || textarea.hasAttribute('required');
            
            console.log('Checking textarea:', textarea.id, {
                isVisible,
                isRequired,
                value: textarea.value.trim(),
                parentDisplay: reasoningDiv?.style.display,
                parentHidden: reasoningDiv?.classList.contains('hidden')
            });
            
            // If visible AND required AND empty, it's an error
            if (isVisible && isRequired && !textarea.value.trim()) {
                hasEmptyRequired = true;
                
                // Highlight the empty textarea
                textarea.classList.add('border-red-500', '!border-4');
                
                // Show error message for this textarea
                const fieldName = textarea.id.replace('reasoning_text_', '');
                const errorMsg = document.getElementById('error_msg_' + fieldName);
                if (errorMsg) {
                    errorMsg.classList.remove('hidden');
                }
                
                // Find the question label
                if (reasoningDiv) {
                    const questionDiv = reasoningDiv.previousElementSibling;
                    if (questionDiv) {
                        const label = questionDiv.querySelector('p');
                        if (label) {
                            const questionText = label.textContent.trim();
                            emptyFields.push(questionText);
                        }
                    }
                }
            }
        });
        
        if (hasEmptyRequired) {
            e.preventDefault();
            e.stopPropagation();
            
            // Scroll to first empty field
            const firstEmptyTextarea = document.querySelector('[id^="reasoning_text_"].border-red-500');
            if (firstEmptyTextarea) {
                firstEmptyTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => {
                    firstEmptyTextarea.focus();
                }, 300);
            }
            
            // Show detailed error message
            let errorMessage = '⚠️ Required Reasoning Missing\n\n';
            errorMessage += 'You have selected "Strongly Disagree" for the following question(s), which requires you to provide reasoning:\n\n';
            errorMessage += emptyFields.map((field, idx) => `${idx + 1}. ${field}`).join('\n');
            errorMessage += '\n\nPlease provide your reasoning in the text box(es) highlighted in red before submitting.';
            
            alert(errorMessage);
            console.error('Submission blocked: Empty reasoning field(s) for questions:', emptyFields);
            return false;
        }
        
        console.log('✓ All required reasoning fields filled');
        
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
    
    // Add real-time validation for reasoning textareas
    document.querySelectorAll('[id^="reasoning_text_"]').forEach(textarea => {
        textarea.addEventListener('input', function() {
            const fieldName = this.id.replace('reasoning_text_', '');
            const errorMsg = document.getElementById('error_msg_' + fieldName);
            
            // Remove error styling and message when user starts typing
            if (this.value.trim()) {
                this.classList.remove('border-red-500', '!border-4');
                if (errorMsg) {
                    errorMsg.classList.add('hidden');
                }
            }
        });
        
        // Also validate on blur
        textarea.addEventListener('blur', function() {
            const reasoningDiv = this.closest('[id^="reasoning_"]');
            const isVisible = reasoningDiv && 
                             !reasoningDiv.classList.contains('hidden') && 
                             reasoningDiv.style.display !== 'none' &&
                             this.style.display !== 'none';
            
            const fieldName = this.id.replace('reasoning_text_', '');
            const errorMsg = document.getElementById('error_msg_' + fieldName);
            
            if (this.required && isVisible) {
                if (!this.value.trim()) {
                    this.classList.add('border-red-500', '!border-4');
                    if (errorMsg) {
                        errorMsg.classList.remove('hidden');
                    }
                } else {
                    this.classList.remove('border-red-500', '!border-4');
                    if (errorMsg) {
                        errorMsg.classList.add('hidden');
                    }
                }
            }
        });
    });
    
    console.log('✓ Student Feedback form initialized');
    
    // ===== AUTO-SAVE FUNCTIONALITY =====
    const formKey = 'studentFeedback_' + (document.querySelector('[name="assignment_id"]')?.value || 'default');
    let autosaveTimeout;
    
    // Save form data to localStorage
    function saveFormData() {
        const formData = {};
        
        // Save all radio buttons
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            formData[radio.name] = radio.value;
        });
        
        // Save all textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            if (textarea.value.trim()) {
                formData[textarea.name] = textarea.value;
            }
        });
        
        // Save selected teacher if exists
        const teacherSelect = document.querySelector('[name="teacher_id"]');
        if (teacherSelect) {
            formData['teacher_id'] = teacherSelect.value;
        }
        
        // Save to localStorage
        try {
            localStorage.setItem(formKey, JSON.stringify({
                data: formData,
                savedAt: new Date().toISOString()
            }));
            console.log('✓ Form data auto-saved');
        } catch (e) {
            console.error('Failed to save form data:', e);
        }
    }
    
    // Load form data from localStorage
    function loadFormData() {
        try {
            const saved = localStorage.getItem(formKey);
            if (!saved) return;
            
            const { data, savedAt } = JSON.parse(saved);
            const savedDate = new Date(savedAt);
            const hoursSince = (new Date() - savedDate) / (1000 * 60 * 60);
            
            // Don't load data older than 7 days
            if (hoursSince > 168) {
                localStorage.removeItem(formKey);
                return;
            }
            
            console.log('Loading saved draft from:', savedDate.toLocaleString());
            
            // Restore radio buttons
            Object.keys(data).forEach(name => {
                if (name.includes('[rating]')) {
                    const radio = document.querySelector(`input[name="${name}"][value="${data[name]}"]`);
                    if (radio) {
                        radio.checked = true;
                        
                        // Trigger the rating change handler
                        const match = name.match(/responses\[([^\]]+)\]\[rating\]/);
                        if (match) {
                            handleRatingChange(match[1], data[name]);
                        }
                    }
                }
            });
            
            // Restore textareas (after a small delay to ensure reasoning textareas are visible)
            setTimeout(() => {
                Object.keys(data).forEach(name => {
                    if (!name.includes('[rating]') && name !== 'teacher_id') {
                        const textarea = document.querySelector(`textarea[name="${name}"]`);
                        if (textarea) {
                            textarea.value = data[name];
                        }
                    }
                });
                
                // Restore teacher selection
                if (data['teacher_id']) {
                    const teacherSelect = document.querySelector('[name="teacher_id"]');
                    if (teacherSelect) {
                        teacherSelect.value = data['teacher_id'];
                    }
                }
            }, 500);
            
            console.log('✓ Draft restored from:', savedDate.toLocaleString());
        } catch (e) {
            console.error('Failed to load form data:', e);
        }
    }
    
    // Debounced auto-save on input change
    function scheduleAutosave() {
        clearTimeout(autosaveTimeout);
        autosaveTimeout = setTimeout(saveFormData, 1000); // Save 1 second after last change
    }
    
    // Attach auto-save listeners
    document.querySelectorAll('input[type="radio"], textarea, select').forEach(element => {
        element.addEventListener('change', scheduleAutosave);
        if (element.tagName === 'TEXTAREA') {
            element.addEventListener('input', scheduleAutosave);
        }
    });
    
    // Clear saved data on successful submission
    const originalSubmitHandler = form.onsubmit;
    form.addEventListener('submit', function(e) {
        // If form passes validation and user confirms, clear the draft
        setTimeout(() => {
            if (!e.defaultPrevented) {
                localStorage.removeItem(formKey);
                console.log('✓ Draft cleared after submission');
            }
        }, 100);
    });
    
    // Load saved data on page load
    loadFormData();
    
    // Show draft info if exists
    const saved = localStorage.getItem(formKey);
    if (saved) {
        const { savedAt } = JSON.parse(saved);
        console.log('💾 Draft available from:', new Date(savedAt).toLocaleString());
    }
    
    // ===== KEYBOARD NAVIGATION =====
    let focusedQuestionIndex = -1;
    const allQuestions = [];
    
    // Group radio buttons by question
    document.querySelectorAll('.question-radio').forEach(radio => {
        const questionField = radio.dataset.question;
        if (!allQuestions.find(q => q.field === questionField)) {
            const radios = document.querySelectorAll(`input[data-question="${questionField}"]`);
            allQuestions.push({
                field: questionField,
                radios: Array.from(radios)
            });
        }
    });
    
    console.log('✓ Keyboard navigation enabled for', allQuestions.length, 'questions');
    
    // Add keyboard event listener
    document.addEventListener('keydown', function(e) {
        // Don't interfere if user is typing in a textarea
        if (document.activeElement.tagName === 'TEXTAREA') {
            return;
        }
        
        // Arrow Up/Down - Navigate between questions
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusedQuestionIndex = Math.min(focusedQuestionIndex + 1, allQuestions.length - 1);
            focusQuestion(focusedQuestionIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusedQuestionIndex = Math.max(focusedQuestionIndex - 1, 0);
            focusQuestion(focusedQuestionIndex);
        }
        // Arrow Left/Right - Select rating options
        else if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            if (focusedQuestionIndex >= 0) {
                e.preventDefault();
                const question = allQuestions[focusedQuestionIndex];
                const currentIndex = question.radios.findIndex(r => r.checked);
                
                let newIndex;
                if (e.key === 'ArrowLeft') {
                    newIndex = currentIndex > 0 ? currentIndex - 1 : 0;
                } else {
                    newIndex = currentIndex < question.radios.length - 1 ? currentIndex + 1 : question.radios.length - 1;
                }
                
                question.radios[newIndex].checked = true;
                question.radios[newIndex].dispatchEvent(new Event('change', { bubbles: true }));
                
                // Highlight the selected radio
                highlightSelectedRadio(question.radios[newIndex]);
            }
        }
        // Number keys 1-5 - Quick select ratings
        else if (e.key >= '1' && e.key <= '5') {
            if (focusedQuestionIndex >= 0) {
                e.preventDefault();
                const question = allQuestions[focusedQuestionIndex];
                const index = parseInt(e.key) - 1;
                
                if (index < question.radios.length) {
                    question.radios[index].checked = true;
                    question.radios[index].dispatchEvent(new Event('change', { bubbles: true }));
                    
                    // Highlight the selected radio
                    highlightSelectedRadio(question.radios[index]);
                    
                    // Auto-advance to next question
                    if (focusedQuestionIndex < allQuestions.length - 1) {
                        setTimeout(() => {
                            focusedQuestionIndex++;
                            focusQuestion(focusedQuestionIndex);
                        }, 200);
                    }
                }
            }
        }
        // Space or Enter - Confirm selection and move to next
        else if ((e.key === ' ' || e.key === 'Enter') && focusedQuestionIndex >= 0) {
            if (document.activeElement.tagName !== 'TEXTAREA' && document.activeElement.tagName !== 'BUTTON') {
                e.preventDefault();
                if (focusedQuestionIndex < allQuestions.length - 1) {
                    focusedQuestionIndex++;
                    focusQuestion(focusedQuestionIndex);
                }
            }
        }
    });
    
    // Focus on a specific question
    function focusQuestion(index) {
        if (index < 0 || index >= allQuestions.length) return;
        
        const question = allQuestions[index];
        const firstRadio = question.radios[0];
        
        // Find the question row (flex container with question text and radios)
        const questionRow = firstRadio.closest('.flex.flex-row');
        const questionContainer = firstRadio.closest('.border-b');
        
        // Scroll to question
        questionContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
        
        // Visual highlight - apply to the flex row, not the whole container
        questionRow.style.backgroundColor = '#dbeafe';
        questionRow.style.borderRadius = '0.5rem';
        questionRow.style.padding = '0.75rem';
        questionRow.style.marginLeft = '-0.75rem';
        questionRow.style.marginRight = '-0.75rem';
        questionRow.style.transition = 'all 0.3s ease';
        questionRow.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.2)';
        
        // Remove highlight from other questions
        document.querySelectorAll('.flex.flex-row').forEach(row => {
            if (row !== questionRow && row.closest('.border-b')) {
                row.style.backgroundColor = '';
                row.style.borderRadius = '';
                row.style.padding = '';
                row.style.marginLeft = '';
                row.style.marginRight = '';
                row.style.boxShadow = '';
            }
        });
        
        console.log('→ Focused on question:', question.field, '(' + (index + 1) + '/' + allQuestions.length + ')');
    }
    
    // Highlight selected radio temporarily
    function highlightSelectedRadio(radio) {
        const label = radio.closest('label');
        label.style.transform = 'scale(1.3)';
        label.style.transition = 'transform 0.2s';
        
        setTimeout(() => {
            label.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Click on any radio to focus that question
    document.querySelectorAll('.question-radio').forEach(radio => {
        radio.addEventListener('click', function() {
            const questionField = this.dataset.question;
            focusedQuestionIndex = allQuestions.findIndex(q => q.field === questionField);
        });
    });
    
    // Show keyboard shortcuts hint
    console.log('⌨️ Keyboard Shortcuts:');
    console.log('  ↑↓ - Navigate questions');
    console.log('  ←→ - Change rating');
    console.log('  1-5 - Quick select (Strongly Agree to Strongly Disagree)');
    console.log('  Space/Enter - Confirm and move to next');
});
</script>

<style>
/* Responsive radio button layout */
@media (max-width: 768px) {
    .md\:w-1\/2 {
        width: 100%;
    }
}

/* Shake animation for validation errors */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.shake {
    animation: shake 0.5s;
}

/* Pulse animation for required field notice */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Enhanced border styles for required fields with errors */
.border-red-500.\\!border-4 {
    border-color: #ef4444 !important;
    border-width: 4px !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}
</style>

@endsection










