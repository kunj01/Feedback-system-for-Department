@extends('layouts.app')

@section('title', 'Disagree Responses')
@section('page-title', 'Disagree Responses')

@section('content')
<div class="max-w-7xl mx-auto py-4 px-3 sm:px-4">

    <!-- Header + Inline Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h1 class="text-lg font-bold text-gray-900">Disagree Responses</h1>
        </div>
        <div class="flex items-center gap-4 mt-1 sm:mt-0 text-xs font-medium">
            <span class="text-gray-500">Total: <span class="text-gray-900 font-bold">{{ $stats['total'] }}</span></span>
            <span class="text-red-400">S.Disagree: <span class="text-red-600 font-bold">{{ $stats['strongly_disagree'] }}</span></span>
            <span class="text-orange-400">Disagree: <span class="text-orange-600 font-bold">{{ $stats['disagree'] }}</span></span>
            <span class="text-blue-400">With Reason: <span class="text-blue-600 font-bold">{{ $stats['with_reasoning'] }}</span></span>
        </div>
    </div>

    <!-- Compact Filters -->
    <div class="bg-white rounded-lg shadow-sm border px-3 py-2 mb-3">
        <form method="GET" action="{{ route('admin.student-feedback.disagree') }}" class="flex flex-wrap gap-2 items-center">
            <select name="rating_type" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 focus:border-transparent">
                <option value="all" {{ request('rating_type', 'all') == 'all' ? 'selected' : '' }}>All Ratings</option>
                <option value="Strongly Disagree" {{ request('rating_type') == 'Strongly Disagree' ? 'selected' : '' }}>Strongly Disagree</option>
                <option value="Disagree" {{ request('rating_type') == 'Disagree' ? 'selected' : '' }}>Disagree</option>
            </select>
            <select name="subject_id" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>
            <select name="teacher_id" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition text-xs font-medium">Filter</button>
            <a href="{{ route('admin.student-feedback.disagree') }}" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition text-xs">Clear</a>
        </form>
    </div>

    <!-- Results -->
    @if($disagreeItems->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <svg class="w-10 h-10 mx-auto text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-semibold text-gray-600">No disagree responses found</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Question</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Reasoning</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($disagreeItems as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-2 whitespace-nowrap">
                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ $item['rating'] === 'Strongly Disagree' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $item['rating'] === 'Strongly Disagree' ? 'S. Disagree' : 'Disagree' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 max-w-[200px]">
                                <p class="text-gray-800 leading-snug line-clamp-2" title="{{ $item['question'] }}">{{ $item['question'] }}</p>
                            </td>
                            <td class="px-3 py-2 max-w-[180px]">
                                @if($item['reasoning'])
                                    <p class="text-gray-600 leading-snug line-clamp-2" title="{{ $item['reasoning'] }}">{{ $item['reasoning'] }}</p>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-800">{{ $item['student_name'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-gray-600">{{ $item['subject'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-gray-600">{{ $item['teacher'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-gray-400">{{ $item['submitted_at']->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
