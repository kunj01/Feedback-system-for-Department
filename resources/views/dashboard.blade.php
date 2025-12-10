@extends('layouts.app')

@section('title', 'Dashboard - T&P System')
@section('page-title', 'Dashboard')

@section('content')
<!-- Student Listing Section -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Student Overview</h2>
        <a href="{{ route('students.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            View All Students →
        </a>
    </div>

    <!-- Advanced Filters -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="label">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Name, ID, email..."
                        class="input-field"
                    >
                </div>
                <div>
                    <label class="label">Department</label>
                    <select name="department" class="input-field">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Academic Year</label>
                    <select name="academic_year" class="input-field">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Batch</label>
                    <select name="batch" class="input-field">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                        <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>
                            {{ $batch }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="label">Placed Status</label>
                    <select name="placed" class="input-field">
                        <option value="">All</option>
                        <option value="yes" {{ request('placed') == 'yes' ? 'selected' : '' }}>Placed</option>
                        <option value="no" {{ request('placed') == 'no' ? 'selected' : '' }}>Not Placed</option>
                    </select>
                </div>
                <div>
                    <label class="label">Eligible</label>
                    <select name="eligible" class="input-field">
                        <option value="">All</option>
                        <option value="1" {{ request('eligible') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('eligible') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div>
                    <label class="label">Training Status</label>
                    <select name="training_status" class="input-field">
                        <option value="">All</option>
                        <option value="NOT_ASSIGNED" {{ request('training_status') == 'NOT_ASSIGNED' ? 'selected' : '' }}>Not Assigned</option>
                        <option value="IN_TRAINING" {{ request('training_status') == 'IN_TRAINING' ? 'selected' : '' }}>In Training</option>
                        <option value="COMPLETED" {{ request('training_status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="label">Project Guide</label>
                    <select name="guide" class="input-field">
                        <option value="">All Guides</option>
                        @foreach($guides as $guide)
                        <option value="{{ $guide->id }}" {{ request('guide') == $guide->id ? 'selected' : '' }}>
                            {{ $guide->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Min CGPA</label>
                    <input
                        type="number"
                        name="min_cgpa"
                        value="{{ request('min_cgpa') }}"
                        placeholder="e.g., 7.0"
                        step="0.1"
                        min="0"
                        max="10"
                        class="input-field"
                    >
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-200">
                <button type="submit" class="btn-primary">Apply Filters</button>
                <a href="{{ route('dashboard') }}" class="btn-secondary">Reset</a>
                <div class="ml-auto text-sm text-gray-600">
                    Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID NO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGPA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package (LPA)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stipend</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $student->student_id ?? $student->roll_no ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($student->first_name ?? $student->user->name ?? 'NA', 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $student->first_name ? ($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name) : ($student->user->name ?? 'N/A') }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $student->personal_email ?? $student->user->personal_email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->department->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($student->btech_cgpa_upto_5th || $student->cgpa)
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @php $cgpa = $student->btech_cgpa_upto_5th ?? $student->cgpa; @endphp
                                @if($cgpa >= 8) bg-green-100 text-green-800
                                @elseif($cgpa >= 6) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ number_format($cgpa, 2) }}
                            </span>
                            @else
                            <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $placement = $student->placements()->with('company')->latest()->first();
                                $isPlaced = $placement && $placement->status == 'OFFERED';
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $isPlaced ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $isPlaced ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $placement && $placement->company ? $placement->company->name : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $placement && $placement->package ? number_format($placement->package, 2) : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $placement && $placement->stipend ? '₹ ' . number_format($placement->stipend, 0) : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                            <p class="mt-2">No students found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
