@extends('layouts.app')

@section('title', 'Feedback on Curriculum')
@section('page-title', 'Feedback on Curriculum (Academic-Teacher-Industry)')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Feedback on Curriculum</h2>
                    <p class="mt-2 text-sm text-gray-600">(Academic-Teacher-Industry)</p>
                    <p class="mt-4 text-sm text-gray-500">
                        The feedback provided will be used for the quality improvement in the following Program/course.
                    </p>
                </div>

                <form method="POST" action="{{ route('forms.submit', $formName) }}" class="space-y-8" id="feedbackForm">
                    @csrf

                    <!-- Teacher Selection (for multi-teacher forms) -->
                    @if($allAssignments->count() > 1)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Subject and Teacher</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Subject Dropdown -->
                                <div>
                                    <label for="subject_select" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subject <span class="text-red-500">*</span>
                                    </label>
                                    <select id="subject_select" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">-- Select Subject --</option>
                                        @php
                                            $uniqueSubjects = $allAssignments->filter(function($assignment) {
                                                return $assignment->subject_id && $assignment->subject;
                                            })->unique('subject_id');
                                        @endphp
                                        @foreach($uniqueSubjects as $assignment)
                                            <option value="{{ $assignment->subject_id }}">
                                                {{ $assignment->subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Teacher Dropdown -->
                                <div>
                                    <label for="teacher_assignment_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teacher <span class="text-red-500">*</span>
                                    </label>
                                    <select name="teacher_assignment_id" id="teacher_assignment_id" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                            disabled>
                                        <option value="">-- Select Teacher --</option>
                                        @foreach($allAssignments as $teacherAssignment)
                                            <option value="{{ $teacherAssignment->id }}"
                                                    data-subject-id="{{ $teacherAssignment->subject_id }}"
                                                    data-status="{{ $teacherAssignment->status }}"
                                                    {{ $teacherAssignment->status === 'completed' ? 'disabled' : '' }}
                                                    class="teacher-option"
                                                    style="color: {{ $teacherAssignment->status === 'completed' ? '#059669' : '#dc2626' }}; font-weight: 500;">
                                                {{ $teacherAssignment->teacher->name ?? 'Unknown' }}
                                                @if($teacherAssignment->status === 'completed')
                                                    - ✓ Submitted
                                                @else
                                                    - ● Remaining
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('teacher_assignment_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const subjectSelect = document.getElementById('subject_select');
                            const teacherSelect = document.getElementById('teacher_assignment_id');
                            const allTeacherOptions = Array.from(teacherSelect.querySelectorAll('.teacher-option'));

                            subjectSelect.addEventListener('change', function() {
                                const selectedSubjectId = this.value;
                                
                                // Reset teacher dropdown
                                teacherSelect.value = '';
                                
                                if (!selectedSubjectId) {
                                    teacherSelect.disabled = true;
                                    allTeacherOptions.forEach(option => option.style.display = 'none');
                                    return;
                                }
                                
                                // Enable teacher dropdown
                                teacherSelect.disabled = false;
                                
                                // Filter teachers by subject
                                allTeacherOptions.forEach(option => {
                                    if (option.dataset.subjectId === selectedSubjectId) {
                                        option.style.display = 'block';
                                        // Auto-select first non-completed teacher
                                        if (!teacherSelect.value && option.dataset.status !== 'completed') {
                                            teacherSelect.value = option.value;
                                        }
                                    } else {
                                        option.style.display = 'none';
                                    }
                                });
                            });
                        });
                        </script>
                    @else
                        <!-- Hidden field for single teacher assignment -->
                        <input type="hidden" name="teacher_assignment_id" value="{{ $assignment->id }}">
                    @endif

                    <!-- Program and Course Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Program Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="program" class="block text-sm font-medium text-gray-700 mb-2">
                                    Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="responses[program]" id="program" value="B.Tech. (IT)" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 mb-2">
                                    Course (if applicable)
                                </label>
                                <input type="text" name="responses[course]" id="course"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Questions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Curriculum Evaluation</h3>
                        <p class="text-sm text-gray-600 mb-6">Please rate each aspect on a scale of 1-5. Please tick (√) where applicable.</p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 60px;">
                                            Sr. No.
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 40%;">
                                            Criteria
                                        </th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">
                                            Excellent<br><span class="font-bold">(5)</span>
                                        </th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">
                                            Very Good<br><span class="font-bold">(4)</span>
                                        </th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">
                                            Good<br><span class="font-bold">(3)</span>
                                        </th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">
                                            Satisfactory<br><span class="font-bold">(2)</span>
                                        </th>
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">
                                            Needs Improvement<br><span class="font-bold">(1)</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                    $questions = [
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
                                    ];
                                    @endphp
                                    
                                    @foreach($questions as $index => $question)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 text-sm font-medium text-gray-900 text-center">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $question['label'] }} <span class="text-red-500">*</span>
                                        </td>
                                        @for($i = 5; $i >= 1; $i--)
                                        <td class="px-2 py-4 text-center align-middle">
                                            <div class="flex items-center justify-center">
                                                <input type="radio" name="responses[{{ $question['field'] }}]" value="{{ $i }}" required
                                                       class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            </div>
                                        </td>
                                        @endfor
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Additional Suggestions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Suggestions and Remarks</h3>
                        <div>
                            <label for="additional_suggestions" class="block text-sm font-medium text-gray-700 mb-2">
                                Please share any additional suggestions or remarks, if any:
                            </label>
                            <textarea name="responses[additional_suggestions]" id="additional_suggestions" rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t">
                        <a href="{{ route('forms.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition text-sm">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-medium">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="h-20"></div>

<script>
// Add form submission confirmation
document.getElementById('feedbackForm')?.addEventListener('submit', function(e) {
    if (!confirm('Are you sure you want to submit this feedback? You cannot edit it after submission.')) {
        e.preventDefault();
    }
});
</script>
@endsection
