@extends('layouts.app')

@section('title', 'Student Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Student Management</h1>
            <p class="text-gray-600 mt-1">Manage student enrollments and academic records</p>
        </div>
        <div class="flex items-center space-x-3">
            @can('create', App\Models\Student::class)
            @if(auth()->user()->hasRole(['Admin', 'TnP']))
            <a href="{{ route('students.import.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out shadow-sm inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Import Students
            </a>
            @endif
            <a href="{{ route('students.create') }}" class="btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Student
            </a>
            @endcan
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="label">Search</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Name, enrollment, email..."
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
            <label class="label">Placement Status</label>
            <select name="placement_status" class="input-field">
                <option value="">All Status</option>
                <option value="Not Placed" {{ request('placement_status') == 'Not Placed' ? 'selected' : '' }}>Not Placed</option>
                <option value="Placed" {{ request('placement_status') == 'Placed' ? 'selected' : '' }}>Placed</option>
                <option value="Pursuing Higher Studies" {{ request('placement_status') == 'Pursuing Higher Studies' ? 'selected' : '' }}>Higher Studies</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Filter</button>
            <a href="{{ route('students.index') }}" class="btn-secondary">Reset</a>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGPA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package (in LPA Only)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stipend</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $student->student_id ?? $student->roll_no }}</div>
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
                            @can('view', $student)
                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            @endcan
                            @can('update', $student)
                            <a href="{{ route('students.edit', $student) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @endcan
                            @can('delete', $student)
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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
@endsection
