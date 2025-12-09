@extends('layouts.app')

@section('title', 'Company Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Company Management</h1>
            <p class="text-gray-600 mt-1">Manage companies for placement opportunities</p>
        </div>
        @can('create', App\Models\Company::class)
        <a href="{{ route('companies.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Company
        </a>
        @endcan
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('companies.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="label">Search</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Company name, industry, location..."
                class="input-field"
            >
        </div>
        <div>
            <label class="label">Industry</label>
            <select name="industry" class="input-field">
                <option value="">All Industries</option>
                @foreach($industries as $industry)
                <option value="{{ $industry }}" {{ request('industry') == $industry ? 'selected' : '' }}>
                    {{ $industry }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">Filter</button>
            <a href="{{ route('companies.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Companies Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($companies as $company)
    <div class="card hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800">{{ $company->name }}</h3>
                <p class="text-sm text-gray-500">{{ $company->industry }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($company->name, 0, 2)) }}
            </div>
        </div>

        <div class="space-y-2 mb-4">
            <div class="flex items-start text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-gray-600">{{ $company->location }}</span>
            </div>
            @if($company->website)
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:text-blue-700 truncate">
                    {{ Str::limit($company->website, 30) }}
                </a>
            </div>
            @endif
            @if($company->contact_person)
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-gray-600">{{ $company->contact_person }}</span>
            </div>
            @endif
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="text-gray-600">Placements: <span class="font-semibold text-green-600">{{ $company->placements->count() }}</span></span>
            </div>
        </div>

        <div class="flex gap-2 pt-4 border-t border-gray-200">
            @can('view', $company)
            <a href="{{ route('companies.show', $company) }}" class="flex-1 text-center btn-secondary text-sm py-2">
                View
            </a>
            @endcan
            @can('update', $company)
            <a href="{{ route('companies.edit', $company) }}" class="flex-1 text-center btn-primary text-sm py-2">
                Edit
            </a>
            @endcan
            @can('delete', $company)
            <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger text-sm py-2 px-3" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <p class="mt-2 text-gray-500">No companies found</p>
        </div>
    </div>
    @endforelse
</div>

@if($companies->hasPages())
<div class="mt-6">
    {{ $companies->links() }}
</div>
@endif
@endsection
