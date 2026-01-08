@extends('layouts.app')

@section('title', 'Thank You')
@section('page-title', 'Thank You!')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card text-center">
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100">
                <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Thank You!</h1>
        <p class="text-lg text-gray-600 mb-6">
            Your feedback has been submitted successfully. We greatly appreciate your time and valuable input.
        </p>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                <strong>Your feedback matters!</strong><br>
                Your responses will help us continuously improve our curriculum and educational programs to better serve students and meet industry needs.
            </p>
        </div>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('dashboard') }}" class="btn-primary">
                Go to Dashboard
            </a>
            <a href="{{ route('curriculum-feedback.create') }}" class="btn-secondary">
                Submit Another Response
            </a>
        </div>
    </div>
</div>
@endsection
