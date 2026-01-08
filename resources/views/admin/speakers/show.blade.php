@extends('layouts.app')

@section('title', 'Speaker Details')
@section('page-title', 'External Speaker Details')

@section('content')
<div class="mb-6">
    <a 
        href="{{ route('admin.speakers.index') }}" 
        class="inline-flex items-center text-blue-600 hover:text-blue-800"
    >
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Speakers
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="card bg-white">
    <div class="border-b pb-4 mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $speaker->name }}</h2>
            <p class="text-gray-600 mt-1">External Speaker Details</p>
        </div>
        
        <!-- Status Badge -->
        <div>
            @if($speaker->approval_status === 'pending')
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-yellow-100 text-yellow-800">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Pending Approval
                </span>
            @elseif($speaker->approval_status === 'approved')
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-green-100 text-green-800">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Approved
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-red-100 text-red-800">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Rejected
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Name -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-medium text-gray-500 mb-1">Speaker Name</label>
            <p class="text-lg text-gray-900 font-semibold">{{ $speaker->name }}</p>
        </div>

        <!-- Email -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
            <p class="text-lg text-gray-900">
                <a href="mailto:{{ $speaker->email }}" class="text-blue-600 hover:text-blue-800">
                    {{ $speaker->email }}
                </a>
            </p>
        </div>

        <!-- Department -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
            <p class="text-lg text-gray-900 font-semibold">{{ $speaker->department }}</p>
        </div>

        <!-- Venue -->
        <div class="p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-medium text-gray-500 mb-1">Venue</label>
            <p class="text-lg text-gray-900 font-semibold">{{ $speaker->venue }}</p>
        </div>

        <!-- Date -->
        <div class="p-4 bg-blue-50 rounded-lg">
            <label class="block text-sm font-medium text-blue-700 mb-1">Event Date</label>
            <p class="text-lg text-blue-900 font-bold">{{ $speaker->date->format('F d, Y') }}</p>
        </div>

        <!-- Time -->
        <div class="p-4 bg-blue-50 rounded-lg">
            <label class="block text-sm font-medium text-blue-700 mb-1">Event Time</label>
            <p class="text-lg text-blue-900 font-bold">{{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</p>
        </div>
    </div>

    <!-- Submission Details -->
    <div class="border-t pt-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Submitted By</label>
                <p class="text-gray-900 font-semibold">{{ $speaker->creator->name }}</p>
                <p class="text-sm text-gray-500">{{ $speaker->creator->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Submitted On</label>
                <p class="text-gray-900">{{ $speaker->created_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Approval Details -->
    @if($speaker->approval_status !== 'pending')
        <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ $speaker->approval_status === 'approved' ? 'Approved' : 'Rejected' }} By</label>
                    <p class="text-gray-900 font-semibold">{{ $speaker->approver->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $speaker->approver->email ?? '' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ $speaker->approval_status === 'approved' ? 'Approved' : 'Rejected' }} On</label>
                    <p class="text-gray-900">{{ $speaker->approved_at ? $speaker->approved_at->format('F d, Y h:i A') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex gap-3 pt-6 border-t">
        @if($speaker->approval_status === 'pending')
            <form action="{{ route('admin.speakers.approve', $speaker->id) }}" method="POST" class="flex-1">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold inline-flex items-center justify-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Speaker
                </button>
            </form>

            <form action="{{ route('admin.speakers.reject', $speaker->id) }}" method="POST" class="flex-1">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors font-semibold inline-flex items-center justify-center"
                    onclick="return confirm('Are you sure you want to reject this speaker?');"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Speaker
                </button>
            </form>
        @endif

        <form 
            action="{{ route('admin.speakers.destroy', $speaker->id) }}" 
            method="POST" 
            class="inline"
            onsubmit="return confirm('Are you sure you want to delete this speaker? This action cannot be undone.');"
        >
            @csrf
            @method('DELETE')
            <button 
                type="submit" 
                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold inline-flex items-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
