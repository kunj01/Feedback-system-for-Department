@extends('layouts.app')

@section('title', 'Add Speaker')
@section('page-title', 'Add External Speaker')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card bg-white">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">External Speaker Information</h3>
            <p class="text-sm text-gray-600 mt-1">Please fill in the details of the external speaker for the upcoming speech/session.</p>
        </div>

        <form action="{{ route('faculty.speakers.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Speaker Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Enter speaker's full name"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="speaker@example.com"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Venue -->
            <div class="mb-4">
                <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">
                    Venue <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="venue" 
                    id="venue" 
                    value="{{ old('venue') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('venue') border-red-500 @enderror"
                    placeholder="e.g., Main Auditorium, Seminar Hall"
                    required
                >
                @error('venue')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Department -->
            <div class="mb-4">
                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                    Department <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="department" 
                    id="department" 
                    value="{{ old('department') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror"
                    placeholder="e.g., Computer Science, Mechanical Engineering"
                    required
                >
                @error('department')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Time Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="date" 
                        id="date" 
                        value="{{ old('date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror"
                        required
                    >
                    @error('date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time -->
                <div>
                    <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                        Time <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="time" 
                        name="time" 
                        id="time" 
                        value="{{ old('time') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('time') border-red-500 @enderror"
                        required
                    >
                    @error('time')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button 
                    type="submit" 
                    class="flex-1 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    Add Speaker
                </button>
                <a 
                    href="{{ route('faculty.speakers.index') }}" 
                    class="flex-1 bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
