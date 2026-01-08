@extends('layouts.app')

@section('title', 'Course Feedback')
@section('page-title', 'Provide Feedback')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back
    </a>
</div>

@php
    $subjects = [
        1 => ['name' => 'Data Structures', 'code' => 'CSE-301'],
        2 => ['name' => 'Operating Systems', 'code' => 'CSE-302'],
        3 => ['name' => 'Database Management', 'code' => 'CSE-303'],
        4 => ['name' => 'Computer Networks', 'code' => 'CSE-304'],
        5 => ['name' => 'Software Engineering', 'code' => 'CSE-305'],
    ];
    
    $facultyData = [
        1 => [
            ['id' => 1, 'name' => 'Dr. Rajesh Kumar'],
            ['id' => 2, 'name' => 'Prof. Anita Sharma'],
            ['id' => 3, 'name' => 'Dr. Suresh Patel'],
        ],
        2 => [
            ['id' => 4, 'name' => 'Dr. Vijay Singh'],
            ['id' => 5, 'name' => 'Prof. Meena Reddy'],
        ],
        3 => [
            ['id' => 6, 'name' => 'Dr. Priya Joshi'],
            ['id' => 7, 'name' => 'Prof. Amit Gupta'],
            ['id' => 8, 'name' => 'Dr. Neha Kapoor'],
        ],
        4 => [
            ['id' => 9, 'name' => 'Dr. Arun Verma'],
            ['id' => 10, 'name' => 'Prof. Kavita Mehta'],
        ],
        5 => [
            ['id' => 11, 'name' => 'Dr. Sanjay Desai'],
            ['id' => 12, 'name' => 'Prof. Rekha Nair'],
        ],
    ];
    
    $subject = $subjects[$subjectId] ?? ['name' => 'Unknown Subject', 'code' => 'N/A'];
    $faculties = $facultyData[$subjectId] ?? [];
    
    // Check session for completed feedbacks
    $completedFeedbacks = session()->get('completed_feedbacks', []);
    
    // Mark faculties as completed based on session
    foreach ($faculties as &$faculty) {
        $key = $subjectId . '_' . $faculty['id'];
        $faculty['completed'] = isset($completedFeedbacks[$key]);
    }
    
    $completedCount = count(array_filter($faculties, fn($f) => $f['completed']));
@endphp

<!-- Subject Header Card -->
<div class="card mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $subject['name'] }}</h2>
            <p class="text-gray-600">{{ $subject['code'] }}</p>
        </div>
        <div class="text-right">
            <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-lg">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-semibold text-gray-700">{{ $completedCount }}/{{ count($faculties) }} Completed</span>
            </div>
        </div>
    </div>

    <!-- Faculty Dropdown -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Select Faculty Member <span class="text-red-500">*</span>
        </label>
        <select id="facultySelect" class="input-field text-lg" onchange="handleFacultySelection(this.value)">
            <option value="">-- Choose a faculty member --</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty['id'] }}" data-completed="{{ $faculty['completed'] ? '1' : '0' }}" style="background-color: {{ $faculty['completed'] ? '#d1fae5' : '#fee2e2' }}; color: {{ $faculty['completed'] ? '#065f46' : '#991b1b' }}; font-weight: 500;">
                    {{ $faculty['completed'] ? '●' : '●' }} {{ $faculty['name'] }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<!-- Instructions -->
<div class="card bg-blue-50 border border-blue-200">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div class="text-sm text-blue-700">
            <p class="font-medium mb-1">How to proceed:</p>
            <p>Select a faculty member from the dropdown above to provide your feedback. Your responses are confidential.</p>
        </div>
    </div>
</div>

<script>
function handleFacultySelection(facultyId) {
    if (!facultyId) return;
    
    const select = document.getElementById('facultySelect');
    const selectedOption = select.options[select.selectedIndex];
    const isCompleted = selectedOption.getAttribute('data-completed') === '1';
    
    if (isCompleted) {
        alert('You have already submitted feedback for this faculty member.');
        select.value = '';
        return;
    }
    
    // Navigate to feedback form
    window.location.href = `/feedback/subject/{{ $subjectId }}/faculty/${facultyId}`;
}
</script>
@endsection
