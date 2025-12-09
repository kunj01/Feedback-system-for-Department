@extends('layouts.app')

@section('title', 'Placement Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Placement Management</h1>
            <p class="text-gray-600 mt-1">Manage student placement records and offers</p>
        </div>
        @can('create', App\Models\StudentPlacement::class)
        <a href="{{ route('placements.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Placement
        </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('placements.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <label class="label">Search</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Student, company, job title..."
                class="input-field"
            >
        </div>
        <div>
            <label class="label">Company</label>
            <select name="company_id" class="input-field">
                <option value="">All Companies</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Type</label>
            <select name="placement_type" class="input-field">
                <option value="">All Types</option>
                @foreach($placementTypes as $type)
                <option value="{{ $type }}" {{ request('placement_type') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Filter</button>
            <a href="{{ route('placements.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Placements Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($placements as $placement)
    <div class="card hover:shadow-lg transition-shadow">
        <!-- Header with Confirmed Badge -->
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $placement->job_title }}</h3>
                <p class="text-sm text-gray-600">{{ $placement->company->name }}</p>
            </div>
            @if($placement->is_confirmed)
            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                ✓ Confirmed
            </span>
            @endif
        </div>

        <!-- Student Info -->
        <div class="flex items-center gap-3 mb-4 pb-4 border-b">
            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                {{ strtoupper(substr($placement->student->user->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-medium text-gray-900">{{ $placement->student->user->name }}</p>
                <p class="text-xs text-gray-500">{{ $placement->student->enrollment_number }}</p>
            </div>
        </div>

        <!-- Details -->
        <dl class="space-y-2 text-sm mb-4">
            <div class="flex justify-between">
                <dt class="text-gray-500">Package:</dt>
                <dd class="font-semibold text-green-600">₹{{ number_format($placement->package_lpa, 2) }} LPA</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Type:</dt>
                <dd class="font-medium text-gray-900">{{ $placement->placement_type }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Location:</dt>
                <dd class="text-gray-900">{{ $placement->location ?? 'N/A' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Offer Date:</dt>
                <dd class="text-gray-900">{{ $placement->offer_date ? $placement->offer_date->format('M d, Y') : 'N/A' }}</dd>
            </div>
        </dl>

        <!-- Actions -->
        <div class="flex gap-2 pt-4 border-t">
            @can('view', $placement)
            <a href="{{ route('placements.show', $placement) }}" class="btn-secondary flex-1 text-center text-sm py-2">
                View Details
            </a>
            @endcan
            @can('update', $placement)
            <a href="{{ route('placements.edit', $placement) }}" class="text-green-600 hover:text-green-900 p-2" title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            @endcan
            @can('delete', $placement)
            <form action="{{ route('placements.destroy', $placement) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 p-2" title="Delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
            @endcan
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="card text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <p class="mt-2 text-gray-500">No placement records found</p>
        </div>
    </div>
    @endforelse
</div>

@if($placements->hasPages())
<div class="mt-6">
    {{ $placements->links() }}
</div>
@endif
@endsection
