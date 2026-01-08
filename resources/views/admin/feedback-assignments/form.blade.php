@extends('layouts.app')

@section('title', 'Create Feedback Assignment - SCFMS')
@section('page-title', {{ isset($assignment) ? 'Edit Feedback Assignment' : 'Create Feedback Assignment' }})

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('feedback-assignments.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Assignments
    </a>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($assignment) ? 'Edit Feedback Assignment' : 'Create New Feedback Assignment' }}
        </h2>

        <form action="{{ isset($assignment) ? route('feedback-assignments.update', $assignment['id']) : route('feedback-assignments.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($assignment))
                @method('PUT')
            @endif

            <!-- Template & Course Selection -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Assignment Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label required">Feedback Template</label>
                        <select name="template_id" class="input-field" required>
                            <option value="">Select Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template['id'] }}" {{ old('template_id', $assignment['template_id'] ?? '') == $template['id'] ? 'selected' : '' }}>
                                    {{ $template['name'] }} ({{ $template['target_type'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label required">Course</label>
                        <select name="course_id" class="input-field" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course['id'] }}" {{ old('course_id', $assignment['course_id'] ?? '') == $course['id'] ? 'selected' : '' }}>
                                    {{ $course['code'] }} - {{ $course['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Feedback Period -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Feedback Period</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="label required">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $assignment['start_date'] ?? '') }}" class="input-field" required min="{{ date('Y-m-d') }}">
                        @error('start_date')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label required">Start Time</label>
                        <input type="time" name="start_time" value="{{ old('start_time', $assignment['start_time'] ?? '00:00') }}" class="input-field" required>
                        @error('start_time')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label required">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $assignment['end_date'] ?? '') }}" class="input-field" required min="{{ date('Y-m-d') }}">
                        @error('end_date')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label required">End Time</label>
                        <input type="time" name="end_time" value="{{ old('end_time', $assignment['end_time'] ?? '23:59') }}" class="input-field" required>
                        @error('end_time')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Grace Period -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Grace Period (Optional)</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="label">Grace Period Days</label>
                        <input type="number" name="grace_period_days" value="{{ old('grace_period_days', $assignment['grace_period_days'] ?? 0) }}" min="0" max="7" class="input-field" placeholder="0">
                        @error('grace_period_days')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        <p class="text-gray-500 text-xs mt-2">Allow students to submit feedback after deadline (0-7 days)</p>
                    </div>

                    <div>
                        <div class="flex items-center h-full">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="send_reminders" value="1" {{ old('send_reminders', $assignment['send_reminders'] ?? 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                                <span class="ml-3 text-sm font-medium text-gray-700">Send Reminders</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Notifications -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Email Notifications</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="notify_on_start" value="1" {{ old('notify_on_start', $assignment['notify_on_start'] ?? 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                        <span class="ml-3 text-sm font-medium text-gray-700">Notify when period starts</span>
                    </label>

                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="remind_3days" value="1" {{ old('remind_3days', $assignment['remind_3days'] ?? 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                        <span class="ml-3 text-sm font-medium text-gray-700">Reminder 3 days before</span>
                    </label>

                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="remind_1day" value="1" {{ old('remind_1day', $assignment['remind_1day'] ?? 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                        <span class="ml-3 text-sm font-medium text-gray-700">Reminder 1 day before</span>
                    </label>

                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="remind_2hours" value="1" {{ old('remind_2hours', $assignment['remind_2hours'] ?? 1) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded">
                        <span class="ml-3 text-sm font-medium text-gray-700">Reminder 2 hours before</span>
                    </label>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Assignment Guidelines</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Start date must be in the future</li>
                        <li>End date must be after start date</li>
                        <li>Students will receive notifications based on your selections</li>
                        <li>Grace period allows late submissions without penalties</li>
                        <li>Response rate will be tracked automatically</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($assignment) ? 'Update' : 'Create' }} Assignment
                </button>
                <a href="{{ route('feedback-assignments.index') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
