@extends('layouts.app')

@section('title', 'NAAC Curriculum Feedback Analysis Report')

@section('page-title', 'Curriculum Feedback Analysis Report (External Expert)')

@section('content')
<div class="max-w-7xl mx-auto bg-white shadow-lg rounded-lg">
    
    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-8 py-6 rounded-t-lg">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-2">{{ $title_info['institute'] }}</h1>
            <h3 class="text-lg font-medium mt-4 border-t border-white/30 pt-4">
                Analysis of Feedback on Curriculum (External Expert)
            </h3>
            <p class="text-sm mt-2">Academic Year: <span class="font-semibold">{{ $title_info['academic_year'] }}</span></p>
            <p class="text-xs mt-1 opacity-90">Report Generated: {{ $title_info['report_date'] }}</p>
            <p class="text-xs opacity-90">Total Responses: {{ $title_info['total_responses'] }}</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="px-8 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.speakers.feedback.responses') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Responses
            </a>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Report
            </button>
            <a href="{{ route('admin.speakers.analysis.export-pdf') }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="px-8 py-6">
        
        {{-- 1. Descriptive Analysis --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                1. Descriptive Analysis
            </h3>
            <div class="prose max-w-none">
                <ul class="space-y-3 text-gray-700 leading-relaxed">
                    @foreach($descriptive_analysis as $point)
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <span class="flex-1">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- 2. Question-wise Statistical Analysis --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                2. Question-wise Statistical Analysis
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                S.No
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                Question / Parameter
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                Excellent<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                Very Good<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                Good<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                Satisfactory<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-blue-500">
                                Needs Improvement<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                Average<br/>Rating
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $sno = 1; @endphp
                        @foreach($statistics as $questionKey => $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">
                                    {{ $sno++ }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">
                                    {{ $stat['label'] }}
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-200 bg-green-50">
                                    <span class="font-semibold">{{ number_format($stat['percentages'][5], 1) }}%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-200 bg-blue-50">
                                    <span class="font-semibold">{{ number_format($stat['percentages'][4], 1) }}%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-200 bg-yellow-50">
                                    <span class="font-semibold">{{ number_format($stat['percentages'][3], 1) }}%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-200 bg-orange-50">
                                    <span class="font-semibold">{{ number_format($stat['percentages'][2], 1) }}%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 border-r border-gray-200 bg-red-50">
                                    <span class="font-semibold">{{ number_format($stat['percentages'][1], 1) }}%</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900">
                                    <span class="font-bold text-blue-600">{{ number_format($stat['average'], 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 3. Visual Analysis --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                3. Visual Analysis
            </h3>
            <div class="bg-gray-50 p-6 rounded-lg">
                <canvas id="feedbackChart" class="w-full" style="max-height: 600px;"></canvas>
            </div>
            <div class="mt-4 flex justify-center space-x-6 text-sm">
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-green-500 rounded mr-2"></span>
                    <span>Excellent</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-blue-500 rounded mr-2"></span>
                    <span>Very Good</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-yellow-500 rounded mr-2"></span>
                    <span>Good</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-orange-500 rounded mr-2"></span>
                    <span>Satisfactory</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-red-500 rounded mr-2"></span>
                    <span>Needs Improvement</span>
                </div>
            </div>
        </section>

        {{-- 4. Overall Consolidated Summary --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                4. Overall Consolidated Summary
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-indigo-500">
                                Excellent<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-indigo-500">
                                Very Good<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-indigo-500">
                                Good<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-indigo-500">
                                Satisfactory<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r border-indigo-500">
                                Needs Improvement<br/>(%)
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                Overall Average<br/>Rating
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr>
                            <td class="px-4 py-4 text-base text-center font-bold text-gray-900 border-r border-gray-200 bg-green-50">
                                {{ number_format($overall_summary['percentages'][5], 2) }}%
                            </td>
                            <td class="px-4 py-4 text-base text-center font-bold text-gray-900 border-r border-gray-200 bg-blue-50">
                                {{ number_format($overall_summary['percentages'][4], 2) }}%
                            </td>
                            <td class="px-4 py-4 text-base text-center font-bold text-gray-900 border-r border-gray-200 bg-yellow-50">
                                {{ number_format($overall_summary['percentages'][3], 2) }}%
                            </td>
                            <td class="px-4 py-4 text-base text-center font-bold text-gray-900 border-r border-gray-200 bg-orange-50">
                                {{ number_format($overall_summary['percentages'][2], 2) }}%
                            </td>
                            <td class="px-4 py-4 text-base text-center font-bold text-gray-900 border-r border-gray-200 bg-red-50">
                                {{ number_format($overall_summary['percentages'][1], 2) }}%
                            </td>
                            <td class="px-4 py-4 text-base text-center font-bold text-indigo-700 bg-indigo-50">
                                {{ number_format($overall_summary['average'], 2) }} / 5.00
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-3 text-sm text-gray-600 italic">
                Note: Overall summary is calculated based on {{ $overall_summary['total_questions'] }} questions 
                across {{ $overall_summary['total_responses'] }} expert responses 
                (Total data points: {{ $overall_summary['total_questions'] * $overall_summary['total_responses'] }}).
            </p>
        </section>

        {{-- 5. Interpretation & Inference --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                5. Interpretation & Inference
            </h3>
            <div class="space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-600 p-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Overall Assessment:</h4>
                    <p class="text-gray-700 leading-relaxed text-justify">{{ $interpretations['overall'] }}</p>
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4">
                    <h4 class="font-semibold text-gray-800 mb-2">Parameter-wise Performance:</h4>
                    <p class="text-gray-700 leading-relaxed text-justify">{{ $interpretations['balance'] }}</p>
                </div>
            </div>
        </section>

        {{-- 6. Actionable Recommendations --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                6. Actionable Recommendations
            </h3>
            <div class="space-y-4">
                @foreach($recommendations as $index => $recommendation)
                    <div class="bg-white border border-gray-300 rounded-lg p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $recommendation['title'] }}</h4>
                                </div>
                                <p class="text-gray-700 leading-relaxed ml-11 text-justify">{{ $recommendation['description'] }}</p>
                            </div>
                            <span class="ml-4 px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap
                                @if($recommendation['priority'] === 'Critical') bg-red-100 text-red-800
                                @elseif($recommendation['priority'] === 'High') bg-orange-100 text-orange-800
                                @elseif($recommendation['priority'] === 'Medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ $recommendation['priority'] }} Priority
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 7. Conclusion --}}
        <section class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-600">
                7. Conclusion
            </h3>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <p class="text-gray-800 leading-relaxed text-justify">
                    The comprehensive analysis of curriculum feedback from external industry and academic experts 
                    provides valuable insights into the strengths and areas for improvement of the current curriculum. 
                    With an overall average rating of <strong class="text-blue-700">{{ number_format($overall_summary['average'], 2) }} out of 5.00</strong>, 
                    the curriculum demonstrates 
                    @if($overall_summary['average'] >= 4.5)
                        exceptional quality and industry alignment.
                    @elseif($overall_summary['average'] >= 4.0)
                        very good quality with strong industry alignment.
                    @elseif($overall_summary['average'] >= 3.5)
                        satisfactory quality with scope for enhancement.
                    @else
                        significant potential for improvement.
                    @endif
                </p>
                <p class="text-gray-800 leading-relaxed text-justify mt-3">
                    The feedback validates the curriculum's effectiveness in achieving learning outcomes while highlighting 
                    opportunities for continuous enhancement. Implementation of the recommended actions will further strengthen 
                    the curriculum's relevance, rigor, and responsiveness to evolving industry requirements. Regular stakeholder 
                    engagement and systematic curriculum review mechanisms will ensure sustained quality improvement and 
                    alignment with NAAC/NBA accreditation standards.
                </p>
                <p class="text-gray-800 leading-relaxed text-justify mt-3">
                    The department is committed to addressing the feedback constructively and implementing necessary modifications 
                    to enhance curriculum quality, thereby ensuring that graduates are well-prepared to meet the challenges of 
                    the professional world and contribute meaningfully to society.
                </p>
            </div>
        </section>

        {{-- Signature Section --}}
        <section class="mt-12 pt-6 border-t-2 border-gray-300">
            <div class="flex justify-between items-end">
                <div class="text-sm text-gray-600">
                    <p><strong>Prepared by:</strong> Academic Coordinator</p>
                    <p class="mt-1">Date: {{ $title_info['report_date'] }}</p>
                </div>
                <div class="text-sm text-gray-600 text-right">
                    <p><strong>Reviewed by:</strong> Head of Department</p>
                    <p class="mt-1">Signature: _________________</p>
                </div>
            </div>
        </section>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('feedbackChart');
    
    const labels = [
        @foreach($chart_data as $data)
            '{{ $data['label'] }}',
        @endforeach
    ];

    const chartData = {
        labels: labels,
        datasets: [
            {
                label: 'Excellent',
                data: [
                    @foreach($chart_data as $data)
                        {{ $data['data']['excellent'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1
            },
            {
                label: 'Very Good',
                data: [
                    @foreach($chart_data as $data)
                        {{ $data['data']['very_good'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            },
            {
                label: 'Good',
                data: [
                    @foreach($chart_data as $data)
                        {{ $data['data']['good'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(234, 179, 8, 0.8)',
                borderColor: 'rgba(234, 179, 8, 1)',
                borderWidth: 1
            },
            {
                label: 'Satisfactory',
                data: [
                    @foreach($chart_data as $data)
                        {{ $data['data']['satisfactory'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(249, 115, 22, 0.8)',
                borderColor: 'rgba(249, 115, 22, 1)',
                borderWidth: 1
            },
            {
                label: 'Needs Improvement',
                data: [
                    @foreach($chart_data as $data)
                        {{ $data['data']['needs_improvement'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }
        ]
    };

    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Percentage (%)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Curriculum Parameters',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Curriculum Feedback Analysis - Percentage Distribution',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.x.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
    
    .shadow-lg {
        box-shadow: none !important;
    }
}
</style>
@endsection
