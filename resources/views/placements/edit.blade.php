@extends('layouts.app')

@section('title', 'Edit Placement')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('placements.index') }}" class="hover:text-blue-600">Placements</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('placements.show', $placement) }}" class="hover:text-blue-600">Placement #{{ $placement->id }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit Placement</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('placements.update', $placement) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Student & Company -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="student_id" class="label required">Student</label>
                            <select id="student_id" name="student_id" class="input-field @error('student_id') border-red-500 @enderror" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ (old('student_id', $placement->student_id) == $student->id) ? 'selected' : '' }}>
                                    {{ $student->user->name }} ({{ $student->enrollment_number }})
                                </option>
                                @endforeach
                            </select>
                            @error('student_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_id" class="label required">Company</label>
                            <select id="company_id" name="company_id" class="input-field @error('company_id') border-red-500 @enderror" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ (old('company_id', $placement->company_id) == $company->id) ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('company_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Job Details -->
                    <div>
                        <label for="job_title" class="label required">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $placement->job_title) }}" placeholder="e.g., Software Engineer" class="input-field @error('job_title') border-red-500 @enderror" required>
                        @error('job_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="placement_type" class="label required">Placement Type</label>
                            <select id="placement_type" name="placement_type" class="input-field @error('placement_type') border-red-500 @enderror" required>
                                <option value="">Select Type</option>
                                <option value="Full-Time" {{ old('placement_type', $placement->placement_type) == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                <option value="Internship" {{ old('placement_type', $placement->placement_type) == 'Internship' ? 'selected' : '' }}>Internship</option>
                                <option value="Part-Time" {{ old('placement_type', $placement->placement_type) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                <option value="Contract" {{ old('placement_type', $placement->placement_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                            </select>
                            @error('placement_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="package_lpa" class="label required">Package (LPA)</label>
                            <input type="number" id="package_lpa" name="package_lpa" value="{{ old('package_lpa', $placement->package_lpa) }}" step="0.01" min="0" placeholder="0.00" class="input-field @error('package_lpa') border-red-500 @enderror" required>
                            @error('package_lpa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="location" class="label">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $placement->location) }}" placeholder="e.g., Mumbai, Bangalore" class="input-field @error('location') border-red-500 @enderror">
                            @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_id" class="label">Related Project</label>
                            <select id="project_id" name="project_id" class="input-field @error('project_id') border-red-500 @enderror">
                                <option value="">Select Project (Optional)</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ (old('project_id', $placement->project_id) == $project->id) ? 'selected' : '' }}>
                                    {{ Str::limit($project->title, 40) }}
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="offer_date" class="label">Offer Date</label>
                            <input type="date" id="offer_date" name="offer_date" value="{{ old('offer_date', $placement->offer_date?->format('Y-m-d')) }}" class="input-field @error('offer_date') border-red-500 @enderror">
                            @error('offer_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="joining_date" class="label">Joining Date</label>
                            <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', $placement->joining_date?->format('Y-m-d')) }}" class="input-field @error('joining_date') border-red-500 @enderror">
                            @error('joining_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="label">Job Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Role responsibilities, requirements..." class="input-field @error('description') border-red-500 @enderror">{{ old('description', $placement->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Placement
                    </button>
                    <a href="{{ route('placements.show', $placement) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        @can('delete', $placement)
        <div class="card border-2 border-red-200 mt-6">
            <h3 class="text-lg font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">Once you delete this placement, there is no going back. Please be certain.</p>
            <form action="{{ route('placements.destroy', $placement) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this placement? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Placement</button>
            </form>
        </div>
        @endcan
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">💼 Guidelines</h3>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>• Select the student being placed</li>
                <li>• Choose the hiring company</li>
                <li>• Enter package in Lakhs Per Annum (LPA)</li>
                <li>• Specify placement type (Full-Time/Internship)</li>
                <li>• Link to project if applicable</li>
                <li>• Add offer and joining dates</li>
            </ul>
        </div>

        <div class="card bg-green-50 border-green-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">📋 Placement Types</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><span class="font-medium">Full-Time:</span> Permanent role</li>
                <li><span class="font-medium">Internship:</span> Training period</li>
                <li><span class="font-medium">Part-Time:</span> Flexible hours</li>
                <li><span class="font-medium">Contract:</span> Fixed duration</li>
            </ul>
        </div>
    </div>
</div>
@endsection
