@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('projects.index') }}" class="hover:text-blue-600">Projects</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">{{ Str::limit($project->title, 30) }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit Project</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('projects.update', $project) }}">
                @csrf
                @method('PUT')

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
                            <option value="{{ $student->id }}"
                                {{ (old('student_id', $project->student_id) == $student->id) ? 'selected' : '' }}>
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
                            value="{{ old('title', $project->title) }}"
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
                        >{{ old('description', $project->description) }}</textarea>
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
                                <option value="{{ $guide->id }}"
                                    {{ (old('guide_id', $project->guide_id) == $guide->id) ? 'selected' : '' }}>
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
                                value="{{ old('academic_year', $project->academic_year) }}"
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
                                value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}"
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
                                value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
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
                            <option value="Proposed" {{ old('status', $project->status) == 'Proposed' ? 'selected' : '' }}>Proposed</option>
                            <option value="In Progress" {{ old('status', $project->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status', $project->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="On Hold" {{ old('status', $project->status) == 'On Hold' ? 'selected' : '' }}>On Hold</option>
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
                        Update Project
                    </button>
                    <a href="{{ route('projects.show', $project) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        @can('delete', $project)
        <div class="card border-2 border-red-200 mt-6">
            <h3 class="text-lg font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">Once you delete this project, there is no going back. Please be certain.</p>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Project</button>
            </form>
        </div>
        @endcan
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Project Info</h3>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Created</dt>
                    <dd class="text-gray-900 font-medium">{{ $project->created_at->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Last Updated</dt>
                    <dd class="text-gray-900 font-medium">{{ $project->updated_at->diffForHumans() }}</dd>
                </div>
                @if($project->evaluations_count > 0)
                <div>
                    <dt class="text-gray-500">Evaluations</dt>
                    <dd class="text-gray-900 font-medium">{{ $project->evaluations_count }} recorded</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="card bg-blue-50 border-blue-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">💡 Quick Tips</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Update status as project progresses</li>
                <li>• Add end date when timeline is confirmed</li>
                <li>• Change guide if reassignment needed</li>
            </ul>
        </div>
    </div>
</div>
@endsection
