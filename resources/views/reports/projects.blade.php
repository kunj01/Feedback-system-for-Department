@extends('layouts.app')

@section('title', 'Project Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('reports.index') }}" class="hover:text-blue-600">Reports</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Project Report</span>
    </div>

    <h1 class="text-3xl font-bold text-gray-800">Project Report</h1>
    <p class="text-gray-600 mt-2">Comprehensive listing of all projects with status tracking</p>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('reports.projects') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="department_id" class="form-label">Department</label>
            <select id="department_id" name="department_id" class="input-field">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="guide_id" class="form-label">Guide</label>
            <select id="guide_id" name="guide_id" class="input-field">
                <option value="">All Guides</option>
                @foreach($guides as $guide)
                    <option value="{{ $guide->id }}" {{ $guideId == $guide->id ? 'selected' : '' }}>
                        {{ $guide->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="input-field">
                <option value="">All Statuses</option>
                <option value="PLANNING" {{ $status == 'PLANNING' ? 'selected' : '' }}>Planning</option>
                <option value="IN_PROGRESS" {{ $status == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                <option value="COMPLETED" {{ $status == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                <option value="ON_HOLD" {{ $status == 'ON_HOLD' ? 'selected' : '' }}>On Hold</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Apply</button>
            <a href="{{ route('reports.projects') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Results Summary -->
<div class="flex items-center justify-between mb-4">
    <p class="text-gray-600">
        Showing <span class="font-semibold">{{ $projects->firstItem() ?? 0 }}</span> to
        <span class="font-semibold">{{ $projects->lastItem() ?? 0 }}</span> of
        <span class="font-semibold">{{ $projects->total() }}</span> projects
    </p>
</div>

<!-- Projects Table -->
<div class="card">
    @if($projects->count() > 0)
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Students</th>
                        <th>Department</th>
                        <th>Guide</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $project->title }}</p>
                                    @if($project->description)
                                        <p class="text-sm text-gray-600 truncate max-w-xs">{{ Str::limit($project->description, 60) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    @foreach($project->students->take(2) as $student)
                                        <p class="text-gray-700">{{ $student->user->name }}</p>
                                    @endforeach
                                    @if($project->students->count() > 2)
                                        <p class="text-gray-500 text-xs">+{{ $project->students->count() - 2 }} more</p>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $project->students->first()->user->department->name ?? 'N/A' }}</td>
                            <td>{{ $project->guide->name ?? 'Not assigned' }}</td>
                            <td>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $project->status == 'COMPLETED' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $project->status == 'IN_PROGRESS' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $project->status == 'PLANNING' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $project->status == 'ON_HOLD' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td>{{ $project->start_date?->format('d M, Y') ?? 'N/A' }}</td>
                            <td>{{ $project->end_date?->format('d M, Y') ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 text-lg">No projects found matching the selected filters.</p>
            <a href="{{ route('reports.projects') }}" class="btn-secondary mt-4">Clear Filters</a>
        </div>
    @endif
</div>
@endsection
