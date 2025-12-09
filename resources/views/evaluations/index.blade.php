@extends('layouts.app')

@section('title', 'Evaluation Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Evaluation Management</h1>
            <p class="text-gray-600 mt-1">Manage student project evaluations and grades</p>
        </div>
        @can('create', App\Models\Evaluation::class)
        <a href="{{ route('evaluations.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Evaluation
        </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('evaluations.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="md:col-span-2">
            <label class="label">Search</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Student, project, remarks..."
                class="input-field"
            >
        </div>
        <div>
            <label class="label">Project</label>
            <select name="project_id" class="input-field">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                    {{ Str::limit($project->title, 30) }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Mode</label>
            <select name="mode" class="input-field">
                <option value="">All Modes</option>
                <option value="ONLINE" {{ request('mode') == 'ONLINE' ? 'selected' : '' }}>Online</option>
                <option value="OFFLINE" {{ request('mode') == 'OFFLINE' ? 'selected' : '' }}>Offline</option>
                <option value="NA" {{ request('mode') == 'NA' ? 'selected' : '' }}>N/A</option>
            </select>
        </div>
        <div>
            <label class="label">Grade</label>
            <select name="grade" class="input-field">
                <option value="">All Grades</option>
                @foreach($grades as $grade)
                <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                    {{ $grade }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Filter</button>
            <a href="{{ route('evaluations.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Evaluations Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks (15)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Internal (75)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($evaluations as $evaluation)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr($evaluation->student->user->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $evaluation->student->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $evaluation->student->enrollment_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($evaluation->project->title, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $evaluation->evaluation_date->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $evaluation->mode }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold text-gray-900">
                            {{ number_format($evaluation->marks_out_of_15, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold text-gray-900">
                            {{ number_format($evaluation->internal_exam_marks, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($evaluation->internal_exam_grade == 'A+') bg-green-100 text-green-800
                            @elseif($evaluation->internal_exam_grade == 'A') bg-blue-100 text-blue-800
                            @elseif($evaluation->internal_exam_grade == 'B+') bg-yellow-100 text-yellow-800
                            @elseif($evaluation->internal_exam_grade == 'B') bg-orange-100 text-orange-800
                            @elseif($evaluation->internal_exam_grade == 'C') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $evaluation->internal_exam_grade }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">{{ number_format($evaluation->attendance_percent, 1) }}%</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            @can('view', $evaluation)
                            <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            @endcan
                            @can('update', $evaluation)
                            <a href="{{ route('evaluations.edit', $evaluation) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @endcan
                            @can('delete', $evaluation)
                            <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this evaluation?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2">No evaluations found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($evaluations->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $evaluations->links() }}
    </div>
    @endif
</div>
@endsection
