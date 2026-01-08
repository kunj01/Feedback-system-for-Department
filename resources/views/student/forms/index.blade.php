@extends('layouts.app')

@section('title', 'Feedback Forms')
@section('page-title', 'Assigned Feedback')

@section('content')
<div class="max-w-7xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('info') }}
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card bg-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Feedback</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $assignments->count() }}</p>
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
                    <p class="text-2xl font-bold text-gray-800">{{ $assignments->where('status', 'pending')->count() }}</p>
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
                    <p class="text-2xl font-bold text-gray-800">{{ $assignments->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms List -->
    <div class="card">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Your Assigned Forms</h3>
        
        @forelse($groupedAssignments as $formName => $formAssignments)
            @php
                $firstAssignment = $formAssignments->first();
                $allCompleted = $formAssignments->every(fn($a) => $a->status === 'completed');
                $hasMultipleTeachers = $formAssignments->count() > 1;
            @endphp
            <div class="border rounded-lg p-4 mb-4 {{ $allCompleted ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $firstAssignment->form_title }}</h4>
                            
                            <!-- Status Badge -->
                            @if($allCompleted)
                                <span class="px-2 py-1 text-xs font-semibold rounded border bg-green-100 text-green-800 border-green-200">
                                    Completed
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded border bg-yellow-100 text-yellow-800 border-yellow-200">
                                    Pending
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 mt-2">Assigned {{ $firstAssignment->created_at->diffForHumans() }}</p>
                        
                        @if($hasMultipleTeachers && $firstAssignment->is_multi_teacher)
                            <p class="text-sm text-blue-600 mt-1 font-medium">
                                <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                                Multiple Teachers - Select inside form
                            </p>
                        @endif
                        
                        <!-- Period Information -->
                        @if($firstAssignment->start_date || $firstAssignment->end_date)
                            <div class="mt-2 text-sm text-gray-600 space-y-1">
                                @if($firstAssignment->start_date)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Opens: <strong>{{ $firstAssignment->start_date->format('M d, Y H:i') }}</strong></span>
                                    </div>
                                @endif
                                
                                @if($firstAssignment->end_date)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Closes: <strong>{{ $firstAssignment->end_date->format('M d, Y H:i') }}</strong></span>
                                        
                                        @if($firstAssignment->grace_period_hours > 0)
                                            <span class="ml-2 text-xs text-yellow-600">
                                                (+{{ $firstAssignment->grace_period_hours }}h grace)
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Countdown/Time Remaining -->
                                    @if($firstAssignment->isActive() && !$firstAssignment->isUpcoming())
                                        @php
                                            $now = now();
                                            $effectiveEnd = $firstAssignment->end_date->copy()->addHours($firstAssignment->grace_period_hours ?? 0);
                                            $timeRemaining = $now->diffInHours($effectiveEnd);
                                            $daysRemaining = floor($timeRemaining / 24);
                                            $hoursRemaining = $timeRemaining % 24;
                                        @endphp
                                        
                                        @if($timeRemaining > 0)
                                            <div class="mt-1 flex items-center text-orange-600 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                @if($daysRemaining > 0)
                                                    {{ $daysRemaining }} day{{ $daysRemaining > 1 ? 's' : '' }} {{ $hoursRemaining }} hour{{ $hoursRemaining > 1 ? 's' : '' }} remaining
                                                @else
                                                    {{ $hoursRemaining }} hour{{ $hoursRemaining > 1 ? 's' : '' }} remaining
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        @endif
                        
                        @if($allCompleted)
                            <div class="mt-2 inline-flex items-center px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                All feedbacks completed
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('forms.download', $firstAssignment->form_name) }}" class="btn-secondary">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                        
                        @if(!$allCompleted)
                            @if($firstAssignment->isActive())
                                <a href="{{ route('forms.show', $firstAssignment->form_name) }}" class="btn-primary">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Fill Form
                                </a>
                            @elseif($firstAssignment->isUpcoming())
                                <button disabled class="btn-secondary opacity-50 cursor-not-allowed" title="Form not yet available">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Not Yet Available
                                </button>
                            @elseif($firstAssignment->hasEnded())
                                <button disabled class="btn-secondary opacity-50 cursor-not-allowed" title="Submission deadline has passed">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Deadline Passed
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold mb-2">No Feedback Assigned</h3>
                <p>You don't have any feedback forms assigned to you yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
