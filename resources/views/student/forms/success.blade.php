@extends('layouts.app')

@section('title', 'Feedback Submitted')
@section('page-title', 'Submission Successful')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full">
        <!-- Success Animation Container -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Success Icon with Animation -->
            <div class="bg-gradient-to-r from-green-400 to-emerald-500 px-8 py-12 text-center">
                <div class="inline-block">
                    <div class="relative">
                        <!-- Animated Circle Background -->
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center mx-auto animate-bounce-slow">
                            <svg class="w-20 h-20 text-green-500 animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <!-- Decorative Circles -->
                        <div class="absolute top-0 left-0 w-full h-full">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-white opacity-20 rounded-full animate-ping-slow"></div>
                        </div>
                    </div>
                </div>
                
                <h1 class="mt-8 text-4xl font-bold text-white">
                    Feedback Submitted Successfully!
                </h1>
                <p class="mt-4 text-lg text-green-50">
                    Thank you for taking the time to provide your valuable feedback.
                </p>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-10">
                <!-- Summary Information -->
                <div class="space-y-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Submission Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Form Name</p>
                                    <p class="text-gray-600">{{ $formTitle ?? 'Student Feedback Form' }}</p>
                                </div>
                            </div>
                            
                            @if(isset($teacherName) && $teacherName)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Teacher</p>
                                    <p class="text-gray-600">{{ $teacherName }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if(isset($subjectName) && $subjectName)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Subject</p>
                                    <p class="text-gray-600">{{ $subjectName }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Submitted At</p>
                                    <p class="text-gray-600">{{ now()->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Total Responses</p>
                                    <p class="text-gray-600">{{ $responseCount ?? 'N/A' }} questions answered</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-700">Status</p>
                                    <p class="text-green-600 font-semibold">Completed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notice -->
                    <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-amber-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-amber-800">
                                <p class="font-semibold mb-1">Important Note</p>
                                <p>Your responses have been recorded and cannot be edited. Your feedback will be kept confidential and used solely for improving the quality of education.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            What's Next?
                        </h3>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-start">
                                <span class="inline-block w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold">1</span>
                                <span>Check your dashboard for any remaining feedback forms</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold">2</span>
                                <span>You'll receive a confirmation email shortly</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0 text-xs font-bold">3</span>
                                <span>Thank you for helping us improve our courses!</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('dashboard') }}" 
                       class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Return to Dashboard
                    </a>
                    
                    <a href="{{ route('forms.index') }}" 
                       class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-white text-gray-700 font-semibold rounded-lg border-2 border-gray-300 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View All Forms
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes checkmark {
    0% {
        stroke-dasharray: 50;
        stroke-dashoffset: 50;
    }
    100% {
        stroke-dasharray: 50;
        stroke-dashoffset: 0;
    }
}

@keyframes ping-slow {
    75%, 100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

.animate-checkmark {
    animation: checkmark 0.6s ease-in-out forwards;
}

.animate-ping-slow {
    animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
@endsection
