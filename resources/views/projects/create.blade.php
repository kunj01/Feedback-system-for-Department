@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('projects.index') }}" class="hover:text-blue-600">Projects</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Create New Project</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Create New Project</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Student Selection -->
                    <div>
                        <label for="student_id" class="label required">Student</label>
                        <select
                            id="student_id"
                            name="student_id"
                            class="input-field @error('student_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->user->name }} ({{ $student->enrollment_number }})
                            </option>
                            @endforeach
                        </select>
                        @error('student_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Title -->
                    <div>
                        <label for="title" class="label required">Project Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Enter project title"
                            class="input-field @error('title') border-red-500 @enderror"
                            required
                        >
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="label required">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Describe the project objectives and scope"
                            class="input-field @error('description') border-red-500 @enderror"
                            required
                        >{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guide & Academic Year -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="guide_id" class="label">Project Guide</label>
                            <select
                                id="guide_id"
                                name="guide_id"
                                class="input-field @error('guide_id') border-red-500 @enderror"
                            >
                                <option value="">Select Guide (Optional)</option>
                                @foreach($guides as $guide)
                                <option value="{{ $guide->id }}" {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('guide_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="academic_year" class="label required">Academic Year</label>
                            <input
                                type="text"
                                id="academic_year"
                                name="academic_year"
                                value="{{ old('academic_year') }}"
                                placeholder="e.g., 2023-24"
                                class="input-field @error('academic_year') border-red-500 @enderror"
                                required
                            >
                            @error('academic_year')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Date & End Date -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="label required">Start Date</label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="input-field @error('start_date') border-red-500 @enderror"
                                required
                            >
                            @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="label">End Date</label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                class="input-field @error('end_date') border-red-500 @enderror"
                            >
                            @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Optional - Expected completion date</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="label required">Status</label>
                        <select
                            id="status"
                            name="status"
                            class="input-field @error('status') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Status</option>
                            <option value="Proposed" {{ old('status') == 'Proposed' ? 'selected' : '' }}>Proposed</option>
                            <option value="In Progress" {{ old('status', 'In Progress') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="On Hold" {{ old('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Project
                    </button>
                    <a href="{{ route('projects.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">📋 Project Guidelines</h3>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>• Assign one student per project</li>
                <li>• Guide assignment is optional initially</li>
                <li>• Academic year format: YYYY-YY</li>
                <li>• Set realistic start and end dates</li>
                <li>• Status can be updated later</li>
            </ul>
        </div>

        <div class="card bg-yellow-50 border-yellow-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">Status Types</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><span class="font-medium">Proposed:</span> Idea stage</li>
                <li><span class="font-medium">In Progress:</span> Active work</li>
                <li><span class="font-medium">Completed:</span> Finished</li>
                <li><span class="font-medium">On Hold:</span> Paused</li>
            </ul>
        </div>
    </div>
</div>
@endsection
