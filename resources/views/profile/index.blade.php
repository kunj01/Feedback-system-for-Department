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
        
        @if(session('password_success'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-green-800 font-medium">{{ session('password_success') }}</p>
                </div>
            </div>
        @endif

        @if(session('password_error'))
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-800 font-medium">{{ session('password_error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-red-800 font-medium mb-2">Please correct the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" id="current_password" name="current_password" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" id="new_password" name="new_password" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       required>
                @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>
            <div>
                <button type="submit" class="btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
