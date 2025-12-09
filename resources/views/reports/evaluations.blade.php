@extends('layouts.app')

@section('title', 'Evaluation Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('reports.index') }}" class="hover:text-blue-600">Reports</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Evaluation Report</span>
    </div>

    <h1 class="text-3xl font-bold text-gray-800">Evaluation Report</h1>
    <p class="text-gray-600 mt-2">Comprehensive listing of all evaluations with grade distribution</p>
</div>

<!-- Grade Distribution Summary -->
@if($gradeDistribution->count() > 0)
    <div class="card mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Grade Distribution</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            @foreach(['A+', 'A', 'B+', 'B', 'C', 'D', 'F'] as $grade)
                @php
                    $gradeCount = $gradeDistribution->firstWhere('internal_exam_grade', $grade);
                    $count = $gradeCount ? $gradeCount->count : 0;
                    $total = $gradeDistribution->sum('count');
                    $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                @endphp
                <div class="text-center p-4 rounded-lg
                    {{ $grade == 'A+' ? 'bg-green-50 border-2 border-green-200' : '' }}
                    {{ $grade == 'A' ? 'bg-blue-50 border-2 border-blue-200' : '' }}
                    {{ $grade == 'B+' ? 'bg-yellow-50 border-2 border-yellow-200' : '' }}
                    {{ $grade == 'B' ? 'bg-orange-50 border-2 border-orange-200' : '' }}
                    {{ in_array($grade, ['C', 'D', 'F']) ? 'bg-red-50 border-2 border-red-200' : '' }}">
                    <p class="text-3xl font-bold
                        {{ $grade == 'A+' ? 'text-green-600' : '' }}
                        {{ $grade == 'A' ? 'text-blue-600' : '' }}
                        {{ $grade == 'B+' ? 'text-yellow-600' : '' }}
                        {{ $grade == 'B' ? 'text-orange-600' : '' }}
                        {{ in_array($grade, ['C', 'D', 'F']) ? 'text-red-600' : '' }}">
                        {{ $count }}
                    </p>
                    <p class="text-sm font-semibold text-gray-800 mt-1">Grade {{ $grade }}</p>
                    <p class="text-xs text-gray-600">{{ $percentage }}%</p>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('reports.evaluations') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <label for="project_id" class="form-label">Project</label>
            <select id="project_id" name="project_id" class="input-field">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                        {{ Str::limit($project->title, 30) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="grade" class="form-label">Grade</label>
            <select id="grade" name="grade" class="input-field">
                <option value="">All Grades</option>
                <option value="A+" {{ $grade == 'A+' ? 'selected' : '' }}>A+</option>
                <option value="A" {{ $grade == 'A' ? 'selected' : '' }}>A</option>
                <option value="B+" {{ $grade == 'B+' ? 'selected' : '' }}>B+</option>
                <option value="B" {{ $grade == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ $grade == 'C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ $grade == 'D' ? 'selected' : '' }}>D</option>
                <option value="F" {{ $grade == 'F' ? 'selected' : '' }}>F</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Apply</button>
            <a href="{{ route('reports.evaluations') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Results Summary -->
<div class="flex items-center justify-between mb-4">
    <p class="text-gray-600">
        Showing <span class="font-semibold">{{ $evaluations->firstItem() ?? 0 }}</span> to
        <span class="font-semibold">{{ $evaluations->lastItem() ?? 0 }}</span> of
        <span class="font-semibold">{{ $evaluations->total() }}</span> evaluations
    </p>
</div>

<!-- Evaluations Table -->
<div class="card">
    @if($evaluations->count() > 0)
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Department</th>
                        <th>Project</th>
                        <th>Evaluation Date</th>
                        <th class="text-center">Marks (out of 15)</th>
                        <th class="text-center">Internal Exam (out of 75)</th>
                        <th class="text-center">Total (out of 90)</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Attendance %</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluations as $evaluation)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $evaluation->student->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $evaluation->student->roll_number }}</p>
                                </div>
                            </td>
                            <td>{{ $evaluation->student->user->department->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($evaluation->project->title, 30) }}</td>
                            <td>{{ $evaluation->evaluation_date?->format('d M, Y') ?? 'N/A' }}</td>
                            <td class="text-center font-semibold">{{ $evaluation->marks_out_of_15 ?? 0 }}</td>
                            <td class="text-center font-semibold">{{ $evaluation->internal_exam_marks ?? 0 }}</td>
                            <td class="text-center font-bold text-blue-600">
                                {{ ($evaluation->marks_out_of_15 ?? 0) + ($evaluation->internal_exam_marks ?? 0) }}
                            </td>
                            <td class="text-center">
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                    {{ $evaluation->internal_exam_grade == 'A+' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $evaluation->internal_exam_grade == 'A' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $evaluation->internal_exam_grade == 'B+' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $evaluation->internal_exam_grade == 'B' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ in_array($evaluation->internal_exam_grade, ['C', 'D']) ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $evaluation->internal_exam_grade == 'F' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $evaluation->internal_exam_grade ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-medium {{ ($evaluation->attendance_percentage ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $evaluation->attendance_percentage ?? 0 }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:text-blue-800">
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
            {{ $evaluations->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 text-lg">No evaluations found matching the selected filters.</p>
            <a href="{{ route('reports.evaluations') }}" class="btn-secondary mt-4">Clear Filters</a>
        </div>
    @endif
</div>
@endsection
