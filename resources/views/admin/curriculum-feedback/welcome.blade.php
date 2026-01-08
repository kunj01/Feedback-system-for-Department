@extends('layouts.app')

@section('title', 'Curriculum Feedback Options')
@section('page-title', 'Provide Curriculum Feedback')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Feedback on Curriculum</h1>
        <p class="text-gray-600">
            Please select your role to provide feedback on our curriculum. Your input is valuable for continuous improvement.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Academic Option -->
        <a href="{{ route('curriculum-feedback.create', ['type' => 'academic']) }}" 
           class="card hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer group">
            <div class="text-center">
                <div class="mx-auto mb-4 w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Academic</h3>
                <p class="text-sm text-gray-600 mb-4">
                    For academicians, researchers, and academic professionals
                </p>
                <span class="inline-flex items-center text-blue-600 font-semibold group-hover:text-blue-700">
                    Start Feedback
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>

        <!-- Teacher Option -->
        <a href="{{ route('curriculum-feedback.create', ['type' => 'teacher']) }}" 
           class="card hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer group">
            <div class="text-center">
                <div class="mx-auto mb-4 w-20 h-20 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Teacher</h3>
                <p class="text-sm text-gray-600 mb-4">
                    For teachers, faculty members, and educators
                </p>
                <span class="inline-flex items-center text-green-600 font-semibold group-hover:text-green-700">
                    Start Feedback
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>

        <!-- Industry Option -->
        <a href="{{ route('curriculum-feedback.create', ['type' => 'industry']) }}" 
           class="card hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer group">
            <div class="text-center">
                <div class="mx-auto mb-4 w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Industry Professional</h3>
                <p class="text-sm text-gray-600 mb-4">
                    For industry experts, employers, and professionals
                </p>
                <span class="inline-flex items-center text-purple-600 font-semibold group-hover:text-purple-700">
                    Start Feedback
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>
    </div>

    <!-- Additional Information -->
    <div class="card mt-8 bg-blue-50 border border-blue-200">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-blue-900 mb-2">Important Information</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Your feedback is valuable and will be used to improve our curriculum</li>
                    <li>• The survey takes approximately 10-15 minutes to complete</li>
                    <li>• All responses are anonymous unless you choose to provide your contact information</li>
                    <li>• Please provide honest and constructive feedback</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
