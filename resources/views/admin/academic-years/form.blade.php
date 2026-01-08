@extends('layouts.app')

@section('title', 'Create Academic Year - SCFMS')
@section('page-title', {{ $academicYear ? 'Edit Academic Year' : 'Create Academic Year' }})

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('academic-years.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Academic Years
    </a>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($academicYear) ? 'Edit Academic Year' : 'Create New Academic Year' }}
        </h2>

        <form action="{{ isset($academicYear) ? route('academic-years.update', $academicYear['id']) : route('academic-years.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($academicYear))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Year Input -->
                <div>
                    <label class="label required">Academic Year</label>
                    <input type="text" name="year" value="{{ old('year', $academicYear['year'] ?? '') }}" placeholder="e.g., 2024-25" class="input-field" required>
                    @error('year')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-gray-500 text-xs mt-2">Format: YYYY-YY (e.g., 2024-25)</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="label required">Status</label>
                    <select name="status" class="input-field" required>
                        <option value="">Select Status</option>
                        <option value="upcoming" {{ old('status', $academicYear['status'] ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="active" {{ old('status', $academicYear['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $academicYear['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Start Date -->
                <div>
                    <label class="label required">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $academicYear['start_date'] ?? '') }}" class="input-field" required>
                    @error('start_date')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="label required">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $academicYear['end_date'] ?? '') }}" class="input-field" required>
                    @error('end_date')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="label">Description (Optional)</label>
                <textarea name="description" rows="4" class="input-field" placeholder="Add any notes about this academic year...">{{ old('description', $academicYear['description'] ?? '') }}</textarea>
                @error('description')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Academic Year Guidelines</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Only one academic year can be active at a time</li>
                        <li>Start and end dates define the academic period</li>
                        <li>Semesters are created within academic years</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($academicYear) ? 'Update' : 'Create' }} Academic Year
                </button>
                <a href="{{ route('academic-years.index') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
