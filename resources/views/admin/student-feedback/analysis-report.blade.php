@extends('layouts.app')

@section('title', 'Student Feedback Analysis Report')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 text-center border-b-4 border-blue-600 pb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-lg p-6">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Smt. K. D. Patel Department of Information Technology</h1>
        <h2 class="text-3xl font-semibold text-blue-600 mt-3 mb-4">Analysis of Student Feedback</h2>
        <div class="mt-4 text-gray-700 space-y-2 bg-white rounded-lg p-4 inline-block shadow-sm">
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
                <strong>Total Responses Analyzed:</strong> {{ $analysis['title_info']['total_responses'] }}
            </p>
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
        <h3 class="text-xl font-semibold text-gray-800 mb-6 pb-3 border-b-2 border-blue-600 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            3. Visual Analysis - Percentage Distribution
        </h3>
        
        <div class="space-y-5">
            @foreach($analysis['question_stats'] as $field => $stats)
                <!-- Card for each question -->
                <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl border-2 border-gray-200 p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                        <!-- Question Text - Left Side -->
                        <div class="lg:w-2/5">
                            <p class="text-sm font-medium text-gray-800 leading-relaxed">{{ $stats['question'] }}</p>
                        </div>
                        
                        <!-- Progress Bar & Badges - Right Side -->
                        <div class="lg:w-3/5 space-y-3">
                            <!-- Horizontal Progress Bar -->
                            <div class="flex h-10 rounded-lg overflow-hidden border-2 border-gray-300 shadow-md">
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
                                             class="flex items-center justify-center text-white text-xs font-bold hover:opacity-85 transition-all duration-200 cursor-pointer relative group"
                                             title="{{ $rating }}: {{ number_format($percentage, 1) }}%">
                                            @if($percentage >= 7)
                                                <span class="z-10">{{ number_format($percentage, 1) }}%</span>
                                            @endif
                                            <!-- Tooltip on hover -->
                                            <div class="absolute hidden group-hover:block bg-gray-900 text-white text-xs rounded-md py-1.5 px-3 -top-10 left-1/2 transform -translate-x-1/2 whitespace-nowrap z-30 shadow-xl">
                                                <span class="font-semibold">{{ $rating }}</span>: {{ number_format($percentage, 1) }}%
                                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900"></div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            
                            <!-- Percentage Badges Below Bar -->
                            <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
                                @foreach(['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'] as $rating)
                                    @php 
                                        $percentage = $stats['percentages'][$rating] ?? 0;
                                        $badgeColors = [
                                            'Strongly Agree' => 'bg-green-100 text-green-800 border-green-300',
                                            'Agree' => 'bg-blue-100 text-blue-800 border-blue-300',
                                            'Neutral' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                            'Disagree' => 'bg-orange-100 text-orange-800 border-orange-300',
                                            'Strongly Disagree' => 'bg-red-100 text-red-800 border-red-300'
                                        ];
                                    @endphp
                                    @if($percentage > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $badgeColors[$rating] }}">
                                            {{ $rating }}: {{ number_format($percentage, 1) }}%
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="mt-8 pt-6 border-t-2 border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-5">
            <h5 class="text-center text-base font-bold text-gray-700 mb-5 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Rating Legend
            </h5>
            <div class="flex justify-center gap-4 flex-wrap">
                <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border-2 border-green-300 shadow-sm hover:shadow-md transition-shadow">
                    <span class="w-7 h-7 bg-green-500 rounded-md shadow-sm border-2 border-green-600"></span>
                    <span class="text-sm font-semibold text-gray-800">Strongly Agree</span>
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border-2 border-blue-300 shadow-sm hover:shadow-md transition-shadow">
                    <span class="w-7 h-7 bg-blue-500 rounded-md shadow-sm border-2 border-blue-600"></span>
                    <span class="text-sm font-semibold text-gray-800">Agree</span>
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border-2 border-yellow-300 shadow-sm hover:shadow-md transition-shadow">
                    <span class="w-7 h-7 bg-yellow-500 rounded-md shadow-sm border-2 border-yellow-600"></span>
                    <span class="text-sm font-semibold text-gray-800">Neutral</span>
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border-2 border-orange-300 shadow-sm hover:shadow-md transition-shadow">
                    <span class="w-7 h-7 bg-orange-500 rounded-md shadow-sm border-2 border-orange-600"></span>
                    <span class="text-sm font-semibold text-gray-800">Disagree</span>
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border-2 border-red-300 shadow-sm hover:shadow-md transition-shadow">
                    <span class="w-7 h-7 bg-red-500 rounded-md shadow-sm border-2 border-red-600"></span>
                    <span class="text-sm font-semibold text-gray-800">Strongly Disagree</span>
                </div>
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
        <p class="mt-1">Smt. K. D. Patel Department of Information Technology - Academic Year {{ $analysis['title_info']['academic_year'] }}</p>
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
    /* Hide tooltips and hover effects in print */
    .group-hover\\:block {
        display: none !important;
    }
    /* Simplify cards for printing */
    .hover\\:shadow-lg {
        box-shadow: none !important;
    }
}

/* Tooltip styling */
.group:hover .group-hover\\:block {
    display: block;
}

/* Smooth transitions for all interactive elements */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-opacity {
    transition: opacity 0.2s ease-in-out;
}

.transition-shadow {
    transition: box-shadow 0.3s ease-in-out;
}

/* Ensure progress bars don't overflow */
.overflow-hidden {
    overflow: hidden;
}

/* Card hover effect */
.hover\\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .lg\\:w-2\\/5 {
        width: 100% !important;
        text-align: left !important;
        margin-bottom: 0.75rem;
    }
    .lg\\:w-3\\/5 {
        width: 100% !important;
    }
}

@media (max-width: 768px) {
    /* Stack badges vertically on small screens */
    .flex-wrap {
        flex-direction: column;
        align-items: flex-start;
    }
    
    /* Make table text smaller on mobile */
    table {
        font-size: 0.75rem;
    }
    
    /* Adjust padding for mobile */
    .px-4 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
}

/* Improve table responsiveness */
@media print {
    table {
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    thead {
        display: table-header-group;
    }
}

/* Badge animations */
.inline-flex {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection
