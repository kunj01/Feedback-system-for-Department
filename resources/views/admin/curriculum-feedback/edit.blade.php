@extends('layouts.app')

@section('title', 'Edit Curriculum Feedback')
@section('page-title', 'Edit Curriculum Feedback')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="card mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Feedback Response</h1>
                <p class="text-sm text-gray-500 mt-1">Response ID: #{{ $feedback->id }}</p>
            </div>
            <a href="{{ route('curriculum-feedback.show', $feedback) }}" class="btn-secondary">
                Cancel
            </a>
        </div>
    </div>

    <form action="{{ route('curriculum-feedback.update', $feedback) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="respondent_type" value="{{ $feedback->respondent_type }}">

        <!-- Respondent Information -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-3">1</span>
                Respondent Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $feedback->name) }}" 
                           class="input-field @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $feedback->designation) }}" 
                           class="input-field @error('designation') border-red-500 @enderror">
                    @error('designation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Organization</label>
                    <input type="text" name="organization" value="{{ old('organization', $feedback->organization) }}" 
                           class="input-field @error('organization') border-red-500 @enderror">
                    @error('organization')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $feedback->email) }}" 
                           class="input-field @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $feedback->phone) }}" 
                           class="input-field @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Department</label>
                    <input type="text" name="department" value="{{ old('department', $feedback->department) }}" 
                           class="input-field @error('department') border-red-500 @enderror">
                    @error('department')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Academic Year</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $feedback->academic_year) }}" 
                           class="input-field @error('academic_year') border-red-500 @enderror">
                    @error('academic_year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Program</label>
                    <input type="text" name="program" value="{{ old('program', $feedback->program) }}" 
                           class="input-field @error('program') border-red-500 @enderror">
                    @error('program')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Status</label>
                    <select name="status" class="input-field">
                        <option value="submitted" {{ old('status', $feedback->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="reviewed" {{ old('status', $feedback->status) === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    </select>
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
            
            $curriculumQuestions = [
                'curriculum_relevance' => 'Relevance of curriculum to industry/academic needs',
                'curriculum_breadth' => 'Breadth and depth of curriculum content',
                'curriculum_integration' => 'Integration of crosscutting issues',
                'curriculum_flexibility' => 'Flexibility and choice in curriculum',
                'curriculum_outcomes' => 'Learning outcomes alignment',
            ];
            
            $teachingQuestions = [
                'teaching_pedagogy' => 'Teaching pedagogy effectiveness',
                'teaching_assessment' => 'Assessment methods',
                'teaching_practical' => 'Practical exposure',
                'teaching_innovation' => 'Innovation and creativity',
                'teaching_technology' => 'Technology integration',
            ];
            
            $infraQuestions = [
                'infra_library' => 'Library resources',
                'infra_labs' => 'Laboratory facilities',
                'infra_technology' => 'Technology infrastructure',
                'infra_learning_spaces' => 'Learning spaces',
            ];
            
            $industryQuestions = [
                'industry_skills' => 'Skill development',
                'industry_employability' => 'Employability',
                'industry_practical' => 'Practical knowledge',
                'industry_soft_skills' => 'Soft skills',
                'industry_ethics' => 'Professional ethics',
            ];
        @endphp

        <!-- Curriculum Aspects -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-green-600 text-white px-3 py-1 rounded-md text-sm mr-3">2</span>
                Curriculum Aspects
            </h3>
            @foreach($curriculumQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field, $feedback->$field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Teaching-Learning Process -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-purple-600 text-white px-3 py-1 rounded-md text-sm mr-3">3</span>
                Teaching-Learning Process
            </h3>
            @foreach($teachingQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field, $feedback->$field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Infrastructure -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-indigo-600 text-white px-3 py-1 rounded-md text-sm mr-3">4</span>
                Infrastructure and Resources
            </h3>
            @foreach($infraQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field, $feedback->$field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if($feedback->respondent_type === 'industry')
        <!-- Industry Readiness -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-orange-600 text-white px-3 py-1 rounded-md text-sm mr-3">5</span>
                Industry Readiness
            </h3>
            @foreach($industryQuestions as $field => $question)
                <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $loop->iteration }}. {{ $question }}</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ratingOptions as $value => $option)
                            <label class="flex-1 min-w-[120px] cursor-pointer">
                                <input type="radio" name="{{ $field }}" value="{{ $value }}" 
                                       class="peer hidden" {{ old($field, $feedback->$field) == $value ? 'checked' : '' }}>
                                <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                            peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                            hover:border-{{ $option['color'] }}-400">
                                    <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Overall Assessment -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2">
                <span class="bg-red-600 text-white px-3 py-1 rounded-md text-sm mr-3">{{ $feedback->respondent_type === 'industry' ? '6' : '5' }}</span>
                Overall Assessment
            </h3>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Overall Satisfaction</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($ratingOptions as $value => $option)
                        <label class="flex-1 min-w-[120px] cursor-pointer">
                            <input type="radio" name="overall_satisfaction" value="{{ $value }}" 
                                   class="peer hidden" {{ old('overall_satisfaction', $feedback->overall_satisfaction) == $value ? 'checked' : '' }}>
                            <div class="p-3 rounded-lg border-2 border-gray-300 text-center transition-all
                                        peer-checked:border-{{ $option['color'] }}-500 peer-checked:bg-{{ $option['color'] }}-50 
                                        hover:border-{{ $option['color'] }}-400">
                                <div class="font-bold text-lg text-gray-700">{{ $value }}</div>
                                <div class="text-xs text-gray-600">{{ $option['label'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="label">Strengths</label>
                    <textarea name="strengths" rows="3" class="input-field">{{ old('strengths', $feedback->strengths) }}</textarea>
                </div>

                <div>
                    <label class="label">Areas for Improvement</label>
                    <textarea name="improvements" rows="3" class="input-field">{{ old('improvements', $feedback->improvements) }}</textarea>
                </div>

                <div>
                    <label class="label">Suggestions</label>
                    <textarea name="suggestions" rows="3" class="input-field">{{ old('suggestions', $feedback->suggestions) }}</textarea>
                </div>

                <div>
                    <label class="label">Additional Comments</label>
                    <textarea name="additional_comments" rows="3" class="input-field">{{ old('additional_comments', $feedback->additional_comments) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('curriculum-feedback.show', $feedback) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Feedback
            </button>
        </div>
    </form>
</div>
@endsection
