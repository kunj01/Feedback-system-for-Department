@extends('layouts.app')

@section('title', 'Feedback Forms')
@section('page-title', 'Assigned Feedback')

@section('content')
<div class="max-w-7xl mx-auto">
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
                    <p class="text-sm text-gray-600">Total Submissions Required</p>
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
                    <p class="text-sm text-gray-600">Remaining to Submit</p>
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
                            @if(!$allCompleted)
                                <span class="px-2 py-1 text-xs font-semibold rounded border bg-yellow-100 text-yellow-800 border-yellow-200">
                                    Pending
                                </span>
                            @endif
                        </div>
                        
                        @if($allCompleted)
                            <div class="mt-2 inline-flex items-center px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                All feedbacks completed
                            </div>
                        @endif
                    </div>
                    
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
