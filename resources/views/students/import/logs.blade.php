@extends('layouts.app')

@section('title', 'Import Logs')
@section('page-title', 'Student Import Logs')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Import History</h2>
        <a href="{{ route('students.import.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
            New Import
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($logs->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-semibold">No import logs found</p>
                <p class="text-sm mt-2">Start by importing students using the wizard</p>
            </div>
        @else
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Filename</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Uploaded By</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Results</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $log->id }}</td>
                        <td class="px-6 py-4 text-sm">{{ $log->filename }}</td>
                        <td class="px-6 py-4 text-sm">{{ $log->uploader->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($log->status === 'COMPLETED')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Completed</span>
                            @elseif($log->status === 'FAILED')
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Failed</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">{{ $log->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center space-x-4 text-xs">
                                <span class="text-green-600">✓ {{ $log->created_count }}</span>
                                <span class="text-orange-600">↻ {{ $log->updated_count }}</span>
                                <span class="text-red-600">✗ {{ $log->skipped_count }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('students.import.show', $log->id) }}" class="text-blue-600 hover:text-blue-700 mr-3">View</a>
                            <a href="{{ route('students.import.download', $log->id) }}" class="text-green-600 hover:text-green-700">Download</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
