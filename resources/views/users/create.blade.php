@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('users.index') }}" class="hover:text-blue-600">Users</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Create New User</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Create New User</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="label required">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter full name"
                            class="input-field @error('name') border-red-500 @enderror"
                            required
                            autofocus
                        >
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="label required">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="user@example.com"
                            class="input-field @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="label required">Role</label>
                        <select
                            id="role"
                            name="role"
                            class="input-field @error('role') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                            @endforeach
                        </select>
                        @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Select the user's primary role in the system</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="label required">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimum 8 characters"
                            class="input-field @error('password') border-red-500 @enderror"
                            required
                        >
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="label required">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-enter password"
                            class="input-field"
                            required
                        >
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <span class="ml-2 text-sm text-gray-700">Active (User can login to the system)</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">Role Descriptions</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="font-medium text-purple-700">Admin</p>
                    <p class="text-gray-600">Full system access and user management</p>
                </div>
                <div>
                    <p class="font-medium text-blue-700">TnP Officer</p>
                    <p class="text-gray-600">Manage placements and company interactions</p>
                </div>
                <div>
                    <p class="font-medium text-green-700">Department Head</p>
                    <p class="text-gray-600">Oversee department students and projects</p>
                </div>
                <div>
                    <p class="font-medium text-yellow-700">Project Guide</p>
                    <p class="text-gray-600">Evaluate and mentor student projects</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Student</p>
                    <p class="text-gray-600">Submit projects and view evaluations</p>
                </div>
            </div>
        </div>

        <div class="card bg-yellow-50 border-yellow-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">Password Requirements</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Minimum 8 characters</li>
                <li>• Mix of letters and numbers recommended</li>
                <li>• User will receive login credentials</li>
            </ul>
        </div>
    </div>
</div>
@endsection
