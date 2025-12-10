@extends('layouts.app')

@section('title', 'Import Preview')
@section('page-title', 'Import Preview - Dry Run Results')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">

        <!-- Summary Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Import Summary</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <div class="text-3xl font-bold text-gray-800">{{ $summary['total_rows'] }}</div>
                    <div class="text-sm text-gray-600">Total Rows</div>
                </div>
                <div class="p-4 bg-green-100 rounded-lg">
                    <div class="text-3xl font-bold text-green-800">{{ $summary['valid_count'] }}</div>
                    <div class="text-sm text-green-700">Valid</div>
                </div>
                <div class="p-4 bg-yellow-100 rounded-lg">
                    <div class="text-3xl font-bold text-yellow-800">{{ $summary['warning_count'] }}</div>
                    <div class="text-sm text-yellow-700">Warnings</div>
                </div>
                <div class="p-4 bg-red-100 rounded-lg">
                    <div class="text-3xl font-bold text-red-800">{{ $summary['error_count'] }}</div>
                    <div class="text-sm text-red-700">Errors</div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Will Create: <strong class="text-blue-600">{{ $summary['will_create'] }}</strong> new students</span>
                    <span class="text-gray-600">Will Update: <strong class="text-orange-600">{{ $summary['will_update'] }}</strong> existing students</span>
                </div>
            </div>
        </div>

        <!-- Error Rows -->
        @if(count($validationResults['errors']) > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-red-800 mb-3">❌ Rows with Errors ({{ count($validationResults['errors']) }})</h3>
            <p class="text-sm text-red-700 mb-4">These rows will be skipped during import. Please fix the errors and re-upload.</p>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-red-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-red-800">Row</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-red-800">ID NO</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-red-800">Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($validationResults['errors'] as $error)
                        <tr class="border-t border-red-100">
                            <td class="px-4 py-2 text-sm">{{ $error['row_number'] }}</td>
                            <td class="px-4 py-2 text-sm">{{ $error['data']['ID NO'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-red-600">
                                <ul class="list-disc list-inside">
                                    @foreach($error['errors'] as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Warning Rows -->
        @if(count($validationResults['warnings']) > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-yellow-800 mb-3">⚠️ Rows with Warnings ({{ count($validationResults['warnings']) }})</h3>
            <p class="text-sm text-yellow-700 mb-4">These rows can be imported but have warnings. Review carefully.</p>

            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-yellow-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-yellow-800">Row</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-yellow-800">ID NO</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-yellow-800">Full Name</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-yellow-800">Warnings</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($validationResults['warnings'] as $warning)
                        <tr class="border-t border-yellow-100">
                            <td class="px-4 py-2 text-sm">{{ $warning['row_number'] }}</td>
                            <td class="px-4 py-2 text-sm">{{ $warning['data']['ID NO'] }}</td>
                            <td class="px-4 py-2 text-sm">{{ $warning['data']['Full Name'] }}</td>
                            <td class="px-4 py-2 text-sm text-yellow-600">
                                <ul class="list-disc list-inside">
                                    @foreach($warning['warnings'] as $warn)
                                        <li>{{ $warn }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Valid Rows (collapsed by default) -->
        @if(count($validationResults['valid']) > 0)
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-green-800 mb-3">✅ Valid Rows ({{ count($validationResults['valid']) }})</h3>
            <p class="text-sm text-green-700 mb-4">These rows will be imported successfully.</p>

            <details class="cursor-pointer">
                <summary class="text-sm font-semibold text-green-700 hover:text-green-800">Click to view all valid rows</summary>
                <div class="mt-4 overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-green-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-green-800">Row</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-green-800">ID NO</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-green-800">Full Name</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-green-800">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-green-800">City</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($validationResults['valid'] as $valid)
                            <tr class="border-t border-green-100">
                                <td class="px-4 py-2 text-sm">{{ $valid['row_number'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $valid['data']['ID NO'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $valid['data']['Full Name'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $valid['data']['CHARUSAT Email Id'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $valid['data']['City'] ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </div>
        @endif

        <!-- Import Actions -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Proceed with Import?</h3>

            @if($summary['error_count'] > 0)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <strong>Note:</strong> Rows with errors will be skipped. Only valid and warning rows will be imported.
                </div>
            @endif

            <form action="{{ route('students.import.execute') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <input type="hidden" name="file_data" value="{{ base64_encode(serialize(['results' => $validationResults, 'filename' => $filename])) }}">

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Merge Strategy</label>
                    <select name="strategy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="merge">Merge - Update only blank/NA fields</option>
                        <option value="overwrite">Overwrite - Replace all fields</option>
                        <option value="skip">Skip - Don't update existing records</option>
                    </select>
                    <p class="text-sm text-gray-600 mt-1">Choose how to handle existing student records</p>
                </div>

                <div class="flex items-center space-x-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                        Confirm & Import
                    </button>
                    <a href="{{ route('students.import.index') }}" class="px-6 py-2 bg-red-500 hover:bg-red-700 text-white font-semibold rounded-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
