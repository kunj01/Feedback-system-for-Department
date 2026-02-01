@extends('layouts.app')

@section('title', 'Teacher Performance Reports')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Teacher Performance Reports</h1>
        <p class="text-sm text-gray-600">Select a teacher to view their detailed performance report based on student feedback</p>
    </div>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.student-feedback.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Student Feedback
        </a>
    </div>

    <!-- Teachers List -->
    @if($teachers->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No teachers found in the system.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <ul class="divide-y divide-gray-200">
                @foreach($teachers as $teacher)
                    <li class="hover:bg-gray-50 transition-colors duration-150">
                        <div class="px-4 py-4 flex items-center justify-between">
                            <!-- Teacher Info -->
                            <div class="flex items-center flex-1 min-w-0">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Name and Department -->
                                <div class="ml-4 flex-1 min-w-0">
                                    <div class="flex items-center">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $teacher->name }}</h3>
                                        <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ $teacher->department ?? 'N/A' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Subjects -->
                                    @if($teacher->subjects->isNotEmpty())
                                        <div class="mt-1 flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <span class="truncate">
                                                {{ $teacher->subjects->pluck('name')->take(3)->join(', ') }}
                                                @if($teacher->subjects->count() > 3)
                                                    <span class="text-gray-400"> (+{{ $teacher->subjects->count() - 3 }} more)</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Feedback Count -->
                                <div class="ml-4 flex-shrink-0 text-center">
                                    <div class="text-lg font-bold text-blue-600">{{ $teacher->feedback_count }}</div>
                                    <div class="text-xs text-gray-500">Responses</div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="ml-4 flex-shrink-0 flex items-center space-x-2">
                                @if($teacher->feedback_count > 0)
                                    <a href="{{ route('admin.teacher-reports.show', $teacher->id) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        View Report
                                    </a>
                                    <a href="{{ route('admin.teacher-reports.export-pdf', $teacher->id) }}" 
                                       class="inline-flex items-center p-2 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                       title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                @else
                                    <button disabled 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        No Feedback
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Information Box -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-3">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-xs text-blue-700">
                    <strong>About Teacher Performance Reports:</strong> These reports provide detailed analysis of individual teacher performance based on student feedback. Each report includes question-wise statistics, strengths, areas for improvement, and actionable recommendations.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
