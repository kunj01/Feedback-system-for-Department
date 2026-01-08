<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form Assignments - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Feedback Form Assignments</h1>
            <p class="text-gray-600 mt-2">Assign feedback forms to students</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Assignment Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Assign Forms</h2>
                
                <form method="POST" action="{{ route('admin.feedback.assignments.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Select Student</label>
                        <select name="student_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Choose Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->user->name }} ({{ $student->student_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Academic Year</label>
                        <input type="text" name="academic_year" required placeholder="e.g., 2024-25" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('academic_year')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Select Subjects</label>
                        <div class="space-y-2">
                            @foreach($subjects as $id => $name)
                                <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" name="subject_ids[]" value="{{ $id }}" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-gray-700">{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('subject_ids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                        Assign Selected Subjects
                    </button>
                </form>
            </div>

            <!-- Current Assignments -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Current Assignments</h2>
                
                @if($assignments->isEmpty())
                    <p class="text-gray-500 text-center py-8">No assignments yet</p>
                @else
                    <div class="space-y-4 max-h-[600px] overflow-y-auto">
                        @php
                            $groupedAssignments = $assignments->groupBy('student_id');
                        @endphp
                        
                        @foreach($groupedAssignments as $studentId => $studentAssignments)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">
                                            {{ $studentAssignments->first()->student->user->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $studentAssignments->first()->student->student_id }} | 
                                            Year: {{ $studentAssignments->first()->academic_year }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($studentAssignments as $assignment)
                                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                            <span class="text-sm text-gray-700">
                                                {{ $subjects[$assignment->subject_id] }}
                                            </span>
                                            <form method="POST" action="{{ route('admin.feedback.assignments.destroy', $assignment->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Remove this assignment?')"
                                                        class="text-red-600 hover:text-red-800 text-sm">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('dashboard') }}" 
               class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
