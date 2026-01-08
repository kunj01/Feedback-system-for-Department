@extends('layouts.app')

@section('title', 'Create Semester - SCFMS')
@section('page-title', {{ isset($semester) ? 'Edit Semester' : 'Create Semester' }})

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('semesters.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Semesters
    </a>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($semester) ? 'Edit Semester' : 'Create New Semester' }}
        </h2>

        <form action="{{ isset($semester) ? route('semesters.update', $semester['id']) : route('semesters.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($semester))
                @method('PUT')
            @endif

            <!-- Academic Year Selection -->
            <div>
                <label class="label required">Academic Year</label>
                <select name="academic_year_id" class="input-field" required>
                    <option value="">Select Academic Year</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year['id'] }}" {{ old('academic_year_id', $semester['academic_year_id'] ?? '') == $year['id'] ? 'selected' : '' }}>
                            {{ $year['year'] }}
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Semester Number -->
                <div>
                    <label class="label required">Semester Number</label>
                    <select name="semester_number" class="input-field" required onchange="updateSemesterName()">
                        <option value="">Select Semester</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester_number', $semester['semester_number'] ?? '') == $i ? 'selected' : '' }}>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('semester_number')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Semester Name -->
                <div>
                    <label class="label required">Semester Name</label>
                    <input type="text" name="name" value="{{ old('name', $semester['name'] ?? '') }}" placeholder="e.g., Semester 1 (Odd)" class="input-field" required>
                    @error('name')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Start Date -->
                <div>
                    <label class="label required">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $semester['start_date'] ?? '') }}" class="input-field" required>
                    @error('start_date')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="label required">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $semester['end_date'] ?? '') }}" class="input-field" required>
                    @error('end_date')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="label required">Status</label>
                <select name="status" class="input-field" required>
                    <option value="">Select Status</option>
                    <option value="upcoming" {{ old('status', $semester['status'] ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="active" {{ old('status', $semester['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ old('status', $semester['status'] ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-gray-500 text-xs mt-2">Only one semester can be active at a time</p>
            </div>

            <!-- Notes -->
            <div>
                <label class="label">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="input-field" placeholder="Add any additional notes...">{{ old('notes', $semester['notes'] ?? '') }}</textarea>
                @error('notes')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Semester Guidelines</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Semesters are created within academic years</li>
                        <li>Only one semester can be active at a time per academic year</li>
                        <li>Odd semesters: 1, 3, 5, 7 | Even semesters: 2, 4, 6, 8</li>
                        <li>Courses are assigned to specific semesters</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($semester) ? 'Update' : 'Create' }} Semester
                </button>
                <a href="{{ route('semesters.index') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateSemesterName() {
    const select = document.querySelector('select[name="semester_number"]');
    const input = document.querySelector('input[name="name"]');
    const number = select.value;
    if (number) {
        const isOdd = number % 2 === 1;
        input.value = 'Semester ' + number + ' (' + (isOdd ? 'Odd' : 'Even') + ')';
    }
}
</script>
@endsection
