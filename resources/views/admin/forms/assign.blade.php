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

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Details Card -->
    <div class="card mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $formTitle }}</h2>
                <p class="text-gray-600">{{ $formName }}</p>
            </div>
            <a href="{{ route('forms.download', $formName) }}" class="btn-secondary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Form
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assign to Students -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Assign to Students</h3>
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
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Students</label>
                    <div class="space-y-2 max-h-96 overflow-y-auto border rounded-lg p-4">
                        @foreach($students as $student)
                            @php
                                $isAssigned = $assignments->where('student_id', $student->id)->isNotEmpty();
                            @endphp
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded {{ $isAssigned ? 'bg-green-50' : '' }}">
                                <input 
                                    type="checkbox" 
                                    name="student_ids[]" 
                                    value="{{ $student->id }}"
                                    {{ $isAssigned ? 'disabled checked' : '' }}
                                    class="form-checkbox h-5 w-5 text-blue-600">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $student->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->user->email }} - {{ $student->student_id }}</p>
                                </div>
                                @if($isAssigned)
                                    <span class="ml-auto text-xs font-semibold text-green-600">Already Assigned</span>
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
            <div class="card bg-gradient-to-br from-gray-400 to-gray-700 border-2 border-gray-500 hover:shadow-xl hover:from-gray-300 hover:to-gray-600 transition-all duration-300 cursor-pointer" onclick="openAssignmentsModal()">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-black flex items-center">
                            <svg class="w-6 h-6 mr-2 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Current Assignments
                        </h3>
                        <p class="text-sm text-black ml-8 mt-1">{{ $assignments->count() }} student(s) assigned</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-4 py-2 bg-white text-gray-900 rounded-lg font-semibold text-sm shadow-lg hover:bg-gray-100 transition-colors">
                            View All
                        </span>
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Multi-Teacher Feedback Configuration -->
            <div class="card overflow-hidden">
                <div class="w-full flex items-center justify-between p-5 bg-gradient-to-br from-gray-400 to-gray-700 transition-all duration-300">
                    <div class="flex items-center flex-1">
                        <svg class="w-6 h-6 mr-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <div class="text-left">
                            <h4 class="text-base font-bold text-black">Multi-Teacher Feedback Mode</h4>
                            <p class="text-xs text-black">Configure subject-wise teacher evaluation</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="relative inline-block w-12 h-6 cursor-pointer" onclick="event.stopPropagation(); toggleMultiTeacherToggle();">
                            <input type="checkbox" name="is_multi_teacher" id="multiTeacherToggle" class="sr-only">
                            <span id="toggleBackground" class="absolute inset-0 bg-red-500 rounded-full transition-all duration-300 shadow-md border-2 border-red-600"></span>
                            <span id="toggleCircle" class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 shadow-lg"></span>
                        </div>
                        <span id="toggleLabel" class="text-xs font-bold text-red-600 uppercase">OFF</span>
                    </div>
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
                                        <label class="flex items-center p-2 hover:bg-blue-50 rounded cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="subject_id" 
                                                value="{{ $subject->id }}"
                                                class="form-radio h-4 w-4 text-blue-600 subject-radio"
                                                onchange="toggleSubjectTeachers({{ $subject->id }})">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $subject->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $subject->code }} - {{ $subject->teachers->count() }} teachers</p>
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 p-2">No subjects available. Please add subjects and teachers first.</p>
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
        </div>
    </div>
</div>

<!-- Assignments Modal -->
<div id="assignmentsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 transition-opacity duration-300" onclick="if(event.target === this) closeAssignmentsModal()">
    <div id="modalContent" class="relative p-6 border w-full max-w-4xl shadow-2xl rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4 pb-3 border-b">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-7 h-7 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                All Assigned Students ({{ $assignments->count() }})
            </h3>
            <button onclick="closeAssignmentsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
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
                                <p class="text-sm font-semibold text-gray-900">{{ $assignment->student->user->name }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $assignment->student->student_id }} • {{ $assignment->student->user->email }}</p>
                                @if($assignment->is_multi_teacher && $assignment->teacher)
                                    <p class="text-xs text-blue-600 mt-1 font-medium">
                                        <span class="bg-blue-100 px-2 py-0.5 rounded">Subject: {{ $assignment->subject->name ?? 'N/A' }}</span>
                                        <span class="bg-purple-100 px-2 py-0.5 rounded ml-1">Teacher: {{ $assignment->teacher->name ?? 'N/A' }}</span>
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
</script>
@endsection
