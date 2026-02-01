@extends('layouts.app')

@section('title', 'Forms - Admin')
@section('page-title', 'Forms Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Forms & Documents</h2>
        <div class="flex gap-3">
            <button onclick="document.getElementById('help-modal').classList.toggle('hidden')" class="btn-secondary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Help
            </button>
            <a href="{{ route('forms.create') }}" class="btn-primary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Upload Form
            </a>
        </div>
    </div>

    <!-- Help Modal -->
    <div id="help-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-xl font-bold text-gray-900">📋 Feedback Period Configuration Guide</h3>
                <button onclick="document.getElementById('help-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4 text-gray-700">
                <div>
                    <h4 class="font-semibold text-lg mb-2">🕐 Setting Feedback Periods</h4>
                    <p class="text-sm mb-2">When assigning forms to students, you can configure when they can access and submit:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm ml-2">
                        <li><strong>Start Date & Time:</strong> When the form becomes available to students</li>
                        <li><strong>End Date & Time:</strong> Submission deadline</li>
                        <li><strong>Grace Period (Hours):</strong> Extra time after deadline for late submissions (0-168 hours)</li>
                    </ul>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <h5 class="font-semibold text-sm mb-1">💡 Example Scenarios:</h5>
                    <div class="space-y-2 text-sm">
                        <div>
                            <strong>Semester Feedback:</strong>
                            <p class="text-xs text-gray-600 ml-3">Start: Jan 1, 2026 00:00 | End: Jan 15, 2026 23:59 | Grace: 48h</p>
                            <p class="text-xs text-gray-600 ml-3">→ Students have until Jan 17, 11:59 PM to submit</p>
                        </div>
                        <div>
                            <strong>Always Available:</strong>
                            <p class="text-xs text-gray-600 ml-3">Start: (empty) | End: (empty) | Grace: 0h</p>
                            <p class="text-xs text-gray-600 ml-3">→ No time restrictions</p>
                        </div>
                        <div>
                            <strong>Urgent Survey:</strong>
                            <p class="text-xs text-gray-600 ml-3">Start: Today 2:00 PM | End: Tomorrow 2:00 PM | Grace: 2h</p>
                            <p class="text-xs text-gray-600 ml-3">→ 24-hour window + 2-hour grace period</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-lg mb-2">📊 Status Badges Explained</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded border bg-blue-100 text-blue-800 border-blue-200">Upcoming</span>
                            <span class="text-xs">Not started yet</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded border bg-green-100 text-green-800 border-green-200">Active</span>
                            <span class="text-xs">Currently accepting submissions</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded border bg-yellow-100 text-yellow-800 border-yellow-200">Grace Period</span>
                            <span class="text-xs">Late submissions allowed</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded border bg-red-100 text-red-800 border-red-200">Ended</span>
                            <span class="text-xs">Deadline passed</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-lg mb-2">🔔 Automatic Reminders</h4>
                    <p class="text-sm mb-2">Students automatically receive notifications:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm ml-2">
                        <li>24 hours before form becomes available</li>
                        <li>3 days before deadline</li>
                        <li>1 day before deadline</li>
                        <li>2 hours before deadline (final reminder)</li>
                    </ul>
                    <p class="text-xs text-gray-600 mt-2">💻 Command: <code class="bg-gray-100 px-2 py-1 rounded">php artisan feedback:send-reminders</code> (runs hourly)</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <h5 class="font-semibold text-sm mb-1">⚠️ Important Notes:</h5>
                    <ul class="list-disc list-inside space-y-1 text-xs text-gray-700">
                        <li>All date/time inputs use your system timezone</li>
                        <li>Leave fields empty for "always available" forms</li>
                        <li>Grace period extends deadline but doesn't change displayed end date</li>
                        <li>Students see countdown timers on their dashboard</li>
                        <li>Forms in grace period show yellow "Grace Period" badge</li>
                    </ul>
                </div>
            </div>

            <div class="mt-5 pt-3 border-t flex justify-end">
                <button onclick="document.getElementById('help-modal').classList.add('hidden')" class="btn-primary">
                    Got it, thanks!
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Bar -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('forms.index') }}" class="flex space-x-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search forms by name..." class="input-field pl-10">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Search
            </button>
            @if($search)
                <a href="{{ route('forms.index') }}" class="btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    <!-- Stats Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Forms</p>
                    <p class="text-3xl font-bold text-gray-800">{{ count($forms) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                        <path d="M14 2v6h6M9 15h6M9 12h6M9 18h6" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Word Documents</p>
                    <p class="text-3xl font-bold text-blue-600">{{ count(array_filter($forms, fn($f) => in_array($f['extension'], ['DOC', 'DOCX']))) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">PDF Documents</p>
                    <p class="text-3xl font-bold text-red-600">{{ count(array_filter($forms, fn($f) => $f['extension'] === 'PDF')) }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M10 12h4v6h-4z" fill="white"/>
                        <path d="M10 10h4v1h-4z" fill="white"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms List -->
    @if(count($forms) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($forms as $form)
                <div class="card hover:shadow-lg transition duration-200">
                    <!-- File Icon & Type Badge -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-{{ $form['color'] }}-100 p-4 rounded-lg">
                            <svg class="w-10 h-10 text-{{ $form['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $form['icon'] }}"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-{{ $form['color'] }}-100 text-{{ $form['color'] }}-800 rounded-full text-xs font-semibold">
                            {{ $form['extension'] }}
                        </span>
                    </div>

                    <!-- File Name -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate" title="{{ $form['display_name'] }}">
                        {{ $form['display_name'] }}
                    </h3>

                    <!-- File Info -->
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                            <span>Size: {{ $form['size'] }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $form['modified'] }}</span>
                        </div>
                    </div>

                    <!-- Assignment Stats -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <div>
                                <p class="text-gray-600">Assigned</p>
                                <p class="font-bold text-gray-800">{{ $form['assignment_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Pending</p>
                                <p class="font-bold text-yellow-600">{{ $form['pending_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Done</p>
                                <p class="font-bold text-green-600">{{ $form['completed_count'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2 border-t pt-4">
                        <a href="{{ route('forms.show', $form['name']) }}" class="btn-primary flex-1 text-center text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Assign
                        </a>
                        <a href="{{ route('forms.download', $form['name']) }}" class="btn-secondary flex-1 text-center text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                        <form action="{{ route('forms.destroy', $form['name']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this form and all its assignments?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger text-sm">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 mb-4">{{ $search ? 'No forms found matching your search.' : 'No forms uploaded yet.' }}</p>
            <a href="{{ route('forms.create') }}" class="btn-primary">Upload First Form</a>
        </div>
    @endif
</div>
@endsection