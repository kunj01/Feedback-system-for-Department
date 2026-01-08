@extends('layouts.app')

@section('title', 'Upload Form - SCFMS')
@section('page-title', 'Upload New Form')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('forms.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Forms
    </a>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Upload New Form</h2>

        <form action="{{ route('forms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- File Upload Area -->
            <div>
                <label class="label required">Select Form File</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition" id="drop-zone">
                    <input type="file" name="form_file" id="form_file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx" required onchange="updateFileName(this)">
                    <label for="form_file" class="cursor-pointer">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 font-medium mb-2">Click to upload or drag and drop</p>
                        <p class="text-gray-500 text-sm">PDF, Word, Excel files (Max 10MB)</p>
                        <p class="text-blue-600 font-semibold mt-4" id="file-name"></p>
                    </label>
                </div>
                @error('form_file')
                    <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Supported Formats -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Supported File Types</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li><strong>PDF</strong> - Portable Document Format (.pdf)</li>
                        <li><strong>Word</strong> - Microsoft Word Documents (.doc, .docx)</li>
                        <li><strong>Excel</strong> - Microsoft Excel Spreadsheets (.xls, .xlsx)</li>
                        <li>Maximum file size: <strong>10 MB</strong></li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex space-x-4 pt-6 border-t">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Form
                </button>
                <a href="{{ route('forms.index') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileNameDisplay = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileNameDisplay.textContent = input.files[0].name;
    }
}

// Drag and drop functionality
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('form_file');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }, false);
});

dropZone.addEventListener('drop', (e) => {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileName(fileInput);
    }
}, false);
</script>
@endsection
