@extends('layouts.app')

@section('title', 'Speaker Feedback Detail')
@section('page-title', 'Speaker Feedback Detail')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Curriculum Feedback Detail</h2>
    <p class="text-gray-600 mt-1">Feedback from: {{ $speaker->name }}</p>
</div>

<!-- Speaker & Event Information -->
<div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Speaker & Event Information</h3>
    </div>
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Speaker Name</h4>
                <p class="text-gray-900 font-semibold">{{ $speaker->name }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                <p class="text-gray-900">{{ $speaker->email }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Department</h4>
                <p class="text-gray-900">{{ $speaker->department }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Venue</h4>
                <p class="text-gray-900">{{ $speaker->venue }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Event Date</h4>
                <p class="text-gray-900">{{ $speaker->date->format('l, F d, Y') }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Event Time</h4>
                <p class="text-gray-900">{{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Feedback Submitted</h4>
                <p class="text-gray-900">{{ $speaker->feedback->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Average Rating</h4>
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-blue-600">{{ $speaker->feedback->average_rating }}</span>
                    <span class="text-sm text-gray-500 ml-1">/ 5.0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Responses -->
<div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Curriculum Feedback Responses</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Visual</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $questions = [
                        ['q1_content_of_syllabus', 'Content of syllabus'],
                        ['q2_relevance_to_industry', 'Relevance of syllabus to industry/research requirements'],
                        ['q3_course_outcomes', 'Course outcomes are well defined'],
                        ['q4_reading_materials', 'Sufficient reading materials and digital resources provided'],
                        ['q5_advanced_topics', 'Incorporation of advanced topics'],
                        ['q6_pedagogy', 'Pedagogy proposed'],
                        ['q7_theory_practical_balance', 'Have a desired balance between theory and practical'],
                        ['q8_assessment_methods', 'Assessment methods are fair, measuring the outcomes'],
                        ['q9_project_component', 'Project component in the course, if applicable'],
                        ['q10_industrial_training', 'Industrial training/practical exposure in the course, if applicable'],
                    ];
                @endphp

                @foreach($questions as $index => $question)
                    @php
                        $field = $question[0];
                        $label = $question[1];
                        $rating = $speaker->feedback->$field ?? 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $label }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                @if($rating == 5) bg-green-100 text-green-800
                                @elseif($rating == 4) bg-blue-100 text-blue-800
                                @elseif($rating == 3) bg-yellow-100 text-yellow-800
                                @elseif($rating == 2) bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $rating }} / 5
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Additional Comments -->
@if($speaker->feedback->additional_comments)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Additional Comments</h3>
    </div>
    <div class="p-6">
        <p class="text-gray-700 whitespace-pre-line">{{ $speaker->feedback->additional_comments }}</p>
    </div>
</div>
@endif

<!-- Action Buttons -->
<div class="flex gap-4">
    <a href="{{ route('admin.speakers.feedback.responses') }}" 
       class="bg-gray-600 text-white px-6 py-2.5 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Feedback List
    </a>
    
    <button onclick="window.print()" 
            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Print Feedback
    </button>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .sidebar, nav, button, a {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
@endsection
