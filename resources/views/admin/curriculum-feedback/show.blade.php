@extends('layouts.app')

@section('title', 'View Feedback Response')
@section('page-title', 'Feedback Response Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('curriculum-feedback.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
        <div class="flex gap-2">
            <a href="{{ route('curriculum-feedback.edit', $feedback) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('curriculum-feedback.destroy', $feedback) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Respondent Information -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">Respondent Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500">Type</p>
                <p class="text-base text-gray-900 mt-1">
                    @if($feedback->respondent_type === 'academic')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Academic</span>
                    @elseif($feedback->respondent_type === 'teacher')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Teacher</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">Industry Professional</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Name</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->name ?: 'Anonymous' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Designation</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->designation ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Organization</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->organization ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->email ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Phone</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->phone ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Department</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->department ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Academic Year</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->academic_year ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Program</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->program ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status</p>
                <p class="text-base text-gray-900 mt-1">
                    @if($feedback->status === 'reviewed')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Reviewed</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Submitted</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Submitted On</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">IP Address</p>
                <p class="text-base text-gray-900 mt-1">{{ $feedback->ip_address ?: '-' }}</p>
            </div>
        </div>
    </div>

    @php
        function getRatingBadge($rating) {
            if (!$rating) return '<span class="text-gray-400">Not Rated</span>';
            $color = $rating >= 4 ? 'green' : ($rating >= 3 ? 'yellow' : 'red');
            $label = \App\Models\CurriculumFeedback::getRatingLabel($rating);
            return "<span class='px-3 py-1 text-sm font-semibold rounded-full bg-{$color}-100 text-{$color}-800'>{$rating}/5 - {$label}</span>";
        }
    @endphp

    <!-- Curriculum Aspects Ratings -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">Curriculum Aspects</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Relevance to Industry/Academic Needs</p>
                <p>{!! getRatingBadge($feedback->curriculum_relevance) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Breadth and Depth</p>
                <p>{!! getRatingBadge($feedback->curriculum_breadth) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Integration of Crosscutting Issues</p>
                <p>{!! getRatingBadge($feedback->curriculum_integration) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Flexibility and Choice</p>
                <p>{!! getRatingBadge($feedback->curriculum_flexibility) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Learning Outcomes Alignment</p>
                <p>{!! getRatingBadge($feedback->curriculum_outcomes) !!}</p>
            </div>
            <div class="pt-3 border-t">
                <div class="flex justify-between items-center">
                    <p class="text-base font-semibold text-gray-800">Average</p>
                    <p class="text-lg font-bold text-blue-600">{{ $feedback->curriculum_average }}/5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Teaching-Learning Process Ratings -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">Teaching-Learning Process</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Teaching Pedagogy Effectiveness</p>
                <p>{!! getRatingBadge($feedback->teaching_pedagogy) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Assessment Methods</p>
                <p>{!! getRatingBadge($feedback->teaching_assessment) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Practical Exposure</p>
                <p>{!! getRatingBadge($feedback->teaching_practical) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Innovation and Creativity</p>
                <p>{!! getRatingBadge($feedback->teaching_innovation) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Technology Integration</p>
                <p>{!! getRatingBadge($feedback->teaching_technology) !!}</p>
            </div>
            <div class="pt-3 border-t">
                <div class="flex justify-between items-center">
                    <p class="text-base font-semibold text-gray-800">Average</p>
                    <p class="text-lg font-bold text-blue-600">{{ $feedback->teaching_average }}/5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Infrastructure Ratings -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">Infrastructure and Resources</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Library Resources</p>
                <p>{!! getRatingBadge($feedback->infra_library) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Laboratory Facilities</p>
                <p>{!! getRatingBadge($feedback->infra_labs) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Technology Infrastructure</p>
                <p>{!! getRatingBadge($feedback->infra_technology) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Learning Spaces</p>
                <p>{!! getRatingBadge($feedback->infra_learning_spaces) !!}</p>
            </div>
            <div class="pt-3 border-t">
                <div class="flex justify-between items-center">
                    <p class="text-base font-semibold text-gray-800">Average</p>
                    <p class="text-lg font-bold text-blue-600">{{ $feedback->infrastructure_average }}/5</p>
                </div>
            </div>
        </div>
    </div>

    @if($feedback->respondent_type === 'industry')
    <!-- Industry Readiness Ratings -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">Industry Readiness</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Skill Development</p>
                <p>{!! getRatingBadge($feedback->industry_skills) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Employability</p>
                <p>{!! getRatingBadge($feedback->industry_employability) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Practical Knowledge</p>
                <p>{!! getRatingBadge($feedback->industry_practical) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Soft Skills</p>
                <p>{!! getRatingBadge($feedback->industry_soft_skills) !!}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-700">Professional Ethics</p>
                <p>{!! getRatingBadge($feedback->industry_ethics) !!}</p>
            </div>
            <div class="pt-3 border-t">
                <div class="flex justify-between items-center">
                    <p class="text-base font-semibold text-gray-800">Average</p>
                    <p class="text-lg font-bold text-blue-600">{{ $feedback->industry_readiness_average }}/5</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Overall Satisfaction -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">Overall Assessment</h3>
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Overall Satisfaction</p>
            <p class="text-2xl font-bold text-blue-600">{!! getRatingBadge($feedback->overall_satisfaction) !!}</p>
        </div>

        @if($feedback->strengths)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Strengths</p>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-gray-700">{{ $feedback->strengths }}</p>
            </div>
        </div>
        @endif

        @if($feedback->improvements)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Areas for Improvement</p>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-gray-700">{{ $feedback->improvements }}</p>
            </div>
        </div>
        @endif

        @if($feedback->suggestions)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-500 mb-2">Suggestions</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-700">{{ $feedback->suggestions }}</p>
            </div>
        </div>
        @endif

        @if($feedback->additional_comments)
        <div>
            <p class="text-sm font-medium text-gray-500 mb-2">Additional Comments</p>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-gray-700">{{ $feedback->additional_comments }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
