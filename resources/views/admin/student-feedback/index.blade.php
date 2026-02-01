@extends('layouts.app')

@section('title', 'Student Feedback Responses')
@section('page-title', 'Student Feedback Responses')

@section('content')
<div class="space-y-6">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Feedback</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Average Rating</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['average_rating'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['this_month'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">5-Star Ratings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['ratings_breakdown'][5] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="GET" action="{{ route('admin.student-feedback.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Student, Subject, Faculty..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Ratings</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.student-feedback.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.student-feedback.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feedbacks as $feedback)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $feedback->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $feedback->student->user->name ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $feedback->email ?? $feedback->student->user->email ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $feedback->formAssignment->subject->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $feedback->formAssignment->teacher->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $ratings = [];
                                if (is_array($feedback->responses)) {
                                    foreach ($feedback->responses as $key => $value) {
                                        if (is_numeric($value) && $value >= 1 && $value <= 5) {
                                            $ratings[] = $value;
                                        }
                                    }
                                }
                                $avgRating = !empty($ratings) ? number_format(array_sum($ratings) / count($ratings), 1) : 'N/A';
                            @endphp
                            <div class="flex items-center">
                                @if($avgRating !== 'N/A')
                                    <span class="text-yellow-400 mr-1">★</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $avgRating }}</span>
                                @else
                                    <span class="text-sm text-gray-500">N/A</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $feedback->created_at->format('M d, Y') }}<br>
                            <span class="text-xs">{{ $feedback->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('form-responses.view', $feedback->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-4 text-lg font-medium text-gray-900">No feedback found</p>
                            <p class="mt-2 text-sm text-gray-500">There are no feedback responses matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($feedbacks->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $feedbacks->links() }}
        </div>
        @endif
    </div>

    <!-- Rating Distribution Chart -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating Distribution</h3>
        <div class="space-y-3">
            @foreach([5, 4, 3, 2, 1] as $rating)
            @php
                $count = $stats['ratings_breakdown'][$rating];
                $percentage = $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0;
            @endphp
            <div class="flex items-center">
                <div class="w-20 flex items-center">
                    @for($i = 0; $i < $rating; $i++)
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
                <div class="flex-1 mx-4">
                    <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                        <div class="bg-yellow-400 h-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                <div class="w-20 text-right">
                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                    <span class="text-xs text-gray-500">({{ $percentage }}%)</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
