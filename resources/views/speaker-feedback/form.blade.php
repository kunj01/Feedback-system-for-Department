<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speaker Feedback Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .rating-stars input[type="radio"] {
            display: none;
        }
        .rating-stars label {
            cursor: pointer;
            font-size: 2rem;
            color: #d1d5db;
            transition: color 0.2s;
        }
        .rating-stars input[type="radio"]:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #fbbf24;
        }
        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-t-2xl shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">📝</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Event Feedback Form</h1>
            <p class="text-gray-600">We value your feedback to improve our events</p>
        </div>

        <!-- Event Details -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Event Details
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-indigo-200 text-sm">Speaker</p>
                    <p class="font-semibold">{{ $speaker->name }}</p>
                </div>
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-indigo-200 text-sm">Department</p>
                    <p class="font-semibold">{{ $speaker->department }}</p>
                </div>
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-indigo-200 text-sm">Venue</p>
                    <p class="font-semibold">{{ $speaker->venue }}</p>
                </div>
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-indigo-200 text-sm">Date & Time</p>
                    <p class="font-semibold">{{ $speaker->date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Feedback Form -->
        <div class="bg-white rounded-b-2xl shadow-lg p-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('speaker.feedback.store', $speaker->feedback_token) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Event Quality -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> How would you rate the overall quality of the event?
                    </label>
                    <textarea name="event_quality" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Please share your thoughts about the event organization, schedule, and coordination...">{{ old('event_quality') }}</textarea>
                    @error('event_quality')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Venue Facilities -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> How were the venue facilities (audio/visual equipment, seating, etc.)?
                    </label>
                    <textarea name="venue_facilities" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Share your experience with the venue, technical setup, and facilities...">{{ old('venue_facilities') }}</textarea>
                    @error('venue_facilities')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hospitality -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> How was the hospitality provided by the department?
                    </label>
                    <textarea name="hospitality" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Tell us about the reception, refreshments, and assistance provided...">{{ old('hospitality') }}</textarea>
                    @error('hospitality')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Overall Experience -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="text-red-500">*</span> What was your overall experience as a speaker at our institution?
                    </label>
                    <textarea name="overall_experience" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Share your overall experience and impression of the event...">{{ old('overall_experience') }}</textarea>
                    @error('overall_experience')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Suggestions -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Any suggestions for improvement?
                    </label>
                    <textarea name="suggestions" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Share any suggestions or recommendations for future events...">{{ old('suggestions') }}</textarea>
                    @error('suggestions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">
                        <span class="text-red-500">*</span> Overall Rating (1-5 Stars)
                    </label>
                    <div class="rating-stars">
                        <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == '5' ? 'checked' : '' }} required/>
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == '4' ? 'checked' : '' }}/>
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == '3' ? 'checked' : '' }}/>
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == '2' ? 'checked' : '' }}/>
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == '1' ? 'checked' : '' }}/>
                        <label for="star1">★</label>
                    </div>
                    @error('rating')
                        <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-4 px-6 rounded-lg hover:from-indigo-700 hover:to-purple-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        Submit Feedback
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 mt-4">
                    <span class="text-red-500">*</span> Required fields
                </p>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">Thank you for taking the time to provide your valuable feedback!</p>
            <p class="text-xs mt-2 text-gray-500">{{ config('app.name') }} © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
