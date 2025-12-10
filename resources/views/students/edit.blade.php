@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('students.index') }}" class="hover:text-blue-600">Students</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('students.show', $student) }}" class="hover:text-blue-600">{{ $student->user->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit Student: {{ $student->user->name }}</h1>
</div>

<form method="POST" action="{{ route('students.update', $student) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic Information -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Basic Information</h3>
                <div class="space-y-6">
                    <!-- Student ID -->
                    <div>
                        <label for="student_id" class="label">Student ID Number</label>
                        <input
                            type="text"
                            id="student_id"
                            name="student_id"
                            value="{{ old('student_id', $student->student_id) }}"
                            placeholder="e.g., 20IT004"
                            class="input-field @error('student_id') border-red-500 @enderror"
                        >
                        @error('student_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Fields -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="first_name" class="label">First Name</label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name', $student->first_name) }}"
                                placeholder="First Name"
                                class="input-field @error('first_name') border-red-500 @enderror"
                            >
                            @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="label">Middle Name</label>
                            <input
                                type="text"
                                id="middle_name"
                                name="middle_name"
                                value="{{ old('middle_name', $student->middle_name) }}"
                                placeholder="Middle Name"
                                class="input-field @error('middle_name') border-red-500 @enderror"
                            >
                            @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="label">Last Name</label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name', $student->last_name) }}"
                                placeholder="Last Name"
                                class="input-field @error('last_name') border-red-500 @enderror"
                            >
                            @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Roll & Registration Number -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="roll_no" class="label">Roll Number</label>
                            <input
                                type="text"
                                id="roll_no"
                                name="roll_no"
                                value="{{ old('roll_no', $student->roll_no) }}"
                                placeholder="e.g., 101"
                                class="input-field @error('roll_no') border-red-500 @enderror"
                            >
                            @error('roll_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="registration_no" class="label">Registration Number</label>
                            <input
                                type="text"
                                id="registration_no"
                                name="registration_no"
                                value="{{ old('registration_no', $student->registration_no) }}"
                                placeholder="e.g., REG123456"
                                class="input-field @error('registration_no') border-red-500 @enderror"
                            >
                            @error('registration_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Gender & DOB -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="gender" class="label">Gender</label>
                            <select
                                id="gender"
                                name="gender"
                                class="input-field @error('gender') border-red-500 @enderror"
                            >
                                <option value="">Select Gender</option>
                                <option value="M" {{ old('gender', $student->gender) == 'M' ? 'selected' : '' }}>Male</option>
                                <option value="F" {{ old('gender', $student->gender) == 'F' ? 'selected' : '' }}>Female</option>
                                <option value="O" {{ old('gender', $student->gender) == 'O' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="dob" class="label">Date of Birth</label>
                            <input
                                type="date"
                                id="dob"
                                name="dob"
                                value="{{ old('dob', $student->dob?->format('Y-m-d')) }}"
                                class="input-field @error('dob') border-red-500 @enderror"
                            >
                            @error('dob')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="label">Department</label>
                        <select
                            id="department_id"
                            name="department_id"
                            class="input-field @error('department_id') border-red-500 @enderror"
                        >
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course, Batch & Academic Year -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="course" class="label">Course</label>
                            <input
                                type="text"
                                id="course"
                                name="course"
                                value="{{ old('course', $student->course) }}"
                                placeholder="e.g., B.Tech"
                                class="input-field @error('course') border-red-500 @enderror"
                            >
                            @error('course')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="batch" class="label">Batch</label>
                            <input
                                type="number"
                                id="batch"
                                name="batch"
                                value="{{ old('batch', $student->batch) }}"
                                placeholder="e.g., 2020"
                                class="input-field @error('batch') border-red-500 @enderror"
                            >
                            @error('batch')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="academic_year" class="label">Academic Year</label>
                            <input
                                type="text"
                                id="academic_year"
                                name="academic_year"
                                value="{{ old('academic_year', $student->academic_year) }}"
                                placeholder="e.g., 2023-24"
                                class="input-field @error('academic_year') border-red-500 @enderror"
                            >
                            @error('academic_year')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Training Status & Counsellor -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="training_status" class="label">Training Status</label>
                            <select
                                id="training_status"
                                name="training_status"
                                class="input-field @error('training_status') border-red-500 @enderror"
                            >
                                <option value="NOT_ASSIGNED" {{ old('training_status', $student->training_status) == 'NOT_ASSIGNED' ? 'selected' : '' }}>Not Assigned</option>
                                <option value="IN_TRAINING" {{ old('training_status', $student->training_status) == 'IN_TRAINING' ? 'selected' : '' }}>In Training</option>
                                <option value="COMPLETED" {{ old('training_status', $student->training_status) == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('training_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="counsellor_name" class="label">Counsellor Name</label>
                            <input
                                type="text"
                                id="counsellor_name"
                                name="counsellor_name"
                                value="{{ old('counsellor_name', $student->counsellor_name) }}"
                                placeholder="Counsellor Name"
                                class="input-field @error('counsellor_name') border-red-500 @enderror"
                            >
                            @error('counsellor_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Eligibility & Admission Type -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="is_eligible" class="label">Eligible for Placement</label>
                            <select
                                id="is_eligible"
                                name="is_eligible"
                                class="input-field @error('is_eligible') border-red-500 @enderror"
                            >
                                <option value="1" {{ old('is_eligible', $student->is_eligible) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_eligible', $student->is_eligible) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_eligible')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="admission_type" class="label">Admission Type</label>
                            <input
                                type="text"
                                id="admission_type"
                                name="admission_type"
                                value="{{ old('admission_type', $student->admission_type) }}"
                                placeholder="e.g., ACPC, Management"
                                class="input-field @error('admission_type') border-red-500 @enderror"
                            >
                            @error('admission_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Contact Information</h3>
                <div class="space-y-6">
                    <!-- Contact Number -->
                    <div>
                        <label for="contact" class="label">Contact Number</label>
                        <input
                            type="tel"
                            id="contact"
                            name="contact"
                            value="{{ old('contact', $student->contact) }}"
                            placeholder="e.g., +91 9876543210"
                            class="input-field @error('contact') border-red-500 @enderror"
                        >
                        @error('contact')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emails -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="label">CHARUSAT Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $student->email) }}"
                                placeholder="student@charusat.edu.in"
                                class="input-field @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="personal_email" class="label">Personal Email</label>
                            <input
                                type="email"
                                id="personal_email"
                                name="personal_email"
                                value="{{ old('personal_email', $student->personal_email) }}"
                                placeholder="student@gmail.com"
                                class="input-field @error('personal_email') border-red-500 @enderror"
                            >
                            @error('personal_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="label">City</label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            value="{{ old('city', $student->city) }}"
                            placeholder="e.g., Ahmedabad"
                            class="input-field @error('city') border-red-500 @enderror"
                        >
                        @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="label">Address</label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="Full Address"
                            class="input-field @error('address') border-red-500 @enderror"
                        >{{ old('address', $student->address) }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Family Information</h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="father_name" class="label">Father's Name</label>
                            <input
                                type="text"
                                id="father_name"
                                name="father_name"
                                value="{{ old('father_name', $student->father_name) }}"
                                placeholder="Father's Name"
                                class="input-field @error('father_name') border-red-500 @enderror"
                            >
                            @error('father_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mother_name" class="label">Mother's Name</label>
                            <input
                                type="text"
                                id="mother_name"
                                name="mother_name"
                                value="{{ old('mother_name', $student->mother_name) }}"
                                placeholder="Mother's Name"
                                class="input-field @error('mother_name') border-red-500 @enderror"
                            >
                            @error('mother_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Academic Performance</h3>
                <div class="space-y-6">
                    <!-- SSC & HSC -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ssc_percentage" class="label">SSC (10th) Percentage</label>
                            <input
                                type="number"
                                id="ssc_percentage"
                                name="ssc_percentage"
                                value="{{ old('ssc_percentage', $student->ssc_percentage) }}"
                                placeholder="e.g., 85.50"
                                step="0.01"
                                min="0"
                                max="100"
                                class="input-field @error('ssc_percentage') border-red-500 @enderror"
                            >
                            @error('ssc_percentage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="hsc_percentage" class="label">HSC (12th) Percentage</label>
                            <input
                                type="number"
                                id="hsc_percentage"
                                name="hsc_percentage"
                                value="{{ old('hsc_percentage', $student->hsc_percentage) }}"
                                placeholder="e.g., 82.30"
                                step="0.01"
                                min="0"
                                max="100"
                                class="input-field @error('hsc_percentage') border-red-500 @enderror"
                            >
                            @error('hsc_percentage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Diploma & B.Tech CGPA -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="diploma_percentage" class="label">Diploma Percentage</label>
                            <input
                                type="number"
                                id="diploma_percentage"
                                name="diploma_percentage"
                                value="{{ old('diploma_percentage', $student->diploma_percentage) }}"
                                placeholder="e.g., 78.50"
                                step="0.01"
                                min="0"
                                max="100"
                                class="input-field @error('diploma_percentage') border-red-500 @enderror"
                            >
                            @error('diploma_percentage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">If applicable</p>
                        </div>
                        <div>
                            <label for="btech_cgpa_upto_5th" class="label">B.Tech CGPA (Upto 5th Sem)</label>
                            <input
                                type="number"
                                id="btech_cgpa_upto_5th"
                                name="btech_cgpa_upto_5th"
                                value="{{ old('btech_cgpa_upto_5th', $student->btech_cgpa_upto_5th) }}"
                                placeholder="e.g., 8.25"
                                step="0.01"
                                min="0"
                                max="10"
                                class="input-field @error('btech_cgpa_upto_5th') border-red-500 @enderror"
                            >
                            @error('btech_cgpa_upto_5th')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Current CGPA -->
                    <div>
                        <label for="cgpa" class="label">Current CGPA</label>
                        <input
                            type="number"
                            id="cgpa"
                            name="cgpa"
                            value="{{ old('cgpa', $student->cgpa) }}"
                            placeholder="e.g., 8.5"
                            step="0.01"
                            min="0"
                            max="10"
                            class="input-field @error('cgpa') border-red-500 @enderror"
                        >
                        @error('cgpa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Out of 10</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-blue-50 border-blue-200">
                <h3 class="font-semibold text-gray-800 mb-3">Actions</h3>
                <div class="space-y-2">
                    <button type="submit" class="w-full btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Student
                    </button>
                    <a href="{{ route('students.show', $student) }}" class="block w-full btn-secondary text-center">
                        Cancel
                    </a>
                </div>
            </div>

            <div class="card bg-gray-50">
                <h3 class="font-semibold text-gray-800 mb-3">Student Information</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-600">Account Email</p>
                        <p class="font-medium break-all">{{ $student->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">User ID</p>
                        <p class="font-medium">#{{ $student->user_id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Student ID</p>
                        <p class="font-medium">#{{ $student->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Created</p>
                        <p class="font-medium">{{ $student->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Last Updated</p>
                        <p class="font-medium">{{ $student->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            @can('delete', $student)
            <div class="card bg-red-50 border-red-200">
                <h3 class="font-semibold text-red-800 mb-2">Danger Zone</h3>
                <p class="text-sm text-gray-600 mb-3">Permanently delete this student record</p>
                <button type="button" onclick="if(confirm('Are you sure you want to delete this student? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }" class="btn-danger w-full">
                    Delete Student
                </button>
            </div>
            @endcan
        </div>
    </div>
</form>

@can('delete', $student)
<form id="delete-form" action="{{ route('students.destroy', $student) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endcan

@endsection
