@extends('layouts.app')

@section('title', $project->title)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('projects.index') }}" class="hover:text-blue-600">Projects</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>{{ Str::limit($project->title, 40) }}</span>
    </div>
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $project->title }}</h1>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    @if($project->status == 'Completed') bg-green-100 text-green-800
                    @elseif($project->status == 'In Progress') bg-blue-100 text-blue-800
                    @elseif($project->status == 'Proposed') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $project->status }}
                </span>
                <span class="text-sm text-gray-600">{{ $project->academic_year }}</span>
            </div>
        </div>
        @can('update', $project)
        <a href="{{ route('projects.edit', $project) }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Project
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Description -->
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Project Description</h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
        </div>

        <!-- Timeline -->
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Timeline</h2>
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500">Start Date</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $project->start_date->format('M d, Y') }}</p>
                </div>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-gray-500">End Date</p>
                    <p class="text-lg font-semibold text-gray-900">
                        @if($project->end_date)
                        {{ $project->end_date->format('M d, Y') }}
                        @else
                        <span class="text-gray-400">Not set</span>
                        @endif
                    </p>
                </div>
                @if($project->start_date && $project->end_date)
                <div class="flex-1">
                    <p class="text-sm text-gray-500">Duration</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $project->start_date->diffInDays($project->end_date) }} days</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Evaluations -->
        @if($project->evaluations->isNotEmpty())
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Evaluations ({{ $project->evaluations->count() }})</h2>
            <div class="space-y-3">
                @foreach($project->evaluations as $evaluation)
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $evaluation->evaluation_type }}</h3>
                            <p class="text-sm text-gray-600">Evaluated by {{ $evaluation->evaluatedBy->name }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                            {{ $evaluation->score }}/{{ $evaluation->max_score }}
                        </span>
                    </div>
                    @if($evaluation->remarks)
                    <p class="text-sm text-gray-700 mt-2">{{ $evaluation->remarks }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $evaluation->evaluation_date->format('M d, Y') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="card bg-gray-50">
            <p class="text-center text-gray-500 py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                No evaluations recorded yet
            </p>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Student Info -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Student</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-lg font-semibold">
                    {{ strtoupper(substr($project->student->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $project->student->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $project->student->enrollment_number }}</p>
                </div>
            </div>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Department</dt>
                    <dd class="text-gray-900 font-medium">{{ $project->student->user->department->name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900">{{ $project->student->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">CGPA</dt>
                    <dd class="text-gray-900 font-medium">{{ number_format($project->student->cgpa, 2) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Guide Info -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Project Guide</h3>
            @if($project->guide)
            <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-semibold">
                    {{ strtoupper(substr($project->guide->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $project->guide->name }}</p>
                    <p class="text-sm text-gray-600">Faculty Guide</p>
                </div>
            </div>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900">{{ $project->guide->email }}</dd>
                </div>
            </dl>
            @else
            <p class="text-center text-gray-500 py-6 text-sm">
                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                No guide assigned
            </p>
            @endif
        </div>

        <!-- Statistics -->
        <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <h3 class="font-semibold mb-4">Project Statistics</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>Evaluations</span>
                    <span class="font-bold">{{ $project->evaluations->count() }}</span>
                </div>
                @if($project->evaluations->isNotEmpty())
                <div class="flex justify-between">
                    <span>Avg Score</span>
                    <span class="font-bold">
                        {{ number_format($project->evaluations->avg('score'), 1) }}/{{ $project->evaluations->first()->max_score }}
                    </span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span>Days Active</span>
                    <span class="font-bold">{{ $project->start_date->diffInDays(now()) }}</span>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Metadata</h3>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Created</dt>
                    <dd class="text-gray-900">{{ $project->created_at->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Last Updated</dt>
                    <dd class="text-gray-900">{{ $project->updated_at->diffForHumans() }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
