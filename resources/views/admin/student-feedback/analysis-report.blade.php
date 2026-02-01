@extends('layouts.app')

@section('title', 'Student Feedback Analysis Report')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 text-center border-b-2 border-blue-600 pb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $analysis['title_info']['institute'] }}</h1>
        <h2 class="text-2xl font-semibold text-blue-600 mt-3">Analysis of Student Feedback</h2>
        <div class="mt-4 text-gray-600 space-y-1">
            <p><strong>Academic Year:</strong> {{ $analysis['title_info']['academic_year'] }}</p>
            <p><strong>Report Generated:</strong> {{ $analysis['title_info']['report_date'] }}</p>
            <p><strong>Total Responses Analyzed:</strong> {{ $analysis['title_info']['total_responses'] }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('admin.student-feedback.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Feedback List
        </a>
        <div class="space-x-3">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
            <a href="{{ route('admin.student-feedback.analysis.export-pdf') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
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
                <div class="text-4xl font-bold text-blue-600">{{ $analysis['overall_average'] }}<span class="text-2xl">/5.0</span></div>
                <div class="mt-2 text-sm text-blue-700">
                    Based on {{ $analysis['total_responses'] }} responses across 20 parameters
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

    <!-- Visual Analysis Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b-2 border-blue-600">
            3. Visual Analysis
        </h3>
        <h4 class="text-center text-lg font-semibold text-gray-700 mb-6">Student Feedback Analysis - Percentage Distribution</h4>
        
        <div class="space-y-2">
            @foreach($analysis['question_stats'] as $field => $stats)
                <div class="flex items-center gap-2">
                    <div class="w-2/5 text-xs text-gray-700 pr-2 text-right leading-tight">{{ $stats['question'] }}</div>
                    <div class="w-3/5 flex h-7 rounded overflow-hidden border border-gray-300 shadow-sm">
                        @php
                            $colors = [
                                'Strongly Agree' => '#10b981',
                                'Agree' => '#3b82f6',
                                'Neutral' => '#f59e0b',
                                'Disagree' => '#f97316',
                                'Strongly Disagree' => '#ef4444'
                            ];
                        @endphp
                        @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $rating)
                            @php $percentage = $stats['percentages'][$rating] ?? 0; @endphp
                            @if($percentage > 0)
                                <div style="width: {{ $percentage }}%; background-color: {{ $colors[$rating] }}" 
                                     class="flex items-center justify-center text-white text-xs font-semibold hover:opacity-90 transition-opacity"
                                     title="{{ $rating }}: {{ $percentage }}%">
                                    @if($percentage >= 10){{ number_format($percentage, 1) }}%@endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="mt-8 pt-4 border-t flex justify-center gap-8 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 bg-green-500 rounded shadow-sm"></span>
                <span class="text-sm font-medium text-gray-700">Strongly Agree</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 bg-blue-500 rounded shadow-sm"></span>
                <span class="text-sm font-medium text-gray-700">Agree</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 bg-yellow-500 rounded shadow-sm"></span>
                <span class="text-sm font-medium text-gray-700">Neutral</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 bg-orange-500 rounded shadow-sm"></span>
                <span class="text-sm font-medium text-gray-700">Disagree</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 bg-red-500 rounded shadow-sm"></span>
                <span class="text-sm font-medium text-gray-700">Strongly Disagree</span>
            </div>
        </div>
    </div>

    <!-- Question-wise Statistical Analysis -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
            4. Question-wise Statistical Analysis
        </h3>

        <!-- Section 1: Student Experience -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-3 bg-blue-50 p-3 rounded">Section 1: Student Experience</h4>
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
                        @php
                            $studentExperienceQuestions = ['prepare_for_class', 'ask_questions_freely', 'actively_participate', 'feel_comfortable_sharing', 'developing_skills'];
                            $sno = 1;
                        @endphp
                        @foreach($studentExperienceQuestions as $field)
                            @if(isset($analysis['question_stats'][$field]))
                                @php $stats = $analysis['question_stats'][$field]; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300">{{ $sno++ }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-300">{{ $stats['question'] }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Strongly Agree'] ?? 0) > 0 ? 'bg-green-50' : '' }}">
                                        {{ $stats['percentages']['Strongly Agree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Agree'] ?? 0) > 0 ? 'bg-blue-50' : '' }}">
                                        {{ $stats['percentages']['Agree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Neutral'] ?? 0) > 0 ? 'bg-yellow-50' : '' }}">
                                        {{ $stats['percentages']['Neutral'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Disagree'] ?? 0) > 0 ? 'bg-orange-50' : '' }}">
                                        {{ $stats['percentages']['Disagree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Strongly Disagree'] ?? 0) > 0 ? 'bg-red-50' : '' }}">
                                        {{ $stats['percentages']['Strongly Disagree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-bold
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

        <!-- Section 2: Instructor Experience -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-3 bg-purple-50 p-3 rounded">Section 2: Instructor Experience</h4>
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
                        @php
                            $instructorQuestions = ['instructor_approachable', 'instructor_effective', 'presentations_clear', 'instructor_stimulated', 'instructor_used_time', 'instructor_introduces_concepts', 'instructor_positive_environment', 'instructor_communicates'];
                        @endphp
                        @foreach($instructorQuestions as $field)
                            @if(isset($analysis['question_stats'][$field]))
                                @php $stats = $analysis['question_stats'][$field]; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300">{{ $sno++ }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-300">{{ $stats['question'] }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Strongly Agree'] ?? 0) > 0 ? 'bg-green-50' : '' }}">
                                        {{ $stats['percentages']['Strongly Agree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Agree'] ?? 0) > 0 ? 'bg-blue-50' : '' }}">
                                        {{ $stats['percentages']['Agree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Neutral'] ?? 0) > 0 ? 'bg-yellow-50' : '' }}">
                                        {{ $stats['percentages']['Neutral'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Disagree'] ?? 0) > 0 ? 'bg-orange-50' : '' }}">
                                        {{ $stats['percentages']['Disagree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Strongly Disagree'] ?? 0) > 0 ? 'bg-red-50' : '' }}">
                                        {{ $stats['percentages']['Strongly Disagree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-bold
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

        <!-- Section 3: Course Content -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-3 bg-green-50 p-3 rounded">Section 3: Course Content</h4>
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
                        @php
                            $courseContentQuestions = ['learning_objectives_clear', 'content_organized', 'opportunities_practice', 'access_materials', 'content_prepares', 'teaching_assessments', 'diverse_perspectives'];
                        @endphp
                        @foreach($courseContentQuestions as $field)
                            @if(isset($analysis['question_stats'][$field]))
                                @php $stats = $analysis['question_stats'][$field]; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300">{{ $sno++ }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-300">{{ $stats['question'] }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Strongly Agree'] ?? 0) > 0 ? 'bg-green-50' : '' }}">
                                        {{ $stats['percentages']['Strongly Agree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Agree'] ?? 0) > 0 ? 'bg-blue-50' : '' }}">
                                        {{ $stats['percentages']['Agree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Neutral'] ?? 0) > 0 ? 'bg-yellow-50' : '' }}">
                                        {{ $stats['percentages']['Neutral'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Disagree'] ?? 0) > 0 ? 'bg-orange-50' : '' }}">
                                        {{ $stats['percentages']['Disagree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold border-r border-gray-300 {{ ($stats['percentages']['Strongly Disagree'] ?? 0) > 0 ? 'bg-red-50' : '' }}">
                                        {{ $stats['percentages']['Strongly Disagree'] ?? 0 }}%
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded text-sm font-bold
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
    </div>

    <!-- Strengths and Areas for Improvement -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
            5. Strengths and Areas for Improvement
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Strengths -->
            <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Strengths (Rating ≥ 4.5)
                </h4>
                @if(!empty($analysis['strengths_weaknesses']['strengths']))
                    <ul class="space-y-2">
                        @foreach($analysis['strengths_weaknesses']['strengths'] as $strength)
                            <li class="flex items-start">
                                <span class="inline-block w-12 text-green-700 font-bold text-sm">{{ $strength['average'] }}</span>
                                <span class="text-gray-700 text-sm">{{ $strength['question'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 text-sm italic">No parameters scored 4.5 or higher</p>
                @endif
            </div>

            <!-- Areas for Improvement -->
            <div class="bg-red-50 rounded-lg p-5 border border-red-200">
                <h4 class="font-semibold text-red-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Areas for Improvement (Rating < 3.0)
                </h4>
                @if(!empty($analysis['strengths_weaknesses']['weaknesses']))
                    <ul class="space-y-2">
                        @foreach($analysis['strengths_weaknesses']['weaknesses'] as $weakness)
                            <li class="flex items-start">
                                <span class="inline-block w-12 text-red-700 font-bold text-sm">{{ $weakness['average'] }}</span>
                                <span class="text-gray-700 text-sm">{{ $weakness['question'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 text-sm italic">No parameters scored below 3.0 - Good overall performance!</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
            6. Recommendations
        </h3>
        <div class="space-y-3">
            @foreach($analysis['recommendations'] as $index => $recommendation)
                <div class="flex items-start bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <span class="inline-flex items-center justify-center w-6 h-6 mr-3 text-sm font-bold text-white bg-blue-600 rounded-full flex-shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <p class="text-gray-700">{{ $recommendation }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-gray-500 text-sm border-t pt-4 print:block">
        <p>This report was automatically generated by the Student Feedback System</p>
        <p class="mt-1">{{ $analysis['title_info']['institute'] }} - Academic Year {{ $analysis['title_info']['academic_year'] }}</p>
    </div>
</div>

<style>
@media print {
    .print\\:hidden {
        display: none !important;
    }
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection
