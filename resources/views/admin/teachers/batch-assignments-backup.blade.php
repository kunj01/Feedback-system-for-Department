@extends('layouts.app')

@section('title', 'Batch Assignments - ' . $teacher->name)

@section('content')
<div class="min-h-screen bg-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Teachers
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mr-3">
                            <span class="text-lg font-bold text-indigo-600">{{ substr($teacher->name, 0, 1) }}</span>
                        </div>
                        {{ $teacher->name }}
                    </h1>
                    <p class="text-sm text-gray-600 ml-13">{{ $teacher->email }} @if($teacher->department)• {{ $teacher->department }}@endif</p>
                </div>
                <button onclick="openAssignBatchModal()" class="btn-success flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Assign Batches
                </button>
            </div>
        </div>

        <!-- Current Batch Assignments -->
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Current Batch Assignments ({{ $teacher->batches->count() }})
            </h2>

            @if($teacher->batches->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Semester</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Division</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Batch</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Subject</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Type</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Notes</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($teacher->batches as $batch)
                                @php
                                    $pivotData = $batch->pivot;
                                    $subject = $subjects->firstWhere('id', $pivotData->subject_id);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded font-semibold">
                                            Sem {{ $batch->division->semester }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-medium">{{ $batch->division->name }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded font-semibold">
                                            {{ $batch->batch_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs">{{ $subject ? $subject->name : '-' }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($pivotData->type === 'lab')
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Lab</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">Theory</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $pivotData->notes ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <button onclick="unassignBatch({{ $pivotData->id }})" class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500">No batch assignments yet</p>
                    <button onclick="openAssignBatchModal()" class="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Assign Batches Now
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Assign Batch Modal -->
<div id="assignBatchModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm hidden items-center justify-center z-50" onclick="if(event.target === this) closeAssignBatchModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <div class="p-5 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Assign Batches to {{ $teacher->name }}</h3>
            <button onclick="closeAssignBatchModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="assignBatchForm" class="overflow-y-auto" style="max-height: calc(90vh - 140px);">
            @csrf
            
            <div class="p-5 space-y-4">
                <!-- Filter Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Filter Batches</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Semester</label>
                            <select id="filterSemester" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="updateDivisionFilter(); filterBatches();">
                                <option value="">All Semesters</option>
                                <option value="4">Semester 4</option>
                                <option value="6">Semester 6</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Division</label>
                            <select id="filterDivision" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="filterBatches()">
                                <option value="">All Divisions</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" data-semester="{{ $division->semester }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Assignment Rows -->
                <div id="assignmentRows">
                    <!-- Rows will be added here -->
                </div>

                <button type="button" onclick="addAssignmentRow()" class="w-full py-2 px-4 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Another Assignment
                </button>
            </div>

            <div class="p-5 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeAssignBatchModal()" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">
                    Cancel
                </button>
                <button type="submit" class="btn-success text-sm">
                    Assign Batches
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const teacherId = {{ $teacher->id }};
const divisions = @json($divisions);
const subjects = @json($subjects);
let rowCounter = 0;

// Debug: Log data on page load
console.log('Teacher ID:', teacherId);
console.log('Divisions:', divisions);
console.log('Subjects:', subjects);

function openAssignBatchModal() {
    const modal = document.getElementById('assignBatchModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reset filters
    document.getElementById('filterSemester').value = '';
    document.getElementById('filterDivision').value = '';
    updateDivisionFilter();
    
    // Clear existing rows
    document.getElementById('assignmentRows').innerHTML = '';
    rowCounter = 0;
    
    // Add first row automatically
    setTimeout(() => {
        addAssignmentRow();
    }, 100);
}

function closeAssignBatchModal() {
    document.getElementById('assignBatchModal').classList.add('hidden');
    document.getElementById('assignBatchModal').classList.remove('flex');
    document.getElementById('assignmentRows').innerHTML = '';
    rowCounter = 0;
}

function updateDivisionFilter() {
    const selectedSemester = document.getElementById('filterSemester').value;
    const divisionSelect = document.getElementById('filterDivision');
    const options = divisionSelect.querySelectorAll('option[value!=""]');
    
    // Reset division selection
    divisionSelect.value = '';
    
    options.forEach(option => {
        const divisionSemester = option.dataset.semester;
        
        if (selectedSemester && divisionSemester !== selectedSemester) {
            option.style.display = 'none';
        } else {
            option.style.display = '';
        }
    });
}

function addAssignmentRow() {
    try {
        rowCounter++;
        const container = document.getElementById('assignmentRows');
        
        if (!container) {
            console.error('Assignment rows container not found');
            return;
        }
        
        const row = document.createElement('div');
        row.className = 'assignment-row border border-gray-200 rounded-lg p-4 bg-gray-50 mb-3';
        row.dataset.rowId = rowCounter;
        
        const batchOptions = getBatchOptions();
        const subjectOptions = getSubjectOptions();
        
        row.innerHTML = `
            <div class="flex items-start justify-between mb-3">
                <h5 class="text-sm font-semibold text-gray-700">Assignment #${rowCounter}</h5>
                ${rowCounter > 1 ? `<button type="button" onclick="removeAssignmentRow(${rowCounter})" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>` : ''}
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Batch *</label>
                    <select name="batch_ids[]" required class="batch-select w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Batch</option>
                        ${batchOptions}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Subject</label>
                    <select name="subject_ids[]" class="subject-select w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Subject</option>
                        ${subjectOptions}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Type *</label>
                    <select name="types[]" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="theory">Theory (Lecture)</option>
                        <option value="lab">Lab (Practical)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                    <input type="text" name="notes[]" placeholder="Optional notes" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        `;
        
        container.appendChild(row);
        
        // Apply current filters to the newly added row
        setTimeout(() => {
            filterBatches();
        }, 50);
        
    } catch (error) {
        console.error('Error adding assignment row:', error);
    }
}

function removeAssignmentRow(rowId) {
    const row = document.querySelector(`[data-row-id="${rowId}"]`);
    if (row) {
        row.remove();
    }
}

function getBatchOptions() {
    let options = '';
    try {
        if (!divisions || divisions.length === 0) {
            console.warn('No divisions available');
            return options;
        }
        
        divisions.forEach(division => {
            if (division.batches && division.batches.length > 0) {
                division.batches.forEach(batch => {
                    options += `<option value="${batch.id}" data-semester="${division.semester}" data-division="${division.id}">${division.name} - ${batch.batch_name}</option>`;
                });
            }
        });
    } catch (error) {
        console.error('Error generating batch options:', error);
    }
    return options;
}

function getSubjectOptions(filterSemester = null) {
    let options = '';
    try {
        if (!subjects || subjects.length === 0) {
            console.warn('No subjects available');
            return options;
        }
        
        subjects.forEach(subject => {
            // Filter by semester if specified
            if (filterSemester && subject.semester != filterSemester) {
                return;
            }
            options += `<option value="${subject.id}" data-semester="${subject.semester}">${subject.name} (Sem ${subject.semester})</option>`;
        });
    } catch (error) {
        console.error('Error generating subject options:', error);
    }
    return options;
}

function filterBatches() {
    try {
        const selectedSemester = document.getElementById('filterSemester').value;
        const selectedDivision = document.getElementById('filterDivision').value;
        
        // Filter batch selects
        const batchSelects = document.querySelectorAll('.batch-select');
        batchSelects.forEach(select => {
            const options = select.querySelectorAll('option[value!=""]');
            options.forEach(option => {
                const semester = option.dataset.semester;
                const division = option.dataset.division;
                
                let shouldShow = true;
                
                if (selectedSemester && semester !== selectedSemester) {
                    shouldShow = false;
                }
                
                if (selectedDivision && division !== selectedDivision) {
                    shouldShow = false;
                }
                
                option.style.display = shouldShow ? '' : 'none';
            });
            
            // Reset selection if current selection is now hidden
            const currentOption = select.options[select.selectedIndex];
            if (currentOption && currentOption.style.display === 'none') {
                select.value = '';
            }
        });
        
        // Filter subject selects
        const subjectSelects = document.querySelectorAll('.subject-select');
        subjectSelects.forEach(select => {
            const options = select.querySelectorAll('option[value!=""]');
            options.forEach(option => {
                const semester = option.dataset.semester;
                
                let shouldShow = true;
                
                if (selectedSemester && semester !== selectedSemester) {
                    shouldShow = false;
                }
                
                option.style.display = shouldShow ? '' : 'none';
            });
            
            // Reset selection if current selection is now hidden
            const currentOption = select.options[select.selectedIndex];
            if (currentOption && currentOption.style.display === 'none') {
                select.value = '';
            }
        });
    } catch (error) {
        console.error('Error filtering batches:', error);
    }
}

// Form submission
const form = document.getElementById('assignBatchForm');
if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            batch_ids: formData.getAll('batch_ids[]'),
            subject_ids: formData.getAll('subject_ids[]'),
            types: formData.getAll('types[]'),
            notes: formData.getAll('notes[]'),
        };
        
        try {
            const response = await fetch(`/admin/teachers/${teacherId}/batches/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to assign batches'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while assigning batches');
        }
    });
}

function unassignBatch(pivotId) {
    if (!confirm('Are you sure you want to remove this batch assignment?')) {
        return;
    }
    
    fetch(`/admin/teachers/${teacherId}/batches/unassign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ pivot_id: pivotId })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            window.location.reload();
        } else {
            alert('Error: ' + (result.message || 'Failed to remove assignment'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while removing the assignment');
    });
}
</script>
@endsection
