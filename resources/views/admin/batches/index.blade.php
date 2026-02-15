@extends('layouts.app')

@section('title', 'Batch Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-3">
    <div class="max-w-full mx-auto px-3 sm:px-4 lg:px-6">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-3 mb-3">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Batch Management
                    </h1>
                    <p class="text-gray-600 text-sm mt-0.5">Manage batches and view students by batch</p>
                </div>
                <button onclick="openAddBatchModal()" class="btn-success flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Batch
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            
            <!-- Divisions List -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-lg p-3">
                <h2 class="text-lg font-bold text-gray-900 mb-2">Divisions</h2>
                <form method="GET" action="{{ route('admin.batches.index') }}" class="mb-3">
                    <select name="division_id" onchange="this.form.submit()" class="form-select w-full rounded-lg border-gray-300">
                        <option value="">Select Division</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ $selectedDivisionId == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                                ({{ $division->batches_count }} batches)
                            </option>
                        @endforeach
                    </select>
                </form>

                @if($selectedDivisionId && count($batches) > 0)
                <div class="space-y-1.5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1.5">Batches:</h3>
                    @foreach($batches as $batch)
                    <div class="p-2 border border-gray-200 rounded hover:border-indigo-500 transition-colors cursor-pointer {{ request('batch_id') == $batch->id ? 'bg-indigo-50 border-indigo-500' : '' }}"
                         onclick="selectBatch({{ $batch->id }})">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-sm text-gray-900">{{ $batch->batch_name }}</div>
                                <div class="text-xs text-gray-600">{{ $batch->students_count }} students</div>
                            </div>
                            <div class="flex space-x-1.5">
                                <button onclick="event.stopPropagation(); editBatch({{ $batch->id }})" class="text-indigo-600 hover:text-indigo-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="event.stopPropagation(); deleteBatch({{ $batch->id }})" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Students List -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-bold text-gray-900">Students</h2>
                    @if($selectedBatchId && (count($assignedStudents) > 0 || count($unassignedStudents) > 0))
                    <div class="flex space-x-2">
                        <button onclick="assignSelectedStudents()" class="btn-primary text-sm">
                            Assign Selected to Batch
                        </button>
                        <button onclick="unassignSelectedStudents()" class="btn-secondary text-sm">
                            Unassign Selected
                        </button>
                    </div>
                    @endif
                </div>

                @if($selectedBatchId && (count($assignedStudents) > 0 || count($unassignedStudents) > 0))
                <!-- Filter Section -->
                <div class="bg-gray-50 rounded-lg p-2.5 mb-3 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Search Input -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search Students
                            </label>
                            <input 
                                type="text" 
                                id="studentSearch" 
                                placeholder="Search by name or enrollment number..." 
                                class="form-input w-full rounded-lg border-gray-300 text-sm"
                                oninput="filterStudents()"
                            >
                        </div>

                        <!-- Filter Buttons -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Filter by Status</label>
                            <div class="flex space-x-1.5">
                                <button 
                                    onclick="setStatusFilter('all')" 
                                    id="filterAll"
                                    class="filter-btn active px-3 py-1.5 rounded text-xs font-medium transition-colors"
                                >
                                    All (<span id="countAll">{{ count($assignedStudents) + count($unassignedStudents) }}</span>)
                                </button>
                                <button 
                                    onclick="setStatusFilter('assigned')" 
                                    id="filterAssigned"
                                    class="filter-btn px-3 py-1.5 rounded text-xs font-medium transition-colors"
                                >
                                    Assigned (<span id="countAssigned">{{ count($assignedStudents) }}</span>)
                                </button>
                                <button 
                                    onclick="setStatusFilter('unassigned')" 
                                    id="filterUnassigned"
                                    class="filter-btn px-3 py-1.5 rounded text-xs font-medium transition-colors"
                                >
                                    Unassigned (<span id="countUnassigned">{{ count($unassignedStudents) }}</span>)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search Results Info -->
                    <div id="searchInfo" class="mt-2 text-xs text-gray-600 hidden">
                        <span id="searchResultText"></span>
                        <button onclick="clearSearch()" class="ml-2 text-indigo-600 hover:text-indigo-800 font-medium">
                            Clear Search
                        </button>
                    </div>
                </div>
                @endif
                
                @if($selectedBatchId)
                    @if(count($assignedStudents) > 0)
                    <div class="mb-3 assigned-students-section">
                        <h3 class="text-base font-semibold text-green-700 mb-2">✓ Assigned Students ({{ count($assignedStudents) }})</h3>
                        <div class="overflow-x-auto border rounded">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-green-50 border-b border-gray-200">
                                        <th class="px-3 py-2 text-left">
                                            <input type="checkbox" id="selectAllAssigned" onchange="toggleSelectAll('assigned', this.checked)" class="rounded">
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Enrollment No</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Email</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Batch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedStudents as $student)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-3 py-2">
                                            <input type="checkbox" class="student-checkbox assigned-checkbox" value="{{ $student->id }}" data-type="assigned" onclick="updateSelectAll('assigned')">
                                        </td>
                                        <td class="px-3 py-2 text-xs">{{ $student->enrollment_no ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs font-medium">
                                            {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">{{ $student->email ?? $student->personal_email ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $student->batchGroup->batch_name ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if(count($unassignedStudents) > 0)
                    <div class="unassigned-students-section">
                        <h3 class="text-base font-semibold text-orange-700 mb-2">⚠ Unassigned Students ({{ count($unassignedStudents) }})</h3>
                        <div class="overflow-x-auto border rounded">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-orange-50 border-b border-gray-200">
                                        <th class="px-3 py-2 text-left">
                                            <input type="checkbox" id="selectAllUnassigned" onchange="toggleSelectAll('unassigned', this.checked)" class="rounded">
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Enrollment No</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Email</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unassignedStudents as $student)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-3 py-2">
                                            <input type="checkbox" class="student-checkbox unassigned-checkbox" value="{{ $student->id }}" data-type="unassigned" onclick="updateSelectAll('unassigned')">
                                        </td>
                                        <td class="px-3 py-2 text-xs">{{ $student->enrollment_no ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs font-medium">
                                            {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                        </td>
                                        <td class="px-3 py-2 text-xs">{{ $student->email ?? $student->personal_email ?? '-' }}</td>
                                        <td class="px-3 py-2 text-xs">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Not Assigned
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- No Filter Results Message -->
                    <div id="noFilterResults" class="text-center py-6 hidden">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-gray-600 text-sm mt-2">No students match your search criteria</p>
                        <button onclick="clearSearch(); setStatusFilter('all')" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Clear all filters
                        </button>
                    </div>

                    @if(count($assignedStudents) == 0 && count($unassignedStudents) == 0)
                    <div class="text-center py-6">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-600 text-sm mt-2">No students found in this division</p>
                    </div>
                    @endif
                @else
                <div class="text-center py-6">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-600 text-sm mt-2">Select a batch to view students</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Add Batch Modal -->
<div id="batchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-3 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="p-4">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Add Batch</h3>
            <form id="batchForm">
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Division *</label>
                    <select id="batchDivisionId" required class="form-select w-full rounded border-gray-300 text-sm">
                        <option value="">Select Division</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Batch Name *</label>
                    <input type="text" id="batchName" required placeholder="e.g., A1, B2, C1" class="form-input w-full rounded border-gray-300 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea id="batchDescription" rows="2" class="form-input w-full rounded border-gray-300 text-sm"></textarea>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeBatchModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-select, .form-input {
        @apply border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50;
    }
    .btn-primary {
        @apply bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded transition-colors;
    }
    .btn-secondary {
        @apply bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-1.5 px-3 rounded transition-colors;
    }
    .btn-success {
        @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 px-3 rounded transition-colors;
    }
    .filter-btn {
        @apply bg-gray-200 text-gray-700 border border-gray-300;
    }
    .filter-btn:hover {
        @apply bg-gray-300;
    }
    .filter-btn.active {
        @apply bg-indigo-600 text-white border-indigo-600;
    }
    .student-row-hidden {
        display: none !important;
    }
</style>

<script>
let currentStatusFilter = 'all';

function selectBatch(batchId) {
    const url = new URL(window.location.href);
    url.searchParams.set('batch_id', batchId);
    url.searchParams.set('division_id', '{{ $selectedDivisionId }}');
    window.location.href = url.toString();
}

function setStatusFilter(status) {
    currentStatusFilter = status;
    
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('filter' + status.charAt(0).toUpperCase() + status.slice(1)).classList.add('active');
    
    // Apply filter
    filterStudents();
}

function filterStudents() {
    const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
    const assignedSection = document.querySelector('.assigned-students-section');
    const unassignedSection = document.querySelector('.unassigned-students-section');
    
    let visibleCount = 0;
    let assignedVisible = 0;
    let unassignedVisible = 0;
    
    // Filter assigned students
    if (assignedSection) {
        const rows = assignedSection.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = currentStatusFilter === 'all' || currentStatusFilter === 'assigned';
            
            if (matchesSearch && matchesStatus) {
                row.classList.remove('student-row-hidden');
                visibleCount++;
                assignedVisible++;
            } else {
                row.classList.add('student-row-hidden');
            }
        });
        
        // Show/hide entire section
        if (currentStatusFilter === 'unassigned') {
            assignedSection.style.display = 'none';
        } else if (assignedVisible === 0 && searchTerm) {
            assignedSection.style.display = 'none';
        } else {
            assignedSection.style.display = 'block';
        }
    }
    
    // Filter unassigned students
    if (unassignedSection) {
        const rows = unassignedSection.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = currentStatusFilter === 'all' || currentStatusFilter === 'unassigned';
            
            if (matchesSearch && matchesStatus) {
                row.classList.remove('student-row-hidden');
                visibleCount++;
                unassignedVisible++;
            } else {
                row.classList.add('student-row-hidden');
            }
        });
        
        // Show/hide entire section
        if (currentStatusFilter === 'assigned') {
            unassignedSection.style.display = 'none';
        } else if (unassignedVisible === 0 && searchTerm) {
            unassignedSection.style.display = 'none';
        } else {
            unassignedSection.style.display = 'block';
        }
    }
    
    // Update search info
    const searchInfo = document.getElementById('searchInfo');
    const searchResultText = document.getElementById('searchResultText');
    const noFilterResults = document.getElementById('noFilterResults');
    
    if (searchTerm) {
        searchInfo.classList.remove('hidden');
        searchResultText.textContent = `Found ${visibleCount} student(s) matching "${searchTerm}"`;
    } else {
        searchInfo.classList.add('hidden');
    }
    
    // Show/hide no results message
    if (visibleCount === 0 && (searchTerm || currentStatusFilter !== 'all')) {
        noFilterResults.classList.remove('hidden');
    } else {
        noFilterResults.classList.add('hidden');
    }
}

function clearSearch() {
    document.getElementById('studentSearch').value = '';
    filterStudents();
}

function toggleSelectAll(type, checked) {
    // Only toggle visible checkboxes
    const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        if (!row.classList.contains('student-row-hidden')) {
            cb.checked = checked;
        }
    });
}

function updateSelectAll(type) {
    const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
    const selectAllCheckbox = document.getElementById(`selectAll${type.charAt(0).toUpperCase() + type.slice(1)}`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = allChecked;
    }
}

function assignSelectedStudents() {
    const checkboxes = document.querySelectorAll('.unassigned-checkbox:checked');
    // Only get IDs from visible rows
    const studentIds = Array.from(checkboxes)
        .filter(cb => !cb.closest('tr').classList.contains('student-row-hidden'))
        .map(cb => cb.value);
    
    if (studentIds.length === 0) {
        alert('Please select at least one unassigned student to assign');
        return;
    }
    
    if (!confirm(`Assign ${studentIds.length} student(s) to this batch?`)) {
        return;
    }
    
    fetch('/admin/batches/assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            batch_id: {{ $selectedBatchId ?? 'null' }},
            student_ids: studentIds
        })
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            alert(response.message);
            location.reload();
        } else {
            alert('Error: ' + response.message);
        }
    })
    .catch(err => {
        alert('Error assigning students: ' + err.message);
    });
}

function unassignSelectedStudents() {
    const checkboxes = document.querySelectorAll('.assigned-checkbox:checked');
    // Only get IDs from visible rows
    const studentIds = Array.from(checkboxes)
        .filter(cb => !cb.closest('tr').classList.contains('student-row-hidden'))
        .map(cb => cb.value);
    
    if (studentIds.length === 0) {
        alert('Please select at least one assigned student to unassign');
        return;
    }
    
    if (!confirm(`Remove ${studentIds.length} student(s) from this batch?`)) {
        return;
    }
    
    fetch('/admin/batches/unassign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            student_ids: studentIds
        })
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            alert(response.message);
            location.reload();
        } else {
            alert('Error: ' + response.message);
        }
    })
    .catch(err => {
        alert('Error unassigning students: ' + err.message);
    });
}

function openAddBatchModal() {
    document.getElementById('batchForm').reset();
    document.getElementById('batchModal').classList.remove('hidden');
    
    @if($selectedDivisionId)
    document.getElementById('batchDivisionId').value = '{{ $selectedDivisionId }}';
    @endif
}

function closeBatchModal() {
    document.getElementById('batchModal').classList.add('hidden');
}

document.getElementById('batchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const data = {
        division_id: document.getElementById('batchDivisionId').value,
        batch_name: document.getElementById('batchName').value,
        description: document.getElementById('batchDescription').value,
    };
    
    fetch('/admin/batches', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            alert(response.message);
            location.reload();
        } else {
            alert('Error: ' + response.message);
        }
    });
});

function editBatch(id) {
    alert('Edit batch ID: ' + id);
}

function deleteBatch(id) {
    if (!confirm('Are you sure you want to delete this batch?')) {
        return;
    }
    
    fetch(`/admin/batches/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(response => {
        alert(response.message);
        if (response.success) {
            location.reload();
        }
    });
}

// Keyboard shortcut: Focus search on Ctrl+F or Cmd+F (but not if in input already)
document.addEventListener('keydown', function(e) {
    const searchInput = document.getElementById('studentSearch');
    if (searchInput && (e.ctrlKey || e.metaKey) && e.key === 'f') {
        // Only prevent default if search input exists and is visible
        if (searchInput.offsetParent !== null) {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
    }
});
</script>
@endsection
