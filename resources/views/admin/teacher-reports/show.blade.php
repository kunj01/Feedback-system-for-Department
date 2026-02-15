@extends('layouts.app')

@section('title', 'Teacher Performance Report - ' . $teacher->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 text-center border-b-2 border-blue-600 pb-6">
        <h1 class="text-3xl font-bold text-gray-900">U. V. Patel College of Engineering</h1>
        <h2 class="text-2xl font-semibold text-blue-600 mt-3">Teacher Performance Report</h2>
        <div class="mt-4 bg-blue-50 rounded-lg p-4 inline-block">
            <h3 class="text-xl font-bold text-gray-800">{{ $teacher->name }}</h3>
            @if($teacher->department)
                <p class="text-gray-600">Department: {{ $teacher->department }}</p>
            @endif
            @if($teacher->subjects->isNotEmpty())
                <p class="text-gray-600 text-sm mt-1">
                    Subjects: {{ $teacher->subjects->pluck('name')->implode(', ') }}
                </p>
            @endif
        </div>
        <div class="mt-4 text-gray-600 space-y-1">
            <p><strong>Academic Year:</strong> {{ $analysis['title_info']['academic_year'] }}</p>
            <p><strong>Report Generated:</strong> {{ $analysis['title_info']['report_date'] }}</p>
            <p><strong>Total Responses:</strong> {{ $analysis['title_info']['total_responses'] }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('admin.teacher-reports.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Teachers List
        </a>
        <div class="space-x-3">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
            <a href="{{ route('admin.teacher-reports.export-pdf', $teacher->id) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
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
                    Based on {{ $responses->count() }} responses across 8 instructor parameters
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-900 mb-3">Rating Distribution</h4>
                <div class="space-y-2">
                    @foreach($analysis['rating_distribution'] as $rating => $percentage)
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

    <!-- Question-wise Statistical Analysis -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            3. Question-wise Statistical Analysis
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Question</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Strongly Agree</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Agree</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Neutral</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disagree</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Strongly Disagree</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($analysis['question_stats'] as $stats)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stats['question'] }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">
                                <span class="font-semibold">{{ $stats['percentages']['Strongly Agree'] ?? 0 }}%</span>
                                <div class="text-xs text-gray-500">({{ $stats['responses']['Strongly Agree'] }})</div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">
                                <span class="font-semibold">{{ $stats['percentages']['Agree'] ?? 0 }}%</span>
                                <div class="text-xs text-gray-500">({{ $stats['responses']['Agree'] }})</div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">
                                <span class="font-semibold">{{ $stats['percentages']['Neutral'] ?? 0 }}%</span>
                                <div class="text-xs text-gray-500">({{ $stats['responses']['Neutral'] }})</div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">
                                <span class="font-semibold">{{ $stats['percentages']['Disagree'] ?? 0 }}%</span>
                                <div class="text-xs text-gray-500">({{ $stats['responses']['Disagree'] }})</div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-700">
                                <span class="font-semibold">{{ $stats['percentages']['Strongly Disagree'] ?? 0 }}%</span>
                                <div class="text-xs text-gray-500">({{ $stats['responses']['Strongly Disagree'] }})</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($stats['average'] >= 4.5) bg-green-100 text-green-800
                                    @elseif($stats['average'] >= 4.0) bg-blue-100 text-blue-800
                                    @elseif($stats['average'] >= 3.0) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ number_format(($stats['average'] / 5) * 100, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Strengths and Areas for Improvement -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            4. Strengths and Areas for Improvement
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

    <!-- Student Comments on Low Ratings -->
    @if(!empty($analysis['low_rating_reasoning']))
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                5. Student Comments on Low Ratings
            </h3>
            @foreach($analysis['low_rating_reasoning'] as $field => $reasons)
                @if(!empty($reasons))
                    <div class="mb-4 bg-orange-50 p-4 rounded-lg border border-orange-200">
                        <h4 class="font-semibold text-orange-900 mb-2">
                            {{ $analysis['question_stats'][$field]['question'] ?? $field }}
                        </h4>
                        <ul class="space-y-1 text-sm text-gray-700">
                            @foreach($reasons as $reason)
                                <li class="flex items-start">
                                    <span class="text-orange-600 mr-2">•</span>
                                    <span>{{ $reason }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <!-- Recommendations -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
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
        <p class="mt-1">U. V. Patel College of Engineering - Academic Year 2023-24</p>
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
