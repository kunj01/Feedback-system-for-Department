@extends('layouts.app')

@section('title', 'Dashboard - T&P System')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Statistics Cards -->
    <a href="{{ route('students.index') }}" class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Students</p>
                <p class="text-3xl font-bold mt-2">{{ \App\Models\Student::count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('projects.index') }}" class="card bg-gradient-to-br from-green-500 to-green-600 text-white hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Active Projects</p>
                <p class="text-3xl font-bold mt-2">{{ \App\Models\Project::whereIn('status', ['OPEN', 'IN_PROGRESS'])->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('placements.index') }}" class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Placements</p>
                <p class="text-3xl font-bold mt-2">{{ \App\Models\StudentPlacement::where('is_confirmed', true)->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('companies.index') }}" class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white hover:shadow-lg transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Companies</p>
                <p class="text-3xl font-bold mt-2">{{ \App\Models\Company::count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activities -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activities</h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 pb-3 border-b">
                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 font-medium">New project assigned to John Doe</p>
                    <p class="text-xs text-gray-500">2 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 pb-3 border-b">
                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 font-medium">Evaluation submitted by Guide 1</p>
                    <p class="text-xs text-gray-500">4 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 pb-3 border-b">
                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 font-medium">Placement offer confirmed</p>
                    <p class="text-xs text-gray-500">6 hours ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 font-medium">New student registered</p>
                    <p class="text-xs text-gray-500">1 day ago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('students.create') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-200 block text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <p class="text-sm font-medium text-gray-700">Add Student</p>
            </a>
            <a href="{{ route('projects.create') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-200 block text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-700">New Project</p>
            </a>
            <a href="{{ route('evaluations.create') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-200 block text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-sm font-medium text-gray-700">Add Evaluation</p>
            </a>
            <a href="{{ route('placements.create') }}" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-200 block text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-700">Record Placement</p>
            </a>
        </div>
    </div>
</div>
@endsection
