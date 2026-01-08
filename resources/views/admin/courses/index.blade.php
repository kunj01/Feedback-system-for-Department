@extends('layouts.app')

@section('title', 'Courses - SCFMS')
@section('page-title', 'Course Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Courses</h2>
        <a href="{{ route('courses.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Course
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="label">Semester</label>
                <select name="semester_id" class="input-field" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester['id'] }}" {{ request('semester_id') == $semester['id'] ? 'selected' : '' }}>
                            {{ $semester['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label">Department</label>
                <select name="department_id" class="input-field" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept['id'] }}" {{ request('department_id') == $dept['id'] ? 'selected' : '' }}>
                            {{ $dept['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label">Course Type</label>
                <select name="type" class="input-field" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="theory" {{ request('type') === 'theory' ? 'selected' : '' }}>Theory</option>
                    <option value="practical" {{ request('type') === 'practical' ? 'selected' : '' }}>Practical</option>
                    <option value="elective" {{ request('type') === 'elective' ? 'selected' : '' }}>Elective</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card">
            <p class="text-gray-600 text-sm">Total Courses</p>
            <p class="text-3xl font-bold text-gray-800">{{ count($courses) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Theory Courses</p>
            <p class="text-3xl font-bold text-blue-600">{{ count(array_filter($courses, fn($c) => $c['type'] === 'theory')) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Practical Courses</p>
            <p class="text-3xl font-bold text-purple-600">{{ count(array_filter($courses, fn($c) => $c['type'] === 'practical')) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Elective Courses</p>
            <p class="text-3xl font-bold text-green-600">{{ count(array_filter($courses, fn($c) => $c['type'] === 'elective')) }}</p>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Code</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Course Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Department</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Semester</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Credits</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $course['code'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $course['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $course['department'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $course['semester'] }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($course['type'] === 'theory')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Theory</span>
                                @elseif($course['type'] === 'practical')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">Practical</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Elective</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $course['credits'] }}</td>
                            <td class="px-6 py-4 text-sm flex space-x-2">
                                <a href="{{ route('courses.edit', $course['id']) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('courses.destroy', $course['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <p class="text-gray-600">No courses found.</p>
                                <a href="{{ route('courses.create') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">Create Now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
