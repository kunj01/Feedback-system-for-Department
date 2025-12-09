@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center space-x-6">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
                <div class="mt-2">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </span>
                    @if(auth()->user()->is_active)
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            Active
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-gray-900">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-gray-900">{{ auth()->user()->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-gray-900">{{ auth()->user()->phone ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <p class="mt-1 text-gray-900">{{ auth()->user()->department->name ?? 'Not assigned' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="mt-1 text-gray-900">{{ auth()->user()->getRoleNames()->implode(', ') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Member Since</label>
                <p class="mt-1 text-gray-900">{{ auth()->user()->created_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
        <form method="POST" action="#" class="space-y-4">
            @csrf
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-input mt-1 w-full" required>
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-input mt-1 w-full" required>
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input mt-1 w-full" required>
            </div>
            <div>
                <button type="submit" class="btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
