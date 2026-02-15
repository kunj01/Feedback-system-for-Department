@extends('layouts.app')

@section('title', 'Timetable Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Timetable Management
                    </h1>
                    <p class="text-gray-600 mt-1">Manage class schedules, subjects, and faculty assignments</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="openAddEntryModal()" class="btn-success flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Entry
                    </button>
                    <button onclick="generateFeedbackAllocations()" class="btn-primary flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Generate Feedback Allocations
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" action="{{ route('admin.timetable.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select name="semester" id="semesterFilter" class="form-select w-full rounded-lg border-gray-300">
                            <option value="">Select Semester</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem }}">Semester {{ $sem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                        <select name="branch" id="branchFilter" class="form-select w-full rounded-lg border-gray-300">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch }}">{{ $branch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Division</label>
                        <select name="division_id" id="divisionFilter" class="form-select w-full rounded-lg border-gray-300">
                            <option value="">Select Division</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ $selectedDivisionId == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn-primary">
                        View Timetable
                    </button>
                </div>
            </form>
        </div>

        <!-- Timetable Grid -->
        @if($selectedDivisionId && isset($timetableData['timetable']))
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-fixed">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <th class="border border-indigo-700 px-2 py-2 text-center font-semibold text-sm w-24">Time</th>
                            @foreach($timetableData['days'] as $day)
                                <th class="border border-indigo-700 px-2 py-2 text-center font-semibold text-sm">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetableData['timeSlots'] as $timeSlot)
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 text-center font-semibold bg-gray-50 whitespace-nowrap text-xs">
                                {{ $timeSlot }}
                            </td>
                            @foreach($timetableData['days'] as $day)
                                <td class="border border-gray-300 px-1 py-1 text-center align-top relative">
                                    @php
                                        $entries = $timetableData['timetable'][$timeSlot][$day] ?? collect();
                                        
                                        // Group entries by subject and faculty
                                        $groupedEntries = $entries->groupBy(function($entry) {
                                            return $entry->subject_id . '-' . $entry->faculty_id . '-' . $entry->room_no;
                                        });
                                    @endphp
                                    
                                    @if($entries->isEmpty())
                                        <div class="text-gray-400 text-xs py-2">-</div>
                                    @else
                                        @foreach($groupedEntries as $key => $group)
                                            @php
                                                $firstEntry = $group->first();
                                                $hasLab = $group->whereNotNull('batch_id')->count() > 0;
                                                $hasLecture = $group->whereNull('batch_id')->count() > 0;
                                                
                                                if ($hasLab && !$hasLecture) {
                                                    $bgColor = 'bg-green-100';
                                                    $borderColor = 'border-green-300';
                                                } elseif ($hasLecture) {
                                                    $bgColor = 'bg-blue-100';
                                                    $borderColor = 'border-blue-300';
                                                } else {
                                                    $bgColor = 'bg-gray-50';
                                                    $borderColor = 'border-gray-300';
                                                }
                                                
                                                $batchList = $group->whereNotNull('batch_id')->pluck('batch.batch_name')->filter()->join(', ');
                                            @endphp
                                            <div class="mb-1 p-1 rounded border {{ $bgColor }} {{ $borderColor }} text-xs hover:shadow-sm transition-shadow cursor-pointer"
                                                 onclick="editEntry({{ $firstEntry->id }})"
                                                 title="{{ $firstEntry->subject->subject_name }} - {{ $firstEntry->faculty->faculty_name }}">
                                                
                                                <div class="font-bold text-xs">
                                                    {{ $firstEntry->subject->subject_code }}
                                                </div>
                                                
                                                <div class="text-[10px] mt-0.5">
                                                    {{ $firstEntry->faculty->short_code }}
                                                </div>
                                                
                                                <div class="text-[10px]">
                                                    {{ $firstEntry->room_no }}
                                                </div>
                                                
                                                @if($batchList)
                                                    <div class="text-[10px] font-semibold mt-0.5 text-green-700">
                                                        {{ $batchList }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="mt-6 flex items-center justify-center space-x-6">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-200 border-2 border-blue-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Lecture</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-200 border-2 border-green-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Lab</span>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-600 mt-4">Select a division to view timetable</p>
        </div>
        @endif

    </div>
</div>

<!-- Add/Edit Entry Modal -->
<div id="entryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4" id="modalTitle">Add Timetable Entry</h3>
            <form id="entryForm">
                <input type="hidden" id="entryId">
                <input type="hidden" id="divisionId" value="{{ $selectedDivisionId }}">
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Day *</label>
                        <select id="day" required class="form-select w-full rounded-lg border-gray-300">
                            <option value="">Select Day</option>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Slot *</label>
                        <select id="timeSlot" required class="form-select w-full rounded-lg border-gray-300">
                            <option value="">Select Time</option>
                            @foreach(['09:10-10:10', '10:10-11:10', '11:10-12:10', '12:10-01:10', '01:10-02:10', '02:20-03:20', '03:20-04:20'] as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <select id="subjectId" required class="form-select w-full rounded-lg border-gray-300">
                        <option value="">Select Subject</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Faculty *</label>
                    <select id="facultyId" required class="form-select w-full rounded-lg border-gray-300">
                        <option value="">Select Faculty</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room No *</label>
                        <input type="text" id="roomNo" required class="form-input w-full rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Batch (Optional)</label>
                        <select id="batchId" class="form-select w-full rounded-lg border-gray-300">
                            <option value="">Lecture (All Students)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEntryModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Entry</button>
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
        @apply bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors;
    }
    .btn-secondary {
        @apply bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors;
    }
    .btn-success {
        @apply bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors;
    }
</style>

<script>
let currentDivisionId = {{ $selectedDivisionId ?? 'null' }};

// Filter by semester and branch to load divisions
document.getElementById('semesterFilter').addEventListener('change', loadDivisions);
document.getElementById('branchFilter').addEventListener('change', loadDivisions);

function loadDivisions() {
    const semester = document.getElementById('semesterFilter').value;
    const branch = document.getElementById('branchFilter').value;
    
    if (!semester || !branch) return;
    
    fetch(`/api/divisions?semester=${semester}&branch=${branch}`)
        .then(res => res.json())
        .then(divisions => {
            const select = document.getElementById('divisionFilter');
            select.innerHTML = '<option value="">Select Division</option>';
            divisions.forEach(div => {
                select.innerHTML += `<option value="${div.id}">${div.name}</option>`;
            });
        });
}

function openAddEntryModal() {
    if (!currentDivisionId) {
        alert('Please select a division first');
        return;
    }
    
    document.getElementById('modalTitle').textContent = 'Add Timetable Entry';
    document.getElementById('entryForm').reset();
    document.getElementById('entryId').value = '';
    document.getElementById('divisionId').value = currentDivisionId;
    
    loadSubjects();
    loadFaculties();
    loadBatches();
    
    document.getElementById('entryModal').classList.remove('hidden');
}

function closeEntryModal() {
    document.getElementById('entryModal').classList.add('hidden');
}

function loadSubjects() {
    fetch(`/api/timetable/subjects?division_id=${currentDivisionId}`)
        .then(res => res.json())
        .then(subjects => {
            const select = document.getElementById('subjectId');
            select.innerHTML = '<option value="">Select Subject</option>';
            subjects.forEach(sub => {
                select.innerHTML += `<option value="${sub.id}">${sub.subject_code} - ${sub.subject_name}</option>`;
            });
        });
}

function loadFaculties() {
    fetch('/api/timetable/faculties')
        .then(res => res.json())
        .then(faculties => {
            const select = document.getElementById('facultyId');
            select.innerHTML = '<option value="">Select Faculty</option>';
            faculties.forEach(fac => {
                select.innerHTML += `<option value="${fac.id}">${fac.short_code} - ${fac.faculty_name}</option>`;
            });
        });
}

function loadBatches() {
    fetch(`/api/timetable/batches/${currentDivisionId}`)
        .then(res => res.json())
        .then(batches => {
            const select = document.getElementById('batchId');
            select.innerHTML = '<option value="">Lecture (All Students)</option>';
            batches.forEach(batch => {
                select.innerHTML += `<option value="${batch.id}">${batch.batch_name}</option>`;
            });
        });
}

document.getElementById('entryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const entryId = document.getElementById('entryId').value;
    const url = entryId ? `/admin/timetable/${entryId}` : '/admin/timetable';
    const method = entryId ? 'PUT' : 'POST';
    
    const data = {
        division_id: currentDivisionId,
        day: document.getElementById('day').value,
        time_slot: document.getElementById('timeSlot').value,
        subject_id: document.getElementById('subjectId').value,
        faculty_id: document.getElementById('facultyId').value,
        room_no: document.getElementById('roomNo').value,
        batch_id: document.getElementById('batchId').value || null,
    };
    
    fetch(url, {
        method: method,
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

function editEntry(id) {
    // Implement edit functionality
    alert('Edit functionality - Entry ID: ' + id);
}

function generateFeedbackAllocations() {
    if (!currentDivisionId) {
        alert('Please select a division first');
        return;
    }
    
    if (!confirm('Generate feedback allocations based on this timetable?')) {
        return;
    }
    
    fetch('/admin/timetable/generate-feedback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ division_id: currentDivisionId })
    })
    .then(res => res.json())
    .then(response => {
        alert(response.message);
    });
}
</script>
@endsection
