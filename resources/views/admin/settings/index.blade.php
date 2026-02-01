@extends('layouts.app')

@section('title', 'Admin Settings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
                    <p class="text-gray-600 mt-1">Configure system-wide settings and preferences</p>
                </div>
            </div>
        </div>

        <!-- Settings Sections -->
        <div class="space-y-6">
            
            <!-- Multi-Teacher Feedback Mode Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Multi-Teacher Feedback Mode
                    </h2>
                    <p class="text-indigo-100 mt-2">Enable students to provide feedback for all teachers assigned to a subject</p>
                </div>
                
                <div class="p-8">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 pr-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Feature Description</h3>
                            <div class="space-y-3 text-gray-600">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p><strong>When Enabled:</strong> Students can give feedback to all teachers assigned to a subject in multi-teacher mode</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p><strong>When Disabled:</strong> Standard single-teacher feedback model is used</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <p><strong>Impact:</strong> Affects how feedback forms are displayed and submitted across the system</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-center justify-center bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                            <label for="multiTeacherToggle" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="multiTeacherToggle" class="sr-only" {{ $multiTeacherMode ? 'checked' : '' }} onchange="toggleMultiTeacherMode(this)">
                                    <div class="toggle-bg w-20 h-10 bg-gray-300 rounded-full shadow-inner transition"></div>
                                    <div class="toggle-dot absolute left-1 top-1 bg-white w-8 h-8 rounded-full shadow transition transform"></div>
                                </div>
                            </label>
                            <p id="toggleStatus" class="mt-4 text-sm font-semibold {{ $multiTeacherMode ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $multiTeacherMode ? 'ENABLED' : 'DISABLED' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Click to toggle</p>
                        </div>
                    </div>
                    
                    <!-- Status Banner -->
                    <div id="statusBanner" class="mt-6 p-4 rounded-lg {{ $multiTeacherMode ? 'bg-green-50 border-l-4 border-green-500' : 'bg-gray-50 border-l-4 border-gray-400' }}">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 {{ $multiTeacherMode ? 'text-green-600' : 'text-gray-600' }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="{{ $multiTeacherMode ? 'text-green-800' : 'text-gray-700' }} font-medium">
                                Multi-Teacher Feedback Mode is currently <strong>{{ $multiTeacherMode ? 'ACTIVE' : 'INACTIVE' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    Quick Links
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('admin.subjects.index') }}" class="quick-link-card">
                        <svg class="w-8 h-8 text-indigo-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900">Manage Subjects</h3>
                        <p class="text-sm text-gray-600 mt-1">Add, edit, and assign teachers to subjects</p>
                    </a>
                    
                    <a href="{{ route('admin.teachers.index') }}" class="quick-link-card">
                        <svg class="w-8 h-8 text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900">Manage Teachers</h3>
                        <p class="text-sm text-gray-600 mt-1">Add and manage teacher profiles</p>
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="quick-link-card">
                        <svg class="w-8 h-8 text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-900">Dashboard</h3>
                        <p class="text-sm text-gray-600 mt-1">Return to main dashboard</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
#multiTeacherToggle:checked ~ .toggle-bg {
    background-color: #10b981;
}

#multiTeacherToggle:checked ~ .toggle-dot {
    transform: translateX(2.5rem);
}

.toggle-bg {
    transition: background-color 0.3s ease;
}

.toggle-dot {
    transition: transform 0.3s ease;
}

.quick-link-card {
    @apply block p-6 bg-gray-50 hover:bg-gray-100 rounded-xl border-2 border-gray-200 hover:border-indigo-300 transition-all hover:shadow-lg;
}
</style>

<script>
function toggleMultiTeacherMode(checkbox) {
    const enabled = checkbox.checked;
    
    fetch('{{ route("admin.settings.multi-teacher-mode") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ enabled: enabled })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUI(enabled);
            showNotification(data.message, 'success');
        } else {
            checkbox.checked = !enabled;
            showNotification(data.message || 'Failed to update setting', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating setting:', error);
        checkbox.checked = !enabled;
        showNotification('Failed to update setting', 'error');
    });
}

function updateUI(enabled) {
    const statusText = document.getElementById('toggleStatus');
    const statusBanner = document.getElementById('statusBanner');
    
    if (enabled) {
        statusText.textContent = 'ENABLED';
        statusText.className = 'mt-4 text-sm font-semibold text-green-600';
        statusBanner.className = 'mt-6 p-4 rounded-lg bg-green-50 border-l-4 border-green-500';
        statusBanner.querySelector('svg').className = 'w-6 h-6 text-green-600 mr-3';
        statusBanner.querySelector('p').className = 'text-green-800 font-medium';
        statusBanner.querySelector('p').innerHTML = 'Multi-Teacher Feedback Mode is currently <strong>ACTIVE</strong>';
    } else {
        statusText.textContent = 'DISABLED';
        statusText.className = 'mt-4 text-sm font-semibold text-gray-500';
        statusBanner.className = 'mt-6 p-4 rounded-lg bg-gray-50 border-l-4 border-gray-400';
        statusBanner.querySelector('svg').className = 'w-6 h-6 text-gray-600 mr-3';
        statusBanner.querySelector('p').className = 'text-gray-700 font-medium';
        statusBanner.querySelector('p').innerHTML = 'Multi-Teacher Feedback Mode is currently <strong>INACTIVE</strong>';
    }
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-xl z-50 transform transition-all`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

@endsection
