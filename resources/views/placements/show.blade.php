@extends('layouts.app')

@section('title', 'Placement Details')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
        <a href="{{ route('placements.index') }}" class="hover:text-blue-600">Placements</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span>Placement #{{ $placement->id }}</span>
    </div>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                {{ $placement->job_title }}
                @if($placement->is_confirmed)
                    <span class="px-3 py-1 text-sm font-semibold bg-green-100 text-green-800 rounded-full">Confirmed</span>
                @endif
            </h1>
            <p class="text-gray-600 mt-1">{{ $placement->company->name }} • {{ $placement->location }}</p>
        </div>
        <div class="flex gap-2">
            @can('update', $placement)
                <a href="{{ route('placements.edit', $placement) }}" class="btn-secondary">Edit</a>
            @endcan
            @can('delete', $placement)
                <form action="{{ route('placements.destroy', $placement) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this placement?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            @endcan
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Placement Details Card -->
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Placement Details</h2>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Job Title</label>
                    <p class="text-gray-800 font-semibold">{{ $placement->job_title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Placement Type</label>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                        {{ $placement->placement_type == 'Full-Time' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $placement->placement_type == 'Internship' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $placement->placement_type == 'Part-Time' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $placement->placement_type == 'Contract' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ $placement->placement_type }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Package</label>
                    <p class="text-2xl font-bold text-green-600">₹ {{ number_format($placement->package_lpa, 2) }} LPA</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Location</label>
                    <p class="text-gray-800">{{ $placement->location ?? 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Offer Date</label>
                    <p class="text-gray-800">{{ $placement->offer_date?->format('d M, Y') ?? 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Joining Date</label>
                    <p class="text-gray-800">{{ $placement->joining_date?->format('d M, Y') ?? 'Not specified' }}</p>
                </div>
            </div>

            @if($placement->description)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Job Description</label>
                    <p class="text-gray-700 whitespace-pre-line">{{ $placement->description }}</p>
                </div>
            @endif
        </div>

        <!-- Related Project -->
        @if($placement->project)
            <div class="card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Related Project</h2>

                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">{{ $placement->project->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $placement->project->description }}</p>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-gray-600">
                                Guide: <span class="font-medium">{{ $placement->project->guide->name ?? 'Not assigned' }}</span>
                            </span>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                {{ $placement->project->status == 'COMPLETED' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $placement->project->status == 'IN_PROGRESS' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $placement->project->status == 'PLANNING' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $placement->project->status == 'ON_HOLD' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $placement->project->status)) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('projects.show', $placement->project) }}" class="btn-secondary text-sm">View Project</a>
                </div>
            </div>
        @endif

        <!-- Confirmation Section -->
        @if(!$placement->is_confirmed)
            @can('update', $placement)
                <div class="card bg-yellow-50 border-2 border-yellow-200">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-yellow-800 mb-1">Placement Not Confirmed</h3>
                            <p class="text-sm text-yellow-700 mb-3">This placement is pending confirmation. Once confirmed, this will be marked as the final placement for the student.</p>
                            <form action="{{ route('placements.confirm', $placement) }}" method="POST" onsubmit="return confirm('Are you sure you want to confirm this placement as final? This action cannot be undone.');">
                                @csrf
                                <button type="submit" class="btn-primary">Confirm Final Placement</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        @else
            <div class="card bg-green-50 border-2 border-green-200">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-green-800 mb-1">Confirmed Placement</h3>
                        <p class="text-sm text-green-700">This is the confirmed final placement for the student.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Activity Timeline -->
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Activity Timeline</h2>

            <div class="space-y-4">
                @if($placement->is_confirmed)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Placement Confirmed</p>
                            <p class="text-sm text-gray-600">{{ $placement->updated_at->format('d M, Y h:i A') }}</p>
                        </div>
                    </div>
                @endif

                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Placement Created</p>
                        <p class="text-sm text-gray-600">{{ $placement->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Student Information -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h3>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($placement->student->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $placement->student->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $placement->student->roll_number }}</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <label class="block text-gray-600">Department</label>
                    <p class="font-medium text-gray-800">{{ $placement->student->user->department->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-gray-600">Email</label>
                    <p class="font-medium text-gray-800">{{ $placement->student->user->email }}</p>
                </div>

                @if($placement->student->cgpa)
                    <div>
                        <label class="block text-gray-600">CGPA</label>
                        <p class="font-medium text-gray-800">{{ number_format($placement->student->cgpa, 2) }}</p>
                    </div>
                @endif
            </div>

            <a href="{{ route('students.show', $placement->student) }}" class="btn-secondary w-full mt-4 text-center">View Student Profile</a>
        </div>

        <!-- Company Information -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Company Information</h3>

            <div class="space-y-3 text-sm">
                <div>
                    <label class="block text-gray-600">Company Name</label>
                    <p class="font-medium text-gray-800">{{ $placement->company->name }}</p>
                </div>

                @if($placement->company->website)
                    <div>
                        <label class="block text-gray-600">Website</label>
                        <a href="{{ $placement->company->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $placement->company->website }}</a>
                    </div>
                @endif

                @if($placement->company->contact_person)
                    <div>
                        <label class="block text-gray-600">Contact Person</label>
                        <p class="font-medium text-gray-800">{{ $placement->company->contact_person }}</p>
                    </div>
                @endif

                @if($placement->company->contact_email)
                    <div>
                        <label class="block text-gray-600">Contact Email</label>
                        <p class="font-medium text-gray-800">{{ $placement->company->contact_email }}</p>
                    </div>
                @endif

                @if($placement->company->contact_phone)
                    <div>
                        <label class="block text-gray-600">Contact Phone</label>
                        <p class="font-medium text-gray-800">{{ $placement->company->contact_phone }}</p>
                    </div>
                @endif
            </div>

            <a href="{{ route('companies.show', $placement->company) }}" class="btn-secondary w-full mt-4 text-center">View Company Details</a>
        </div>

        <!-- Metadata -->
        <div class="card bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Metadata</h3>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Created At:</span>
                    <span class="font-medium text-gray-800">{{ $placement->created_at->format('d M, Y') }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Last Updated:</span>
                    <span class="font-medium text-gray-800">{{ $placement->updated_at->diffForHumans() }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-semibold {{ $placement->is_confirmed ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $placement->is_confirmed ? 'Confirmed' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
