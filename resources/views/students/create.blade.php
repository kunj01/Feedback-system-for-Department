@extends('layouts.app')

@section('title', 'Create Student')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('students.index') }}" class="hover:text-blue-600">Students</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Create New Student</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Create New Student</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('students.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- User Selection -->
                    <div>
                        <label for="user_id" class="label required">Student User Account</label>
                        <select
                            id="user_id"
                            name="user_id"
                            class="input-field @error('user_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Student User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Select a user with 'Student' role</p>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="label required">Department</label>
                        <select
                            id="department_id"
                            name="department_id"
                            class="input-field @error('department_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Enrollment Number -->
                    <div>
                        <label for="enrollment_number" class="label required">Enrollment Number</label>
                        <input
                            type="text"
                            id="enrollment_number"
                            name="enrollment_number"
                            value="{{ old('enrollment_number') }}"
                            placeholder="e.g., 21CE001"
                            class="input-field @error('enrollment_number') border-red-500 @enderror"
                            required
                        >
                        @error('enrollment_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label for="contact_number" class="label required">Contact Number</label>
                        <input
                            type="tel"
                            id="contact_number"
                            name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="e.g., +91 9876543210"
                            class="input-field @error('contact_number') border-red-500 @enderror"
                            required
                        >
                        @error('contact_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Academic Year & Semester -->
                    <div class="grid grid-cols-2 gap-4">
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

                        <div>
                            <label for="semester" class="label required">Current Semester</label>
                            <select
                                id="semester"
                                name="semester"
                                class="input-field @error('semester') border-red-500 @enderror"
                                required
                            >
                                <option value="">Select Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                @endfor
                            </select>
                            @error('semester')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- CGPA -->
                    <div>
                        <label for="cgpa" class="label">CGPA</label>
                        <input
                            type="number"
                            id="cgpa"
                            name="cgpa"
                            value="{{ old('cgpa') }}"
                            placeholder="e.g., 8.5"
                            step="0.01"
                            min="0"
                            max="10"
                            class="input-field @error('cgpa') border-red-500 @enderror"
                        >
                        @error('cgpa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Out of 10 (optional)</p>
                    </div>

                    <!-- Placement Status -->
                    <div>
                        <label for="placement_status" class="label required">Placement Status</label>
                        <select
                            id="placement_status"
                            name="placement_status"
                            class="input-field @error('placement_status') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Status</option>
                            <option value="Not Placed" {{ old('placement_status') == 'Not Placed' ? 'selected' : '' }}>Not Placed</option>
                            <option value="Placed" {{ old('placement_status') == 'Placed' ? 'selected' : '' }}>Placed</option>
                            <option value="Pursuing Higher Studies" {{ old('placement_status') == 'Pursuing Higher Studies' ? 'selected' : '' }}>Pursuing Higher Studies</option>
                        </select>
                        @error('placement_status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Student
                    </button>
                    <a href="{{ route('students.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">📋 Instructions</h3>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>• User must have 'Student' role assigned</li>
                <li>• Enrollment number must be unique</li>
                <li>• Academic year format: YYYY-YY</li>
                <li>• CGPA is optional and can be added later</li>
                <li>• Contact number should include country code</li>
            </ul>
        </div>

        <div class="card bg-yellow-50 border-yellow-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">⚠️ Note</h3>
            <p class="text-sm text-gray-600">
                If you don't see any users in the dropdown, create a user with 'Student' role first from the User Management page.
            </p>
        </div>
    </div>
</div>
@endsection
