@extends('layouts.app')

@section('title', 'Feedback Details')
@section('page-title', 'Feedback Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.student-feedback.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Feedback List
        </a>
    </div>

    <!-- Feedback Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($feedback->student->user->name ?? 'N', 0, 1)) }}
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $feedback->student->user->name ?? 'Unknown Student' }}</h2>
                    <p class="text-gray-600 mt-1">Student ID: {{ $feedback->student_id }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Submitted on {{ $feedback->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-end space-y-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= $feedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    @endfor
                    <span class="ml-2 text-lg font-semibold text-gray-700">{{ $feedback->overall_rating }}/5</span>
                </div>
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm font-semibold">
                    Feedback #{{ $feedback->id }}
                </span>
            </div>
        </div>
    </div>

    <!-- Subject and Faculty Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Subject Information
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Subject ID</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $feedback->subject_id }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Faculty Information
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Faculty ID</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $feedback->faculty_id }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Responses -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Question Responses
        </h3>

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
            $responses = $feedback->responses;
        @endphp

        <div class="space-y-4">
            @foreach($questions as $key => $question)
                <div class="border-b border-gray-200 pb-4 last:border-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $loop->iteration }}. {{ $question }}</p>
                        </div>
                        <div class="ml-4 flex items-center">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= ($responses[$key] ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-3 text-lg font-semibold text-gray-700">{{ $responses[$key] ?? 0 }}/5</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Average Score -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between bg-blue-50 rounded-lg p-4">
                <span class="text-lg font-semibold text-gray-900">Average Question Score:</span>
                <span class="text-2xl font-bold text-blue-600">{{ number_format(collect($responses)->avg(), 1) }}/5</span>
            </div>
        </div>
    </div>

    <!-- Comments -->
    @if($feedback->comments)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            Additional Comments
        </h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-700 whitespace-pre-line">{{ $feedback->comments }}</p>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.student-feedback.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
            Back to List
        </a>
        <form action="{{ route('admin.student-feedback.destroy', $feedback->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                Delete Feedback
            </button>
        </form>
    </div>
</div>
@endsection
