@extends('layouts.app')

@section('title', 'Department Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Department Management</h1>
            <p class="text-gray-600 mt-1">Manage academic departments and their heads</p>
        </div>
        @can('create', App\Models\Department::class)
        <a href="{{ route('departments.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Department
        </a>
        @endcan
    </div>
</div>

<!-- Search -->
<div class="card mb-6">
    <form method="GET" action="{{ route('departments.index') }}" class="flex gap-4">
        <div class="flex-1">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or code..."
                class="input-field"
            >
        </div>
        <button type="submit" class="btn-primary">Search</button>
        <a href="{{ route('departments.index') }}" class="btn-secondary">Reset</a>
    </form>
</div>

<!-- Departments Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($departments as $department)
    <div class="card hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($department->code, 0, 2)) }}
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $department->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $department->code }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-3 mb-4">
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-gray-600">Head:</span>
                <span class="ml-1 font-medium text-gray-800">{{ $department->head->name ?? 'Not Assigned' }}</span>
            </div>
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                <span class="text-gray-600">Students:</span>
                <span class="ml-1 font-medium text-blue-600">{{ $department->students->count() }}</span>
            </div>
        </div>

        <div class="flex gap-2 pt-4 border-t border-gray-200">
            @can('view', $department)
            <a href="{{ route('departments.show', $department) }}" class="flex-1 text-center btn-secondary text-sm py-2">
                View
            </a>
            @endcan
            @can('update', $department)
            <a href="{{ route('departments.edit', $department) }}" class="flex-1 text-center btn-primary text-sm py-2">
                Edit
            </a>
            @endcan
            @can('delete', $department)
            <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger text-sm py-2 px-3" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
            @endcan
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="card text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <p class="mt-2 text-gray-500">No departments found</p>
        </div>
    </div>
    @endforelse
</div>

@if($departments->hasPages())
<div class="mt-6">
    {{ $departments->links() }}
</div>
@endif
@endsection
