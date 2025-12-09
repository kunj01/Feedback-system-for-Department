@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Reports & Analytics</h1>
    <p class="text-gray-600 mt-2">Comprehensive insights into placements, projects, and evaluations</p>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="department_id" class="form-label">Department</label>
            <select id="department_id" name="department_id" class="input-field">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="academic_year" class="form-label">Academic Year</label>
            <select id="academic_year" name="academic_year" class="input-field">
                <option value="">All Years</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year }}" {{ $academicYear == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="placement_type" class="form-label">Placement Type</label>
            <select id="placement_type" name="placement_type" class="input-field">
                <option value="">All Types</option>
                <option value="Full-Time" {{ $placementType == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                <option value="Internship" {{ $placementType == 'Internship' ? 'selected' : '' }}>Internship</option>
                <option value="Part-Time" {{ $placementType == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                <option value="Contract" {{ $placementType == 'Contract' ? 'selected' : '' }}>Contract</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Apply Filters</button>
            <a href="{{ route('reports.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Key Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Placements -->
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Placements</p>
                <p class="text-3xl font-bold mt-2">{{ $totalPlacements }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Confirmed Placements -->
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Confirmed</p>
                <p class="text-3xl font-bold mt-2">{{ $confirmedPlacements }}</p>
                <p class="text-green-100 text-xs mt-1">
                    {{ $totalPlacements > 0 ? round(($confirmedPlacements / $totalPlacements) * 100, 1) : 0 }}% of total
                </p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Average Package -->
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Average Package</p>
                <p class="text-3xl font-bold mt-2">₹ {{ number_format($averagePackage, 2) }}</p>
                <p class="text-purple-100 text-xs mt-1">LPA</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Highest Package -->
    <div class="card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Highest Package</p>
                <p class="text-3xl font-bold mt-2">₹ {{ number_format($highestPackage, 2) }}</p>
                <p class="text-orange-100 text-xs mt-1">LPA</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Placements by Type -->
    <div class="card">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Placements by Type</h2>

        @if($placementsByType->count() > 0)
            <div class="space-y-3">
                @foreach($placementsByType as $type)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $type->placement_type }}</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $type->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $type->placement_type == 'Full-Time' ? 'bg-blue-500' : ($type->placement_type == 'Internship' ? 'bg-purple-500' : ($type->placement_type == 'Part-Time' ? 'bg-yellow-500' : 'bg-gray-500')) }}"
                                 style="width: {{ $confirmedPlacements > 0 ? ($type->count / $confirmedPlacements) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No placement data available</p>
        @endif
    </div>

    <!-- Project Statistics -->
    <div class="card">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Project Statistics</h2>

        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-3xl font-bold text-blue-600">{{ $projectStats['total'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Total Projects</p>
            </div>

            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-3xl font-bold text-green-600">{{ $projectStats['completed'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Completed</p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-3xl font-bold text-purple-600">{{ $projectStats['in_progress'] }}</p>
                <p class="text-sm text-gray-600 mt-1">In Progress</p>
            </div>

            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-3xl font-bold text-yellow-600">{{ $projectStats['planning'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Planning</p>
            </div>
        </div>
    </div>
</div>

<!-- Department-wise Statistics -->
@if($departmentStats->count() > 0)
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Department-wise Placement Statistics</h2>
            <a href="{{ route('reports.placements') }}" class="text-blue-600 hover:underline text-sm">View Detailed Report →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th class="text-center">Total Placements</th>
                        <th class="text-right">Average Package (LPA)</th>
                        <th class="text-right">Highest Package (LPA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departmentStats as $stat)
                        <tr>
                            <td class="font-medium">{{ $stat['department'] }}</td>
                            <td class="text-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    {{ $stat['total_placements'] }}
                                </span>
                            </td>
                            <td class="text-right font-semibold text-green-600">₹ {{ number_format($stat['average_package'], 2) }}</td>
                            <td class="text-right font-semibold text-orange-600">₹ {{ number_format($stat['highest_package'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Top Hiring Companies -->
@if($topCompanies->count() > 0)
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Top Hiring Companies</h2>
            <a href="{{ route('companies.index') }}" class="text-blue-600 hover:underline text-sm">View All Companies →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($topCompanies as $company)
                <div class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $company->company->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $company->placements_count }} placement{{ $company->placements_count > 1 ? 's' : '' }}</p>
                        </div>
                        <a href="{{ route('companies.show', $company->company) }}" class="text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Recent Placements -->
@if($recentPlacements->count() > 0)
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Placements</h2>
            <a href="{{ route('reports.placements') }}" class="text-blue-600 hover:underline text-sm">View All →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Company</th>
                        <th>Job Title</th>
                        <th>Type</th>
                        <th class="text-right">Package (LPA)</th>
                        <th>Offer Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPlacements as $placement)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $placement->student->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $placement->student->roll_number }}</p>
                                </div>
                            </td>
                            <td>{{ $placement->company->name }}</td>
                            <td>{{ $placement->job_title }}</td>
                            <td>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $placement->placement_type == 'Full-Time' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $placement->placement_type == 'Internship' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $placement->placement_type == 'Part-Time' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $placement->placement_type == 'Contract' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $placement->placement_type }}
                                </span>
                            </td>
                            <td class="text-right font-semibold text-green-600">₹ {{ number_format($placement->package_lpa, 2) }}</td>
                            <td>{{ $placement->offer_date?->format('d M, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Quick Links to Detailed Reports -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <a href="{{ route('reports.placements') }}" class="card hover:shadow-lg transition-shadow bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-500 text-white rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Placement Report</h3>
                <p class="text-sm text-gray-600">Detailed placement analysis</p>
            </div>
        </div>
    </a>

    <a href="{{ route('reports.projects') }}" class="card hover:shadow-lg transition-shadow bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-purple-500 text-white rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Project Report</h3>
                <p class="text-sm text-gray-600">Project status & analytics</p>
            </div>
        </div>
    </a>

    <a href="{{ route('reports.evaluations') }}" class="card hover:shadow-lg transition-shadow bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-green-500 text-white rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Evaluation Report</h3>
                <p class="text-sm text-gray-600">Grade distribution & metrics</p>
            </div>
        </div>
    </a>
</div>
@endsection
