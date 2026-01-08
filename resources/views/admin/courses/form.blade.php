@extends('layouts.app')

@section('title', 'Create Course - SCFMS')
@section('page-title', {{ isset($course) ? 'Edit Course' : 'Create Course' }})

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('courses.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Courses
    </a>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($course) ? 'Edit Course' : 'Create New Course' }}
        </h2>

        <form action="{{ isset($course) ? route('courses.update', $course['id']) : route('courses.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($course))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Course Code -->
                <div>
                    <label class="label required">Course Code</label>
                    <input type="text" name="code" value="{{ old('code', $course['code'] ?? '') }}" placeholder="e.g., CS101" class="input-field" required>
                    @error('code')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-gray-500 text-xs mt-2">Unique identifier for the course</p>
                </div>

                <!-- Department -->
                <div>
                    <label class="label required">Department</label>
                    <select name="department_id" class="input-field" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept['id'] }}" {{ old('department_id', $course['department_id'] ?? '') == $dept['id'] ? 'selected' : '' }}>
                                {{ $dept['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Course Name -->
            <div>
                <label class="label required">Course Name</label>
                <input type="text" name="name" value="{{ old('name', $course['name'] ?? '') }}" placeholder="e.g., Introduction to Computer Science" class="input-field" required>
                @error('name')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Semester -->
                <div>
                    <label class="label required">Semester</label>
                    <select name="semester_id" class="input-field" required>
                        <option value="">Select Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester['id'] }}" {{ old('semester_id', $course['semester_id'] ?? '') == $semester['id'] ? 'selected' : '' }}>
                                {{ $semester['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Course Type -->
                <div>
                    <label class="label required">Type</label>
                    <select name="type" class="input-field" required>
                        <option value="">Select Type</option>
                        <option value="theory" {{ old('type', $course['type'] ?? '') === 'theory' ? 'selected' : '' }}>Theory</option>
                        <option value="practical" {{ old('type', $course['type'] ?? '') === 'practical' ? 'selected' : '' }}>Practical</option>
                        <option value="elective" {{ old('type', $course['type'] ?? '') === 'elective' ? 'selected' : '' }}>Elective</option>
                    </select>
                    @error('type')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Credits -->
                <div>
                    <label class="label required">Credits</label>
                    <select name="credits" class="input-field" required>
                        <option value="">Select Credits</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('credits', $course['credits'] ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('credits')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="label">Description (Optional)</label>
                <textarea name="description" rows="4" class="input-field" placeholder="Add course description...">{{ old('description', $course['description'] ?? '') }}</textarea>
                @error('description')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Faculty Assignment Section -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Faculty Assignment</h3>
                <div>
                    <label class="label">Assign Faculty (Optional)</label>
                    <select name="faculty_ids[]" multiple class="input-field" style="height: 150px;">
                        @foreach($faculty as $f)
                            <option value="{{ $f['id'] }}" {{ in_array($f['id'], old('faculty_ids', isset($course['faculty_ids']) ? $course['faculty_ids'] : [])) ? 'selected' : '' }}>
                                {{ $f['name'] }} ({{ $f['email'] }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-2">Hold Ctrl/Cmd to select multiple faculty members</p>
                    @error('faculty_ids')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Course Information</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Course code must be unique across all courses</li>
                        <li>Credits typically range from 1 to 6 for a course</li>
                        <li>Course type helps in categorizing and planning</li>
                        <li>Faculty can be assigned later through faculty management</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex space-x-4 pt-6 border-t">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($course) ? 'Update' : 'Create' }} Course
                </button>
                <a href="{{ route('courses.index') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
