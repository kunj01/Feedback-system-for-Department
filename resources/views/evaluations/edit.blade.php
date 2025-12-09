@extends('layouts.app')

@section('title', 'Edit Evaluation')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('evaluations.index') }}" class="hover:text-blue-600">Evaluations</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('evaluations.show', $evaluation) }}" class="hover:text-blue-600">Evaluation #{{ $evaluation->id }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit Evaluation</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('evaluations.update', $evaluation) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Project Selection -->
                    <div>
                        <label for="project_id" class="label required">Project</label>
                        <select
                            id="project_id"
                            name="project_id"
                            class="input-field @error('project_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ (old('project_id', $evaluation->project_id) == $project->id) ? 'selected' : '' }}>
                                {{ $project->title }} ({{ $project->student->user->name }})
                            </option>
                            @endforeach
                        </select>
                        @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Selection -->
                    <div>
                        <label for="student_id" class="label required">Student</label>
                        <select
                            id="student_id"
                            name="student_id"
                            class="input-field @error('student_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ (old('student_id', $evaluation->student_id) == $student->id) ? 'selected' : '' }}>
                                {{ $student->user->name }} ({{ $student->enrollment_number }})
                            </option>
                            @endforeach
                        </select>
                        @error('student_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guide Selection -->
                    <div>
                        <label for="guide_id" class="label required">Evaluator (Guide)</label>
                        <select
                            id="guide_id"
                            name="guide_id"
                            class="input-field @error('guide_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Evaluator</option>
                            @foreach($guides as $guide)
                            <option value="{{ $guide->id }}"
                                {{ (old('guide_id', $evaluation->guide_id) == $guide->id) ? 'selected' : '' }}>
                                {{ $guide->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('guide_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Evaluation Date & Mode -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="evaluation_date" class="label required">Evaluation Date</label>
                            <input
                                type="date"
                                id="evaluation_date"
                                name="evaluation_date"
                                value="{{ old('evaluation_date', $evaluation->evaluation_date->format('Y-m-d')) }}"
                                class="input-field @error('evaluation_date') border-red-500 @enderror"
                                required
                            >
                            @error('evaluation_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mode" class="label required">Evaluation Mode</label>
                            <select
                                id="mode"
                                name="mode"
                                class="input-field @error('mode') border-red-500 @enderror"
                                required
                            >
                                <option value="">Select Mode</option>
                                <option value="ONLINE" {{ old('mode', $evaluation->mode) == 'ONLINE' ? 'selected' : '' }}>Online</option>
                                <option value="OFFLINE" {{ old('mode', $evaluation->mode) == 'OFFLINE' ? 'selected' : '' }}>Offline</option>
                                <option value="NA" {{ old('mode', $evaluation->mode) == 'NA' ? 'selected' : '' }}>N/A</option>
                            </select>
                            @error('mode')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Marks Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Marks & Grading</h3>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="marks_out_of_15" class="label required">Marks (Out of 15)</label>
                                <input
                                    type="number"
                                    id="marks_out_of_15"
                                    name="marks_out_of_15"
                                    value="{{ old('marks_out_of_15', $evaluation->marks_out_of_15) }}"
                                    step="0.01"
                                    min="0"
                                    max="15"
                                    class="input-field @error('marks_out_of_15') border-red-500 @enderror"
                                    required
                                >
                                @error('marks_out_of_15')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="internal_exam_marks" class="label required">Internal Exam Marks (Out of 75)</label>
                                <input
                                    type="number"
                                    id="internal_exam_marks"
                                    name="internal_exam_marks"
                                    value="{{ old('internal_exam_marks', $evaluation->internal_exam_marks) }}"
                                    step="0.01"
                                    min="0"
                                    max="75"
                                    class="input-field @error('internal_exam_marks') border-red-500 @enderror"
                                    required
                                >
                                @error('internal_exam_marks')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">Grade will be auto-calculated</p>
                            </div>
                        </div>

                        <div>
                            <label for="attendance_percent" class="label required">Attendance Percentage</label>
                            <input
                                type="number"
                                id="attendance_percent"
                                name="attendance_percent"
                                value="{{ old('attendance_percent', $evaluation->attendance_percent) }}"
                                step="0.01"
                                min="0"
                                max="100"
                                class="input-field @error('attendance_percent') border-red-500 @enderror"
                                required
                            >
                            @error('attendance_percent')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label for="remarks" class="label">Remarks & Observations</label>
                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            placeholder="Enter evaluation feedback, strengths, areas for improvement..."
                            class="input-field @error('remarks') border-red-500 @enderror"
                        >{{ old('remarks', $evaluation->remarks) }}</textarea>
                        @error('remarks')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Evaluation
                    </button>
                    <a href="{{ route('evaluations.show', $evaluation) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        @can('delete', $evaluation)
        <div class="card border-2 border-red-200 mt-6">
            <h3 class="text-lg font-semibold text-red-600 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">Once you delete this evaluation, there is no going back. Please be certain.</p>
            <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this evaluation? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Evaluation</button>
            </form>
        </div>
        @endcan
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Evaluation Info</h3>
            <dl class="text-sm space-y-2">
                <div>
                    <dt class="text-gray-500">Current Grade</dt>
                    <dd class="text-gray-900 font-bold text-lg">{{ $evaluation->internal_exam_grade }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Created</dt>
                    <dd class="text-gray-900 font-medium">{{ $evaluation->created_at->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Last Updated</dt>
                    <dd class="text-gray-900 font-medium">{{ $evaluation->updated_at->diffForHumans() }}</dd>
                </div>
            </dl>
        </div>

        <div class="card bg-blue-50 border-blue-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-3">📊 Grading Scale</h3>
            <ul class="text-sm text-gray-700 space-y-2">
                <li><span class="font-semibold text-green-700">A+:</span> 70-75 marks</li>
                <li><span class="font-semibold text-blue-700">A:</span> 60-69 marks</li>
                <li><span class="font-semibold text-yellow-700">B+:</span> 50-59 marks</li>
                <li><span class="font-semibold text-orange-700">B:</span> 40-49 marks</li>
                <li><span class="font-semibold text-red-700">C:</span> 35-39 marks</li>
                <li><span class="font-semibold text-gray-700">F:</span> Below 35</li>
            </ul>
        </div>
    </div>
</div>
@endsection
