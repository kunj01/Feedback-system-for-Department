@extends('layouts.app')

@section('title', 'All Comments')
@section('page-title', 'All Comments')

@section('content')
<div class="max-w-7xl mx-auto py-4 px-3 sm:px-4">

    <!-- Header + Inline Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            <h1 class="text-lg font-bold text-gray-900">All Comments</h1>
        </div>
        <div class="flex items-center gap-4 mt-1 sm:mt-0 text-xs font-medium">
            <span class="text-gray-500">Total: <span class="text-gray-900 font-bold">{{ $stats['total'] }}</span></span>
            <span class="text-emerald-400">Open-ended: <span class="text-emerald-600 font-bold">{{ $stats['open_ended'] }}</span></span>
            <span class="text-red-400">Reasoning: <span class="text-red-600 font-bold">{{ $stats['reasoning'] }}</span></span>
        </div>
    </div>

    <!-- Compact Filters -->
    <div class="bg-white rounded-lg shadow-sm border px-3 py-2 mb-3">
        <form method="GET" action="{{ route('admin.student-feedback.comments') }}" class="flex flex-wrap gap-2 items-center">
            <select name="type" class="px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-indigo-500 focus:border-transparent">
                <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                <option value="open_ended" {{ request('type') == 'open_ended' ? 'selected' : '' }}>Open-ended Only</option>
                <option value="reasoning" {{ request('type') == 'reasoning' ? 'selected' : '' }}>Disagree Reasoning</option>
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
            <a href="{{ route('admin.student-feedback.comments') }}" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition text-xs">Clear</a>
        </form>
    </div>

    <!-- Results -->
    @if($comments->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            <p class="text-sm font-semibold text-gray-600">No comments found</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Question</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Comment</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Teacher</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($comments as $comment)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-2 whitespace-nowrap">
                                @if($comment['type'] === 'open_ended')
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Feedback</span>
                                @else
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Reasoning</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 max-w-[180px]">
                                <p class="text-gray-700 leading-snug line-clamp-2" title="{{ $comment['question'] }}">{{ $comment['question'] }}</p>
                            </td>
                            <td class="px-3 py-2 max-w-[250px]">
                                <p class="text-gray-800 leading-snug line-clamp-2" title="{{ $comment['comment'] }}">{{ $comment['comment'] }}</p>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-800">{{ $comment['student_name'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-gray-600">{{ $comment['subject'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-gray-600">{{ $comment['teacher'] }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-gray-400">{{ $comment['submitted_at']->format('d M Y') }}</td>
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
