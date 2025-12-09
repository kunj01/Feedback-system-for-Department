@extends('layouts.app')

@section('title', 'Company Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('companies.index') }}" class="hover:text-blue-600">Companies</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Company Details</span>
    </div>
    <div class="flex justify-between items-start">
        <h1 class="text-3xl font-bold text-gray-800">{{ $company->name }}</h1>
        @can('update', $company)
        <a href="{{ route('companies.edit', $company) }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Company
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="card">
            <div class="flex items-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                    {{ strtoupper(substr($company->name, 0, 2)) }}
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $company->name }}</h2>
                    <p class="text-gray-600">{{ $company->industry }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Location</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $company->location }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Industry</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $company->industry }}</p>
                </div>
                @if($company->website)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Website</p>
                    <a href="{{ $company->website }}" target="_blank" class="text-lg font-semibold text-blue-600 hover:text-blue-700">
                        Visit Website →
                    </a>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Placements</p>
                    <p class="text-lg font-semibold text-green-600">{{ $company->placements->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        @if($company->contact_person || $company->contact_email || $company->contact_phone)
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contact Information
            </h3>
            <div class="grid grid-cols-2 gap-6">
                @if($company->contact_person)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Contact Person</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $company->contact_person }}</p>
                </div>
                @endif
                @if($company->contact_email)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Email</p>
                    <a href="mailto:{{ $company->contact_email }}" class="text-lg font-semibold text-blue-600 hover:text-blue-700">
                        {{ $company->contact_email }}
                    </a>
                </div>
                @endif
                @if($company->contact_phone)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Phone</p>
                    <a href="tel:{{ $company->contact_phone }}" class="text-lg font-semibold text-blue-600 hover:text-blue-700">
                        {{ $company->contact_phone }}
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Placement Records -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Placement Records ({{ $company->placements->count() }})
            </h3>

            @if($company->placements->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($company->placements->take(10) as $placement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ strtoupper(substr($placement->student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $placement->student->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $placement->student->enrollment_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-900">{{ $placement->job_role }}</p>
                                <p class="text-xs text-gray-500">{{ $placement->job_type }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-green-600">₹ {{ number_format($placement->package_lpa, 2) }} LPA</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($placement->offer_status == 'Accepted') bg-green-100 text-green-800
                                    @elseif($placement->offer_status == 'Pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $placement->offer_status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($company->placements->count() > 10)
                <div class="px-4 py-3 bg-gray-50 text-sm text-gray-500 text-center">
                    + {{ $company->placements->count() - 10 }} more placements
                </div>
                @endif
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No placement records yet</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">Quick Actions</h3>
            <div class="space-y-2">
                @can('update', $company)
                <a href="{{ route('companies.edit', $company) }}" class="block w-full btn-primary text-center">
                    Edit Company
                </a>
                @endcan
                <a href="{{ route('companies.index') }}" class="block w-full btn-secondary text-center">
                    Back to Companies
                </a>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-3">Placement Statistics</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Offers</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $company->placements->count() }}</span>
                </div>
                @if($company->placements->count() > 0)
                <div class="flex items-center justify-between pt-3 border-t">
                    <span class="text-sm text-gray-600">Avg. Package</span>
                    <span class="text-lg font-bold text-green-600">
                        ₹ {{ number_format($company->placements->avg('package_lpa'), 2) }} L
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Accepted</span>
                    <span class="text-lg font-bold text-purple-600">
                        {{ $company->placements->where('offer_status', 'Accepted')->count() }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
