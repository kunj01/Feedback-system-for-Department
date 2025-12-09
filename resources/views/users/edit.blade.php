@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('users.index') }}" class="hover:text-blue-600">Users</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Edit User</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Edit User: {{ $user->name }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="label required">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
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
                            value="{{ old('email', $user->email) }}"
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
                            <option value="{{ $role->name }}"
                                {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                            @endforeach
                        </select>
                        @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password (Optional)</h3>
                        <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="label">New Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Minimum 8 characters"
                                class="input-field @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="label">Confirm New Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Re-enter new password"
                                class="input-field"
                            >
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}
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
                        Update User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-gray-50">
            <h3 class="font-semibold text-gray-800 mb-3">User Information</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-gray-600">Created</p>
                    <p class="font-medium">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Last Updated</p>
                    <p class="font-medium">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">User ID</p>
                    <p class="font-medium">#{{ $user->id }}</p>
                </div>
            </div>
        </div>

        <div class="card bg-yellow-50 border-yellow-200 mt-4">
            <h3 class="font-semibold text-gray-800 mb-2">Note</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Only fill password fields to change password</li>
                <li>• User will need to re-login after password change</li>
                <li>• Inactive users cannot access the system</li>
            </ul>
        </div>

        @can('delete', $user)
        @if($user->id !== auth()->id())
        <div class="card bg-red-50 border-red-200 mt-4">
            <h3 class="font-semibold text-red-800 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-3">Permanently delete this user account</p>
            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger w-full">Delete User</button>
            </form>
        </div>
        @endif
        @endcan
    </div>
</div>
@endsection
