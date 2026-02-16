@extends('layouts.app')

@section('title', 'Assign Form - ' . $formTitle)
@section('page-title', 'Assign Form: ' . $formTitle)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('forms.index') }}" class="btn-secondary inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Forms
        </a>
    </div>

    <!-- Form Details Card -->
    <div class="card mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $formTitle }}</h2>
                <p class="text-gray-600">{{ $formName }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('forms.download', $formName) }}" class="btn-secondary">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Form
                </a>
                <a href="{{ route('forms.responses', $formName) }}" class="btn-primary">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Submissions ({{ $assignments->where('status', 'completed')->count() }})
                </a>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t">
            <div>
                <p class="text-sm text-gray-600">Total Assignments</p>
                <p class="text-2xl font-bold text-gray-800">{{ $assignments->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $assignments->where('status', 'pending')->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Completed</p>
                <p class="text-2xl font-bold text-green-600">{{ $assignments->where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- New Batch-wise Assignment Section -->
    <div class="card mb-6 border-2 border-green-200 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Batch-wise Assignment (Recommended)
                </h3>
                <p class="text-sm text-gray-600 mt-1">Assign feedback forms based on teacher-batch assignments. Select students by batch, then select their teachers.</p>
            </div>
            <button type="button" onclick="toggleBatchWiseSection()" class="text-green-600 hover:text-green-800 transition-colors">
                <svg id="batchWiseDropdownIcon" class="w-7 h-7 transform transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <div id="batchWiseSection">
            <form action="{{ route('forms.assign', $formName) }}" method="POST" id="batchWiseAssignForm">
                @csrf
                <input type="hidden" name="batch_wise_mode" value="1">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Student Selection -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-md font-bold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Step 1: Select Students
                            <span class="ml-2 text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">All Students</span>
                        </h4>
                        
                        <div class="mb-3 p-2 bg-white border border-blue-300 rounded-lg">
                            <p class="text-xs text-blue-800 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <strong>All students are displayed below.</strong> Use filters to narrow the list if needed. Students can have multiple batches/labs.
                            </p>
                        </div>
                        
                        <!-- Filter Section -->
                        <div class="mb-3 p-3 bg-white border border-blue-300 rounded-lg">
                            <h5 class="text-xs font-bold text-gray-700 mb-2">Filter Students</h5>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Semester</label>
                                    <select id="batchWiseSemesterFilter" onchange="updateBatchWiseDivisionFilter(); filterBatchWiseStudents();" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">All Semesters</option>
                                        <option value="4">Semester 4</option>
                                        <option value="6">Semester 6</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Division</label>
                                    <select id="batchWiseDivisionFilter" onchange="updateBatchWiseBatchFilter(); filterBatchWiseStudents(); updateTeacherListForStudents();" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                        <option value="">All Divisions</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Batch</label>
                                <select id="batchWiseBatchFilter" onchange="filterBatchWiseStudents(); updateTeacherListForStudents();" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">All Batches</option>
                                </select>
                            </div>
                            <div id="batchWiseFilterSummary" class="mt-2 text-xs text-blue-700 font-medium hidden">
                                <span id="batchWiseFilteredCount">0</span> students found
                            </div>
                        </div>
                        
                        <!-- Student List -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-medium text-gray-700">Select Students</label>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="toggleBatchWiseStudentList()" class="text-xs px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded transition-colors flex items-center gap-1">
                                        <svg id="batchWiseStudentListIcon" class="w-3 h-3 transform transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <span id="batchWiseStudentListText">Hide List</span>
                                    </button>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" id="select-all-batchwise-students" class="form-checkbox h-3 w-3 text-blue-600 mr-1">
                                        <span class="text-xs font-medium text-blue-600">Select All</span>
                                    </label>
                                </div>
                            </div>
                            <div id="batchWiseStudentList" class="space-y-1 max-h-80 overflow-y-auto border rounded-lg p-2 bg-white">
                                @foreach($students as $student)
                                    @php
                                        $studentAssignments = $assignments->where('student_id', $student->id);
                                        $hasAssignments = $studentAssignments->isNotEmpty();
                                        $assignmentCount = $studentAssignments->count();
                                        $divisionName = $student->division ? $student->division->name : 'Unknown';
                                        $batchName = $student->batchGroup ? $student->batchGroup->batch_name : '';
                                    @endphp
                                    <label class="batchwise-student-item flex items-center p-2 hover:bg-blue-50 rounded transition-colors {{ $hasAssignments ? 'bg-yellow-50 border border-yellow-200' : '' }}"
                                           data-semester="{{ $student->semester }}"
                                           data-division="{{ $divisionName }}"
                                           data-batch="{{ $batchName }}"
                                           data-batch-id="{{ $student->batch_id }}"
                                           data-student-id="{{ $student->id }}">
                                        <input type="checkbox" name="batch_wise_student_ids[]" value="{{ $student->id }}"
                                               onchange="updateTeacherListForStudents()"
                                               class="form-checkbox h-3 w-3 text-blue-600 batchwise-student-checkbox">
                                        <div class="ml-2 flex-1 min-w-0">
                                            <div class="flex items-center gap-1 flex-wrap">
                                                <p class="text-xs font-medium text-gray-900 truncate">{{ $student->user->name }}</p>
                                                <span class="text-xs px-1 py-0.5 bg-blue-100 text-blue-700 rounded">{{ $divisionName }}</span>
                                                @if($batchName)
                                                    <span class="text-xs px-1 py-0.5 bg-purple-100 text-purple-700 rounded">{{ $batchName }}</span>
                                                @endif
                                                @if($hasAssignments)
                                                    <span class="text-xs px-1.5 py-0.5 bg-orange-100 text-orange-700 rounded font-semibold" title="Already assigned to {{ $assignmentCount }} teacher(s)">
                                                        {{ $assignmentCount }} teacher{{ $assignmentCount > 1 ? 's' : '' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $student->enrollment_no ?? $student->student_id }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Teacher Selection -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-md font-bold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Step 2: Select Teachers
                        </h4>
                        
                        <div id="batchWiseTeacherInfo" class="mb-3 p-2 bg-white border border-green-300 rounded text-xs text-gray-600">
                            <p><strong>All students are displayed on the left.</strong> Once you select students, teachers who teach those students' batches will appear here.</p>
                            <p class="mt-1 text-green-700">💡 Use filters to narrow down the student list if needed.</p>
                        </div>
                        
                        <!-- Teacher List -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-medium text-gray-700">Available Teachers</label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="select-all-batchwise-teachers" class="form-checkbox h-3 w-3 text-green-600 mr-1">
                                    <span class="text-xs font-medium text-green-600">Select All</span>
                                </label>
                            </div>
                            <div id="batchWiseTeacherList" class="space-y-2 max-h-80 overflow-y-auto border rounded-lg p-2 bg-white">
                                <p class="text-center text-xs text-gray-400 py-8">No students selected yet</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            <p class="font-medium">Ready to assign?</p>
                            <p class="text-xs text-gray-600 mt-1">Students will receive feedback forms for their selected teachers and subjects.</p>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Assign Forms
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assign to Students (Original Method) -->
        <div class="card border-2 border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Manual Assignment</h3>
            <p class="text-xs text-gray-600 mb-4">Assign to specific students without batch-teacher matching.</p>
            <form action="{{ route('forms.assign', $formName) }}" method="POST" id="assignForm">
                @csrf
                
                <!-- Feedback Period Configuration -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Feedback Period (Optional)</h4>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date & Time</label>
                            <input 
                                type="datetime-local" 
                                name="start_date" 
                                id="start_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('start_date') }}">
                            <p class="text-xs text-gray-500 mt-1">Leave empty for immediate access</p>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date & Time</label>
                            <input 
                                type="datetime-local" 
                                name="end_date" 
                                id="end_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('end_date') }}">
                            <p class="text-xs text-gray-500 mt-1">Leave empty for no deadline</p>
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Grace Period -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grace Period (Hours)</label>
                            <input 
                                type="number" 
                                name="grace_period_hours" 
                                id="grace_period_hours"
                                min="0"
                                max="168"
                                value="{{ old('grace_period_hours', 0) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="0">
                            <p class="text-xs text-gray-500 mt-1">Extra hours after end date to allow late submissions</p>
                            @error('grace_period_hours')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Filter Section -->
                <div class="mb-4 p-3 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Students
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Semester Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Semester</label>
                            <select id="semesterFilter" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">All Semesters</option>
                                <option value="4">Semester 4</option>
                                <option value="6">Semester 6</option>
                            </select>
                        </div>
                        
                        <!-- Division Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Division</label>
                            <select id="divisionFilter" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">All Divisions</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Batch Multi-Select -->
                    <div class="mt-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Batches</label>
                        <div id="batchFilterContainer" class="bg-white border border-gray-300 rounded-lg p-2 max-h-24 overflow-y-auto">
                            <p class="text-xs text-gray-400 italic">Select semester and division first</p>
                        </div>
                    </div>
                    
                    <!-- Filter Summary -->
                    <div id="filterSummary" class="mt-2 text-xs text-blue-700 font-medium hidden">
                        <span id="filteredCount">0</span> students found
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-medium text-gray-700">Select Students</label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="toggleManualStudentList()" class="text-xs px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded transition-colors flex items-center gap-1">
                                <svg id="manualStudentListIcon" class="w-3 h-3 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <span id="manualStudentListText">Show List</span>
                            </button>
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    id="select-all-students" 
                                    class="form-checkbox h-4 w-4 text-blue-600 mr-1.5">
                                <span class="text-xs font-medium text-blue-600">Select All Visible</span>
                            </label>
                        </div>
                    </div>
                    <div id="studentListContainer" class="hidden space-y-1.5 max-h-80 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                        @foreach($students as $student)
                            @php
                                $isAssigned = $assignments->where('student_id', $student->id)->isNotEmpty();
                                $divisionName = $student->division ? $student->division->name : 'Unknown';
                                $batchName = $student->batchGroup ? $student->batchGroup->batch_name : '';
                            @endphp
                            <label class="student-item flex items-center p-2 hover:bg-white rounded transition-colors {{ $isAssigned ? 'bg-green-50' : 'bg-white' }}"
                                   data-semester="{{ $student->semester }}"
                                   data-division="{{ $divisionName }}"
                                   data-batch="{{ $batchName }}"
                                   data-student-id="{{ $student->id }}">
                                <input 
                                    type="checkbox" 
                                    name="student_ids[]" 
                                    value="{{ $student->id }}"
                                    {{ $isAssigned ? 'disabled checked' : '' }}
                                    class="form-checkbox h-4 w-4 text-blue-600 student-checkbox">
                                <div class="ml-2 flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <p class="text-xs font-medium text-gray-900 truncate">{{ $student->user->name }}</p>
                                        <span class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded font-semibold">
                                            {{ $divisionName }}
                                        </span>
                                        @if($batchName)
                                            <span class="text-xs px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded font-semibold">
                                                {{ $batchName }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 truncate">{{ $student->enrollment_no ?? $student->student_id }}</p>
                                </div>
                                @if($isAssigned)
                                    <span class="ml-2 text-xs font-semibold text-green-600 whitespace-nowrap">✓ Assigned</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
                
                @error('student_ids')
                    <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-primary w-full">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Assign to Selected Students
                </button>
            </form>
        </div>

        <!-- Right Side: Current Assignments & Multi-Teacher Config -->
        <div class="space-y-6">
            <!-- Current Assignments - Clickable Button -->
            <div class="card bg-gradient-to-br from-blue-50 to-slate-100 border-2 border-blue-200 hover:shadow-xl hover:from-blue-100 hover:to-slate-200 transition-all duration-300 cursor-pointer" onclick="openAssignmentsModal()">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-700 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Current Assignments
                        </h3>
                        <p class="text-sm text-gray-600 ml-8 mt-1">{{ $assignments->count() }} student(s) assigned</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-4 py-2 bg-white text-blue-600 rounded-lg font-semibold text-sm shadow-md hover:bg-blue-50 transition-colors">
                            View All
                        </span>
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Multi-Teacher Feedback Configuration -->
            <div class="card overflow-hidden border-2 border-blue-200 shadow-md">
                <div class="w-full p-5 bg-gradient-to-br from-blue-50 to-slate-100 border-b border-blue-200 transition-all duration-300">
                    <!-- Header Section -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                        <div class="flex items-center flex-1">
                            <svg class="w-6 h-6 mr-3 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div class="text-left">
                                <h4 class="text-base font-bold text-gray-700">Multi-Teacher Feedback Mode</h4>
                                <p class="text-xs text-gray-600">Configure subject-wise teacher evaluation</p>
                            </div>
                        </div>
                        
                        <!-- Actions Section -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-shrink-0">
                            <a href="{{ route('admin.subjects.index') }}" target="_blank" class="px-4 py-2 bg-white hover:bg-blue-50 text-blue-600 text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Manage Subjects
                            </a>
                            <div class="flex items-center gap-2">
                                <div class="relative inline-block w-12 h-6 cursor-pointer" onclick="event.stopPropagation(); toggleMultiTeacherToggle();">
                                    <input type="checkbox" name="is_multi_teacher" id="multiTeacherToggle" class="sr-only">
                                    <span id="toggleBackground" class="absolute inset-0 bg-gray-300 rounded-full transition-all duration-300 shadow-sm border-2 border-gray-400"></span>
                                    <span id="toggleCircle" class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 shadow-md"></span>
                                </div>
                                <span id="toggleLabel" class="text-xs font-bold text-white bg-gray-500 px-2 py-1 rounded uppercase">OFF</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Warning Banner -->
                    @if(isset($multiTeacherModeEnabled) && !$multiTeacherModeEnabled)
                        <div class="mt-3 px-4 py-3 bg-amber-50 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-amber-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm text-amber-700 font-medium">
                                    Global multi-teacher mode is currently disabled in system settings. Enable it from the 
                                    <a href="{{ route('admin.settings.index') }}" class="underline hover:text-amber-800 font-semibold">Settings page</a> 
                                    to use this feature.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Multi-Teacher Configuration (Collapsible) -->
                <div id="multiTeacherSection" class="hidden">
                    <div id="multiTeacherConfig" class="hidden p-4 border-t border-gray-200">
                        <!-- Subject Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Subject <span class="text-red-500">*</span></label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border rounded-lg p-3 bg-white">
                                @if(isset($subjects) && $subjects->count() > 0)
                                    @foreach($subjects as $subject)
                                        <label class="flex items-center p-2 hover:bg-indigo-50 rounded cursor-pointer transition-colors">
                                            <input 
                                                type="radio" 
                                                name="subject_id" 
                                                value="{{ $subject->id }}"
                                                class="form-radio h-4 w-4 text-indigo-600 subject-radio"
                                                onchange="toggleSubjectTeachers({{ $subject->id }})">
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $subject->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $subject->code }} • Sem {{ $subject->semester }} • {{ $subject->teachers->count() }} teacher(s)</p>
                                            </div>
                                            @if($subject->teachers->count() === 0)
                                                <span class="text-xs px-2 py-1 bg-red-100 text-red-600 rounded">No teachers</span>
                                            @endif
                                        </label>
                                    @endforeach
                                @else
                                    <div class="text-center py-6">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">No subjects available</p>
                                        <p class="text-xs text-gray-500 mt-1">Please add subjects and teachers first</p>
                                        <a href="{{ route('admin.subjects.index') }}" class="inline-block mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                            Go to Subject Management
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Teacher Selection (per subject) -->
                        <div id="teacherSelectionContainer">
                            @if(isset($subjects))
                                @foreach($subjects as $subject)
                                    <div id="teachers_subject_{{ $subject->id }}" class="hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <h5 class="text-sm font-semibold text-gray-800 mb-2">Teachers for {{ $subject->name }}</h5>
                                        <div class="space-y-2">
                                            @if($subject->teachers->count() > 0)
                                                @foreach($subject->teachers as $teacher)
                                                    <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer bg-white">
                                                        <input 
                                                            type="checkbox" 
                                                            name="teacher_ids[{{ $subject->id }}][]" 
                                                            value="{{ $teacher->id }}"
                                                            class="form-checkbox h-4 w-4 text-green-600">
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $teacher->designation }} - {{ $teacher->email }}</p>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            @else
                                                <p class="text-xs text-gray-500">No teachers assigned to this subject.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800"><strong>Note:</strong> When enabled, students will need to fill this form separately for each selected teacher.</p>
                        </div>
                        
                        <!-- Save Configuration Button -->
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="saveMultiTeacherConfig()" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Save Configuration</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teacher-Batch Assignments Reference -->
            <div class="card overflow-hidden border-2 border-purple-200 shadow-md mt-6">
                <div class="w-full p-5 bg-gradient-to-br from-purple-50 to-indigo-100 border-b border-purple-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <svg class="w-6 h-6 mr-3 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <div class="text-left">
                                <h4 class="text-base font-bold text-gray-700">Teachers with Batch Assignments</h4>
                                <p class="text-xs text-gray-600">Quick reference for teachers and their assigned batches</p>
                            </div>
                        </div>
                        <button onclick="toggleTeacherBatchSection()" class="text-purple-600 hover:text-purple-800 transition-colors">
                            <svg id="teacherDropdownIcon" class="w-6 h-6 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div id="teacherBatchSection" class="hidden">
                    <div class="p-4 border-t border-gray-200">
                        <!-- Filter Section -->
                        <div class="mb-4 p-3 bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg">
                            <h5 class="text-xs font-bold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filter Teachers
                            </h5>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Semester</label>
                                    <select id="teacherSemesterFilter" onchange="filterTeacherList()" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                        <option value="">All Semesters</option>
                                        <option value="4">Semester 4</option>
                                        <option value="6">Semester 6</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Division</label>
                                    <select id="teacherDivisionFilter" onchange="filterTeacherList()" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                                        <option value="">All Divisions</option>
                                        <option value="4-IT-1">4-IT-1</option>
                                        <option value="4-IT-2">4-IT-2</option>
                                        <option value="6-IT-1">6-IT-1</option>
                                        <option value="6-IT-2">6-IT-2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Teachers List -->
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @if(isset($teachers) && $teachers->count() > 0)
                                @foreach($teachers as $teacher)
                                    <div class="teacher-item border border-gray-200 rounded-lg p-3 bg-white hover:shadow-md transition-shadow" 
                                         data-teacher-semesters="{{ $teacher->batchesBySemester->keys()->implode(',') }}"
                                         data-teacher-divisions="{{ $teacher->batches->pluck('division.name')->unique()->implode(',') }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center mr-2">
                                                        <span class="text-sm font-bold text-purple-600">{{ substr($teacher->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">{{ $teacher->name }}</p>
                                                        <p class="text-xs text-gray-600">{{ $teacher->email }}</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Batches by Semester -->
                                                <div class="ml-10 space-y-2">
                                                    @foreach($teacher->batchesBySemester as $semester => $batches)
                                                        <div class="semester-group" data-semester="{{ $semester }}">
                                                            <p class="text-xs font-bold text-purple-700 mb-1">Semester {{ $semester }}</p>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach($batches as $batch)
                                                                    @php
                                                                        $pivotData = $batch->pivot;
                                                                        $subject = $subjects->firstWhere('id', $pivotData->subject_id);
                                                                    @endphp
                                                                    <div class="batch-item inline-flex items-center px-2 py-1 bg-purple-50 border border-purple-200 rounded text-xs" 
                                                                         data-division="{{ $batch->division->name }}"
                                                                         title="{{ $subject ? $subject->name : 'No subject' }} - {{ $pivotData->type }}{{ $pivotData->notes ? ' - ' . $pivotData->notes : '' }}">
                                                                        <span class="font-semibold text-purple-900">{{ $batch->division->name }}</span>
                                                                        <span class="mx-1 text-purple-400">•</span>
                                                                        <span class="text-purple-700">{{ $batch->batch_name }}</span>
                                                                        @if($pivotData->type === 'lab')
                                                                            <span class="ml-1 px-1 bg-green-200 text-green-800 rounded text-xs">Lab</span>
                                                                        @else
                                                                            <span class="ml-1 px-1 bg-blue-200 text-blue-800 rounded text-xs">Theory</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <div class="ml-3 flex gap-2">
                                                <button type="button"
                                                        onclick="openTeacherAssignModal({{ $teacher->id }}, '{{ addslashes($teacher->name) }}', '{{ addslashes($teacher->email) }}')"
                                                        class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Assign
                                                </button>
                                                <a href="{{ route('admin.teachers.batch-assignments', $teacher) }}" 
                                                   target="_blank"
                                                   class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded transition-colors flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="font-medium">No teachers with batch assignments</p>
                                    <p class="text-sm mt-1">Assign batches to teachers from the Teachers module</p>
                                    <a href="{{ route('admin.teachers.index') }}" class="inline-block mt-3 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition">
                                        Go to Teachers
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignments Modal -->
<div id="assignmentsModal" class="hidden fixed inset-0 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 transition-all duration-300 backdrop-blur-lg" onclick="if(event.target === this) closeAssignmentsModal()" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);">
    <div id="modalContent" class="relative p-6 border w-full max-w-4xl shadow-2xl rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4 pb-3 border-b">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-7 h-7 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                All Assigned Students ({{ $assignments->count() }})
            </h3>
            <button onclick="closeAssignmentsModal()" class="text-gray-400 hover:text-gray-600 transition-colors hover:rotate-90 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            <div class="space-y-2">
                @forelse($assignments as $assignment)
                    <div class="p-4 border rounded-lg {{ $assignment->status === 'completed' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }} hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $assignment->student->user->name }}</p>
                                    @if($assignment->is_lab)
                                        <span class="px-2 py-0.5 text-xs font-bold bg-green-200 text-green-800 rounded-full">🔬 LAB</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-bold bg-blue-200 text-blue-800 rounded-full">📚 LECTURE</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 mt-1">{{ $assignment->student->student_id }} • {{ $assignment->student->user->email }}</p>
                                @if($assignment->is_multi_teacher && $assignment->teacher)
                                    <p class="text-xs text-blue-600 mt-1 font-medium flex items-center gap-1 flex-wrap">
                                        <span class="bg-blue-100 px-2 py-0.5 rounded">Subject: {{ $assignment->subject->name ?? 'N/A' }}</span>
                                        <span class="bg-purple-100 px-2 py-0.5 rounded">Teacher: {{ $assignment->teacher->name ?? 'N/A' }}</span>
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">Assigned {{ $assignment->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                @if($assignment->status === 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Completed
                                    </span>
                                    <p class="text-xs text-gray-600 mt-1">{{ $assignment->completed_at->format('M d, Y H:i') }}</p>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="font-medium">No assignments yet</p>
                        <p class="text-sm mt-1">Select students from the left to assign this form</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Teacher Assign Modal -->
<div id="teacherAssignModal" class="hidden fixed inset-0 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 transition-all duration-300 backdrop-blur-lg" style="background: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) closeTeacherAssignModal()">
    <div class="relative p-6 border w-full max-w-5xl shadow-2xl rounded-xl bg-white transform transition-all duration-300" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4 pb-3 border-b">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Assign to Teacher's Students</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        <span id="modalTeacherName" class="font-semibold"></span> • 
                        <span id="modalTeacherEmail" class="text-gray-500"></span>
                    </p>
                </div>
            </div>
            <button onclick="closeTeacherAssignModal()" class="text-gray-400 hover:text-gray-600 transition-colors hover:rotate-90 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="teacherAssignForm" action="{{ route('forms.assign', $formName) }}" method="POST">
            @csrf
            <input type="hidden" name="teacher_assign_mode" value="1">
            <input type="hidden" name="teacher_assign_teacher_id" id="teacherAssignTeacherId">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                <!-- Teacher's Batches Info -->
                <div class="lg:col-span-1 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-bold text-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Teacher's Batches (Info Only)
                        </h4>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="select-all-teacher-batches" onclick="toggleAllTeacherBatches(this.checked)" class="form-checkbox h-3 w-3 text-purple-600 mr-1" checked>
                            <span class="text-xs font-medium text-purple-600">All</span>
                        </label>
                    </div>
                    <p class="text-xs text-purple-700 mb-2 italic">Note: All students are available for selection regardless of batch</p>
                    <div id="modalTeacherBatches" class="space-y-2 text-xs">
                        <!-- Batches will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Student Selection -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-md font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Select Students
                            <span class="ml-2 text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">All Students Available</span>
                        </h4>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="select-all-teacher-students" class="form-checkbox h-4 w-4 text-blue-600 mr-2">
                            <span class="text-sm font-medium text-blue-600">Select All</span>
                        </label>
                    </div>
                    
                    <!-- Filter Section -->
                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <h5 class="text-xs font-bold text-gray-700 mb-2">Filter Students</h5>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Semester</label>
                                <select id="modalSemesterFilter" onchange="filterModalStudents()" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg bg-white">
                                    <option value="">All</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Division</label>
                                <select id="modalDivisionFilter" onchange="filterModalStudents()" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg bg-white">
                                    <option value="">All</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Batch</label>
                                <select id="modalBatchFilter" onchange="filterModalStudents()" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg bg-white">
                                    <option value="">All</option>
                                </select>
                            </div>
                        </div>
                        <div id="modalFilterSummary" class="mt-2 text-xs text-blue-700 font-medium hidden">
                            <span id="modalFilteredCount">0</span> students found
                        </div>
                    </div>
                    
                    <!-- Student List -->
                    <div id="modalStudentList" class="space-y-1.5 max-h-96 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                        <p class="text-center text-xs text-gray-400 py-4">Loading all students...</p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Section -->
            <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Assign feedback form to selected students</p>
                    <p class="text-xs text-gray-600 mt-1">Students will give feedback to <span id="modalTeacherNameSubmit" class="font-semibold"></span> for their subjects</p>
                </div>
                <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-xl transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Assign Forms
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load existing multi-teacher configuration on page load
document.addEventListener('DOMContentLoaded', function() {
    @php
        // Check if there are any multi-teacher assignments for this form
        $multiTeacherAssignments = $assignments->where('is_multi_teacher', true);
        $hasMultiTeacher = $multiTeacherAssignments->isNotEmpty();
    @endphp
    
    @if($hasMultiTeacher)
        // Enable multi-teacher mode
        const toggle = document.getElementById('multiTeacherToggle');
        const background = document.getElementById('toggleBackground');
        const circle = document.getElementById('toggleCircle');
        const section = document.getElementById('multiTeacherSection');
        const config = document.getElementById('multiTeacherConfig');
        const label = document.getElementById('toggleLabel');
        
        toggle.checked = true;
        background.classList.remove('bg-red-500', 'border-red-600');
        background.classList.add('bg-green-500', 'border-green-600');
        circle.style.transform = 'translateX(24px)';
        section.classList.remove('hidden');
        config.classList.remove('hidden');
        label.textContent = 'ON';
        label.classList.remove('text-red-600');
        label.classList.add('text-green-600');
        
        @php
            // Get the first multi-teacher assignment to determine subject
            $firstAssignment = $multiTeacherAssignments->first();
            $subjectId = $firstAssignment->subject_id;
            $teacherIds = $multiTeacherAssignments->pluck('teacher_id')->unique()->toArray();
        @endphp
        
        // Select the subject
        const subjectRadio = document.querySelector('input[name="subject_id"][value="{{ $subjectId }}"]');
        if (subjectRadio) {
            subjectRadio.checked = true;
            toggleSubjectTeachers({{ $subjectId }});
            
            // Wait for DOM to update and teacher section to be visible, then select the teachers
            setTimeout(() => {
                const teacherDiv = document.getElementById('teachers_subject_{{ $subjectId }}');
                if (teacherDiv && !teacherDiv.classList.contains('hidden')) {
                    @foreach($teacherIds as $teacherId)
                        const checkbox_{{ $teacherId }} = document.querySelector('input[name="teacher_ids[{{ $subjectId }}][]"][value="{{ $teacherId }}"]');
                        if (checkbox_{{ $teacherId }}) {
                            checkbox_{{ $teacherId }}.checked = true;
                            console.log('Checked teacher {{ $teacherId }}');
                        }
                    @endforeach
                }
            }, 300);
        }
    @endif
});

function openAssignmentsModal() {
    const modal = document.getElementById('assignmentsModal');
    const content = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation after a brief delay
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeAssignmentsModal() {
    const modal = document.getElementById('assignmentsModal');
    const content = document.getElementById('modalContent');
    
    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    // Hide after animation completes
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function toggleMultiTeacherSection() {
    const section = document.getElementById('multiTeacherSection');
    const icon = document.getElementById('dropdownIcon');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        section.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

function toggleTeacherBatchSection() {
    const section = document.getElementById('teacherBatchSection');
    const icon = document.getElementById('teacherDropdownIcon');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        section.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

function filterTeacherList() {
    const semesterFilter = document.getElementById('teacherSemesterFilter').value;
    const divisionFilter = document.getElementById('teacherDivisionFilter').value;
    
    const teacherItems = document.querySelectorAll('.teacher-item');
    
    teacherItems.forEach(item => {
        const teacherSemesters = item.dataset.teacherSemesters.split(',');
        const teacherDivisions = item.dataset.teacherDivisions.split(',');
        
        let showTeacher = true;
        
        // Filter by semester
        if (semesterFilter && !teacherSemesters.includes(semesterFilter)) {
            showTeacher = false;
        }
        
        // Filter by division
        if (divisionFilter && !teacherDivisions.includes(divisionFilter)) {
            showTeacher = false;
        }
        
        // Show/hide the teacher item
        if (showTeacher) {
            item.style.display = '';
            
            // Within the shown teacher, filter semester groups and batch items
            if (semesterFilter || divisionFilter) {
                const semesterGroups = item.querySelectorAll('.semester-group');
                semesterGroups.forEach(group => {
                    const groupSemester = group.dataset.semester;
                    
                    if (semesterFilter && groupSemester !== semesterFilter) {
                        group.style.display = 'none';
                    } else {
                        group.style.display = '';
                        
                        // Filter batch items within the group
                        if (divisionFilter) {
                            const batchItems = group.querySelectorAll('.batch-item');
                            batchItems.forEach(batch => {
                                const batchDivision = batch.dataset.division;
                                if (batchDivision === divisionFilter) {
                                    batch.style.display = '';
                                } else {
                                    batch.style.display = 'none';
                                }
                            });
                        } else {
                            // Show all batch items if no division filter
                            const batchItems = group.querySelectorAll('.batch-item');
                            batchItems.forEach(batch => {
                                batch.style.display = '';
                            });
                        }
                    }
                });
            } else {
                // No filters, show everything
                const semesterGroups = item.querySelectorAll('.semester-group');
                semesterGroups.forEach(group => {
                    group.style.display = '';
                    const batchItems = group.querySelectorAll('.batch-item');
                    batchItems.forEach(batch => {
                        batch.style.display = '';
                    });
                });
            }
        } else {
            item.style.display = 'none';
        }
    });
}

function toggleMultiTeacherToggle() {
    const toggle = document.getElementById('multiTeacherToggle');
    const background = document.getElementById('toggleBackground');
    const circle = document.getElementById('toggleCircle');
    const section = document.getElementById('multiTeacherSection');
    const config = document.getElementById('multiTeacherConfig');
    const label = document.getElementById('toggleLabel');
    
    // Toggle the checkbox state
    toggle.checked = !toggle.checked;
    
    if (toggle.checked) {
        // Turn ON - Green and show dropdown
        background.classList.remove('bg-red-500', 'border-red-600');
        background.classList.add('bg-green-500', 'border-green-600');
        circle.style.transform = 'translateX(24px)';
        section.classList.remove('hidden');
        config.classList.remove('hidden');
        if (label) {
            label.textContent = 'ON';
            label.classList.remove('text-red-600');
            label.classList.add('text-green-600');
        }
    } else {
        // Turn OFF - Red and hide dropdown
        background.classList.remove('bg-green-500', 'border-green-600');
        background.classList.add('bg-red-500', 'border-red-600');
        circle.style.transform = 'translateX(0px)';
        section.classList.add('hidden');
        config.classList.add('hidden');
        if (label) {
            label.textContent = 'OFF';
            label.classList.remove('text-green-600');
            label.classList.add('text-red-600');
        }
        // Clear all selections
        document.querySelectorAll('.subject-radio').forEach(rb => rb.checked = false);
        document.querySelectorAll('[id^="teachers_subject_"]').forEach(div => {
            div.classList.add('hidden');
            div.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        });
    }
}

function toggleMultiTeacher() {
    const toggle = document.getElementById('multiTeacherToggle');
    const config = document.getElementById('multiTeacherConfig');
    const label = document.getElementById('toggleLabel');
    
    if (toggle.checked) {
        config.classList.remove('hidden');
        if (label) {
            label.textContent = 'ON';
            label.classList.remove('text-red-600');
            label.classList.add('text-green-600');
        }
    } else {
        config.classList.add('hidden');
        if (label) {
            label.textContent = 'OFF';
            label.classList.remove('text-green-600');
            label.classList.add('text-red-600');
        }
        // Clear all selections
        document.querySelectorAll('.subject-radio').forEach(rb => rb.checked = false);
        document.querySelectorAll('[id^="teachers_subject_"]').forEach(div => {
            div.classList.add('hidden');
            div.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        });
    }
}

function toggleSubjectTeachers(subjectId) {
    // Hide all teacher divs first
    document.querySelectorAll('[id^="teachers_subject_"]').forEach(div => {
        if (div.id !== 'teachers_subject_' + subjectId) {
            div.classList.add('hidden');
            // Only uncheck teachers in OTHER subjects (not the selected one)
            div.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    });
    
    // Show only the selected subject's teachers (without unchecking them)
    const teacherDiv = document.getElementById('teachers_subject_' + subjectId);
    if (teacherDiv) {
        teacherDiv.classList.remove('hidden');
    }
}

function saveMultiTeacherConfig() {
    const formTitle = '{{ $formTitle }}';
    const formFilename = '{{ $formName }}';
    const subjectRadio = document.querySelector('input[name="subject_id"]:checked');
    
    if (!subjectRadio) {
        alert('Please select a subject first.');
        return;
    }
    
    const subjectId = subjectRadio.value;
    const teacherCheckboxes = document.querySelectorAll(`input[name="teacher_ids[${subjectId}][]"]:checked`);
    
    if (teacherCheckboxes.length === 0) {
        alert('Please select at least one teacher for the selected subject.');
        return;
    }
    
    // Get selected students from the main form
    const studentCheckboxes = document.querySelectorAll('input[name="student_ids[]"]:checked');
    if (studentCheckboxes.length === 0) {
        alert('Please select at least one student from the main form first.');
        return;
    }
    
    const teacherIds = Array.from(teacherCheckboxes).map(cb => cb.value);
    const studentIds = Array.from(studentCheckboxes).map(cb => cb.value);
    
    // Get date fields if they exist
    const startDate = document.querySelector('input[name="start_date"]')?.value;
    const endDate = document.querySelector('input[name="end_date"]')?.value;
    const gracePeriod = document.querySelector('input[name="grace_period_hours"]')?.value;
    
    // Get the button for visual feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    
    // Disable button during save
    button.disabled = true;
    button.innerHTML = '<span>Saving...</span>';
    
    // Send AJAX request to save configuration
    fetch('{{ route("forms.saveMultiTeacherConfig") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            form_name: formFilename,
            subject_id: subjectId,
            teacher_ids: teacherIds,
            student_ids: studentIds,
            start_date: startDate,
            end_date: endDate,
            grace_period_hours: gracePeriod
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            button.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>${data.message || 'Saved!'}</span>
            `;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-green-700');
            
            setTimeout(() => {
                // Reload page to show updated assignments
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to save configuration');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving configuration: ' + error.message);
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}

// Student Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const semesterFilter = document.getElementById('semesterFilter');
    const divisionFilter = document.getElementById('divisionFilter');
    const batchFilterContainer = document.getElementById('batchFilterContainer');
    const filterSummary = document.getElementById('filterSummary');
    const filteredCount = document.getElementById('filteredCount');
    const selectAllCheckbox = document.getElementById('select-all-students');
    const studentItems = document.querySelectorAll('.student-item');
    
    let selectedBatches = new Set();
    
    // Extract unique divisions and batches from students
    const divisionsData = {};
    studentItems.forEach(item => {
        const semester = item.dataset.semester;
        const division = item.dataset.division;
        const batch = item.dataset.batch;
        
        if (!divisionsData[semester]) {
            divisionsData[semester] = {};
        }
        if (!divisionsData[semester][division]) {
            divisionsData[semester][division] = new Set();
        }
        if (batch) {
            divisionsData[semester][division].add(batch);
        }
    });
    
    // Semester change handler
    semesterFilter.addEventListener('change', function() {
        const selectedSemester = this.value;
        
        // Update divisions dropdown
        divisionFilter.innerHTML = '<option value="">All Divisions</option>';
        
        if (selectedSemester && divisionsData[selectedSemester]) {
            Object.keys(divisionsData[selectedSemester]).sort().forEach(division => {
                const option = document.createElement('option');
                option.value = division;
                option.textContent = division;
                divisionFilter.appendChild(option);
            });
        }
        
        // Reset batches
        batchFilterContainer.innerHTML = '<p class="text-xs text-gray-400 italic">Select division to see batches</p>';
        selectedBatches.clear();
        
        filterStudents();
    });
    
    // Division change handler
    divisionFilter.addEventListener('change', function() {
        const selectedSemester = semesterFilter.value;
        const selectedDivision = this.value;
        
        // Update batches
        batchFilterContainer.innerHTML = '';
        selectedBatches.clear();
        
        if (selectedSemester && selectedDivision && divisionsData[selectedSemester]?.[selectedDivision]) {
            const batches = Array.from(divisionsData[selectedSemester][selectedDivision]).sort();
            
            if (batches.length > 0) {
                batches.forEach(batch => {
                    const label = document.createElement('label');
                    label.className = 'flex items-center gap-1.5 px-2 py-1 hover:bg-blue-50 rounded cursor-pointer transition-colors';
                    label.innerHTML = `
                        <input type="checkbox" value="${batch}" class="batch-checkbox form-checkbox h-3 w-3 text-blue-600">
                        <span class="text-xs font-medium text-gray-700">${batch}</span>
                    `;
                    batchFilterContainer.appendChild(label);
                });
                
                // Add batch checkbox listeners
                document.querySelectorAll('.batch-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            selectedBatches.add(this.value);
                        } else {
                            selectedBatches.delete(this.value);
                        }
                        filterStudents();
                    });
                });
            } else {
                batchFilterContainer.innerHTML = '<p class="text-xs text-gray-400 italic">No batches available</p>';
            }
        } else {
            batchFilterContainer.innerHTML = '<p class="text-xs text-gray-400 italic">Select division to see batches</p>';
        }
        
        filterStudents();
    });
    
    // Filter students based on selected criteria
    function filterStudents() {
        const selectedSemester = semesterFilter.value;
        const selectedDivision = divisionFilter.value;
        let visibleCount = 0;
        
        studentItems.forEach(item => {
            const itemSemester = item.dataset.semester;
            const itemDivision = item.dataset.division;
            const itemBatch = item.dataset.batch;
            
            let shouldShow = true;
            
            // Filter by semester
            if (selectedSemester && itemSemester !== selectedSemester) {
                shouldShow = false;
            }
            
            // Filter by division
            if (selectedDivision && itemDivision !== selectedDivision) {
                shouldShow = false;
            }
            
            // Filter by batches (if any selected)
            if (selectedBatches.size > 0) {
                if (!itemBatch || !selectedBatches.has(itemBatch)) {
                    shouldShow = false;
                }
            }
            
            // Show/hide item
            if (shouldShow) {
                item.style.display = '';
                const checkbox = item.querySelector('.student-checkbox');
                if (checkbox && !checkbox.disabled) {
                    visibleCount++;
                }
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update filter summary
        filteredCount.textContent = visibleCount;
        filterSummary.classList.toggle('hidden', visibleCount === 0 && !selectedSemester && !selectedDivision);
        
        // Update select all checkbox
        updateSelectAllCheckbox();
    }
    
    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const visibleCheckboxes = getVisibleCheckboxes();
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Get visible non-disabled checkboxes
    function getVisibleCheckboxes() {
        return Array.from(document.querySelectorAll('.student-checkbox:not([disabled])'))
            .filter(cb => cb.closest('.student-item').style.display !== 'none');
    }
    
    // Update Select All checkbox state
    function updateSelectAllCheckbox() {
        if (!selectAllCheckbox) return;
        
        const visibleCheckboxes = getVisibleCheckboxes();
        if (visibleCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            return;
        }
        
        const allChecked = visibleCheckboxes.every(cb => cb.checked);
        const anyChecked = visibleCheckboxes.some(cb => cb.checked);
        
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = anyChecked && !allChecked;
    }
    
    // Update select all on individual checkbox change
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAllCheckbox);
    });
    
    // Initial filter
    filterStudents();
});

// ===== BATCH-WISE ASSIGNMENT FUNCTIONS =====

function toggleBatchWiseSection() {
    const section = document.getElementById('batchWiseSection');
    const icon = document.getElementById('batchWiseDropdownIcon');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        section.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Toggle Batch-wise Student List
function toggleBatchWiseStudentList() {
    const list = document.getElementById('batchWiseStudentList');
    const icon = document.getElementById('batchWiseStudentListIcon');
    const text = document.getElementById('batchWiseStudentListText');
    
    if (list.classList.contains('hidden')) {
        list.classList.remove('hidden');
        icon.classList.add('rotate-180');
        text.textContent = 'Hide List';
    } else {
        list.classList.add('hidden');
        icon.classList.remove('rotate-180');
        text.textContent = 'Show List';
    }
}

// Toggle Manual Assignment Student List
function toggleManualStudentList() {
    const list = document.getElementById('studentListContainer');
    const icon = document.getElementById('manualStudentListIcon');
    const text = document.getElementById('manualStudentListText');
    
    if (list.classList.contains('hidden')) {
        list.classList.remove('hidden');
        icon.classList.add('rotate-180');
        text.textContent = 'Hide List';
    } else {
        list.classList.add('hidden');
        icon.classList.remove('rotate-180');
        text.textContent = 'Show List';
    }
}

// Teacher batch data from PHP
const teacherBatchData = {!! json_encode($teachers->map(function($teacher) use ($subjects) {
    return [
        'id' => $teacher->id,
        'name' => $teacher->name,
        'email' => $teacher->email,
        'batches' => $teacher->batches->map(function($batch) use ($subjects) {
            $subject = $subjects->firstWhere('id', $batch->pivot->subject_id);
            return [
                'id' => $batch->id,
                'name' => $batch->batch_name,
                'division' => $batch->division->name,
                'semester' => $batch->division->semester,
                'subject_id' => $batch->pivot->subject_id,
                'subject_name' => $subject ? $subject->name : 'N/A',
                'type' => $batch->pivot->type,
                'notes' => $batch->pivot->notes,
                'pivot_id' => $batch->pivot->id
            ];
        })
    ];
})->values()) !!};

// Update division filter for batch-wise section
function updateBatchWiseDivisionFilter() {
    const semester = document.getElementById('batchWiseSemesterFilter').value;
    const divisionSelect = document.getElementById('batchWiseDivisionFilter');
    
    // Clear current options except "All Divisions"
    divisionSelect.innerHTML = '<option value="">All Divisions</option>';
    
    // Get unique divisions from students
    const divisions = new Set();
    document.querySelectorAll('.batchwise-student-item').forEach(item => {
        const itemSemester = item.dataset.semester;
        const division = item.dataset.division;
        
        if (!semester || semester === itemSemester) {
            divisions.add(division);
        }
    });
    
    // Add division options
    Array.from(divisions).sort().forEach(division => {
        const option = document.createElement('option');
        option.value = division;
        option.textContent = division;
        divisionSelect.appendChild(option);
    });
}

// Update batch filter for batch-wise section
function updateBatchWiseBatchFilter() {
    const semester = document.getElementById('batchWiseSemesterFilter').value;
    const division = document.getElementById('batchWiseDivisionFilter').value;
    const batchSelect = document.getElementById('batchWiseBatchFilter');
    
    // Clear current options except "All Batches"
    batchSelect.innerHTML = '<option value="">All Batches</option>';
    
    // Get unique batches from students
    const batches = new Set();
    document.querySelectorAll('.batchwise-student-item').forEach(item => {
        const itemSemester = item.dataset.semester;
        const itemDivision = item.dataset.division;
        const batch = item.dataset.batch;
        
        if ((!semester || semester === itemSemester) && 
            (!division || division === itemDivision) && 
            batch) {
            batches.add(batch);
        }
    });
    
    // Add batch options
    Array.from(batches).sort().forEach(batch => {
        const option = document.createElement('option');
        option.value = batch;
        option.textContent = batch;
        batchSelect.appendChild(option);
    });
}

// Filter students in batch-wise section
function filterBatchWiseStudents() {
    const semester = document.getElementById('batchWiseSemesterFilter').value;
    const division = document.getElementById('batchWiseDivisionFilter').value;
    const batch = document.getElementById('batchWiseBatchFilter').value;
    
    let visibleCount = 0;
    
    document.querySelectorAll('.batchwise-student-item').forEach(item => {
        const itemSemester = item.dataset.semester;
        const itemDivision = item.dataset.division;
        const itemBatch = item.dataset.batch;
        const checkbox = item.querySelector('.batchwise-student-checkbox');
        
        let show = true;
        
        if (semester && semester !== itemSemester) show = false;
        if (division && division !== itemDivision) show = false;
        if (batch && batch !== itemBatch) show = false;
        
        if (show && !checkbox.disabled) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Update count
    const summary = document.getElementById('batchWiseFilterSummary');
    const countSpan = document.getElementById('batchWiseFilteredCount');
    countSpan.textContent = visibleCount;
    
    if (visibleCount > 0) {
        summary.classList.remove('hidden');
    } else {
        summary.classList.add('hidden');
    }
}

// Update teacher list based on selected students
function updateTeacherListForStudents() {
    const selectedStudents = Array.from(document.querySelectorAll('.batchwise-student-checkbox:checked:not([disabled])'));
    const teacherListContainer = document.getElementById('batchWiseTeacherList');
    const teacherInfo = document.getElementById('batchWiseTeacherInfo');
    
    if (selectedStudents.length === 0) {
        teacherListContainer.innerHTML = '<p class="text-center text-xs text-gray-400 py-8">No students selected yet</p>';
        teacherInfo.innerHTML = '<p>Select students first. Teachers who teach the selected students\' batches will appear here.</p>';
        return;
    }
    
    // Get selected students' batch IDs
    const selectedBatchIds = new Set();
    selectedStudents.forEach(checkbox => {
        const item = checkbox.closest('.batchwise-student-item');
        const batchId = item.dataset.batchId;
        if (batchId) {
            selectedBatchIds.add(parseInt(batchId));
        }
    });
    
    // Find teachers who teach these batches
    const relevantTeachers = [];
    teacherBatchData.forEach(teacher => {
        const teacherBatches = teacher.batches.filter(b => selectedBatchIds.has(b.id));
        if (teacherBatches.length > 0) {
            relevantTeachers.push({
                ...teacher,
                batches: teacherBatches
            });
        }
    });
    
    if (relevantTeachers.length === 0) {
        teacherListContainer.innerHTML = '<p class="text-center text-xs text-gray-400 py-8">No teachers found for selected students\' batches</p>';
        teacherInfo.innerHTML = '<p class="text-amber-600"><strong>Note:</strong> No teachers are assigned to teach the selected students\' batches.</p>';
        return;
    }
    
    // Update info
    teacherInfo.innerHTML = `<p class="text-green-700"><strong>Found ${relevantTeachers.length} teacher(s)</strong> who teach the selected students. Select which teachers students should give feedback to.</p>`;
    
    // Build teacher list
    teacherListContainer.innerHTML = '';
    relevantTeachers.forEach(teacher => {
        const teacherDiv = document.createElement('div');
        teacherDiv.className = 'batchwise-teacher-item border border-green-200 rounded-lg p-3 bg-white hover:shadow-md transition-shadow';
        teacherDiv.dataset.teacherId = teacher.id;
        
        // Group batches by subject
        const batchesBySubject = {};
        teacher.batches.forEach(batch => {
            const key = `${batch.subject_id}_${batch.subject_name}`;
            if (!batchesBySubject[key]) {
                batchesBySubject[key] = {
                    subject_id: batch.subject_id,
                    subject_name: batch.subject_name,
                    batches: []
                };
            }
            batchesBySubject[key].batches.push(batch);
        });
        
        teacherDiv.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <label class="flex items-center flex-1 cursor-pointer">
                    <input type="checkbox" name="batch_wise_teacher_ids[]" value="${teacher.id}" 
                           class="form-checkbox h-4 w-4 text-green-600 batchwise-teacher-checkbox">
                    <div class="ml-2">
                        <p class="text-sm font-semibold text-gray-900">${teacher.name}</p>
                        <p class="text-xs text-gray-600">${teacher.email}</p>
                    </div>
                </label>
            </div>
            <div class="ml-6 space-y-1">
                ${Object.values(batchesBySubject).map(subjectGroup => `
                    <div class="text-xs">
                        <p class="font-semibold text-purple-700">${subjectGroup.subject_name}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            ${subjectGroup.batches.map(batch => `
                                <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 border border-blue-200 rounded text-xs">
                                    <span class="text-blue-900">${batch.division}</span>
                                    <span class="mx-1 text-blue-400">•</span>
                                    <span class="text-blue-700">${batch.name}</span>
                                    ${batch.type === 'lab' ? '<span class="ml-1 px-1 bg-green-200 text-green-800 rounded text-xs">Lab</span>' : '<span class="ml-1 px-1 bg-indigo-200 text-indigo-800 rounded text-xs">Theory</span>'}
                                </span>
                            `).join('')}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        teacherListContainer.appendChild(teacherDiv);
    });
}

// Select all students in batch-wise section
document.getElementById('select-all-batchwise-students')?.addEventListener('change', function() {
    const visibleCheckboxes = Array.from(document.querySelectorAll('.batchwise-student-checkbox:not([disabled])'))
        .filter(cb => cb.closest('.batchwise-student-item').style.display !== 'none');
    
    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    
    updateTeacherListForStudents();
});

// Select all teachers in batch-wise section
document.getElementById('select-all-batchwise-teachers')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.batchwise-teacher-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Initialize batch-wise section
document.addEventListener('DOMContentLoaded', function() {
    // Initially hide all batch-wise students
    document.querySelectorAll('.batchwise-student-item').forEach(item => {
        item.style.display = 'none';
    });
});

// ===== TEACHER ASSIGN MODAL FUNCTIONS =====

// Get all students data
const allStudents = {!! json_encode($students->map(function($student) use ($assignments) {
    return [
        'id' => $student->id,
        'name' => $student->user->name,
        'enrollment_no' => $student->enrollment_no ?? $student->student_id,
        'batch_id' => $student->batch_id,
        'batch_name' => $student->batchGroup ? $student->batchGroup->batch_name : '',
        'division' => $student->division ? $student->division->name : 'Unknown',
        'semester' => $student->semester,
        'assignment_count' => isset($student->assignment_count) ? $student->assignment_count : 0
    ];
})->values()) !!};

let currentTeacherData = null;

function openTeacherAssignModal(teacherId, teacherName, teacherEmail) {
    // Find teacher data
    const teacher = teacherBatchData.find(t => t.id === teacherId);
    if (!teacher) {
        alert('Teacher data not found');
        return;
    }
    
    currentTeacherData = teacher;
    
    // Update modal header
    document.getElementById('modalTeacherName').textContent = teacherName;
    document.getElementById('modalTeacherEmail').textContent = teacherEmail;
    document.getElementById('modalTeacherNameSubmit').textContent = teacherName;
    document.getElementById('teacherAssignTeacherId').value = teacherId;
    
    // Display teacher's batches
    const batchesContainer = document.getElementById('modalTeacherBatches');
    const batchesBySemester = {};
    teacher.batches.forEach(batch => {
        if (!batchesBySemester[batch.semester]) {
            batchesBySemester[batch.semester] = [];
        }
        batchesBySemester[batch.semester].push(batch);
    });
    
    let batchesHTML = '';
    Object.keys(batchesBySemester).sort().forEach(semester => {
        batchesHTML += `<div class="mb-2">
            <p class="font-bold text-purple-700 mb-1">Semester ${semester}</p>
            <div class="space-y-1">`;
        batchesBySemester[semester].forEach(batch => {
            batchesHTML += `
                <label class="flex items-start gap-2 p-2 bg-white rounded border border-purple-200 cursor-pointer hover:bg-purple-50 transition-colors">
                    <input type="checkbox" class="teacher-batch-checkbox form-checkbox h-3.5 w-3.5 text-purple-600 mt-0.5" 
                           data-batch-id="${batch.id}" 
                           onchange="filterStudentsByBatches()" 
                           checked>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-purple-900">${batch.division}</span>
                            <span class="text-purple-400">•</span>
                            <span class="text-purple-700">${batch.name}</span>
                            <span class="ml-auto px-1.5 py-0.5 ${batch.type === 'lab' ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800'} rounded text-xs">${batch.type === 'lab' ? 'Lab' : 'Theory'}</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-0.5">${batch.subject_name}</p>
                    </div>
                </label>
            `;
        });
        batchesHTML += `</div></div>`;
    });
    batchesContainer.innerHTML = batchesHTML;
    
    // Show ALL students (not just from teacher's batches)
    // This allows flexible assignment since students can be in multiple batches/labs
    const teacherBatchIds = teacher.batches.map(b => b.id);
    
    // Populate filter dropdowns with ALL students
    populateModalFilters(allStudents);
    
    // Display ALL students by default
    displayModalStudents(allStudents);
    
    // Show modal
    const modal = document.getElementById('teacherAssignModal');
    modal.classList.remove('hidden');
}

function closeTeacherAssignModal() {
    const modal = document.getElementById('teacherAssignModal');
    modal.classList.add('hidden');
    currentTeacherData = null;
    
    // Reset form
    document.getElementById('teacherAssignForm').reset();
    document.getElementById('modalStudentList').innerHTML = '';
}

function populateModalFilters(students) {
    // Get unique values
    const semesters = [...new Set(students.map(s => s.semester))].sort();
    const divisions = [...new Set(students.map(s => s.division))].sort();
    const batches = [...new Set(students.map(s => s.batch_name).filter(b => b))].sort();
    
    // Populate semester filter
    const semesterFilter = document.getElementById('modalSemesterFilter');
    semesterFilter.innerHTML = '<option value="">All</option>';
    semesters.forEach(sem => {
        semesterFilter.innerHTML += `<option value="${sem}">Semester ${sem}</option>`;
    });
    
    // Populate division filter
    const divisionFilter = document.getElementById('modalDivisionFilter');
    divisionFilter.innerHTML = '<option value="">All</option>';
    divisions.forEach(div => {
        divisionFilter.innerHTML += `<option value="${div}">${div}</option>`;
    });
    
    // Populate batch filter
    const batchFilter = document.getElementById('modalBatchFilter');
    batchFilter.innerHTML = '<option value="">All</option>';
    batches.forEach(batch => {
        batchFilter.innerHTML += `<option value="${batch}">${batch}</option>`;
    });
}

function displayModalStudents(students) {
    const container = document.getElementById('modalStudentList');
    
    if (students.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-400 py-8">No students found</p>';
        return;
    }
    
    container.innerHTML = '';
    students.forEach(student => {
        const assignmentCount = student.assignment_count || 0;
        const hasAssignments = assignmentCount > 0;
        const studentDiv = document.createElement('label');
        studentDiv.className = `modal-student-item flex items-center p-2 hover:bg-blue-50 rounded transition-colors ${hasAssignments ? 'bg-yellow-50 border border-yellow-200' : 'bg-white'}`;
        studentDiv.dataset.semester = student.semester;
        studentDiv.dataset.division = student.division;
        studentDiv.dataset.batch = student.batch_name;
        
        const assignmentBadge = hasAssignments ? 
            `<span class="text-xs px-1.5 py-0.5 bg-orange-100 text-orange-700 rounded font-semibold" title="Already assigned to ${assignmentCount} teacher(s)">${assignmentCount} teacher${assignmentCount > 1 ? 's' : ''}</span>` : '';
        
        studentDiv.innerHTML = `
            <input type="checkbox" name="teacher_assign_student_ids[]" value="${student.id}"
                   class="form-checkbox h-3 w-3 text-blue-600 modal-student-checkbox">
            <div class="ml-2 flex-1 min-w-0">
                <div class="flex items-center gap-1 flex-wrap">
                    <p class="text-xs font-medium text-gray-900 truncate">${student.name}</p>
                    <span class="text-xs px-1 py-0.5 bg-blue-100 text-blue-700 rounded">${student.division}</span>
                    ${student.batch_name ? `<span class="text-xs px-1 py-0.5 bg-purple-100 text-purple-700 rounded">${student.batch_name}</span>` : ''}
                    ${assignmentBadge}
                </div>
                <p class="text-xs text-gray-500">${student.enrollment_no}</p>
            </div>
        `;
        
        container.appendChild(studentDiv);
    });
    
    updateModalFilterSummary();
}

function filterModalStudents() {
    const semester = document.getElementById('modalSemesterFilter').value;
    const division = document.getElementById('modalDivisionFilter').value;
    const batch = document.getElementById('modalBatchFilter').value;
    
    const studentItems = document.querySelectorAll('.modal-student-item');
    let visibleCount = 0;
    
    studentItems.forEach(item => {
        const itemSemester = item.dataset.semester;
        const itemDivision = item.dataset.division;
        const itemBatch = item.dataset.batch;
        const checkbox = item.querySelector('.modal-student-checkbox');
        
        let show = true;
        
        if (semester && semester !== itemSemester) show = false;
        if (division && division !== itemDivision) show = false;
        if (batch && batch !== itemBatch) show = false;
        
        if (show && !checkbox.disabled) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    updateModalFilterSummary();
}

function updateModalFilterSummary() {
    const visibleItems = Array.from(document.querySelectorAll('.modal-student-item'))
        .filter(item => item.style.display !== 'none');
    
    const summary = document.getElementById('modalFilterSummary');
    const countSpan = document.getElementById('modalFilteredCount');
    countSpan.textContent = visibleItems.length;
    
    if (visibleItems.length > 0) {
        summary.classList.remove('hidden');
    } else {
        summary.classList.add('hidden');
    }
}

// Toggle all teacher batches
function toggleAllTeacherBatches(checked) {
    document.querySelectorAll('.teacher-batch-checkbox').forEach(cb => {
        cb.checked = checked;
    });
    filterStudentsByBatches();
}

// Filter students based on selected batches (disabled - now showing all students)
function filterStudentsByBatches() {
    // Note: This function now shows ALL students regardless of batch selection
    // This allows flexible assignment since students can belong to multiple batches/labs
    
    // Always show all students
    displayModalStudents(allStudents);
    
    // Update "Select All Batches" checkbox state
    const allBatchCheckboxes = document.querySelectorAll('.teacher-batch-checkbox');
    const checkedBatchCheckboxes = document.querySelectorAll('.teacher-batch-checkbox:checked');
    const selectAllBatches = document.getElementById('select-all-teacher-batches');
    if (selectAllBatches) {
        selectAllBatches.checked = allBatchCheckboxes.length === checkedBatchCheckboxes.length;
        selectAllBatches.indeterminate = checkedBatchCheckboxes.length > 0 && checkedBatchCheckboxes.length < allBatchCheckboxes.length;
    }
}

// Select all students in modal
document.getElementById('select-all-teacher-students')?.addEventListener('change', function() {
    const visibleCheckboxes = Array.from(document.querySelectorAll('.modal-student-checkbox'))
        .filter(cb => cb.closest('.modal-student-item').style.display !== 'none');
    
    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Form validation before submission
document.getElementById('teacherAssignForm')?.addEventListener('submit', function(e) {
    const selectedStudents = Array.from(document.querySelectorAll('.modal-student-checkbox:checked'));
    
    if (selectedStudents.length === 0) {
        e.preventDefault();
        alert('Please select at least one student to assign the feedback form.');
        return false;
    }
    
    // Confirm assignment
    const teacherName = document.getElementById('modalTeacherName').textContent;
    const confirmation = confirm(`Assign feedback form to ${selectedStudents.length} student(s) for ${teacherName}?\n\nNote: Students already assigned to this teacher will be skipped automatically.`);
    
    if (!confirmation) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
