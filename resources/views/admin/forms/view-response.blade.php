@extends('layouts.app')

@section('title', 'Response Details')
@section('page-title', 'Form Response Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <button onclick="history.back()" class="btn-secondary inline-flex items-center hover:shadow-md transition-shadow">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Responses
        </button>
    </div>

    <!-- Student Information Card -->
    <div class="card mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 shadow-md">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-blue-500 rounded-lg mr-3">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Student Information</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Student Name</p>
                    <p class="text-base font-bold text-gray-900">{{ $response->student->user->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                <div class="p-2 bg-green-100 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Student ID</p>
                    <p class="text-base font-bold text-gray-900">{{ $response->student->student_id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                <div class="p-2 bg-purple-100 rounded-full">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Email</p>
                    <p class="text-base font-bold text-gray-900">{{ $response->email }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                <div class="p-2 bg-orange-100 rounded-full">
                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Submitted On</p>
                    <p class="text-base font-bold text-gray-900">{{ $response->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Information Card -->
    <div class="card mb-6 bg-gradient-to-br from-green-50 to-teal-50 border-2 border-green-200 shadow-md">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-green-500 rounded-lg mr-3">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Assignment Information</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                <div class="p-2 bg-teal-100 rounded-full">
                    <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Subject</p>
                    <p class="text-base font-bold text-gray-900">{{ $response->formAssignment->subject->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                <div class="p-2 bg-indigo-100 rounded-full">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Teacher</p>
                    <p class="text-base font-bold text-gray-900">{{ $response->formAssignment->teacher->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Program and Course Information -->
    @if(isset($response->responses['program']) || isset($response->responses['course']))
        <div class="card mb-6 bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 shadow-md">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-purple-500 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Program Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(isset($response->responses['program']))
                    <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                        <div class="p-2 bg-purple-100 rounded-full">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Program</p>
                            <p class="text-base font-bold text-gray-900">{{ $response->responses['program'] }}</p>
                        </div>
                    </div>
                @endif
                @if(isset($response->responses['course']))
                    <div class="flex items-center space-x-3 bg-white rounded-lg p-3 shadow-sm">
                        <div class="p-2 bg-pink-100 rounded-full">
                            <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Course</p>
                            <p class="text-base font-bold text-gray-900">{{ $response->responses['course'] }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Feedback Responses -->
    <div class="card shadow-lg">
        <div class="flex items-center mb-6">
            <div class="p-3 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-lg mr-3 shadow-md">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Feedback Responses</h3>
        </div>
        
        @php
            $questions = [
                'content_of_syllabus' => 'The content of syllabus is adequate with respect to the desired learning outcomes',
                'need_based_approach' => 'The curriculum is planned with need based approach',
                'curriculum_updated' => 'The curriculum is updated regularly with integration of contemporary concepts',
                'curriculum_supports' => 'The curriculum supports to meet the objectives and achievement of specified Programme Educational Objectives (PEO), Programme Outcomes (PO) and Programme Specific Outcomes (PSO)',
                'curriculum_fosters' => 'The curriculum fosters higher order thinking in experiential learning',
                'curriculum_provides' => 'The curriculum provides adequate information about effective communication and soft skills',
                'curriculum_integration' => 'The curriculum integration with academic research, industrial consultancy and practices',
                'curriculum_prepares' => 'The curriculum prepares the student for local, national and international opportunities',
                'curriculum_social' => 'The curriculum has considerations of social, cultural, economic, health, Safety and Environment sustainability',
                'curriculum_innovative' => 'The curriculum fosters innovative attitude for developing business'
            ];
            
            $ratingLabels = [
                1 => 'Poor',
                2 => 'Below Average',
                3 => 'Average',
                4 => 'Good',
                5 => 'Excellent'
            ];
        @endphp

        <div class="space-y-3">
            @foreach($questions as $key => $question)
                @if(isset($response->responses[$key]))
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 border-l-4 border-blue-400 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <p class="text-sm font-medium text-gray-700 mb-3">{{ $question }}</p>
                        <div class="flex items-center justify-between">
                            @php
                                $rating = $response->responses[$key];
                            @endphp
                            <div class="flex items-center space-x-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-7 h-7 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-base font-bold text-gray-900 bg-white px-3 py-1 rounded-full shadow-sm">{{ $rating }}/5</span>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-sm font-semibold shadow-sm
                                {{ $rating == 5 ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                                {{ $rating == 4 ? 'bg-blue-100 text-blue-700 border border-blue-300' : '' }}
                                {{ $rating == 3 ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}
                                {{ $rating == 2 ? 'bg-orange-100 text-orange-700 border border-orange-300' : '' }}
                                {{ $rating == 1 ? 'bg-red-100 text-red-700 border border-red-300' : '' }}">
                                {{ $ratingLabels[$rating] ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Additional Suggestions -->
        @if(isset($response->responses['suggestions']) && !empty($response->responses['suggestions']))
            <div class="mt-6 pt-6 border-t-2 border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Additional Suggestions</h4>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-lg p-4 shadow-sm">
                    <p class="text-gray-700 italic leading-relaxed">{{ $response->responses['suggestions'] }}</p>
                </div>
            </div>
        @endif

        <!-- Show raw responses if they don't match the known structure -->
        @php
            $knownKeys = array_merge(array_keys($questions), ['program', 'course', 'suggestions']);
            $unknownKeys = array_diff(array_keys($response->responses), $knownKeys);
        @endphp
        
        @if(count($unknownKeys) > 0)
            <div class="mt-6 pt-6 border-t-2 border-gray-200">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-teal-100 rounded-lg mr-2">
                        <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Other Responses</h4>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-200 shadow-sm">
                    @foreach($unknownKeys as $key)
                        <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-2 h-2 bg-teal-500 rounded-full flex-shrink-0"></div>
                                <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                            </div>
                            <div class="text-right ml-4">
                                @if(is_array($response->responses[$key]))
                                    @if(isset($response->responses[$key]['rating']))
                                        <span class="text-base font-bold text-gray-900">{{ $response->responses[$key]['rating'] }}</span>
                                        @if(isset($response->responses[$key]['reasoning']) && !empty($response->responses[$key]['reasoning']))
                                            <p class="text-sm text-gray-600 italic mt-1">Reasoning: {{ $response->responses[$key]['reasoning'] }}</p>
                                        @endif
                                    @else
                                        <span class="text-base font-bold text-gray-900">{{ json_encode($response->responses[$key]) }}</span>
                                    @endif
                                @else
                                    <span class="text-base font-bold text-gray-900">{{ $response->responses[$key] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
