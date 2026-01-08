<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Already Submitted</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-3">
                Feedback Already Submitted
            </h1>

            <!-- Message -->
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Thank you! You have already submitted your feedback for this event.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-left">
                    <p class="text-sm text-blue-800">
                        <strong>Event Details:</strong>
                    </p>
                    <ul class="mt-2 text-sm text-blue-700 space-y-1">
                        <li><strong>Speaker:</strong> {{ $speaker->name }}</li>
                        <li><strong>Department:</strong> {{ $speaker->department }}</li>
                        <li><strong>Date:</strong> {{ $speaker->date->format('F d, Y') }}</li>
                    </ul>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">
                    <svg class="w-5 h-5 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Each feedback link can only be used once for security purposes.
                </p>
            </div>

            <!-- Appreciation Message -->
            <div class="border-t border-gray-200 pt-6">
                <p class="text-lg font-semibold text-indigo-600 mb-2">
                    We appreciate your valuable feedback!
                </p>
                <p class="text-sm text-gray-600">
                    Your input helps us improve our events and services.
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    If you believe this is an error or need to update your feedback, please contact the event coordinator.
                </p>
            </div>
        </div>

        <!-- Bottom Note -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">{{ config('app.name') }} © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
