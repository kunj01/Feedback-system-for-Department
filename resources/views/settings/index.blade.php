@extends('layouts.app')

@section('page-title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">General Settings</h3>
        <form method="POST" action="#" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Display Name</label>
                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="form-input mt-1 w-full">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" class="form-input mt-1 w-full">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ auth()->user()->phone }}" class="form-input mt-1 w-full">
            </div>
            <div>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Notification Preferences -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Notification Preferences</h3>
        <form method="POST" action="#" class="space-y-4">
            @csrf
            <div class="flex items-center justify-between py-3 border-b">
                <div>
                    <h4 class="font-medium text-gray-900">Email Notifications</h4>
                    <p class="text-sm text-gray-500">Receive notifications via email</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="email_notifications" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div class="flex items-center justify-between py-3 border-b">
                <div>
                    <h4 class="font-medium text-gray-900">Placement Updates</h4>
                    <p class="text-sm text-gray-500">Get notified about new placements</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="placement_notifications" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div class="flex items-center justify-between py-3">
                <div>
                    <h4 class="font-medium text-gray-900">Project Updates</h4>
                    <p class="text-sm text-gray-500">Get notified about project changes</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="project_notifications" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div>
                <button type="submit" class="btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>

    <!-- Privacy & Security -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Privacy & Security</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b">
                <div>
                    <h4 class="font-medium text-gray-900">Two-Factor Authentication</h4>
                    <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
                </div>
                <button class="text-blue-600 hover:text-blue-800 font-medium">Enable</button>
            </div>
            <div class="flex items-center justify-between py-3 border-b">
                <div>
                    <h4 class="font-medium text-gray-900">Active Sessions</h4>
                    <p class="text-sm text-gray-500">Manage your active sessions across devices</p>
                </div>
                <button class="text-blue-600 hover:text-blue-800 font-medium">View</button>
            </div>
            <div class="flex items-center justify-between py-3">
                <div>
                    <h4 class="font-medium text-gray-900">Download Your Data</h4>
                    <p class="text-sm text-gray-500">Request a copy of your personal data</p>
                </div>
                <button class="text-blue-600 hover:text-blue-800 font-medium">Request</button>
            </div>
        </div>
    </div>
</div>
@endsection
