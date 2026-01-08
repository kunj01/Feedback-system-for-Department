@extends('layouts.app')

@section('title', 'Feedback Form')
@section('page-title', 'Feedback')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('feedback.subject', $subjectId) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
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
    
    $faculties = [
        1 => 'Dr. Rajesh Kumar', 2 => 'Prof. Anita Sharma', 3 => 'Dr. Suresh Patel',
        4 => 'Dr. Vijay Singh', 5 => 'Prof. Meena Reddy', 6 => 'Dr. Priya Joshi',
        7 => 'Prof. Amit Gupta', 8 => 'Dr. Neha Kapoor', 9 => 'Dr. Arun Verma',
        10 => 'Prof. Kavita Mehta', 11 => 'Dr. Sanjay Desai', 12 => 'Prof. Rekha Nair',
    ];
    
    $subject = $subjects[$subjectId] ?? ['name' => 'Unknown', 'code' => 'N/A'];
    $faculty = $faculties[$facultyId] ?? 'Unknown Faculty';
@endphp

<!-- Form Header -->
<div class="card mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $subject['name'] }}</h2>
            <p class="text-gray-600">{{ $faculty }} • {{ $subject['code'] }}</p>
        </div>
        <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
            Feedback Form
        </div>
    </div>
</div>

<!-- Feedback Form -->
<form method="POST" action="{{ route('feedback.submit') }}">
    @csrf
    <input type="hidden" name="subject_id" value="{{ $subjectId }}">
    <input type="hidden" name="faculty_id" value="{{ $facultyId }}">

    <!-- Questions -->
    <div class="card mb-4">
        <div class="space-y-6">
            @php
                $questions = [
                    'q1' => 'Course content was well-organized',
                    'q2' => 'Faculty explained concepts clearly',
                    'q3' => 'Pace of the course was appropriate',
                    'q4' => 'Faculty used effective teaching methods',
                    'q5' => 'Faculty was approachable and helpful',
                    'q6' => 'Doubts were addressed satisfactorily',
                    'q7' => 'Class participation was encouraged',
                    'q8' => 'Feedback on assignments was timely',
                ];
            @endphp

            @foreach($questions as $key => $question)
            <div class="pb-4 border-b border-gray-100 last:border-0">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ $loop->iteration }}. {{ $question }}
                </label>
                <div class="flex gap-6">
                    @foreach(['Strongly Disagree' => 1, 'Disagree' => 2, 'Neutral' => 3, 'Agree' => 4, 'Strongly Agree' => 5] as $label => $value)
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="{{ $key }}" value="{{ $value }}" class="w-4 h-4 text-blue-600" required>
                        <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Overall Rating -->
    <div class="card mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-3">Overall Rating</label>
        <div class="flex gap-4">
            @for($i = 1; $i <= 5; $i++)
            <label class="inline-flex flex-col items-center cursor-pointer">
                <input type="radio" name="overall_rating" value="{{ $i }}" class="mb-2" required>
                <div class="flex">
                    @for($j = 1; $j <= $i; $j++)
                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                    @endfor
                </div>
            </label>
            @endfor
        </div>
    </div>

    <!-- Comments -->
    <div class="card mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Comments (Optional)</label>
        <textarea name="comments" rows="3" class="input-field" placeholder="Share your thoughts..."></textarea>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('feedback.subject', $subjectId) }}" class="btn-secondary">
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            Submit Feedback
        </button>
    </div>
</form>

<p class="text-xs text-gray-500 text-center mt-4">Your feedback is anonymous and confidential</p>

<script>
// Auto-save functionality
const AUTOSAVE_KEY = 'feedback_{{ $subjectId }}_{{ $facultyId }}';

// Load saved data on page load
window.addEventListener('DOMContentLoaded', function() {
    const savedData = localStorage.getItem(AUTOSAVE_KEY);
    if (savedData) {
        const data = JSON.parse(savedData);
        
        // Restore radio button selections
        Object.keys(data).forEach(key => {
            const input = document.querySelector(`input[name="${key}"][value="${data[key]}"]`);
            if (input) {
                input.checked = true;
            }
        });
        
        // Restore textarea
        if (data.comments) {
            const textarea = document.querySelector('textarea[name="comments"]');
            if (textarea) textarea.value = data.comments;
        }
        
        // Show restore notification
        showNotification('Previous draft restored', 'info');
    }
});

// Save on input change
document.querySelectorAll('input[type="radio"], textarea').forEach(element => {
    element.addEventListener('change', function() {
        autoSave();
    });
});

function autoSave() {
    const formData = {};
    
    // Get all radio button values
    document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
        formData[input.name] = input.value;
    });
    
    // Get textarea value
    const comments = document.querySelector('textarea[name="comments"]');
    if (comments) {
        formData.comments = comments.value;
    }
    
    // Save to localStorage
    localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(formData));
    
    // Show save indicator
    showNotification('Draft saved', 'success');
}

// Clear autosave on successful submit
document.querySelector('form').addEventListener('submit', function() {
    localStorage.removeItem(AUTOSAVE_KEY);
});

function showNotification(message, type) {
    // Remove existing notification
    const existing = document.querySelector('.autosave-notification');
    if (existing) existing.remove();
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `autosave-notification fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-sm font-medium z-50 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'}`;
    notification.textContent = message;
    notification.style.transition = 'opacity 0.3s';
    
    document.body.appendChild(notification);
    
    // Auto-remove after 2 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}
</script>

@endsection
