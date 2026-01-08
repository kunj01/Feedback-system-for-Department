@extends('layouts.app')

@section('title', 'Manage External Speakers')
@section('page-title', 'Manage External Speakers')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">External Speaker Management</h2>
        <p class="text-gray-600 mt-1">Review and approve speakers submitted by faculty members</p>
    </div>
    <a 
        href="{{ route('admin.speakers.create') }}" 
        class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add New Speaker
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<!-- Filter Tabs -->
<div class="mb-6 flex gap-4 border-b">
    <button 
        onclick="filterSpeakers('all')" 
        class="filter-tab active px-4 py-2 font-medium border-b-2 border-blue-600 text-blue-600" 
        data-status="all"
    >
        All Speakers ({{ $speakers->total() }})
    </button>
    <button 
        onclick="filterSpeakers('pending')" 
        class="filter-tab px-4 py-2 font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900" 
        data-status="pending"
    >
        Pending ({{ $speakers->where('approval_status', 'pending')->count() }})
    </button>
    <button 
        onclick="filterSpeakers('approved')" 
        class="filter-tab px-4 py-2 font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900" 
        data-status="approved"
    >
        Approved ({{ $speakers->where('approval_status', 'approved')->count() }})
    </button>
    <button 
        onclick="filterSpeakers('rejected')" 
        class="filter-tab px-4 py-2 font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900" 
        data-status="rejected"
    >
        Rejected ({{ $speakers->where('approval_status', 'rejected')->count() }})
    </button>
</div>

@if($speakers->count() > 0)
    <div class="card bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Speaker Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department & Venue
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Schedule
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Submitted By
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($speakers as $speaker)
                        <tr class="hover:bg-gray-50 speaker-row" data-status="{{ $speaker->approval_status }}">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $speaker->name }}</div>
                                <div class="text-sm text-gray-500">{{ $speaker->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $speaker->department }}</div>
                                <div class="text-xs text-gray-500">{{ $speaker->venue }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $speaker->date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $speaker->creator->name }}</div>
                                <div class="text-xs text-gray-500">{{ $speaker->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($speaker->approval_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pending
                                    </span>
                                @elseif($speaker->approval_status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a 
                                    href="{{ route('admin.speakers.show', $speaker->id) }}" 
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                    title="View Details"
                                >
                                    View
                                </a>
                                @if($speaker->approval_status === 'pending')
                                    <form 
                                        action="{{ route('admin.speakers.approve', $speaker->id) }}" 
                                        method="POST" 
                                        class="inline"
                                    >
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="text-green-600 hover:text-green-900 mr-3"
                                            title="Approve"
                                        >
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($speakers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $speakers->links() }}
            </div>
        @endif
    </div>
@else
    <div class="card bg-white text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">No speakers found</h3>
        <p class="mt-2 text-gray-600">Get started by adding a new external speaker.</p>
    </div>
@endif

<script>
function filterSpeakers(status) {
    const tabs = document.querySelectorAll('.filter-tab');
    const rows = document.querySelectorAll('.speaker-row');
    
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-blue-600', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-600');
    });
    
    const activeTab = document.querySelector(`[data-status="${status}"]`);
    activeTab.classList.add('active', 'border-blue-600', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-600');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
