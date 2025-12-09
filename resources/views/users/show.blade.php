@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('users.index') }}" class="hover:text-blue-600">Users</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>User Details</span>
    </div>
    <div class="flex justify-between items-start">
        <h1 class="text-3xl font-bold text-gray-800">User Details</h1>
        @can('update', $user)
        <a href="{{ route('users.edit', $user) }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit User
        </a>
        @endcan
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="card">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                @if($user->is_active)
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                </span>
                @else
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Inactive
                </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600 mb-1">User ID</p>
                    <p class="text-lg font-semibold text-gray-800">#{{ $user->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Email</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Created</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Roles & Permissions</h3>

            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Assigned Roles</p>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-lg
                        @if($role->name == 'admin') bg-purple-100 text-purple-800
                        @elseif($role->name == 'tnp') bg-blue-100 text-blue-800
                        @elseif($role->name == 'head') bg-green-100 text-green-800
                        @elseif($role->name == 'guide') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($role->name) }}
                    </span>
                    @empty
                    <p class="text-gray-500">No roles assigned</p>
                    @endforelse
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-600 mb-2">Permissions</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    @php
                        $allPermissions = $user->roles->flatMap->permissions->unique('id');
                    @endphp
                    @if($allPermissions->count() > 0)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($allPermissions->take(10) as $permission)
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $permission->name }}
                        </div>
                        @endforeach
                    </div>
                    @if($allPermissions->count() > 10)
                    <p class="text-sm text-gray-500 mt-3">+ {{ $allPermissions->count() - 10 }} more permissions</p>
                    @endif
                    @else
                    <p class="text-gray-500 text-sm">No permissions assigned</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card bg-blue-50 border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">Quick Actions</h3>
            <div class="space-y-2">
                @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="block w-full btn-primary text-center">
                    Edit Profile
                </a>
                @endcan
                <a href="{{ route('users.index') }}" class="block w-full btn-secondary text-center">
                    Back to Users
                </a>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-3">Account Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    @if($user->is_active)
                    <span class="text-sm font-semibold text-green-600">Active</span>
                    @else
                    <span class="text-sm font-semibold text-red-600">Inactive</span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Email Verified</span>
                    @if($user->email_verified_at)
                    <span class="text-sm font-semibold text-green-600">Yes</span>
                    @else
                    <span class="text-sm font-semibold text-gray-600">No</span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Member Since</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
