@extends('layouts.app')

@section('title', 'Manage External Speakers')
@section('page-title', 'Manage External Speakers')

@section('content')
<!-- Auto-dismissing Success Notification -->
@if(session('success'))
    <div id="success-notification" class="fixed top-20 right-6 z-50 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
        <button onclick="document.getElementById('success-notification').remove()" class="ml-4 text-green-600 hover:text-green-800">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.getElementById('success-notification');
            if (notification) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 3000);
    </script>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">External Speaker Management</h2>
        <p class="text-gray-600 mt-1">Review and approve speakers submitted by faculty members</p>
    </div>
    <div class="flex gap-3">
        <!-- Auto-Approve Toggle -->
        <form action="{{ route('admin.speakers.toggle-auto-approve') }}" method="POST" class="inline">
            @csrf
            <button 
                type="submit"
                class="px-6 py-2.5 rounded-lg transition-colors font-medium inline-flex items-center {{ $autoApproveEnabled ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-300 hover:bg-gray-400 text-gray-700' }}"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($autoApproveEnabled)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    @endif
                </svg>
                Auto-Approve: {{ $autoApproveEnabled ? 'ON' : 'OFF' }}
            </button>
        </form>
        
        <form action="{{ route('admin.speakers.auto-approve') }}" method="POST" class="inline">
            @csrf
            <button 
                type="submit"
                onclick="return confirm('Auto-approve all faculty-approved speakers?');"
                class="bg-purple-600 text-white px-6 py-2.5 rounded-lg hover:bg-purple-700 transition-colors font-medium inline-flex items-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Approve All Pending
            </button>
        </form>
        
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
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<!-- Filter Tabs with Sort Options -->
<div class="mb-6 flex justify-between items-center border-b">
    <div class="flex gap-4">
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
    
    <!-- Sort Options -->
    <div class="flex items-center gap-3 pb-2">
        <label class="text-sm font-medium text-gray-700">Sort by:</label>
        <form method="GET" action="{{ route('admin.speakers.index') }}" class="flex items-center gap-2">
            <select name="sort_by" onchange="this.form.submit()" class="input-field py-1.5 text-sm">
                <option value="date" {{ request('sort_by', 'date') === 'date' ? 'selected' : '' }}>Date</option>
                <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
            </select>
            <select name="sort_order" onchange="this.form.submit()" class="input-field py-1.5 text-sm">
                <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>Newest First</option>
                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Oldest First</option>
            </select>
        </form>
    </div>
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
                                <div class="flex justify-end items-center gap-3">
                                    <a 
                                        href="{{ route('admin.speakers.show', $speaker->id) }}" 
                                        class="text-blue-600 hover:text-blue-900"
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
                                                class="text-green-600 hover:text-green-900"
                                                title="Approve"
                                            >
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                    <form 
                                        action="{{ route('admin.speakers.destroy', $speaker->id) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this speaker? This action cannot be undone.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            title="Delete Speaker"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
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
