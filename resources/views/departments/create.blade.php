@extends('layouts.app')

@section('title', 'Create Department')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('departments.index') }}" class="hover:text-blue-600">Departments</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Create New Department</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Create New Department</h1>
</div>

<div class="max-w-2xl">
    <div class="card">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Department Name -->
                <div>
                    <label for="name" class="label required">Department Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g., Computer Engineering"
                        class="input-field @error('name') border-red-500 @enderror"
                        required
                        autofocus
                    >
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department Code -->
                <div>
                    <label for="code" class="label required">Department Code</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="e.g., CE or COMP"
                        class="input-field @error('code') border-red-500 @enderror"
                        required
                    >
                    @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Short code to identify the department</p>
                </div>

                <!-- Department Head -->
                <div>
                    <label for="head_id" class="label">Department Head</label>
                    <select
                        id="head_id"
                        name="head_id"
                        class="input-field @error('head_id') border-red-500 @enderror"
                    >
                        <option value="">Select Department Head (Optional)</option>
                        @foreach($heads as $head)
                        <option value="{{ $head->id }}" {{ old('head_id') == $head->id ? 'selected' : '' }}>
                            {{ $head->name }} ({{ $head->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('head_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">User must have 'Head' role assigned</p>
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Department
                </button>
                <a href="{{ route('departments.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
