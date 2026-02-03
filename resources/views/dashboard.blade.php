@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $isAdmin ?? $user->hasRole('Admin');
    $isFaculty = $isFaculty ?? $user->hasRole('Faculty');
    
    if (!$isAdmin && !$isFaculty && !isset($assignedForms)) {
        $student = $user->student;
        $assignedForms = $student ? \App\Models\FormAssignment::where('student_id', $student->id)->with(['teacher', 'subject'])->get() : collect([]);
    }
    
    if (!$isAdmin && !$isFaculty && isset($assignedForms)) {
        // Group assignments by form_name for students
        // This ensures that forms with multiple teachers are grouped together as ONE form
        $groupedAssignments = $assignedForms->groupBy('form_name');
        
        // Total unique forms (not individual teacher assignments)
        $totalAssignments = $groupedAssignments->count();
        
        // Count pending forms (forms that have at least one pending teacher feedback)
        // A form is pending if ANY teacher feedback is not yet submitted
        $pendingAssignments = $groupedAssignments->filter(function($group) {
            return $group->contains('status', 'pending');
        })->count();
        
        // Count fully completed forms (forms where ALL teacher feedbacks are submitted)
        // A form is completed ONLY when feedback for ALL teachers has been submitted
        // Example: If a form has 2 teachers, both feedbacks must be completed
        $completedAssignments = $groupedAssignments->filter(function($group) {
            return $group->every(fn($a) => $a->status === 'completed');
        })->count();
    }
@endphp

<!-- Welcome Section -->
<div class="mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Welcome, {{ $user->name }}!</h2>
        @if($isAdmin)
            <p class="text-blue-100">Manage forms and assign them to students.</p>
        @elseif($isFaculty)
            <p class="text-blue-100">Manage external speakers for department sessions and speeches.</p>
        @else
            <p class="text-blue-100">Complete all assigned forms to submit your feedback.</p>
        @endif
    </div>
</div>

@if($isAdmin)
    <!-- Admin Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Forms</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalForms }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Assignments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAssignments }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingAssignments }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $completedAssignments }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <a href="{{ route('forms.index') }}" class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">Manage Forms</h3>
                    <p class="text-blue-100">View, upload, and assign forms to students</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('forms.create') }}" class="card bg-gradient-to-r from-green-500 to-green-600 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">Upload New Form</h3>
                    <p class="text-green-100">Add a new form to the system</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>
        </a>
    </div>

@elseif($isFaculty)
    <!-- Faculty Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Speakers</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalSpeakers ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <a href="{{ route('faculty.speakers.create') }}" class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">Add New Speaker</h3>
                    <p class="text-blue-100">Register external speaker for department session</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('faculty.speakers.index') }}" class="card bg-gradient-to-r from-green-500 to-green-600 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">Manage Speakers</h3>
                    <p class="text-green-100">View and manage all registered speakers</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Recent Speakers -->
    @if(isset($recentSpeakers) && $recentSpeakers->count() > 0)
        <div class="card bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Speakers</h3>
            <div class="space-y-3">
                @foreach($recentSpeakers as $speaker)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $speaker->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $speaker->department }} • {{ $speaker->venue }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $speaker->date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</p>
                        </div>
                        <a href="{{ route('faculty.speakers.show', $speaker->id) }}" class="btn-secondary text-sm px-4 py-2">
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@else
    <!-- Student Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Forms</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAssignments }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingAssignments }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $completedAssignments }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Feedback -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">📋 Assigned Feedback</h3>
            @if($totalAssignments > 0)
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                    {{ $pendingAssignments }} Pending
                </span>
            @endif
        </div>

        @if($totalAssignments > 0)
            <div class="grid grid-cols-1 gap-4">
                @foreach($groupedAssignments as $formName => $formAssignments)
                    @php
                        $firstAssignment = $formAssignments->first();
                        $allCompleted = $formAssignments->every(fn($a) => $a->status === 'completed');
                        $hasMultipleTeachers = $formAssignments->count() > 1;
                        
                        // Calculate urgency based on deadline
                        $deadline = $firstAssignment->deadline;
                        $isUrgent = false;
                        $isDueSoon = false;
                        $daysRemaining = 0;
                        
                        if ($deadline && !$allCompleted) {
                            $daysRemaining = now()->diffInDays($deadline, false);
                            $isUrgent = $daysRemaining <= 2 && $daysRemaining >= 0; // 2 days or less
                            $isDueSoon = $daysRemaining > 2 && $daysRemaining <= 7; // 3-7 days
                        }
                        
                        // Get submission timestamp for completed forms
                        $submittedAt = null;
                        if ($allCompleted) {
                            // Get the latest submission time among all teachers
                            $submittedAt = $formAssignments->max('updated_at');
                        }
                        
                        // Determine card styling
                        $cardBgClass = $allCompleted ? 'bg-green-50 border-2 border-green-300' : 
                                      ($isUrgent ? 'bg-red-50 border-2 border-red-400' : 
                                      ($isDueSoon ? 'bg-orange-50 border-2 border-orange-300' : 'bg-white border border-gray-200'));
                        
                        $iconBgClass = $allCompleted ? 'bg-green-100' : 
                                      ($isUrgent ? 'bg-red-100' : 
                                      ($isDueSoon ? 'bg-orange-100' : 'bg-blue-100'));
                        
                        $iconColorClass = $allCompleted ? 'text-green-600' : 
                                         ($isUrgent ? 'text-red-600' : 
                                         ($isDueSoon ? 'text-orange-600' : 'text-blue-600'));
                    @endphp
                    <div class="card hover:shadow-lg transition-all duration-200 {{ $cardBgClass }}">
                        <div class="flex items-center justify-between">
                            <!-- Form Icon & Details -->
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0">
                                    @if($allCompleted)
                                        <div class="w-12 h-12 rounded-full {{ $iconBgClass }} flex items-center justify-center">
                                            <svg class="w-6 h-6 {{ $iconColorClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @elseif($isUrgent)
                                        <div class="w-12 h-12 rounded-full {{ $iconBgClass }} flex items-center justify-center animate-pulse">
                                            <svg class="w-6 h-6 {{ $iconColorClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full {{ $iconBgClass }} flex items-center justify-center">
                                            <svg class="w-6 h-6 {{ $iconColorClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Form Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-1 truncate">
                                        {{ $firstAssignment->form_title }}
                                    </h4>
                                    <div class="flex flex-col space-y-1 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Assigned {{ $firstAssignment->created_at->diffForHumans() }}
                                        </span>
                                        
                                        @if($deadline && !$allCompleted)
                                            <span class="flex items-center {{ $isUrgent ? 'text-red-600 font-semibold' : ($isDueSoon ? 'text-orange-600 font-medium' : '') }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @if($isUrgent)
                                                    🔴 URGENT: Due {{ $deadline->diffForHumans() }}
                                                @elseif($isDueSoon)
                                                    ⚠️ Due {{ $deadline->diffForHumans() }}
                                                @else
                                                    Due {{ $deadline->format('M d, Y') }}
                                                @endif
                                            </span>
                                        @endif
                                        
                                        @if($allCompleted && $submittedAt)
                                            <span class="flex items-center text-green-600 font-medium">
                                                Submitted on {{ $submittedAt->format('M d, Y \a\t g:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex-shrink-0 hidden sm:block">
                                    @if($allCompleted)
                                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold border border-green-300">
                                            ✓ Completed
                                        </span>
                                    @elseif($isUrgent)
                                        <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold border border-red-300 animate-pulse">
                                            🔥 URGENT
                                        </span>
                                    @elseif($isDueSoon)
                                        <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold border border-orange-300">
                                            ⏰ Due Soon
                                        </span>
                                    @else
                                        <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold border border-yellow-300">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$allCompleted)
                                    <a href="{{ route('forms.show', $formName) }}" 
                                       class="btn-primary text-sm font-semibold px-6 py-3 hover:scale-105 transform transition-all shadow-md">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Fill Form Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card bg-gray-50 text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Feedback Assigned Yet</h3>
                <p class="text-gray-600">You don't have any feedback forms assigned at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Quick Tips -->
    @if($pendingAssignments > 0)
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-blue-800 mb-2">💡 Quick Tips:</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-blue-700">
                        <li>Click <strong>"Fill Form Now"</strong> to complete pending forms</li>
                        <li>Track your progress with the completion status</li>
                        <li>Completed forms are highlighted in green</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
@endif

@endsection
