@extends('layouts.app')

@section('title', 'Import Details')
@section('page-title', 'Import Details #' . $log->id)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('students.import.logs') }}" class="text-blue-600 hover:text-blue-700">
                ← Back to Logs
            </a>
            <a href="{{ route('students.import.download', $log->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                Download Report
            </a>
        </div>

        <!-- Import Info Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Import Information</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Import ID</label>
                    <p class="font-semibold">#{{ $log->id }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <p>
                        @if($log->status === 'COMPLETED')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Completed</span>
                        @elseif($log->status === 'FAILED')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Failed</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">{{ $log->status }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Filename</label>
                    <p class="font-semibold">{{ $log->filename }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Uploaded By</label>
                    <p class="font-semibold">{{ $log->uploader->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Import Date</label>
                    <p class="font-semibold">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Type</label>
                    <p class="font-semibold">{{ $log->import_type }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Import Statistics</h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <div class="text-3xl font-bold text-gray-800">{{ $log->total_rows }}</div>
                    <div class="text-sm text-gray-600">Total Rows</div>
                </div>
                <div class="p-4 bg-green-100 rounded-lg">
                    <div class="text-3xl font-bold text-green-800">{{ $log->created_count }}</div>
                    <div class="text-sm text-green-700">Created</div>
                </div>
                <div class="p-4 bg-orange-100 rounded-lg">
                    <div class="text-3xl font-bold text-orange-800">{{ $log->updated_count }}</div>
                    <div class="text-sm text-orange-700">Updated</div>
                </div>
                <div class="p-4 bg-red-100 rounded-lg">
                    <div class="text-3xl font-bold text-red-800">{{ $log->skipped_count }}</div>
                    <div class="text-sm text-red-700">Skipped</div>
                </div>
            </div>
        </div>

        <!-- Errors Section -->
        @if(!empty($log->errors) && count($log->errors) > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-red-800 mb-4">Errors ({{ count($log->errors) }})</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-red-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-red-800">Row</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-red-800">Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($log->errors as $error)
                        <tr class="border-t border-red-100">
                            <td class="px-4 py-2 text-sm">{{ $error['row'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-red-600">
                                @if(isset($error['errors']) && is_array($error['errors']))
                                    <ul class="list-disc list-inside">
                                        @foreach($error['errors'] as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $error['error'] ?? json_encode($error) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Summary Section -->
        @if($log->summary)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">Import Summary</h3>
            <pre class="text-sm text-blue-700 bg-white p-4 rounded overflow-x-auto">{{ $log->summary }}</pre>
        </div>
        @endif

        <!-- Imported Students Data -->
        @if(isset($students) && $students->count() > 0)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Additional Information - Imported Student Details</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">10%</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">12%</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diploma</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">B.Tech CGPA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Personal Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CHARUSAT Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admission</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Eligible</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Counsellor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Placed By CHARUSAT</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package (LPA)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stipend</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                        @php
                            $placement = $student->placements->first();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $student->student_id ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $student->gender == 'M' ? 'Male' : ($student->gender == 'F' ? 'Female' : 'N/A') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->city ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->ssc_percentage ? number_format($student->ssc_percentage, 2) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->hsc_percentage ? number_format($student->hsc_percentage, 2) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->diploma_percentage ? number_format($student->diploma_percentage, 2) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->btech_cgpa_upto_5th ? number_format($student->btech_cgpa_upto_5th, 2) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->contact ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->personal_email ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->email ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->admission_type ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->is_eligible ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $student->counsellor_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $placement ? ($placement->placed_by_charusat ?? 'N/A') : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $placement && $placement->company ? $placement->company->name : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $placement && $placement->package ? number_format($placement->package, 2) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $placement && $placement->stipend ? '₹' . number_format($placement->stipend, 0) : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $placement ? ($placement->remarks ?? 'N/A') : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
