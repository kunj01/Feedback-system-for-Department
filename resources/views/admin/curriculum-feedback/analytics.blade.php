@extends('layouts.app')

@section('title', 'Curriculum Feedback Analytics')
@section('page-title', 'Curriculum Feedback Analytics')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="card">
        <form method="GET" action="{{ route('curriculum-feedback.analytics') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="label">Academic Year</label>
                <select name="academic_year" class="input-field">
                    <option value="">All Years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ $academicYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Apply Filter</button>
            <a href="{{ route('curriculum-feedback.index') }}" class="btn-secondary">Back to List</a>
        </form>
    </div>

    <!-- Response Count Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <p class="text-blue-100 text-sm font-medium">Total Responses</p>
            <p class="text-3xl font-bold mt-1">{{ array_sum($responseCounts) }}</p>
        </div>
        <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
            <p class="text-green-100 text-sm font-medium">Academic</p>
            <p class="text-3xl font-bold mt-1">{{ $responseCounts['academic'] }}</p>
        </div>
        <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
            <p class="text-purple-100 text-sm font-medium">Teacher</p>
            <p class="text-3xl font-bold mt-1">{{ $responseCounts['teacher'] }}</p>
        </div>
        <div class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
            <p class="text-orange-100 text-sm font-medium">Industry</p>
            <p class="text-3xl font-bold mt-1">{{ $responseCounts['industry'] }}</p>
        </div>
    </div>

    @php
        function getProgressBar($value, $max = 5) {
            $percentage = ($value / $max) * 100;
            $colorClass = $percentage >= 80 ? 'bg-green-500' : ($percentage >= 60 ? 'bg-yellow-500' : 'bg-red-500');
            return [
                'percentage' => round($percentage),
                'colorClass' => $colorClass,
            ];
        }
    @endphp

    <!-- Overall Average Ratings -->
    @if($averages['overall'])
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Overall Average Ratings</h3>
        
        <!-- Curriculum Aspects -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Curriculum Aspects</h4>
            <div class="space-y-4">
                @foreach($averages['overall']['curriculum'] as $field => $value)
                    @php
                        $progress = getProgressBar($value);
                        $label = ucwords(str_replace('_', ' ', str_replace('curriculum_', '', $field)));
                    @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $value }}/5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="{{ $progress['colorClass'] }} h-3 rounded-full transition-all" 
                                 style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Teaching-Learning Process -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Teaching-Learning Process</h4>
            <div class="space-y-4">
                @foreach($averages['overall']['teaching'] as $field => $value)
                    @php
                        $progress = getProgressBar($value);
                        $label = ucwords(str_replace('_', ' ', str_replace('teaching_', '', $field)));
                    @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $value }}/5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="{{ $progress['colorClass'] }} h-3 rounded-full transition-all" 
                                 style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Infrastructure -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Infrastructure and Resources</h4>
            <div class="space-y-4">
                @foreach($averages['overall']['infrastructure'] as $field => $value)
                    @php
                        $progress = getProgressBar($value);
                        $label = ucwords(str_replace('_', ' ', str_replace('infra_', '', $field)));
                    @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $value }}/5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="{{ $progress['colorClass'] }} h-3 rounded-full transition-all" 
                                 style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Industry Readiness -->
        @if($responseCounts['industry'] > 0)
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Industry Readiness</h4>
            <div class="space-y-4">
                @foreach($averages['overall']['industry'] as $field => $value)
                    @php
                        $progress = getProgressBar($value);
                        $label = ucwords(str_replace('_', ' ', str_replace('industry_', '', $field)));
                    @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $value }}/5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="{{ $progress['colorClass'] }} h-3 rounded-full transition-all" 
                                 style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Overall Satisfaction -->
        <div class="mt-6 pt-6 border-t">
            <div class="flex justify-between items-center">
                <h4 class="text-lg font-semibold text-gray-700">Overall Satisfaction</h4>
                <span class="text-3xl font-bold text-blue-600">{{ $averages['overall']['overall_satisfaction'] }}/5</span>
            </div>
            @php
                $progress = getProgressBar($averages['overall']['overall_satisfaction']);
            @endphp
            <div class="w-full bg-gray-200 rounded-full h-4 mt-3">
                <div class="{{ $progress['colorClass'] }} h-4 rounded-full transition-all" 
                     style="width: {{ $progress['percentage'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Comparison by Respondent Type -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach(['academic', 'teacher', 'industry'] as $type)
            @if($averages[$type] && $responseCounts[$type] > 0)
            <div class="card">
                <h4 class="text-lg font-bold text-gray-800 mb-4 capitalize">{{ ucfirst($type) }} Perspective</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Curriculum</span>
                        <span class="font-bold text-blue-600">
                            {{ number_format(collect($averages[$type]['curriculum'])->avg(), 2) }}/5
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Teaching</span>
                        <span class="font-bold text-green-600">
                            {{ number_format(collect($averages[$type]['teaching'])->avg(), 2) }}/5
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Infrastructure</span>
                        <span class="font-bold text-purple-600">
                            {{ number_format(collect($averages[$type]['infrastructure'])->avg(), 2) }}/5
                        </span>
                    </div>
                    @if($type === 'industry' && isset($averages[$type]['industry']))
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Industry Readiness</span>
                        <span class="font-bold text-orange-600">
                            {{ number_format(collect($averages[$type]['industry'])->avg(), 2) }}/5
                        </span>
                    </div>
                    @endif
                    <div class="pt-3 border-t flex justify-between">
                        <span class="text-sm font-semibold text-gray-700">Overall</span>
                        <span class="font-bold text-gray-900">
                            {{ $averages[$type]['overall_satisfaction'] }}/5
                        </span>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @else
    <div class="card text-center py-12">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        <p class="mt-4 text-lg text-gray-600">No feedback data available</p>
        <p class="text-sm text-gray-500 mt-2">Start collecting feedback to view analytics</p>
    </div>
    @endif
</div>
@endsection
