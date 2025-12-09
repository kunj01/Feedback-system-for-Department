@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('companies.index') }}" class="hover:text-blue-600">Companies</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit Company</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit Company: {{ $company->name }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('companies.update', $company) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Company Name -->
                    <div>
                        <label for="name" class="label required">Company Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $company->name) }}"
                            placeholder="e.g., Tech Solutions Pvt. Ltd."
                            class="input-field @error('name') border-red-500 @enderror"
                            required
                            autofocus
                        >
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Industry & Location -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="industry" class="label required">Industry</label>
                            <input
                                type="text"
                                id="industry"
                                name="industry"
                                value="{{ old('industry', $company->industry) }}"
                                placeholder="e.g., IT Services"
                                class="input-field @error('industry') border-red-500 @enderror"
                                required
                            >
                            @error('industry')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="label required">Location</label>
                            <input
                                type="text"
                                id="location"
                                name="location"
                                value="{{ old('location', $company->location) }}"
                                placeholder="e.g., Ahmedabad"
                                class="input-field @error('location') border-red-500 @enderror"
                                required
                            >
                            @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="label">Website</label>
                        <input
                            type="url"
                            id="website"
                            name="website"
                            value="{{ old('website', $company->website) }}"
                            placeholder="https://www.example.com"
                            class="input-field @error('website') border-red-500 @enderror"
                        >
                        @error('website')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>

                        <!-- Contact Person -->
                        <div class="mb-4">
                            <label for="contact_person" class="label">Contact Person</label>
                            <input
                                type="text"
                                id="contact_person"
                                name="contact_person"
                                value="{{ old('contact_person', $company->contact_person) }}"
                                placeholder="e.g., John Doe"
                                class="input-field @error('contact_person') border-red-500 @enderror"
                            >
                            @error('contact_person')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Email & Phone -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="contact_email" class="label">Contact Email</label>
                                <input
                                    type="email"
                                    id="contact_email"
                                    name="contact_email"
                                    value="{{ old('contact_email', $company->contact_email) }}"
                                    placeholder="contact@example.com"
                                    class="input-field @error('contact_email') border-red-500 @enderror"
                                >
                                @error('contact_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_phone" class="label">Contact Phone</label>
                                <input
                                    type="tel"
                                    id="contact_phone"
                                    name="contact_phone"
                                    value="{{ old('contact_phone', $company->contact_phone) }}"
                                    placeholder="+91 9876543210"
                                    class="input-field @error('contact_phone') border-red-500 @enderror"
                                >
                                @error('contact_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Company
                    </button>
                    <a href="{{ route('companies.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">Company Stats</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-gray-600">Total Placements</p>
                    <p class="font-medium text-lg text-green-600">{{ $company->placements->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Created</p>
                    <p class="font-medium">{{ $company->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Company ID</p>
                    <p class="font-medium">#{{ $company->id }}</p>
                </div>
            </div>
        </div>

        @can('delete', $company)
        <div class="card bg-red-50 border-red-200 mt-4">
            <h3 class="font-semibold text-red-800 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-3">Permanently delete this company</p>
            <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger w-full">Delete Company</button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
