@extends('layouts.app')

@section('title', 'Fill Form - ' . $formTitle)
@section('page-title', 'Student Feedback Form')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="card mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $formTitle }}</h2>
                <p class="text-blue-100">Please fill out all required fields below</p>
                @if($assignment->is_multi_teacher && $assignment->teacher)
                    <div class="mt-3 flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 px-3 py-1 rounded-lg">
                            <span class="text-xs font-semibold">Subject:</span>
                            <span class="ml-1">{{ $assignment->subject->name ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-white bg-opacity-20 px-3 py-1 rounded-lg">
                            <span class="text-xs font-semibold">Teacher:</span>
                            <span class="ml-1">{{ $assignment->teacher->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <form action="{{ route('forms.submit', $formName) }}" method="POST" class="space-y-6" id="feedbackForm">
        @csrf

        <!-- Teacher Selection (for multi-teacher forms) -->
        @if($allAssignments->count() > 1 && $assignment->is_multi_teacher)
            <div class="card bg-gradient-to-r from-purple-50 to-blue-50 border-2 border-purple-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    Select Teacher to Evaluate
                </h3>
                <p class="text-sm text-gray-600 mb-4">This form requires feedback for multiple teachers. Please select one teacher below:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($allAssignments as $teacherAssignment)
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all
                                      {{ $teacherAssignment->status === 'completed' ? 'bg-green-50 border-green-300 opacity-60 cursor-not-allowed' : 'bg-white border-gray-300 hover:border-purple-400 hover:shadow-md' }}">
                            <input type="radio" 
                                   name="teacher_assignment_id" 
                                   value="{{ $teacherAssignment->id }}"
                                   {{ $teacherAssignment->status === 'completed' ? 'disabled' : '' }}
                                   {{ $loop->first && $teacherAssignment->status !== 'completed' ? 'checked' : '' }}
                                   required
                                   class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3 flex-1">
                                <div class="font-semibold text-gray-900">{{ $teacherAssignment->teacher->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-gray-600">{{ $teacherAssignment->subject->name ?? 'N/A' }}</div>
                                @if($teacherAssignment->status === 'completed')
                                    <span class="text-xs text-green-600 font-medium">✓ Completed</span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('teacher_assignment_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @else
            <!-- Hidden field for single teacher assignment -->
            <input type="hidden" name="teacher_assignment_id" value="{{ $assignment->id }}">
        @endif

        <!-- Basic Information Card -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Basic Information</h3>
            
            <!-- Email -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    1. Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ auth()->user()->email }}" 
                       required
                       class="input-field @error('email') border-red-500 @enderror"
                       placeholder="your.email@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    2. Name (Optional)
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ auth()->user()->name }}"
                       class="input-field @error('name') border-red-500 @enderror"
                       placeholder="Your full name">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Rating Instructions -->
        <div class="card bg-blue-50 border border-blue-200">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-blue-800 italic">
                    <strong>Mark only one square per row.</strong> Select the rating that best represents your experience.
                </p>
            </div>
        </div>

        <!-- Curricular Aspects -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 flex items-center">
                <span class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm mr-3">3</span>
                Curricular Aspects
            </h3>

            @php
                $curricularQuestions = [
                    'curricular_relevance' => 'Curriculum developed and implemented has relevance to local, national, regional and global development needs.',
                    'curricular_preparation' => 'Curriculum was broad enough to prepare you for career of choice.',
                    'curricular_crosscutting' => 'Curriculum integrates crosscutting issues relevant to processional ethics, gender, human values, environment and sustainability.',
                    'curricular_supplementary' => 'The learning was supplemented by co-curricular activities such as course work outside the curriculum, project work, internships, workshops, conference, symposia etc.'
                ];
                
                $ratings = [
                    'excellent' => 'Excellent',
                    'very_good' => 'Very Good',
                    'good' => 'Good',
                    'average' => 'Average',
                    'below_average' => 'Below Average'
                ];
            @endphp

            <div class="space-y-1">
                @foreach($curricularQuestions as $key => $question)
                    <div class="flex items-center py-4 hover:bg-gray-50 border-b last:border-b-0">
                        <!-- Question Text - Left Side -->
                        <div class="flex-1 pr-6">
                            <p class="text-sm text-gray-700">{{ $question }}</p>
                        </div>
                        
                        <!-- Rating Options - Right Side -->
                        <div class="flex items-center space-x-4 flex-shrink-0">
                            @foreach($ratings as $value => $label)
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <span class="text-xs text-gray-600 mb-1.5 font-medium whitespace-nowrap">{{ $label }}</span>
                                    <input type="radio" 
                                           name="responses[{{ $key }}]" 
                                           value="{{ $value }}" 
                                           required
                                           class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('responses.' . $key)
                        <p class="text-xs text-red-600 mt-1 ml-2">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Teaching-Learning and Evaluation -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 flex items-center">
                <span class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm mr-3">4</span>
                Teaching-Learning and Evaluation
            </h3>

            @php
                $teachingQuestions = [
                    'audiovisual_resources' => 'Audiovisual learning resources provided by teachers facilitated you to improve learning.',
                    'reading_materials' => 'Reading material and other learning resources provided by teachers facilitated you to improve learning.',
                    'hands_on_practice' => 'Hands-on practice in laboratories and project work facilitated in overall development, inculcating skills and time management.',
                    'academic_activities' => 'Academic activities facilitate you to improve experiential learning, participative learning and problem-solving methodology.',
                    'evaluation_pattern' => 'Evaluation pattern (Unit Test, Assignment, and Presentation) made you capable of analyzing concepts and theories.'
                ];
            @endphp

            <div class="space-y-1">
                @foreach($teachingQuestions as $key => $question)
                    <div class="flex items-center py-4 hover:bg-gray-50 border-b last:border-b-0">
                        <!-- Question Text - Left Side -->
                        <div class="flex-1 pr-6">
                            <p class="text-sm text-gray-700">{{ $question }}</p>
                        </div>
                        
                        <!-- Rating Options - Right Side -->
                        <div class="flex items-center space-x-4 flex-shrink-0">
                            @foreach($ratings as $value => $label)
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <span class="text-xs text-gray-600 mb-1.5 font-medium whitespace-nowrap">{{ $label }}</span>
                                    <input type="radio" 
                                           name="responses[{{ $key }}]" 
                                           value="{{ $value }}" 
                                           required
                                           class="w-5 h-5 text-purple-600 focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('responses.' . $key)
                        <p class="text-xs text-red-600 mt-1 ml-2">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Infrastructure and Learning Resources (Page 2) -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 flex items-center">
                <span class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm mr-3">5</span>
                Infrastructure and Learning Resources
            </h3>

            @php
                $infrastructureQuestions = [
                    'infrastructure_classrooms' => 'The infrastructure provided by the institution (classrooms, seminar halls, laboratories, IT facilities) facilitated you for effective learning.',
                    'infrastructure_library' => 'The library facilities and resources provided by the institution helped you in your academic growth.',
                    'infrastructure_sports' => 'Sports and recreational facilities provided by the institution contributed to your overall development.',
                    'infrastructure_internet' => 'Internet and Wi-Fi facilities provided by the institution were adequate and accessible.',
                    'infrastructure_canteen' => 'Canteen and cafeteria facilities provided hygienic and quality food services.'
                ];
            @endphp

            <div class="space-y-1">
                @foreach($infrastructureQuestions as $key => $question)
                    <div class="flex items-center py-4 hover:bg-gray-50 border-b last:border-b-0">
                        <!-- Question Text - Left Side -->
                        <div class="flex-1 pr-6">
                            <p class="text-sm text-gray-700">{{ $question }}</p>
                        </div>
                        
                        <!-- Rating Options - Right Side -->
                        <div class="flex items-center space-x-4 flex-shrink-0">
                            @foreach($ratings as $value => $label)
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <span class="text-xs text-gray-600 mb-1.5 font-medium whitespace-nowrap">{{ $label }}</span>
                                    <input type="radio" 
                                           name="responses[{{ $key }}]" 
                                           value="{{ $value }}" 
                                           required
                                           class="w-5 h-5 text-green-600 focus:ring-2 focus:ring-green-500 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('responses.' . $key)
                        <p class="text-xs text-red-600 mt-1 ml-2">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Mentoring and Support (Page 2 continued) -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 flex items-center">
                <span class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm mr-3">6</span>
                Mentoring and Support
            </h3>

            @php
                $mentoringQuestions = [
                    'mentoring_guidance' => 'The mentoring and guidance provided by faculty members helped you in academic and personal development.',
                    'mentoring_counseling' => 'Counseling services provided by the institution helped you in dealing with academic and personal issues.',
                    'mentoring_career' => 'Career guidance and placement support provided by the institution prepared you for future opportunities.',
                    'mentoring_grievance' => 'Grievance redressal mechanism of the institution is effective and student-friendly.',
                    'mentoring_communication' => 'Communication between students and faculty/administration is open and effective.'
                ];
            @endphp

            <div class="space-y-1">
                @foreach($mentoringQuestions as $key => $question)
                    <div class="flex items-center py-4 hover:bg-gray-50 border-b last:border-b-0">
                        <!-- Question Text - Left Side -->
                        <div class="flex-1 pr-6">
                            <p class="text-sm text-gray-700">{{ $question }}</p>
                        </div>
                        
                        <!-- Rating Options - Right Side -->
                        <div class="flex items-center space-x-4 flex-shrink-0">
                            @foreach($ratings as $value => $label)
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <span class="text-xs text-gray-600 mb-1.5 font-medium whitespace-nowrap">{{ $label }}</span>
                                    <input type="radio" 
                                           name="responses[{{ $key }}]" 
                                           value="{{ $value }}" 
                                           required
                                           class="w-5 h-5 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('responses.' . $key)
                        <p class="text-xs text-red-600 mt-1 ml-2">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Student Activities and Development (Page 3) -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 flex items-center">
                <span class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm mr-3">7</span>
                Student Activities and Development
            </h3>

            @php
                $activitiesQuestions = [
                    'activities_extracurricular' => 'The institution provides adequate opportunities for participation in extracurricular activities (cultural, sports, technical events).',
                    'activities_leadership' => 'Student clubs, committees, and organizations helped you develop leadership and teamwork skills.',
                    'activities_social' => 'Social outreach and community service programs organized by the institution enhanced your social responsibility.',
                    'activities_seminars' => 'Workshops, seminars, and guest lectures organized by the institution enriched your knowledge beyond curriculum.',
                    'activities_competitions' => 'Opportunities to participate in inter-college/inter-university competitions were adequately provided.'
                ];
            @endphp

            <div class="space-y-1">
                @foreach($activitiesQuestions as $key => $question)
                    <div class="flex items-center py-4 hover:bg-gray-50 border-b last:border-b-0">
                        <!-- Question Text - Left Side -->
                        <div class="flex-1 pr-6">
                            <p class="text-sm text-gray-700">{{ $question }}</p>
                        </div>
                        
                        <!-- Rating Options - Right Side -->
                        <div class="flex items-center space-x-4 flex-shrink-0">
                            @foreach($ratings as $value => $label)
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <span class="text-xs text-gray-600 mb-1.5 font-medium whitespace-nowrap">{{ $label }}</span>
                                    <input type="radio" 
                                           name="responses[{{ $key }}]" 
                                           value="{{ $value }}" 
                                           required
                                           class="w-5 h-5 text-orange-600 focus:ring-2 focus:ring-orange-500 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('responses.' . $key)
                        <p class="text-xs text-red-600 mt-1 ml-2">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Overall Satisfaction (Page 3 continued) -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 flex items-center">
                <span class="bg-gray-500 text-white px-3 py-1 rounded-md text-sm mr-3">8</span>
                Overall Satisfaction
            </h3>

            @php
                $overallQuestions = [
                    'overall_quality' => 'Overall quality of education provided by the institution meets your expectations.',
                    'overall_recommend' => 'You would recommend this institution to prospective students.',
                    'overall_satisfied' => 'You are satisfied with your overall experience at this institution.',
                    'overall_value' => 'The institution provides value for money in terms of education and facilities.',
                    'overall_improvement' => 'The institution is responsive to student feedback and continuously works on improvement.'
                ];
            @endphp

            <div class="space-y-1">
                @foreach($overallQuestions as $key => $question)
                    <div class="flex items-center py-4 hover:bg-gray-50 border-b last:border-b-0">
                        <!-- Question Text - Left Side -->
                        <div class="flex-1 pr-6">
                            <p class="text-sm text-gray-700">{{ $question }}</p>
                        </div>
                        
                        <!-- Rating Options - Right Side -->
                        <div class="flex items-center space-x-4 flex-shrink-0">
                            @foreach($ratings as $value => $label)
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <span class="text-xs text-gray-600 mb-1.5 font-medium whitespace-nowrap">{{ $label }}</span>
                                    <input type="radio" 
                                           name="responses[{{ $key }}]" 
                                           value="{{ $value }}" 
                                           required
                                           class="w-5 h-5 text-pink-600 focus:ring-2 focus:ring-pink-500 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('responses.' . $key)
                        <p class="text-xs text-red-600 mt-1 ml-2">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        <!-- Additional Comments (Optional) -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm mr-3">9</span>
                Additional Comments (Optional)
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Strengths of the Institution
                    </label>
                    <textarea name="comments_strengths" 
                              rows="3" 
                              class="input-field"
                              placeholder="What do you think are the strongest points of this institution?"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Areas for Improvement
                    </label>
                    <textarea name="comments_improvements" 
                              rows="3" 
                              class="input-field"
                              placeholder="What areas do you think need improvement?"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Any Other Suggestions or Comments
                    </label>
                    <textarea name="comments_other" 
                              rows="4" 
                              class="input-field"
                              placeholder="Please share any other feedback, suggestions, or concerns..."></textarea>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-bold text-blue-800">Form Completion Status</p>
                    <p class="text-xs text-blue-600">Total Questions: <span class="font-bold">29</span> (9 sections)</p>
                </div>
            </div>
        </div>

        <!-- Error Summary -->
        @if($errors->any())
            <div class="card bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-red-800 mb-2">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t">
            <a href="{{ route('forms.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Forms
            </a>
            
            <button type="submit" class="btn-primary text-lg px-8 py-3">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit Feedback
            </button>
        </div>
    </form>
</div>

<!-- Section Heading Style Update -->
<style>
    .card h3 {
        color: black; /* Uniform heading color */
    }
</style>

<script>
// Form validation feedback
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    const form = this;
    const radioGroups = {};
    
    // Check all radio button groups
    form.querySelectorAll('input[type="radio"]').forEach(radio => {
        const name = radio.name;
        if (!radioGroups[name]) {
            radioGroups[name] = false;
        }
        if (radio.checked) {
            radioGroups[name] = true;
        }
    });
    
    // Find any empty groups
    const emptyGroups = Object.keys(radioGroups).filter(key => !radioGroups[key]);
    
    if (emptyGroups.length > 0) {
        e.preventDefault();
        alert('Please answer all questions before submitting.');
        
        // Scroll to first unanswered question
        const firstEmpty = form.querySelector(`input[name="${emptyGroups[0]}"]`);
        if (firstEmpty) {
            firstEmpty.closest('.card').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});

// Add visual feedback when radio is selected
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove selection from siblings
        const name = this.name;
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
            r.closest('label').classList.remove('ring-2');
        });
    });
});
</script>
@endsection
