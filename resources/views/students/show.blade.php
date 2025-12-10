@extends('layouts.app')

@section('title', 'Student Profile')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('students.index') }}" class="hover:text-blue-600">Students</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Student Profile</span>
    </div>
    <div class="flex justify-between items-start">
        <h1 class="text-3xl font-bold text-gray-800">Student Profile</h1>
        @can('update', $student)
        <a href="{{ route('students.edit', $student) }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Profile
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Basic Information</h3>
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($student->first_name ?? $student->user->name, 0, 2)) }}
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')) ?: $student->user->name }}
                        </h2>
                        <p class="text-gray-600">{{ $student->email ?? $student->user->email }}</p>
                        @if($student->student_id)
                        <p class="text-sm text-indigo-600 font-semibold mt-1">ID: {{ $student->student_id }}</p>
                        @endif
                    </div>
                </div>
                @if($student->is_eligible)
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Eligible
                </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                @if($student->roll_no)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Roll Number</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->roll_no }}</p>
                </div>
                @endif
                @if($student->registration_no)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Registration Number</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->registration_no }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600 mb-1">Gender</p>
                    <p class="text-lg font-semibold text-gray-800">
                        @if($student->gender == 'M') Male
                        @elseif($student->gender == 'F') Female
                        @elseif($student->gender == 'O') Other
                        @else <span class="text-gray-400">Not Specified</span>
                        @endif
                    </p>
                </div>
                @if($student->dob)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Date of Birth</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->dob->format('d M, Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600 mb-1">Department</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->department->name ?? 'Not Assigned' }}</p>
                </div>
                @if($student->course)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Course</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->course }}</p>
                </div>
                @endif
                @if($student->batch)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Batch</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->batch }}</p>
                </div>
                @endif
                @if($student->academic_year)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Academic Year</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->academic_year }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600 mb-1">Training Status</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($student->training_status == 'COMPLETED') bg-green-100 text-green-800
                            @elseif($student->training_status == 'IN_TRAINING') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ str_replace('_', ' ', $student->training_status ?? 'NOT ASSIGNED') }}
                        </span>
                    </p>
                </div>
                @if($student->counsellor_name)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Counsellor</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->counsellor_name }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Contact Information</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Contact Number</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->contact ?? 'Not Provided' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">CHARUSAT Email</p>
                    <p class="text-lg font-semibold text-gray-800 break-all">{{ $student->email ?? $student->user->email }}</p>
                </div>
                @if($student->personal_email)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Personal Email</p>
                    <p class="text-lg font-semibold text-gray-800 break-all">{{ $student->personal_email }}</p>
                </div>
                @endif
                @if($student->city)
                <div>
                    <p class="text-sm text-gray-600 mb-1">City</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->city }}</p>
                </div>
                @endif
            </div>
            @if($student->address)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Address</p>
                <p class="text-lg font-semibold text-gray-800">{{ $student->address }}</p>
            </div>
            @endif
        </div>

        <!-- Family Information -->
        @if($student->father_name || $student->mother_name)
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Family Information</h3>
            <div class="grid grid-cols-2 gap-6">
                @if($student->father_name)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Father's Name</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->father_name }}</p>
                </div>
                @endif
                @if($student->mother_name)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Mother's Name</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->mother_name }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Academic Performance -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Academic Performance</h3>
            <div class="grid grid-cols-2 gap-6">
                @if($student->ssc_percentage)
                <div>
                    <p class="text-sm text-gray-600 mb-1">SSC (10th) Percentage</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($student->ssc_percentage >= 80) bg-green-100 text-green-800
                            @elseif($student->ssc_percentage >= 60) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ number_format($student->ssc_percentage, 2) }}%
                        </span>
                    </p>
                </div>
                @endif
                @if($student->hsc_percentage)
                <div>
                    <p class="text-sm text-gray-600 mb-1">HSC (12th) Percentage</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($student->hsc_percentage >= 80) bg-green-100 text-green-800
                            @elseif($student->hsc_percentage >= 60) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ number_format($student->hsc_percentage, 2) }}%
                        </span>
                    </p>
                </div>
                @endif
                @if($student->diploma_percentage)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Diploma Percentage</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($student->diploma_percentage >= 80) bg-green-100 text-green-800
                            @elseif($student->diploma_percentage >= 60) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ number_format($student->diploma_percentage, 2) }}%
                        </span>
                    </p>
                </div>
                @endif
                @if($student->btech_cgpa_upto_5th)
                <div>
                    <p class="text-sm text-gray-600 mb-1">B.Tech CGPA (Upto 5th Sem)</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($student->btech_cgpa_upto_5th >= 8) bg-green-100 text-green-800
                            @elseif($student->btech_cgpa_upto_5th >= 6) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ number_format($student->btech_cgpa_upto_5th, 2) }} / 10
                        </span>
                    </p>
                </div>
                @endif
                @if($student->cgpa)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Current CGPA</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($student->cgpa >= 8) bg-green-100 text-green-800
                            @elseif($student->cgpa >= 6) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ number_format($student->cgpa, 2) }} / 10
                        </span>
                    </p>
                </div>
                @endif
                @if($student->admission_type)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Admission Type</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->admission_type }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Projects -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Projects ({{ $student->projects->count() }})
            </h3>
            @forelse($student->projects as $project)
            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $project->title }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($project->description, 100) }}</p>
                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                            <span>Guide: {{ $project->guide->name ?? 'Not Assigned' }}</span>
                            <span>•</span>
                            <span>{{ $project->academic_year }}</span>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($project->status == 'Completed') bg-green-100 text-green-800
                        @elseif($project->status == 'In Progress') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $project->status }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">No projects assigned yet</p>
            @endforelse
        </div>

        <!-- Evaluations -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Recent Evaluations ({{ $student->evaluations->count() }})
            </h3>
            @forelse($student->evaluations->take(5) as $evaluation)
            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $evaluation->project->title ?? 'Project' }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Evaluated by: {{ $evaluation->evaluatedBy->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $evaluation->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-blue-600">{{ $evaluation->total_marks }}</span>
                        <p class="text-xs text-gray-500">/ 100</p>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">No evaluations yet</p>
            @endforelse
        </div>

        <!-- Placements -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Placement Records ({{ $student->placements->count() }})
            </h3>
            @forelse($student->placements as $placement)
            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $placement->company->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $placement->job_role }} - {{ $placement->job_type }}</p>
                        <p class="text-sm font-semibold text-green-600 mt-1">₹ {{ number_format($placement->package_lpa, 2) }} LPA</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($placement->offer_status == 'Accepted') bg-green-100 text-green-800
                        @elseif($placement->offer_status == 'Pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $placement->offer_status }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">No placement records</p>
            @endforelse
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">Quick Actions</h3>
            <div class="space-y-2">
                @can('update', $student)
                <a href="{{ route('students.edit', $student) }}" class="block w-full btn-primary text-center">
                    Edit Profile
                </a>
                @endcan
                <a href="{{ route('students.index') }}" class="block w-full btn-secondary text-center">
                    Back to Students
                </a>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-3">Statistics</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Projects</span>
                    <span class="text-lg font-bold text-blue-600">{{ $student->projects->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Evaluations</span>
                    <span class="text-lg font-bold text-purple-600">{{ $student->evaluations->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Placements</span>
                    <span class="text-lg font-bold text-green-600">{{ $student->placements->count() }}</span>
                </div>
                @if($student->evaluations->count() > 0)
                <div class="flex items-center justify-between pt-3 border-t">
                    <span class="text-sm text-gray-600">Avg. Score</span>
                    <span class="text-lg font-bold text-indigo-600">
                        {{ number_format($student->evaluations->avg('total_marks'), 1) }}%
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Account Details</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-gray-600">User ID</p>
                    <p class="font-medium">#{{ $student->user_id }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Student ID</p>
                    <p class="font-medium">#{{ $student->id }}</p>
                </div>
                @if($student->student_id)
                <div>
                    <p class="text-gray-600">ID Number</p>
                    <p class="font-medium">{{ $student->student_id }}</p>
                </div>
                @endif
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
    </div>
</div>
@endsection
