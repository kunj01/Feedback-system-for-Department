<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback on Curriculum (Academic-Teacher-Industry)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Feedback on Curriculum (Academic-Teacher-Industry)</h1>
            
            <!-- Event Details -->
            <div class="bg-blue-50 rounded-lg p-4 grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Speaker Name:</p>
                    <p class="font-semibold">{{ $speaker->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Department:</p>
                    <p class="font-semibold">{{ $speaker->department }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Venue:</p>
                    <p class="font-semibold">{{ $speaker->venue }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Date & Time:</p>
                    <p class="font-semibold">{{ $speaker->date->format('l, F d, Y') }} at {{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Feedback Request -->
        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-800">
                📝 <strong>Feedback Request:</strong> After the event, we kindly request you to provide your valuable feedback about the event organization, venue facilities, hospitality, and overall experience. Your feedback will help us improve future events.
            </p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <h3 class="font-semibold text-red-800 mb-2">Please correct the following errors:</h3>
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Feedback Form -->
        <form action="{{ route('speaker.feedback.store', $temporaryLink->token) }}" method="POST" class="bg-white rounded-lg shadow-md overflow-hidden">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="text-left p-4 font-semibold text-gray-700">NO.</th>
                            <th class="text-left p-4 font-semibold text-gray-700">PARAMETERS</th>
                            <th class="text-center p-4 font-semibold text-gray-700">Excellent<br>(5)</th>
                            <th class="text-center p-4 font-semibold text-gray-700">Very Good<br>(4)</th>
                            <th class="text-center p-4 font-semibold text-gray-700">Good<br>(3)</th>
                            <th class="text-center p-4 font-semibold text-gray-700">Average<br>(2)</th>
                            <th class="text-center p-4 font-semibold text-gray-700">Poor<br>(1)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Question 1 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">1</td>
                            <td class="p-4">Content of syllabus <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q1_content_of_syllabus" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q1_content_of_syllabus" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q1_content_of_syllabus" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q1_content_of_syllabus" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q1_content_of_syllabus" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 2 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">2</td>
                            <td class="p-4">Relevance of syllabus to industry/research requirements <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q2_relevance_to_industry" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q2_relevance_to_industry" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q2_relevance_to_industry" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q2_relevance_to_industry" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q2_relevance_to_industry" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 3 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">3</td>
                            <td class="p-4">Course outcomes are well defined <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q3_course_outcomes" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q3_course_outcomes" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q3_course_outcomes" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q3_course_outcomes" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q3_course_outcomes" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 4 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">4</td>
                            <td class="p-4">Sufficient reading materials and digital resources provided <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q4_reading_materials" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q4_reading_materials" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q4_reading_materials" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q4_reading_materials" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q4_reading_materials" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 5 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">5</td>
                            <td class="p-4">Incorporation of advanced topics <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q5_advanced_topics" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q5_advanced_topics" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q5_advanced_topics" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q5_advanced_topics" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q5_advanced_topics" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 6 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">6</td>
                            <td class="p-4">Pedagogy proposed <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q6_pedagogy" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q6_pedagogy" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q6_pedagogy" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q6_pedagogy" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q6_pedagogy" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 7 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">7</td>
                            <td class="p-4">Have a desired balance between theory and practical <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q7_theory_practical_balance" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q7_theory_practical_balance" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q7_theory_practical_balance" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q7_theory_practical_balance" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q7_theory_practical_balance" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 8 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">8</td>
                            <td class="p-4">Assessment methods are fair, measuring the outcomes <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q8_assessment_methods" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q8_assessment_methods" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q8_assessment_methods" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q8_assessment_methods" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q8_assessment_methods" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 9 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">9</td>
                            <td class="p-4">Project component in the course, if applicable <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q9_project_component" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q9_project_component" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q9_project_component" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q9_project_component" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q9_project_component" value="1" required class="w-5 h-5"></td>
                        </tr>

                        <!-- Question 10 -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-medium">10</td>
                            <td class="p-4">Industrial training/practical exposure in the course, if applicable <span class="text-red-500">*</span></td>
                            <td class="text-center p-4"><input type="radio" name="q10_industrial_training" value="5" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q10_industrial_training" value="4" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q10_industrial_training" value="3" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q10_industrial_training" value="2" required class="w-5 h-5"></td>
                            <td class="text-center p-4"><input type="radio" name="q10_industrial_training" value="1" required class="w-5 h-5"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Additional Comments -->
            <div class="p-6 border-t">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Additional Comments (Optional)
                </label>
                <textarea name="additional_comments" rows="4" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Please share any additional feedback or suggestions...">{{ old('additional_comments') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="p-6 bg-gray-50 border-t flex justify-center">
                <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Click Here to Provide Feedback
                </button>
            </div>
        </form>

        <!-- Note -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800 italic">
                <strong>Note:</strong> This is a unique link generated specifically for you. Please use it to submit your feedback after the event.
            </p>
            <p class="text-sm text-blue-800 mt-2">
                We look forward to your session and thank you in advance for taking the time to share your feedback!
            </p>
            <p class="text-sm text-blue-800 mt-2 font-semibold">
                Best regards
            </p>
        </div>
    </div>
</body>
</html>
