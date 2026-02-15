@extends('layouts.app')

@section('title', 'Manage Subjects')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Subject Management
                    </h1>
                    <p class="text-gray-600 mt-1">Manage subjects, assign teachers, and organize by semester</p>
                </div>
                <div class="flex space-x-3 items-center">
                    <div class="flex items-center gap-2">
                        <label for="semesterDropdown" class="text-sm font-medium text-gray-700">Semester:</label>
                        <select id="semesterDropdown" onchange="handleSemesterChange(this.value)" class="form-select px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-medium shadow-sm hover:border-indigo-400 transition-colors">
                            <option value="">All Semesters</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <a href="{{ route('admin.teachers.index') }}" class="btn-primary flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Manage Teachers
                    </a>
                    <button onclick="openAddSubjectModal()" class="btn-success flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Subject
                    </button>
                </div>
            </div>
        </div>

        <!-- Current Semester Display -->
        <div id="semesterDisplay" class="mb-6" style="display: none;">
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-indigo-800 font-semibold">Currently viewing: <span id="currentSemesterText">All Semesters</span></span>
                    </div>
                    <button onclick="clearSemesterFilter()" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        View All
                    </button>
                </div>
            </div>
        </div>

        <!-- Subjects List -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-br from-slate-50 to-gray-50 px-5 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Subjects List
                    </h2>
                    <div id="sortModeToggle" style="display: none;">
                        <button onclick="toggleSortMode()" id="sortModeBtn" class="text-sm px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            Enable Sort Mode
                        </button>
                    </div>
                </div>
            </div>

            <div id="subjectsContainer" class="p-5">
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-base text-gray-400">Select a semester to view subjects</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add/Edit Subject Modal -->
<div id="subjectModal" class="fixed inset-0 hidden z-50 flex items-center justify-center p-4 transition-all duration-300 backdrop-blur-lg" onclick="if(event.target === this) closeSubjectModal()" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto max-h-[75vh] flex flex-col" onclick="event.stopPropagation()">
        <!-- Modal Header (Fixed) -->
        <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
            <h3 id="subjectModalTitle" class="text-xl font-bold text-gray-900">Add New Subject</h3>
            <button onclick="closeSubjectModal()" class="text-gray-400 hover:text-gray-600 transition-colors hover:rotate-90 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Content (Scrollable) -->
        <div class="overflow-y-auto flex-1 px-6 py-4">
        <form id="subjectForm" onsubmit="saveSubject(event)">
            <input type="hidden" id="subjectId" name="subject_id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Name *</label>
                    <input type="text" id="subjectName" name="name" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="e.g., Data Structures and Algorithms">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code *</label>
                    <input type="text" id="subjectCode" name="code" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="e.g., CS201">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester *</label>
                    <select id="subjectSemester" name="semester" required class="form-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="subjectDescription" name="description" rows="2" class="form-textarea w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Enter subject description (optional)"></textarea>
                </div>

                <!-- Has Lab Toggle -->
                <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                    <div>
                        <label class="text-sm font-medium text-gray-900">Has Lab Sessions</label>
                        <p class="text-xs text-gray-600 mt-1">Enable if this subject includes laboratory sessions</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="subjectHasLab" name="has_lab" class="sr-only peer" onchange="toggleLabTeachers()">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assign Theory Teachers</label>
                    <div class="relative" x-data="{ open: false, search: '', selected: [] }">
                        <div @click="open = !open" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg cursor-pointer flex items-center justify-between text-sm">
                            <span x-show="selected.length === 0" class="text-gray-400">Select theory teachers...</span>
                            <span x-show="selected.length > 0" x-text="selected.length + ' teacher(s) selected'" class="text-gray-700"></span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        
                        <div x-show="open" @click.away="open = false" class="absolute z-30 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            <div class="p-3 border-b">
                                <input x-model="search" type="text" placeholder="Search teachers..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div id="teachersList" class="p-2">
                                @foreach($teachers as $teacher)
                                <label class="flex items-center p-3 hover:bg-gray-50 rounded cursor-pointer transition">
                                    <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}" class="teacher-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Select one or more teachers for theory lectures</p>
                </div>

                <!-- Lab Teachers Section (shown only when has_lab is enabled) -->
                <div id="labTeachersSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                        Assign Lab Teachers
                    </label>
                    <div class="relative" x-data="{ open: false, search: '', selected: [] }">
                        <div @click="open = !open" class="form-input w-full px-3 py-2 border border-indigo-300 bg-indigo-50 rounded-lg cursor-pointer flex items-center justify-between text-sm">
                            <span x-show="selected.length === 0" class="text-gray-400">Select lab teachers...</span>
                            <span x-show="selected.length > 0" x-text="selected.length + ' teacher(s) selected'" class="text-gray-700"></span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        
                        <div x-show="open" @click.away="open = false" class="absolute z-30 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            <div class="p-3 border-b">
                                <input x-model="search" type="text" placeholder="Search teachers..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div id="labTeachersList" class="p-2">
                                @foreach($teachers as $teacher)
                                <label class="flex items-center p-3 hover:bg-indigo-50 rounded cursor-pointer transition">
                                    <input type="checkbox" name="lab_teacher_ids[]" value="{{ $teacher->id }}" class="lab-teacher-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Select one or more teachers for laboratory sessions</p>
                </div>
            </div>
        </form>
        </div>
        
        <!-- Modal Footer (Fixed) -->
        <div class="flex justify-end space-x-3 px-6 py-4 border-t bg-gray-50 rounded-b-2xl flex-shrink-0">
            <button type="button" onclick="closeSubjectModal()" class="btn-secondary">
                Cancel
            </button>
            <button type="submit" form="subjectForm" class="btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Subject
            </button>
        </div>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}

.btn-secondary {
    @apply bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-gray-400;
}

.btn-success {
    @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500;
}

.btn-danger {
    @apply bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-red-500;
}

.subject-card {
    @apply bg-white border border-gray-200 rounded-lg p-4 hover:border-gray-300 hover:shadow-sm transition-all cursor-move;
}

.subject-card.sorting {
    @apply border-indigo-400 bg-indigo-50;
}

.teacher-chip {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-200 mr-2 mb-1.5;
}
</style>

<script>
let currentSemester = null;
let subjects = [];
let isSortMode = false;

// Semester Dropdown Handler
function handleSemesterChange(value) {
    if (value === '') {
        clearSemesterFilter();
    } else {
        currentSemester = parseInt(value);
        document.getElementById('currentSemesterText').textContent = `Semester ${value}`;
        document.getElementById('semesterDisplay').style.display = 'block';
        document.getElementById('sortModeToggle').style.display = 'block';
        loadSubjects(currentSemester);
    }
}

function clearSemesterFilter() {
    currentSemester = null;
    document.getElementById('semesterDropdown').value = '';
    document.getElementById('semesterDisplay').style.display = 'none';
    document.getElementById('sortModeToggle').style.display = 'none';
    isSortMode = false;
    document.getElementById('sortModeBtn').innerHTML = '<svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>Enable Sort Mode';
    document.getElementById('subjectsContainer').innerHTML = `
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-base text-gray-400">Select a semester to view subjects</p>
        </div>
    `;
}

// Subject Modal
function openAddSubjectModal() {
    document.getElementById('subjectModalTitle').textContent = 'Add New Subject';
    document.getElementById('subjectForm').reset();
    document.getElementById('subjectId').value = '';
    
    // Uncheck all teachers
    document.querySelectorAll('.teacher-checkbox').forEach(cb => cb.checked = false);
    
    // Set default semester if one is selected
    if (currentSemester) {
        document.getElementById('subjectSemester').value = currentSemester;
    }
    
    document.getElementById('subjectModal').classList.remove('hidden');
}

function openEditSubjectModal(subject) {
    document.getElementById('subjectModalTitle').textContent = 'Edit Subject';
    document.getElementById('subjectId').value = subject.id;
    document.getElementById('subjectName').value = subject.name;
    document.getElementById('subjectCode').value = subject.code;
    document.getElementById('subjectSemester').value = subject.semester;
    document.getElementById('subjectDescription').value = subject.description || '';
    
    // Uncheck all teachers first
    document.querySelectorAll('.teacher-checkbox').forEach(cb => cb.checked = false);
    
    // Check teachers assigned to this subject
    if (subject.teachers && subject.teachers.length > 0) {
        subject.teachers.forEach(teacher => {
            const checkbox = document.querySelector(`input[name="teacher_ids[]"][value="${teacher.id}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
    
    document.getElementById('subjectModal').classList.remove('hidden');
}

function closeSubjectModal() {
    document.getElementById('subjectModal').classList.add('hidden');
}

// Toggle Lab Teachers Section
function toggleLabTeachers() {
    const hasLab = document.getElementById('subjectHasLab').checked;
    const labSection = document.getElementById('labTeachersSection');
    
    if (hasLab) {
        labSection.classList.remove('hidden');
    } else {
        labSection.classList.add('hidden');
        // Uncheck all lab teacher checkboxes
        document.querySelectorAll('.lab-teacher-checkbox').forEach(cb => cb.checked = false);
    }
}

// Load Subjects
function loadSubjects(semester) {
    fetch(`{{ route('admin.subjects.by-semester') }}?semester=${semester}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            subjects = data.subjects;
            renderSubjects();
        }
    })
    .catch(error => {
        console.error('Error loading subjects:', error);
        showNotification('Failed to load subjects', 'error');
    });
}

// Render Subjects
function renderSubjects() {
    const container = document.getElementById('subjectsContainer');
    
    if (subjects.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-base text-gray-400 mb-3">No subjects found for Semester ${currentSemester}</p>
                <button onclick="openAddSubjectModal()" class="btn-success text-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add First Subject
                </button>
            </div>
        `;
        return;
    }
    
    let html = '<div id="subjectsList" class="space-y-3">';
    
    subjects.forEach(subject => {
        const teacherChips = subject.teachers && subject.teachers.length > 0
            ? subject.teachers.map(t => `<span class="teacher-chip">${t.name}</span>`).join('')
            : '<span class="text-gray-400 text-xs italic">No teachers assigned</span>';
        
        html += `
            <div class="subject-card" data-subject-id="${subject.id}" ${isSortMode ? 'draggable="true"' : ''}>
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            ${isSortMode ? '<svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>' : ''}
                            <h3 class="text-base font-semibold text-gray-900 truncate">${subject.name}</h3>
                            <span class="ml-2 px-2 py-0.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-md flex-shrink-0">${subject.code}</span>
                        </div>
                        ${subject.description ? `<p class="text-xs text-gray-500 mb-2 line-clamp-1">${subject.description}</p>` : ''}
                        <div class="flex flex-wrap items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            ${teacherChips}
                        </div>
                    </div>
                    ${!isSortMode ? `
                    <div class="flex space-x-1 ml-3 flex-shrink-0">
                        <button onclick='openEditSubjectModal(${JSON.stringify(subject)})' class="text-slate-600 hover:text-slate-900 hover:bg-slate-100 p-2 rounded-md transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteSubject(${subject.id})" class="text-rose-600 hover:text-rose-900 hover:bg-rose-50 p-2 rounded-md transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
    
    if (isSortMode) {
        initializeDragAndDrop();
    }
}

// Save Subject
function saveSubject(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const subjectId = document.getElementById('subjectId').value;
    
    const data = {
        name: formData.get('name'),
        code: formData.get('code'),
        semester: formData.get('semester'),
        description: formData.get('description'),
        teacher_ids: formData.getAll('teacher_ids[]')
    };
    
    console.log('Submitting data:', data);
    
    const url = subjectId 
        ? `{{ route('admin.subjects.index') }}/${subjectId}`
        : `{{ route('admin.subjects.store') }}`;
    
    const method = subjectId ? 'PUT' : 'POST';
    
    console.log('URL:', url, 'Method:', method);
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification(data.message, 'success');
            closeSubjectModal();
            if (currentSemester) {
                loadSubjects(currentSemester);
            }
        } else {
            // Show validation errors if available
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join(', ');
                showNotification(errorMessages, 'error');
            } else {
                showNotification(data.message || 'Failed to save subject', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error saving subject:', error);
        showNotification('Failed to save subject. Check console for details.', 'error');
    });
}

// Delete Subject
function deleteSubject(subjectId) {
    if (!confirm('Are you sure you want to delete this subject? This will also remove all teacher assignments.')) {
        return;
    }
    
    fetch(`{{ route('admin.subjects.index') }}/${subjectId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            if (currentSemester) {
                loadSubjects(currentSemester);
            }
        } else {
            showNotification(data.message || 'Failed to delete subject', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting subject:', error);
        showNotification('Failed to delete subject', 'error');
    });
}

// Toggle Sort Mode
function toggleSortMode() {
    isSortMode = !isSortMode;
    const btn = document.getElementById('sortModeBtn');
    
    if (isSortMode) {
        btn.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Save Sort Order';
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-success');
    } else {
        btn.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>Enable Sort Mode';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-secondary');
        saveSortOrder();
    }
    
    renderSubjects();
}

// Drag and Drop
function initializeDragAndDrop() {
    const cards = document.querySelectorAll('.subject-card');
    
    cards.forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragover', handleDragOver);
        card.addEventListener('drop', handleDrop);
        card.addEventListener('dragend', handleDragEnd);
    });
}

let draggedElement = null;

function handleDragStart(e) {
    draggedElement = this;
    this.classList.add('opacity-50');
}

function handleDragOver(e) {
    e.preventDefault();
    const afterElement = getDragAfterElement(e.clientY);
    const container = document.getElementById('subjectsList');
    
    if (afterElement == null) {
        container.appendChild(draggedElement);
    } else {
        container.insertBefore(draggedElement, afterElement);
    }
}

function handleDrop(e) {
    e.preventDefault();
}

function handleDragEnd(e) {
    this.classList.remove('opacity-50');
}

function getDragAfterElement(y) {
    const cards = [...document.querySelectorAll('.subject-card:not(.opacity-50)')];
    
    return cards.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

// Save Sort Order
function saveSortOrder() {
    const cards = document.querySelectorAll('.subject-card');
    const subjectIds = Array.from(cards).map(card => card.dataset.subjectId);
    
    fetch(`{{ route('admin.subjects.sort-order') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            semester: currentSemester,
            subject_ids: subjectIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Sort order saved successfully', 'success');
            loadSubjects(currentSemester);
        } else {
            showNotification(data.message || 'Failed to save sort order', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving sort order:', error);
        showNotification('Failed to save sort order', 'error');
    });
}

// Notification
function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-xl z-50 transform transition-all`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

@endsection
