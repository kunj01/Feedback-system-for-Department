@extends('layouts.app')

@section('title', 'Import Students')
@section('page-title', 'Import Students')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Import Wizard -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Bulk Student Import</h2>
                <a href="{{ route('students.import.template') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Template
                </a>
            </div>

            <!-- Upload File -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Upload Filled Template</h3>

                <form action="{{ route('students.import.dry-run') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Select Excel File</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                        Upload & Validate
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Imports -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Recent Imports</h3>
                <a href="{{ route('students.import.logs') }}" class="text-blue-600 hover:text-blue-700">View All →</a>
            </div>

            <div class="text-gray-600 text-sm">
                View your import history and download import reports in the <a href="{{ route('students.import.logs') }}" class="text-blue-600 hover:underline">Import Logs</a> section.
            </div>
        </div>

    </div>
</div>
@endsection
