@extends('layouts.app')

@section('title', 'Subject Analysis Report - ' . $subject->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 text-center border-b-4 border-blue-600 pb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-lg p-6">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Smt. K. D. Patel Department of Information Technology</h1>
        <h2 class="text-3xl font-semibold text-blue-600 mt-3 mb-4">Subject Analysis Report</h2>
        <h3 class="text-2xl font-semibold text-gray-800 mt-2">{{ $subject->name }} ({{ $subject->code }})</h3>
        <div class="mt-4 text-gray-700 space-y-2 bg-white rounded-lg p-4 inline-block shadow-sm">
            <p class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <strong>Semester:</strong> {{ $analysis['title_info']['semester'] }}
            </p>
            <p class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <strong>Teachers:</strong> {{ $analysis['title_info']['teachers'] }}
            </p>
            <p class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <strong>Academic Year:</strong> {{ $analysis['title_info']['academic_year'] }}
            </p>
            <p class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <strong>Report Generated:</strong> {{ $analysis['title_info']['report_date'] }}
            </p>
            <p class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <strong>Total Responses:</strong> {{ $analysis['total_responses'] }}
            </p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('admin.subject-analysis.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Subject List
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
            <a href="{{ route('admin.subject-analysis.export-pdf', $subject->id) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export as PDF
            </a>
        </div>
    </div>

    <!-- Descriptive Analysis Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            1. Descriptive Analysis
        </h3>
        <div class="space-y-3 text-gray-700">
            @foreach($analysis['descriptive_analysis'] as $statement)
                <p class="pl-4 border-l-4 border-blue-400">{!! $statement !!}</p>
            @endforeach
        </div>
    </div>

    <!-- Overall Performance Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00 2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            2. Overall Performance Summary
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-900 mb-3">Overall Average Rating</h4>
                <div class="text-4xl font-bold text-blue-600">{{ number_format(($analysis['overall_average'] / 5) * 100, 1) }}<span class="text-2xl">%</span></div>
                <div class="mt-2 text-sm text-blue-700">
                    Based on {{ $analysis['total_responses'] }} responses
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-900 mb-3">Rating Distribution</h4>
                <div class="space-y-2">
                    @foreach($analysis['overall_percentages'] as $rating => $percentage)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700">{{ $rating }}:</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-800">{{ $percentage }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher-wise Breakdown (if multiple teachers) -->
    @if(count($analysis['teacher_breakdown']) > 1)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            3. Teacher-wise Performance Breakdown
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Teacher Name</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Responses</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Average Rating</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($analysis['teacher_breakdown'] as $teacher)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $teacher['name'] }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">{{ $teacher['response_count'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full
                                    @if($teacher['average_rating'] >= 4.5) bg-green-100 text-green-800
                                    @elseif($teacher['average_rating'] >= 4.0) bg-blue-100 text-blue-800
                                    @elseif($teacher['average_rating'] >= 3.0) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ number_format(($teacher['average_rating'] / 5) * 100, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Strengths and Weaknesses -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ count($analysis['teacher_breakdown']) > 1 ? '4' : '3' }}. Strengths and Areas for Improvement
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Strengths -->
            <div class="bg-green-50 p-5 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Strengths (≥90%)
                </h4>
                @if(!empty($analysis['strengths_weaknesses']['strengths']))
                    <ul class="space-y-2">
                        @foreach($analysis['strengths_weaknesses']['strengths'] as $strength)
                            <li class="text-sm text-gray-700 flex items-start">
                                <span class="inline-block w-2 h-2 bg-green-600 rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
                                <span>{{ $strength['question'] }} ({{ number_format(($strength['average'] / 5) * 100, 1) }}%)</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-600 italic">No parameters scored above 90%</p>
                @endif
            </div>

            <!-- Weaknesses -->
            <div class="bg-orange-50 p-5 rounded-lg border border-orange-200">
                <h4 class="font-semibold text-orange-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Areas for Improvement (<70%)
                </h4>
                @if(!empty($analysis['strengths_weaknesses']['weaknesses']))
                    <ul class="space-y-2">
                        @foreach($analysis['strengths_weaknesses']['weaknesses'] as $weakness)
                            <li class="text-sm text-gray-700 flex items-start">
                                <span class="inline-block w-2 h-2 bg-orange-600 rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
                                <span>{{ $weakness['question'] }} ({{ number_format(($weakness['average'] / 5) * 100, 1) }}%)</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-600 italic">No parameters scored below 70%</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Question-wise Statistical Analysis -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
            {{ count($analysis['teacher_breakdown']) > 1 ? '5' : '4' }}. Question-wise Statistical Analysis
        </h3>

        @php
            $questionCategories = [
                'Student Experience' => ['prepare_for_class', 'ask_questions_freely', 'actively_participate', 'feel_comfortable_sharing', 'developing_skills'],
                'Instructor Experience' => ['instructor_approachable', 'instructor_effective', 'presentations_clear', 'instructor_stimulated', 'instructor_used_time', 'instructor_introduces_concepts', 'instructor_positive_environment', 'instructor_communicates'],
                'Course Structure' => ['course_objectives_clear', 'course_material_relevant', 'assignments_helpful', 'feedback_timely'],
                'Learning Environment' => ['classroom_conducive', 'resources_adequate', 'overall_satisfaction'],
            ];
            $sectionNumber = 1;
        @endphp

        @foreach($questionCategories as $categoryName => $categoryFields)
            @php
                $categoryStats = array_filter($analysis['question_stats'], function($key) use ($categoryFields) {
                    return in_array($key, $categoryFields);
                }, ARRAY_FILTER_USE_KEY);
            @endphp

            @if(!empty($categoryStats))
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-700 mb-3 bg-blue-50 p-3 rounded">Section {{ $sectionNumber }}: {{ $categoryName }}</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider border-r border-blue-500">S.NO</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider border-r border-blue-500">Question / Parameter</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">Strongly Agree<br/>(%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">Agree<br/>(%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">Neutral<br/>(%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">Disagree<br/>(%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">Strongly Disagree<br/>(%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Average<br/>Rating</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $sno = 1; @endphp
                                @foreach($categoryFields as $field)
                                    @if(isset($analysis['question_stats'][$field]))
                                        @php $stats = $analysis['question_stats'][$field]; @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300">{{ $sno++ }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-300">{{ $stats['question'] }}</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-300">{{ $stats['percentages']['Strongly Agree'] ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-300">{{ $stats['percentages']['Agree'] ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-300">{{ $stats['percentages']['Neutral'] ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-300">{{ $stats['percentages']['Disagree'] ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-300">{{ $stats['percentages']['Strongly Disagree'] ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full
                                                    @if($stats['average'] >= 4.5) bg-green-600 text-white
                                                    @elseif($stats['average'] >= 4.0) bg-blue-600 text-white
                                                    @elseif($stats['average'] >= 3.0) bg-yellow-600 text-white
                                                    @else bg-red-600 text-white
                                                    @endif">
                                                    {{ $stats['average'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @php $sectionNumber++; @endphp
            @endif
        @endforeach
    </div>

    <!-- Recommendations -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            {{ count($analysis['teacher_breakdown']) > 1 ? '6' : '5' }}. Recommendations
        </h3>
        <div class="space-y-3">
            @foreach($analysis['recommendations'] as $index => $recommendation)
                <div class="flex items-start p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full text-sm font-bold mr-3">
                        {{ $index + 1 }}
                    </span>
                    <p class="text-sm text-gray-700 flex-1">{{ $recommendation }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer Note -->
    <div class="bg-gray-50 rounded-lg p-4 text-center text-sm text-gray-600 print:hidden">
        <p>This report is generated automatically based on student feedback responses. For detailed analysis or queries, please contact the academic administration.</p>
    </div>
</div>

<style>
@media print {
    body { font-size: 12px; }
    .print\:hidden { display: none !important; }
    .shadow-md { box-shadow: none !important; }
    .rounded-lg { border: 1px solid #e5e7eb; }
}
</style>
@endsection
