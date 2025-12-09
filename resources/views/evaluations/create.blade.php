@extends('layouts.app')

@section('title', 'Create Evaluation')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('evaluations.index') }}" class="hover:text-blue-600">Evaluations</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Create New Evaluation</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Create New Evaluation</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('evaluations.store') }}">
                @csrf

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
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                            <option value="{{ $guide->id }}" {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
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
                                value="{{ old('evaluation_date', date('Y-m-d')) }}"
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
                                <option value="ONLINE" {{ old('mode') == 'ONLINE' ? 'selected' : '' }}>Online</option>
                                <option value="OFFLINE" {{ old('mode', 'OFFLINE') == 'OFFLINE' ? 'selected' : '' }}>Offline</option>
                                <option value="NA" {{ old('mode') == 'NA' ? 'selected' : '' }}>N/A</option>
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
                                    value="{{ old('marks_out_of_15') }}"
                                    step="0.01"
                                    min="0"
                                    max="15"
                                    placeholder="0.00"
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
                                    value="{{ old('internal_exam_marks') }}"
                                    step="0.01"
                                    min="0"
                                    max="75"
                                    placeholder="0.00"
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
                                value="{{ old('attendance_percent') }}"
                                step="0.01"
                                min="0"
                                max="100"
                                placeholder="0.00"
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
                        >{{ old('remarks') }}</textarea>
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
                        Create Evaluation
                    </button>
                    <a href="{{ route('evaluations.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-blue-50 border-blue-200">
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

        <div class="card bg-yellow-50 border-yellow-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">💡 Guidelines</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Marks out of 15: Project work evaluation</li>
                <li>• Internal exam: Out of 75 marks</li>
                <li>• Grade is auto-calculated from internal marks</li>
                <li>• Attendance should be in percentage</li>
                <li>• Add detailed remarks for student feedback</li>
            </ul>
        </div>
    </div>
</div>
@endsection
