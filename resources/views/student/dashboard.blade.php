@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 mt-1">Student Dashboard</p>
        </div>

        @if(isset($student) && $student)
        <!-- Student Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Academic Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Enrollment No:</span>
                        <span class="font-semibold">{{ $student->enrollment_no ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Semester:</span>
                        <span class="font-semibold">{{ $student->semester ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Branch:</span>
                        <span class="font-semibold">{{ $student->branch ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Division:</span>
                        <span class="font-semibold">{{ $student->division->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Batch:</span>
                        <span class="font-semibold">{{ $student->batchGroup->batch_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('student.timetable') }}" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">View Timetable</span>
                    </a>
                    <a href="{{ route('student.feedback.index') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">Submit Feedback</span>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Feedbacks</h3>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600">{{ count($pendingFeedbacks) }}</div>
                    <p class="text-gray-600 mt-2">Feedback(s) pending</p>
                    @if(count($pendingFeedbacks) > 0)
                        <a href="{{ route('student.feedback.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                            Submit Now →
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subjects -->
        @if(count($subjects) > 0)
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">My Subjects</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($subjects as $subject)
                <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-indigo-500 transition-colors">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $subject->subject_code }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $subject->subject_name }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Sem {{ $subject->semester }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Today's Schedule -->
        @if(isset($timetable) && $timetable)
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Today's Schedule</h3>
            @php
                $today = date('l'); // Monday, Tuesday, etc.
                $todaySchedule = collect();
                foreach($timetable['timetable'] as $timeSlot => $days) {
                    if(isset($days[$today]) && count($days[$today]) > 0) {
                        $todaySchedule->push([
                            'time' => $timeSlot,
                            'entries' => $days[$today]
                        ]);
                    }
                }
            @endphp

            @if($todaySchedule->count() > 0)
            <div class="space-y-3">
                @foreach($todaySchedule as $slot)
                    @foreach($slot['entries'] as $entry)
                    <div class="flex items-center p-4 border-l-4 {{ $entry->batch_id ? 'border-green-500 bg-green-50' : 'border-blue-500 bg-blue-50' }} rounded-r-lg">
                        <div class="flex-shrink-0 w-24 text-sm font-semibold text-gray-700">
                            {{ $slot['time'] }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $entry->subject->subject_code }} - {{ $entry->subject->subject_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $entry->faculty->faculty_name }} • Room {{ $entry->room_no }}</p>
                            @if($entry->batch)
                                <span class="inline-block mt-1 text-xs bg-green-200 text-green-800 px-2 py-1 rounded">Batch: {{ $entry->batch->batch_name }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
            @else
            <p class="text-gray-600 text-center py-8">No classes scheduled for today</p>
            @endif
        </div>
        @endif

        @else
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <p class="text-yellow-800">Your student profile is not complete. Please contact administration.</p>
        </div>
        @endif

    </div>
</div>
@endsection
