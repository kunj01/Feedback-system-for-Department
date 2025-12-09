@extends('layouts.app')

@section('title', 'Department Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('departments.index') }}" class="hover:text-blue-600">Departments</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Department Details</span>
    </div>
    <div class="flex justify-between items-start">
        <h1 class="text-3xl font-bold text-gray-800">{{ $department->name }}</h1>
        @can('update', $department)
        <a href="{{ route('departments.edit', $department) }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Department
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="card">
            <div class="flex items-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                    {{ strtoupper(substr($department->code, 0, 2)) }}
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $department->name }}</h2>
                    <p class="text-gray-600">Code: {{ $department->code }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Department Head</p>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $department->head->name ?? 'Not Assigned' }}
                    </p>
                    @if($department->head)
                    <p class="text-sm text-gray-500">{{ $department->head->email }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Students</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $department->students->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Created</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $department->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $department->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                Students ({{ $department->students->count() }})
            </h3>

            @if($department->students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrollment</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year/Sem</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CGPA</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($department->students->take(10) as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $student->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $student->enrollment_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $student->academic_year }}<br>
                                <span class="text-xs">Sem {{ $student->semester }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($student->cgpa)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($student->cgpa >= 8) bg-green-100 text-green-800
                                    @elseif($student->cgpa >= 6) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ number_format($student->cgpa, 2) }}
                                </span>
                                @else
                                <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($department->students->count() > 10)
                <div class="px-4 py-3 bg-gray-50 text-sm text-gray-500 text-center">
                    + {{ $department->students->count() - 10 }} more students
                </div>
                @endif
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No students enrolled yet</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">Quick Actions</h3>
            <div class="space-y-2">
                @can('update', $department)
                <a href="{{ route('departments.edit', $department) }}" class="block w-full btn-primary text-center">
                    Edit Department
                </a>
                @endcan
                <a href="{{ route('departments.index') }}" class="block w-full btn-secondary text-center">
                    Back to Departments
                </a>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-3">Statistics</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Students</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $department->students->count() }}</span>
                </div>
                @if($department->students->count() > 0)
                <div class="flex items-center justify-between pt-3 border-t">
                    <span class="text-sm text-gray-600">Avg. CGPA</span>
                    <span class="text-lg font-bold text-green-600">
                        {{ number_format($department->students->whereNotNull('cgpa')->avg('cgpa'), 2) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Placed</span>
                    <span class="text-lg font-bold text-purple-600">
                        {{ $department->students->where('placement_status', 'Placed')->count() }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
