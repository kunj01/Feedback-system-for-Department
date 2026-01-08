@extends('layouts.app')

@section('title', 'Feedback Templates - SCFMS')
@section('page-title', 'Feedback Template Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Feedback Templates</h2>
        <a href="{{ route('feedback-templates.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Template
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card">
            <p class="text-gray-600 text-sm">Total Templates</p>
            <p class="text-3xl font-bold text-gray-800">{{ count($templates) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Course Templates</p>
            <p class="text-3xl font-bold text-blue-600">{{ count(array_filter($templates, fn($t) => $t['target_type'] === 'Course')) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Faculty Templates</p>
            <p class="text-3xl font-bold text-purple-600">{{ count(array_filter($templates, fn($t) => $t['target_type'] === 'Faculty')) }}</p>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($templates as $template)
            <div class="card hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $template['name'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $template['description'] }}</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                        {{ $template['target_type'] }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-4 text-center">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-600 text-xs">Questions</p>
                        <p class="text-xl font-bold text-gray-800">{{ $template['question_count'] ?? 0 }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-600 text-xs">Created</p>
                        <p class="text-sm font-bold text-gray-800">{{ date('M d', strtotime($template['created_at'])) }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-600 text-xs">Status</p>
                        <p class="text-sm font-bold text-green-600">Active</p>
                    </div>
                </div>

                <div class="border-t pt-4 flex space-x-2">
                    <a href="{{ route('feedback-templates.edit', $template['id']) }}" class="btn-secondary flex-1 text-center text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('feedback-templates.clone', $template['id']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-secondary w-full text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Clone
                        </button>
                    </form>
                    <form action="{{ route('feedback-templates.destroy', $template['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-2">
                <div class="card text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 mb-4">No feedback templates created yet</p>
                    <a href="{{ route('feedback-templates.create') }}" class="btn-primary">Create First Template</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
