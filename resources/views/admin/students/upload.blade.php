@extends('layouts.app')

@section('title', 'Upload Students')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Bulk Student Upload
                    </h1>
                    <p class="text-gray-600 mt-1">Upload students via CSV file</p>
                </div>
                <a href="{{ route('admin.students.upload.template') }}" class="btn-primary flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Template
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if(session('errors') && count(session('errors')) > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
            <h3 class="text-yellow-800 font-semibold mb-2">Import Errors:</h3>
            <ul class="text-yellow-700 text-sm space-y-1 max-h-48 overflow-y-auto">
                @foreach(session('errors') as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Upload Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Upload CSV File</h2>
            
            <form method="POST" action="{{ route('admin.students.upload.process') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="csv_file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-12 h-12 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">CSV file (MAX. 2MB)</p>
                                <p id="fileName" class="mt-2 text-sm text-indigo-600 font-medium"></p>
                            </div>
                            <input id="csv_file" name="csv_file" type="file" accept=".csv,.txt" required class="hidden" onchange="updateFileName(this)" />
                        </label>
                    </div>
                    @error('csv_file')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-success flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Students
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">CSV Format Instructions</h2>
            
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Required Columns:</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <li><code class="bg-gray-100 px-2 py-1 rounded">enrollment_no</code> - Unique student enrollment number</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">name</code> - Full name of the student</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">semester</code> - Semester number (e.g., 4, 6)</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">branch</code> - Branch code (e.g., IT, CS)</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">division</code> - Division number (e.g., 1, 2)</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">batch</code> - Batch name (e.g., A1, B2, C1)</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Optional Columns:</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <li><code class="bg-gray-100 px-2 py-1 rounded">email</code> - Student email address</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">contact</code> - Contact number</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Example:</h3>
                    <pre class="text-sm text-gray-700 overflow-x-auto">
enrollment_no,name,semester,branch,division,batch,email,contact
22IT001,John Doe,4,IT,2,A1,john@example.com,9876543210
22IT002,Jane Smith,4,IT,2,A1,jane@example.com,9876543211
22IT003,Bob Wilson,4,IT,2,B1,bob@example.com,9876543212
                    </pre>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <h3 class="font-semibold text-blue-900 mb-2">System Behavior:</h3>
                    <ul class="list-disc list-inside text-blue-800 space-y-1">
                        <li>Divisions and batches will be created automatically if they don't exist</li>
                        <li>Existing students (by enrollment number) will be updated</li>
                        <li>New students will be added to the system</li>
                        <li>Invalid rows will be skipped and reported</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Uploads -->
        @if(count($recentUploads) > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Upload Activity</h2>
            <div class="space-y-2">
                @foreach($recentUploads as $upload)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $upload->count }} students</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($upload->created_at)->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    .btn-primary {
        @apply bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors;
    }
    .btn-success {
        @apply bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors;
    }
</style>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    if (fileName) {
        document.getElementById('fileName').textContent = 'Selected: ' + fileName;
    }
}
</script>
@endsection
