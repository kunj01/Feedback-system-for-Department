@extends('layouts.app')

@section('title', 'Create Company')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('companies.index') }}" class="hover:text-blue-600">Companies</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Create New Company</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Create New Company</h1>
</div>

<div class="max-w-3xl">
    <div class="card">
        <form method="POST" action="{{ route('companies.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Company Name -->
                <div>
                    <label for="name" class="label required">Company Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
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
                            value="{{ old('industry') }}"
                            placeholder="e.g., IT Services, Manufacturing"
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
                            value="{{ old('location') }}"
                            placeholder="e.g., Ahmedabad, Gujarat"
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
                        value="{{ old('website') }}"
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
                            value="{{ old('contact_person') }}"
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
                                value="{{ old('contact_email') }}"
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
                                value="{{ old('contact_phone') }}"
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
                    Create Company
                </button>
                <a href="{{ route('companies.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
