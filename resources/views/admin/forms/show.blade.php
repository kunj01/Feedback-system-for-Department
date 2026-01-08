@extends('layouts.app')

@section('title', 'Student Feedback Form - SCFMS')
@section('page-title', 'Student Feedback Form')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    <div class="mb-4">
        <a href="{{ route('forms.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Forms
        </a>
    </div>

    <div class="card shadow-lg">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-t-lg p-6 mb-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold mb-2">📝 Student Feedback Form</h2>
                <p class="text-blue-100 text-sm">Your feedback helps us improve the quality of education. All responses are confidential.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('forms.submit', $formName) }}" method="POST" class="space-y-4">
            @csrf

            <!-- Basic Information Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-600 p-5 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="input-field" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-gray-400 text-xs">(Optional)</span></label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" class="input-field">
                    </div>
                </div>
            </div>

            @php
                $sections = [
                    [
                        'title' => '3. Curricular Aspects',
                        'questions' => [
                            'curriculum_relevance' => 'Curriculum developed and implemented has relevance to local, national, regional and global development needs.',
                            'curriculum_breadth' => 'Curriculum was broad enough to prepare you for career of choice.',
                            'curriculum_integration' => 'Curriculum integrates crosscutting issues relevant to professional ethics, gender, human values, environment and sustainability.',
                            'cocurricular_activities' => 'The learning was supplemented by co-curricular activities such as course work outside the curriculum, project work, internships, workshops, conference, symposia etc.'
                        ]
                    ],
                    [
                        'title' => '4. Teaching-Learning and Evaluation',
                        'questions' => [
                            'audiovisual_resources' => 'Audiovisual learning resources provided by teachers facilitated you to improve learning.',
                            'reading_material' => 'Reading material and other learning resources provided by teachers facilitated you to improve learning.',
                            'hands_on_practice' => 'Hands-on practice in laboratories and project work facilitated in overall development, inculcating skills and time management.',
                            'experiential_learning' => 'Academic activities facilitate you to improve experiential learning, participative learning and problem-solving methodology.',
                            'evaluation_pattern' => 'Evaluation pattern (Unit Test, Assignment, and Presentation) made you capable of analyzing your strength & weakness, and empowered you to use resources effectively.',
                            'lifelong_learning' => 'The overall experience would help you to engage in independent and life-long learning in the broadest context of technological change.'
                        ]
                    ],
                    [
                        'title' => '5. Research and Extension Activities',
                        'questions' => [
                            'research_ecosystem' => 'Institution has an eco-system to promote research and other initiatives for creation and transfer of knowledge.',
                            'research_facilities' => 'Institution has adequate facility to carry out research.',
                            'workshops_seminars' => 'Workshops/seminars on research methodology, Intellectual Property Rights (IPR), entrepreneurship, skill development are organized regularly.',
                            'social_activities' => 'Activities with social relevance (NCC/ NSS/ CHRF/ CHARUSAT Rural Education etc.) are conducted regularly.'
                        ]
                    ],
                    [
                        'title' => '6. Infrastructure and Learning Resources',
                        'questions' => [
                            'teaching_facilities' => 'The institute has adequate facilities for Teaching – learning viz. audiovisual amenities, classrooms, laboratories.',
                            'cultural_sports_facilities' => 'The institute has adequate facilities for Cultural activities, yoga, games (Indoor and outdoor), sports and gymnasium.',
                            'internet_facilities' => 'The institute has adequate LAN, WiFi and Internet Facility.',
                            'canteen_facilities' => 'The institute has adequate and hygienic canteen and food facilities.',
                            'campus_ambience' => 'Campus Ambience (Greenery, Environment friendly eco system, usage of solar lights, saving of electricity, production of electricity, working space) is pleasant.',
                            'library_resources' => 'Adequate learning resources are available in library.'
                        ]
                    ],
                    [
                        'title' => '7. Student Support and Progression',
                        'questions' => [
                            'student_council' => 'Active student council exists and students are involved in activities for institutional development and student welfare.',
                            'grievance_resolution' => 'Institution timely resolves the grievances including sexual harassment and ragging cases.',
                            'counseling_support' => 'Counseling helped in assessing learning level of students, leading to customized attention to needy students.',
                            'participation_support' => 'Institution encourages and provides support to participate in national and international events.',
                            'capacity_development' => 'Capacity development and skills enhancement activities are organized regularly.',
                            'placement_support' => 'Adequate support is provided by Career Development and Placement Cell (CDPC).'
                        ]
                    ],
                    [
                        'title' => '8. Governance and Leadership',
                        'questions' => [
                            'transparent_leadership' => 'The effective and transparent leadership is reflected in various institutional policies/ practices.',
                            'equal_opportunity' => 'Management of Institution follows "Equal Opportunity" for all.',
                            'student_felicitation' => 'Institute felicitates achievement of students through various modes.'
                        ]
                    ]
                ];
            @endphp

            @foreach($sections as $section)
                <div class="space-y-3 bg-gray-50 p-5 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 pb-2 border-b-2 border-blue-600">{{ $section['title'] }} <span class="text-red-500">*</span></h3>
                    
                    @foreach($section['questions'] as $key => $question)
                        <div class="bg-white border-l-4 border-blue-400 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between gap-4">
                                <label class="flex-1 text-sm font-medium text-gray-800 leading-relaxed">{{ $question }}</label>
                                <div class="flex items-center space-x-2 rating-container" data-field="{{ $key }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="rating-circle" data-value="{{ $i }}" title="Rate {{ $i }} out of 5">
                                            <input type="radio" name="{{ $key }}" value="{{ $i }}" {{ old($key) == $i ? 'checked' : '' }} class="sr-only" required>
                                            <div class="circle">
                                                <span class="number">{{ $i }}</span>
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error($key)
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Additional Feedback -->
            <div class="space-y-4 bg-gradient-to-r from-indigo-50 to-purple-50 p-5 rounded-lg border border-indigo-200">
                <h3 class="text-lg font-bold text-gray-800 pb-2 border-b-2 border-indigo-600 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                    </svg>
                    Additional Feedback
                </h3>
                
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">💡 Point specific suggestions (for above points), if any:</label>
                    <textarea name="specific_suggestions" rows="3" class="input-field text-sm" placeholder="Share your specific suggestions to help us improve...">{{ old('specific_suggestions') }}</textarea>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">💬 Your general suggestions are welcome:</label>
                    <textarea name="general_suggestions" rows="3" class="input-field text-sm" placeholder="Share any additional thoughts or suggestions...">{{ old('general_suggestions') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4 border-t-2 border-gray-200">
                <p class="text-sm text-gray-600 font-medium">
                    <span class="text-red-500 text-lg">*</span> Required fields
                </p>
                <div class="flex space-x-3">
                    <a href="{{ route('forms.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium shadow-sm hover:shadow">Cancel</a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-medium shadow-md hover:shadow-lg flex items-center transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit Feedback
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.rating-circle {
    cursor: pointer;
    position: relative;
}

.rating-circle .circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border: 2px solid #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.rating-circle .number {
    font-size: 13px;
    font-weight: 700;
    color: #6b7280;
    transition: all 0.3s ease;
    opacity: 0;
}

.rating-circle:first-of-type .number,
.rating-circle:last-of-type .number {
    opacity: 1;
}

.rating-circle:hover .circle {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.rating-circle input:checked ~ .circle,
.rating-circle.selected .circle {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-color: #1d4ed8;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
    transform: scale(1.1);
}

.rating-circle input:checked ~ .circle .number,
.rating-circle.selected .circle .number {
    color: white;
    opacity: 1;
}

.rating-container:hover .rating-circle .circle {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
}

.rating-container:hover .rating-circle:hover .circle,
.rating-container:hover .rating-circle:hover ~ .rating-circle .circle {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    border-color: #2563eb;
    transform: scale(1.05);
}

.rating-container:hover .rating-circle:hover .number,
.rating-container:hover .rating-circle:hover ~ .rating-circle .number {
    color: white;
    opacity: 1;
}

/* Smooth animations */
.rating-circle .circle {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Circle rating interaction
    document.querySelectorAll('.rating-container').forEach(container => {
        const circles = container.querySelectorAll('.rating-circle');
        
        circles.forEach((circle, index) => {
            // Click handler
            circle.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                
                // Remove selected class from all circles
                circles.forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked and previous circles
                circles.forEach((c, idx) => {
                    if (idx < value) {
                        c.classList.add('selected');
                    }
                });
            });
        });
        
        // Hover effect
        container.addEventListener('mouseenter', function() {
            circles.forEach(c => c.classList.remove('hover-preview'));
        });
        
        circles.forEach((circle) => {
            circle.addEventListener('mouseenter', function() {
                const value = parseInt(this.dataset.value);
                circles.forEach((c, idx) => {
                    if (idx < value) {
                        c.classList.add('hover-preview');
                    } else {
                        c.classList.remove('hover-preview');
                    }
                });
            });
        });
        
        // Reset on mouse leave
        container.addEventListener('mouseleave', function() {
            circles.forEach(c => c.classList.remove('hover-preview'));
            
            // Restore selected state
            const checkedInput = container.querySelector('input[type="radio"]:checked');
            if (checkedInput) {
                const value = parseInt(checkedInput.value);
                circles.forEach((c, idx) => {
                    if (idx < value) {
                        c.classList.add('selected');
                    } else {
                        c.classList.remove('selected');
                    }
                });
            }
        });
        
        // Set initial state for pre-selected values
        const checkedInput = container.querySelector('input[type="radio"]:checked');
        if (checkedInput) {
            const value = parseInt(checkedInput.value);
            circles.forEach((c, idx) => {
                if (idx < value) {
                    c.classList.add('selected');
                }
            });
        }
    });
});
</script>
@endsection
