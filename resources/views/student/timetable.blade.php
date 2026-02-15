@extends('layouts.app')

@section('title', 'My Timetable')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        My Timetable
                    </h1>
                    @if(isset($student) && $student->division)
                        <p class="text-gray-600 mt-1">{{ $student->division->name }} - Batch {{ $student->batchGroup->batch_name ?? 'All' }}</p>
                    @endif
                </div>
                <a href="{{ route('student.dashboard') }}" class="btn-secondary flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if(isset($error))
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
            <p class="text-yellow-800">{{ $error }}</p>
        </div>
        @elseif(isset($timetable))
        <!-- Timetable Grid -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-fixed">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <th class="border border-indigo-700 px-2 py-2 text-center font-semibold text-sm w-24">Time</th>
                            @foreach($days as $day)
                                <th class="border border-indigo-700 px-2 py-2 text-center font-semibold text-sm">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $timeSlot)
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 text-center font-semibold bg-gray-50 whitespace-nowrap text-xs">
                                {{ $timeSlot }}
                            </td>
                            @foreach($days as $day)
                                <td class="border border-gray-300 px-1 py-1 text-center align-top relative">
                                    @php
                                        $entries = $timetable[$timeSlot][$day] ?? collect();
                                        
                                        // Group entries by subject and faculty
                                        $groupedEntries = $entries->groupBy(function($entry) {
                                            return $entry->subject_id . '-' . $entry->faculty_id . '-' . $entry->room_no;
                                        });
                                    @endphp
                                    
                                    @if($entries->isEmpty())
                                        <div class="text-gray-400 text-xs py-2">-</div>
                                    @else
                                        @foreach($groupedEntries as $key => $group)
                                            @php
                                                $firstEntry = $group->first();
                                                $hasLab = $group->whereNotNull('batch_id')->count() > 0;
                                                $hasLecture = $group->whereNull('batch_id')->count() > 0;
                                                
                                                if ($hasLab && !$hasLecture) {
                                                    $bgColor = 'bg-green-100';
                                                    $borderColor = 'border-green-300';
                                                } elseif ($hasLecture) {
                                                    $bgColor = 'bg-blue-100';
                                                    $borderColor = 'border-blue-300';
                                                } else {
                                                    $bgColor = 'bg-gray-50';
                                                    $borderColor = 'border-gray-300';
                                                }
                                                
                                                $batchList = $group->whereNotNull('batch_id')->pluck('batch.batch_name')->filter()->join(', ');
                                            @endphp
                                            <div class="mb-1 p-1 rounded border {{ $bgColor }} {{ $borderColor }} text-xs"
                                                 title="{{ $firstEntry->subject->subject_name }} - {{ $firstEntry->faculty->faculty_name }}">
                                                
                                                <div class="font-bold text-xs">
                                                    {{ $firstEntry->subject->subject_code }}
                                                </div>
                                                
                                                <div class="text-[10px] mt-0.5">
                                                    {{ $firstEntry->faculty->short_code }}
                                                </div>
                                                
                                                <div class="text-[10px]">
                                                    {{ $firstEntry->room_no }}
                                                </div>
                                                
                                                @if($batchList)
                                                    <div class="text-[10px] font-semibold mt-0.5 text-green-700">
                                                        {{ $batchList }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="mt-6 flex items-center justify-center space-x-6">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-200 border-2 border-blue-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Lecture</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-200 border-2 border-green-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-700">Lab (Your Batch)</span>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    .btn-secondary {
        @apply bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors;
    }
</style>
@endsection
