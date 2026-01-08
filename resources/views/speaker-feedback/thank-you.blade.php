<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes checkmark {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        .checkmark {
            stroke-dasharray: 100;
            animation: checkmark 0.8s ease-in-out 0.3s forwards;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-blue-50 to-indigo-100 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full fade-in">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-green-500 to-teal-600 p-8 text-center text-white">
                <div class="mb-4">
                    <svg class="w-24 h-24 mx-auto" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="rgba(255,255,255,0.2)" />
                        <path class="checkmark" fill="none" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" d="M25 50 L40 65 L75 30" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Thank You!</h1>
                <p class="text-green-100 text-lg">Your feedback has been successfully submitted</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Success Message -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                        We Value Your Input
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Thank you for taking the time to share your valuable feedback about your experience at our institution. 
                        Your insights will help us improve our events and provide better experiences for future speakers.
                    </p>
                </div>

                <!-- What Happens Next -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        What Happens Next?
                    </h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Your feedback will be reviewed by our event management team</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Your suggestions will be used to improve future events</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>A confirmation has been recorded in our system</span>
                        </li>
                    </ul>
                </div>

                <!-- Appreciation Box -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 rounded-lg p-6 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-purple-900 mb-1">We Hope to See You Again!</h4>
                            <p class="text-purple-800">
                                We would love to have you as a speaker at future events. Thank you for contributing to our academic community!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="text-center border-t border-gray-200 pt-6">
                    <p class="text-sm text-gray-600 mb-2">
                        If you have any questions or additional comments, feel free to contact us.
                    </p>
                    <p class="text-xs text-gray-500">
                        You can now safely close this window.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>{{ config('app.name') }}</span>
                    <span>{{ date('Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Bottom Note -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                🎉 Your feedback makes a difference!
            </p>
        </div>
    </div>
</body>
</html>
