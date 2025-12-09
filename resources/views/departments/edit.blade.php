@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('departments.index') }}" class="hover:text-blue-600">Departments</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit Department</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit Department: {{ $department->name }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('departments.update', $department) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Department Name -->
                    <div>
                        <label for="name" class="label required">Department Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $department->name) }}"
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
                            value="{{ old('code', $department->code) }}"
                            placeholder="e.g., CE or COMP"
                            class="input-field @error('code') border-red-500 @enderror"
                            required
                        >
                        @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
                            <option value="{{ $head->id }}"
                                {{ old('head_id', $department->head_id) == $head->id ? 'selected' : '' }}>
                                {{ $head->name }} ({{ $head->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('head_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Department
                    </button>
                    <a href="{{ route('departments.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Department Info</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-gray-600">Total Students</p>
                    <p class="font-medium text-lg text-blue-600">{{ $department->students->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Created</p>
                    <p class="font-medium">{{ $department->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Department ID</p>
                    <p class="font-medium">#{{ $department->id }}</p>
                </div>
            </div>
        </div>

        @can('delete', $department)
        <div class="card bg-red-50 border-red-200 mt-4">
            <h3 class="font-semibold text-red-800 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-3">Permanently delete this department</p>
            <form action="{{ route('departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this department? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger w-full">Delete Department</button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
