@extends('layouts.app')

@section('title', 'Curriculum Feedback')
@section('page-title', 'Feedback on Curriculum (Academic-Teacher-Industry)')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Feedback on Curriculum</h2>
                    <p class="mt-2 text-sm text-gray-600">(Academic-Teacher-Industry)</p>
                </div>

                <form method="POST" action="{{ route('curriculum-feedback.store') }}" class="space-y-8">
                    @csrf

                    <!-- Respondent Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Respondent Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="respondent_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Respondent Type <span class="text-red-500">*</span>
                                </label>
                                <select name="respondent_type" id="respondent_type" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    <option value="">Select Type</option>
                                    <option value="academician" {{ old('respondent_type') == 'academician' ? 'selected' : '' }}>Academician</option>
                                    <option value="teacher" {{ old('respondent_type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="industry" {{ old('respondent_type') == 'industry' ? 'selected' : '' }}>Industry Professional</option>
                                </select>
                                @error('respondent_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="institute" class="block text-sm font-medium text-gray-700 mb-2">
                                    Institute <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="institute" id="institute" value="{{ old('institute') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @error('institute')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    E-mail <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="program" class="block text-sm font-medium text-gray-700 mb-2">
                                    Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="program" id="program" value="{{ old('program', 'B.Tech. (IT)') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @error('program')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 mb-2">
                                    Course (if applicable)
                                </label>
                                <input type="text" name="course" id="course" value="{{ old('course') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @error('course')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Questions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Curriculum Evaluation</h3>
                        <p class="text-sm text-gray-600 mb-6">Please rate each aspect on a scale of 1-5</p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">
                                            Criterion
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Excellent<br>(5)
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Very Good<br>(4)
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Good<br>(3)
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Satisfactory<br>(2)
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Needs Improvement<br>(1)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach([
                                        ['field' => 'content_of_syllabus', 'label' => 'Content of syllabus'],
                                        ['field' => 'relevance_to_industry', 'label' => 'Relevance of syllabus to industry/research requirements'],
                                        ['field' => 'course_outcomes_defined', 'label' => 'Course outcomes are well defined'],
                                        ['field' => 'reading_materials_resources', 'label' => 'Sufficient reading materials and digital resources provided'],
                                        ['field' => 'advanced_topics', 'label' => 'Incorporation of advanced topics'],
                                        ['field' => 'pedagogy_proposed', 'label' => 'Pedagogy proposed'],
                                        ['field' => 'theory_practical_balance', 'label' => 'Have a desired balance between theory and practical'],
                                        ['field' => 'assessment_methods', 'label' => 'Assessment methods are fair, measuring the outcomes'],
                                        ['field' => 'project_component', 'label' => 'Project component in the course, if applicable'],
                                        ['field' => 'industrial_training', 'label' => 'Industrial training/practical exposure in the course, if applicable']
                                    ] as $question)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $question['label'] }} <span class="text-red-500">*</span>
                                        </td>
                                        @for($i = 5; $i >= 1; $i--)
                                        <td class="px-4 py-4 text-center">
                                            <input type="radio" name="{{ $question['field'] }}" value="{{ $i }}" 
                                                   {{ old($question['field']) == $i ? 'checked' : '' }} required
                                                   class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        </td>
                                        @endfor
                                    </tr>
                                    @error($question['field'])
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-sm text-red-600">{{ $message }}</td>
                                    </tr>
                                    @enderror
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Additional Suggestions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Suggestions</h3>
                        <div>
                            <label for="additional_suggestions" class="block text-sm font-medium text-gray-700 mb-2">
                                Please share any additional suggestions or remarks
                            </label>
                            <textarea name="additional_suggestions" id="additional_suggestions" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">{{ old('additional_suggestions') }}</textarea>
                            @error('additional_suggestions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('curriculum-feedback.index') }}" 
                           class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="card mb-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Feedback on Curriculum</h1>
            <p class="text-lg text-gray-600 mb-4">
                @if($respondentType === 'academic')
                    <span class="font-semibold text-blue-600">Academic Perspective</span>
                @elseif($respondentType === 'teacher')
                    <span class="font-semibold text-green-600">Teacher Perspective</span>
                @else
                    <span class="font-semibold text-purple-600">Industry Professional Perspective</span>
                @endif
            </p>
            <p class="text-sm text-gray-500">
                Please take a few moments to provide your valuable feedback on our curriculum.
                Your responses will help us improve the quality of education.
            </p>
        </div>
    </div>

    <form action="{{ route('curriculum-feedback.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="respondent_type" value="{{ $respondentType }}">

        <!-- Respondent Information -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-3">1</span>
                Respondent Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="input-field @error('name') border-red-500 @enderror" 
                           placeholder="Your full name">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation') }}" 
                           class="input-field @error('designation') border-red-500 @enderror" 
                           placeholder="Your designation/position">
                    @error('designation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Organization/Institution</label>
                    <input type="text" name="organization" value="{{ old('organization') }}" 
                           class="input-field @error('organization') border-red-500 @enderror" 
                           placeholder="Your organization name">
                    @error('organization')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="input-field @error('email') border-red-500 @enderror" 
                           placeholder="your.email@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="input-field @error('phone') border-red-500 @enderror" 
                           placeholder="+91 XXXXXXXXXX">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Department</label>
                    <input type="text" name="department" value="{{ old('department') }}" 
                           class="input-field @error('department') border-red-500 @enderror" 
                           placeholder="Department name">
                    @error('department')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Academic Year</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', '2024-25') }}" 
                           class="input-field @error('academic_year') border-red-500 @enderror" 
                           placeholder="e.g., 2024-25">
                    @error('academic_year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Program/Course</label>
                    <input type="text" name="program" value="{{ old('program') }}" 
                           class="input-field @error('program') border-red-500 @enderror" 
                           placeholder="e.g., B.Tech CSE">
                    @error('program')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        @php
            $ratingOptions = [
                5 => ['label' => 'Excellent', 'color' => 'emerald'],
                4 => ['label' => 'Very Good', 'color' => 'green'],
                3 => ['label' => 'Good', 'color' => 'yellow'],
                2 => ['label' => 'Fair', 'color' => 'orange'],
                1 => ['label' => 'Poor', 'color' => 'red'],
            ];
        @endphp

        <!-- Curriculum Aspects -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-green-600 text-white px-3 py-1 rounded-md text-sm mr-3">2</span>
                Curriculum Aspects
            </h3>

            @php
                $curriculumQuestions = [
                    'curriculum_relevance' => 'Relevance of curriculum to industry/academic needs and development',
                    'curriculum_breadth' => 'Breadth and depth of curriculum content',
                    'curriculum_integration' => 'Integration of crosscutting issues (ethics, sustainability, values)',
                    'curriculum_flexibility' => 'Flexibility and choice in curriculum',
                    'curriculum_outcomes' => 'Alignment with learning outcomes and career preparation',
                ];
            @endphp

            @foreach($curriculumQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400 hover:bg-{{ $option['color'] }}-25">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error($field)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <!-- Teaching-Learning Process -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-purple-600 text-white px-3 py-1 rounded-md text-sm mr-3">3</span>
                Teaching-Learning Process
            </h3>

            @php
                $teachingQuestions = [
                    'teaching_pedagogy' => 'Effectiveness of teaching pedagogy and methods',
                    'teaching_assessment' => 'Quality and fairness of assessment methods',
                    'teaching_practical' => 'Practical exposure and hands-on learning opportunities',
                    'teaching_innovation' => 'Innovation and creativity in teaching approaches',
                    'teaching_technology' => 'Integration of technology in teaching-learning',
                ];
            @endphp

            @foreach($teachingQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error($field)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <!-- Infrastructure and Resources -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-indigo-600 text-white px-3 py-1 rounded-md text-sm mr-3">4</span>
                Infrastructure and Resources
            </h3>

            @php
                $infraQuestions = [
                    'infra_library' => 'Library resources and learning materials',
                    'infra_labs' => 'Laboratory facilities and equipment',
                    'infra_technology' => 'Technology infrastructure (internet, software, hardware)',
                    'infra_learning_spaces' => 'Learning spaces and classroom facilities',
                ];
            @endphp

            @foreach($infraQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error($field)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <!-- Industry Readiness (for Industry respondents) -->
        @if($respondentType === 'industry')
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-orange-600 text-white px-3 py-1 rounded-md text-sm mr-3">5</span>
                Industry Readiness
            </h3>

            @php
                $industryQuestions = [
                    'industry_skills' => 'Skill development and technical competencies',
                    'industry_employability' => 'Employability and job readiness',
                    'industry_practical' => 'Practical knowledge and application skills',
                    'industry_soft_skills' => 'Soft skills and communication abilities',
                    'industry_ethics' => 'Professional ethics and work culture readiness',
                ];
            @endphp

            @foreach($industryQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error($field)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
        @endif

        <!-- Overall Assessment -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-red-600 text-white px-3 py-1 rounded-md text-sm mr-3">{{ $respondentType === 'industry' ? '6' : '5' }}</span>
                Overall Assessment
            </h3>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Overall Satisfaction with Curriculum</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($ratingOptions as $value => $option)
                        <label class="flex-1 min-w-[120px] cursor-pointer">
                            <input type="radio" name="overall_satisfaction" value="{{ $value }}" 
                                   class="peer hidden" {{ old('overall_satisfaction') == $value ? 'checked' : '' }}>
                            <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                        peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                        hover:border-{{ $option['color'] }}-400">
                                <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('overall_satisfaction')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-4">
                <div>
                    <label class="label">Strengths of the Curriculum</label>
                    <textarea name="strengths" rows="3" 
                              class="input-field @error('strengths') border-red-500 @enderror" 
                              placeholder="Please mention the key strengths...">{{ old('strengths') }}</textarea>
                    @error('strengths')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Areas for Improvement</label>
                    <textarea name="improvements" rows="3" 
                              class="input-field @error('improvements') border-red-500 @enderror" 
                              placeholder="Please suggest areas that need improvement...">{{ old('improvements') }}</textarea>
                    @error('improvements')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Suggestions for Enhancement</label>
                    <textarea name="suggestions" rows="3" 
                              class="input-field @error('suggestions') border-red-500 @enderror" 
                              placeholder="Your suggestions to enhance the curriculum...">{{ old('suggestions') }}</textarea>
                    @error('suggestions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Additional Comments</label>
                    <textarea name="additional_comments" rows="3" 
                              class="input-field @error('additional_comments') border-red-500 @enderror" 
                              placeholder="Any other comments or feedback...">{{ old('additional_comments') }}</textarea>
                    @error('additional_comments')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('dashboard') }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit Feedback
            </button>
        </div>
    </form>
</div>

<style>
    /* Custom styles for radio button transitions */
    .peer:checked ~ div {
        transform: scale(1.02);
    }
</style>
@endsection
