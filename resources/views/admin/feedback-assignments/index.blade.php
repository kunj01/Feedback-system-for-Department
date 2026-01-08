@extends('layouts.app')

@section('title', 'Feedback Assignments - SCFMS')
@section('page-title', 'Feedback Assignment Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Feedback Assignments</h2>
        <a href="{{ route('feedback-assignments.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Assignment
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card">
            <p class="text-gray-600 text-sm">Total Assignments</p>
            <p class="text-3xl font-bold text-gray-800">{{ count($assignments) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Active</p>
            <p class="text-3xl font-bold text-green-600">{{ count(array_filter($assignments, fn($a) => $a['status'] === 'active')) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Pending</p>
            <p class="text-3xl font-bold text-blue-600">{{ count(array_filter($assignments, fn($a) => $a['status'] === 'pending')) }}</p>
        </div>

        <div class="card">
            <p class="text-gray-600 text-sm">Closed</p>
            <p class="text-3xl font-bold text-gray-600">{{ count(array_filter($assignments, fn($a) => $a['status'] === 'closed')) }}</p>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Template</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Period</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Response Rate</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($assignments as $assignment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $assignment['course'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assignment['template'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ date('M d', strtotime($assignment['start_date'])) }} - {{ date('M d, Y', strtotime($assignment['end_date'])) }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $assignment['response_rate'] ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ $assignment['response_rate'] ?? 0 }}%</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($assignment['status'] === 'active')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                                @elseif($assignment['status'] === 'pending')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Closed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm flex space-x-2">
                                <a href="{{ route('feedback-assignments.edit', $assignment['id']) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if($assignment['status'] === 'active')
                                    <form action="{{ route('feedback-assignments.extend-deadline', $assignment['id']) }}" method="POST" class="inline" onsubmit="return confirm('Extend deadline?');">
                                        @csrf
                                        <button type="submit" class="text-purple-600 hover:text-purple-800" title="Extend Deadline">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('feedback-assignments.destroy', $assignment['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <p class="text-gray-600">No feedback assignments yet.</p>
                                <a href="{{ route('feedback-assignments.create') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">Create Now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
