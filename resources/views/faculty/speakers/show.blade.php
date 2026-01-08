@extends('layouts.app')

@section('title', 'Speaker Details')
@section('page-title', 'Speaker Details')

@section('content')
<div class="mb-6">
    <a 
        href="{{ route('faculty.speakers.index') }}" 
        class="inline-flex items-center text-blue-600 hover:text-blue-800"
    >
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Speakers
    </a>
</div>

<div class="card bg-white">
    <div class="border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $speaker->name }}</h2>
        <p class="text-gray-600 mt-1">External Speaker Details</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Speaker Name</label>
            <p class="text-lg text-gray-900">{{ $speaker->name }}</p>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
            <p class="text-lg text-gray-900">
                <a href="mailto:{{ $speaker->email }}" class="text-blue-600 hover:text-blue-800">
                    {{ $speaker->email }}
                </a>
            </p>
        </div>

        <!-- Department -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
            <p class="text-lg text-gray-900">{{ $speaker->department }}</p>
        </div>

        <!-- Venue -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Venue</label>
            <p class="text-lg text-gray-900">{{ $speaker->venue }}</p>
        </div>

        <!-- Date -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Date</label>
            <p class="text-lg text-gray-900">{{ $speaker->date->format('F d, Y') }}</p>
        </div>

        <!-- Time -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Time</label>
            <p class="text-lg text-gray-900">{{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</p>
        </div>

        <!-- Created At -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Added On</label>
            <p class="text-gray-900">{{ $speaker->created_at->format('F d, Y h:i A') }}</p>
        </div>

        <!-- Updated At -->
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
            <p class="text-gray-900">{{ $speaker->updated_at->format('F d, Y h:i A') }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 mt-8 pt-6 border-t">
        <a 
            href="{{ route('faculty.speakers.edit', $speaker->id) }}" 
            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Speaker
        </a>
        <form 
            action="{{ route('faculty.speakers.destroy', $speaker->id) }}" 
            method="POST" 
            class="inline"
            onsubmit="return confirm('Are you sure you want to delete this speaker? This action cannot be undone.');"
        >
            @csrf
            @method('DELETE')
            <button 
                type="submit" 
                class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 transition-colors font-medium inline-flex items-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Speaker
            </button>
        </form>
    </div>
</div>
@endsection
