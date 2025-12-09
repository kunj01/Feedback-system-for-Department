@extends('layouts.app')

@section('title', 'Placement Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('reports.index') }}" class="hover:text-blue-600">Reports</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Placement Report</span>
    </div>

    <h1 class="text-3xl font-bold text-gray-800">Placement Report</h1>
    <p class="text-gray-600 mt-2">Comprehensive listing of all placement records</p>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('reports.placements') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
            <label for="company_id" class="form-label">Company</label>
            <select id="company_id" name="company_id" class="input-field">
                <option value="">All Companies</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="placement_type" class="form-label">Type</label>
            <select id="placement_type" name="placement_type" class="input-field">
                <option value="">All Types</option>
                <option value="Full-Time" {{ $placementType == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                <option value="Internship" {{ $placementType == 'Internship' ? 'selected' : '' }}>Internship</option>
                <option value="Part-Time" {{ $placementType == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                <option value="Contract" {{ $placementType == 'Contract' ? 'selected' : '' }}>Contract</option>
            </select>
        </div>

        <div>
            <label for="is_confirmed" class="form-label">Status</label>
            <select id="is_confirmed" name="is_confirmed" class="input-field">
                <option value="">All</option>
                <option value="1" {{ $isConfirmed === '1' ? 'selected' : '' }}>Confirmed</option>
                <option value="0" {{ $isConfirmed === '0' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Apply</button>
            <a href="{{ route('reports.placements') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Results Summary -->
<div class="flex items-center justify-between mb-4">
    <p class="text-gray-600">
        Showing <span class="font-semibold">{{ $placements->firstItem() ?? 0 }}</span> to
        <span class="font-semibold">{{ $placements->lastItem() ?? 0 }}</span> of
        <span class="font-semibold">{{ $placements->total() }}</span> placements
    </p>
</div>

<!-- Placements Table -->
<div class="card">
    @if($placements->count() > 0)
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Department</th>
                        <th>Company</th>
                        <th>Job Title</th>
                        <th>Type</th>
                        <th class="text-right">Package (LPA)</th>
                        <th>Location</th>
                        <th>Offer Date</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($placements as $placement)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $placement->student->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $placement->student->roll_number }}</p>
                                </div>
                            </td>
                            <td>{{ $placement->student->user->department->name ?? 'N/A' }}</td>
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
                            <td>{{ $placement->location ?? 'N/A' }}</td>
                            <td>{{ $placement->offer_date?->format('d M, Y') ?? 'N/A' }}</td>
                            <td>
                                @if($placement->is_confirmed)
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Confirmed</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('placements.show', $placement) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $placements->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 text-lg">No placements found matching the selected filters.</p>
            <a href="{{ route('reports.placements') }}" class="btn-secondary mt-4">Clear Filters</a>
        </div>
    @endif
</div>
@endsection
