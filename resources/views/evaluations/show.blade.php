@extends('layouts.app')

@section('title', 'Evaluation Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('evaluations.index') }}" class="hover:text-blue-600">Evaluations</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Evaluation #{{ $evaluation->id }}</span>
    </div>
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Evaluation Details</h1>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @if($evaluation->internal_exam_grade == 'A+') bg-green-100 text-green-800
                    @elseif($evaluation->internal_exam_grade == 'A') bg-blue-100 text-blue-800
                    @elseif($evaluation->internal_exam_grade == 'B+') bg-yellow-100 text-yellow-800
                    @elseif($evaluation->internal_exam_grade == 'B') bg-orange-100 text-orange-800
                    @elseif($evaluation->internal_exam_grade == 'C') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    Grade: {{ $evaluation->internal_exam_grade }}
                </span>
                <span class="text-sm text-gray-600">{{ $evaluation->evaluation_date->format('M d, Y') }}</span>
            </div>
        </div>
        @can('update', $evaluation)
        <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Evaluation
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Marks & Performance -->
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Marks & Performance</h2>
            <div class="grid grid-cols-3 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Project Work</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($evaluation->marks_out_of_15, 2) }}</p>
                    <p class="text-xs text-gray-500">Out of 15</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Internal Exam</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($evaluation->internal_exam_marks, 2) }}</p>
                    <p class="text-xs text-gray-500">Out of 75</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Attendance</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($evaluation->attendance_percent, 1) }}%</p>
                    <p class="text-xs text-gray-500">Overall</p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm opacity-90">Final Grade</p>
                        <p class="text-4xl font-bold">{{ $evaluation->internal_exam_grade }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90">Total Marks</p>
                        <p class="text-2xl font-semibold">{{ number_format($evaluation->marks_out_of_15 + $evaluation->internal_exam_marks, 2) }}</p>
                        <p class="text-xs opacity-75">Out of 90</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Details -->
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Project Information</h2>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-500">Project Title</dt>
                    <dd class="text-gray-900 font-medium mt-1">{{ $evaluation->project->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Project Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($evaluation->project->status == 'Completed') bg-green-100 text-green-800
                            @elseif($evaluation->project->status == 'In Progress') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $evaluation->project->status }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Academic Year</dt>
                    <dd class="text-gray-900 font-medium mt-1">{{ $evaluation->project->academic_year }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Evaluation Mode</dt>
                    <dd class="text-gray-900 font-medium mt-1">{{ $evaluation->mode }}</dd>
                </div>
            </dl>
        </div>

        <!-- Remarks & Feedback -->
        @if($evaluation->remarks)
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Evaluator Remarks</h2>
            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $evaluation->remarks }}</p>
            </div>
        </div>
        @endif

        <!-- HOD Approval (if implemented) -->
        @if($evaluation->approved_by_head !== null)
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">HOD Review</h2>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    @if($evaluation->approved_by_head)
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    @else
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">
                        {{ $evaluation->approved_by_head ? 'Approved' : 'Rejected' }}
                    </p>
                    @if($evaluation->head_comments)
                    <p class="text-gray-600 mt-2">{{ $evaluation->head_comments }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Student Info -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Student</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-lg font-semibold">
                    {{ strtoupper(substr($evaluation->student->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $evaluation->student->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $evaluation->student->enrollment_number }}</p>
                </div>
            </div>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Department</dt>
                    <dd class="text-gray-900 font-medium">{{ $evaluation->student->user->department->name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900">{{ $evaluation->student->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Current CGPA</dt>
                    <dd class="text-gray-900 font-medium">{{ number_format($evaluation->student->cgpa, 2) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Evaluator Info -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Evaluator</h3>
            @if($evaluation->guide)
            <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-semibold">
                    {{ strtoupper(substr($evaluation->guide->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $evaluation->guide->name }}</p>
                    <p class="text-sm text-gray-600">Guide/Evaluator</p>
                </div>
            </div>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900">{{ $evaluation->guide->email }}</dd>
                </div>
            </dl>
            @else
            <p class="text-center text-gray-500 py-4 text-sm">No evaluator assigned</p>
            @endif
        </div>

        <!-- Grading Reference -->
        <div class="card bg-gradient-to-br from-gray-50 to-gray-100">
            <h3 class="font-semibold text-gray-800 mb-3">📊 Grading Scale</h3>
            <ul class="text-sm space-y-2">
                <li class="flex justify-between">
                    <span class="font-semibold text-green-700">A+</span>
                    <span class="text-gray-600">70-75</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-blue-700">A</span>
                    <span class="text-gray-600">60-69</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-yellow-700">B+</span>
                    <span class="text-gray-600">50-59</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-orange-700">B</span>
                    <span class="text-gray-600">40-49</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-red-700">C</span>
                    <span class="text-gray-600">35-39</span>
                </li>
                <li class="flex justify-between">
                    <span class="font-semibold text-gray-700">F</span>
                    <span class="text-gray-600">&lt; 35</span>
                </li>
            </ul>
        </div>

        <!-- Metadata -->
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Metadata</h3>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Created</dt>
                    <dd class="text-gray-900">{{ $evaluation->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Last Updated</dt>
                    <dd class="text-gray-900">{{ $evaluation->updated_at->diffForHumans() }}</dd>
                </div>
                @if($evaluation->locked)
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="text-orange-600 font-semibold">🔒 Locked</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
