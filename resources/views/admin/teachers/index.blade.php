@extends('layouts.app')

@section('title', 'Manage Teachers')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Teacher Management
                    </h1>
                    <p class="text-gray-600 mt-1">Manage faculty members and their information</p>
                </div>
                <button onclick="openAddTeacherModal()" class="btn-success flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Teacher
                </button>
            </div>
        </div>

        <!-- Teachers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($teachers as $teacher)
            <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all duration-200 overflow-hidden">
                <!-- Compact Header with Avatar -->
                <div class="bg-gradient-to-br from-slate-50 to-gray-50 p-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center flex-shrink-0 ring-2 ring-white">
                            <span class="text-lg font-bold text-indigo-600">{{ substr($teacher->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-gray-900 truncate">{{ $teacher->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $teacher->email }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $teacher->is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' }}">
                            {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <!-- Compact Info Section -->
                <div class="p-4 space-y-2">
                    @if($teacher->department)
                    <div class="flex items-center text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="truncate">{{ $teacher->department }}</span>
                    </div>
                    @endif
                    
                    @if($teacher->designation)
                    <div class="flex items-center text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="truncate">{{ $teacher->designation }}</span>
                    </div>
                    @endif
                    
                    @if($teacher->subjects && $teacher->subjects->count() > 0)
                    <div class="flex items-center text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>{{ $teacher->subjects->count() }} subject(s) assigned</span>
                    </div>
                    @endif
                </div>
                
                <!-- Compact Action Buttons -->
                <div class="px-4 pb-4 pt-2 flex space-x-2">
                    <button onclick='openEditTeacherModal(@json($teacher))' class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-medium py-2 px-3 rounded-md transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </button>
                    <button onclick="deleteTeacher({{ $teacher->id }})" class="bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-medium py-2 px-3 rounded-md transition-colors duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-lg text-gray-500 mb-3">No teachers found</p>
                <button onclick="openAddTeacherModal()" class="btn-success text-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add First Teacher
                </button>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add/Edit Teacher Modal -->
<div id="teacherModal" class="fixed inset-0 hidden z-50 flex items-center justify-center p-4 transition-all duration-300 backdrop-blur-lg" onclick="if(event.target === this) closeTeacherModal()" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 id="teacherModalTitle" class="text-2xl font-bold text-gray-900">Add New Teacher</h3>
            <button onclick="closeTeacherModal()" class="text-gray-400 hover:text-gray-600 transition-colors hover:rotate-90 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="teacherForm" onsubmit="saveTeacher(event)">
            <input type="hidden" id="teacherId" name="teacher_id">
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" id="teacherName" name="name" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Dr. John Smith">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="teacherEmail" name="email" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., john.smith@university.edu">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" id="teacherDepartment" name="department" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Computer Science">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                    <input type="text" id="teacherDesignation" name="designation" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Assistant Professor">
                </div>

                <div id="statusField" style="display: none;">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" id="teacherIsActive" name="is_active" checked class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-700">Active (Teacher can be assigned to subjects)</span>
                    </label>
                </div>
            </div>

            <div id="errorMessages" class="mb-4 hidden">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800" id="errorText"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                <button type="button" onclick="closeTeacherModal()" class="btn-secondary">
                    Cancel
                </button>
                <button type="submit" id="saveTeacherBtn" class="btn-success">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="saveButtonText">Save Teacher</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}

.btn-secondary {
    @apply bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-gray-400;
}

.btn-success {
    @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500;
}
</style>

<script>
function openAddTeacherModal() {
    document.getElementById('teacherModalTitle').textContent = 'Add New Teacher';
    document.getElementById('teacherForm').reset();
    document.getElementById('teacherId').value = '';
    document.getElementById('teacherIsActive').checked = true;
    document.getElementById('statusField').style.display = 'none';
    document.getElementById('errorMessages').classList.add('hidden');
    // Re-enable button in case it was disabled
    document.getElementById('saveTeacherBtn').disabled = false;
    document.getElementById('saveButtonText').textContent = 'Save Teacher';
    document.getElementById('teacherModal').classList.remove('hidden');
}

function openEditTeacherModal(teacher) {
    document.getElementById('teacherModalTitle').textContent = 'Edit Teacher';
    document.getElementById('teacherId').value = teacher.id;
    document.getElementById('teacherName').value = teacher.name;
    document.getElementById('teacherEmail').value = teacher.email;
    document.getElementById('teacherDepartment').value = teacher.department || '';
    document.getElementById('teacherDesignation').value = teacher.designation || '';
    document.getElementById('teacherIsActive').checked = teacher.is_active;
    document.getElementById('statusField').style.display = 'block';
    document.getElementById('errorMessages').classList.add('hidden');
    // Re-enable button in case it was disabled
    document.getElementById('saveTeacherBtn').disabled = false;
    document.getElementById('saveButtonText').textContent = 'Save Teacher';
    document.getElementById('teacherModal').classList.remove('hidden');
}

function closeTeacherModal() {
    document.getElementById('teacherModal').classList.add('hidden');
    document.getElementById('errorMessages').classList.add('hidden');
    document.getElementById('saveTeacherBtn').disabled = false;
    document.getElementById('saveButtonText').textContent = 'Save Teacher';
}

function saveTeacher(event) {
    event.preventDefault();
    
    // Hide previous errors
    document.getElementById('errorMessages').classList.add('hidden');
    
    // Disable button and show loading state
    const saveBtn = document.getElementById('saveTeacherBtn');
    const saveText = document.getElementById('saveButtonText');
    saveBtn.disabled = true;
    saveText.textContent = 'Saving...';
    
    const teacherId = document.getElementById('teacherId').value;
    const data = {
        name: document.getElementById('teacherName').value,
        email: document.getElementById('teacherEmail').value,
        department: document.getElementById('teacherDepartment').value,
        designation: document.getElementById('teacherDesignation').value,
        is_active: document.getElementById('teacherIsActive').checked
    };
    
    console.log('Saving teacher:', data);
    
    const url = teacherId 
        ? `{{ route('admin.teachers.index') }}/${teacherId}`
        : `{{ route('admin.teachers.store') }}`;
    
    const method = teacherId ? 'PUT' : 'POST';
    
    console.log('URL:', url, 'Method:', method);
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            showNotification(data.message, 'success');
            closeTeacherModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            // Show validation errors
            if (data.errors) {
                let errorText = '';
                for (let field in data.errors) {
                    errorText += data.errors[field].join(', ') + ' ';
                }
                document.getElementById('errorText').textContent = errorText;
                document.getElementById('errorMessages').classList.remove('hidden');
            }
            showNotification(data.message || 'Failed to save teacher', 'error');
            // Re-enable button
            saveBtn.disabled = false;
            saveText.textContent = 'Save Teacher';
        }
    })
    .catch(error => {
        console.error('Error saving teacher:', error);
        showNotification('Failed to save teacher: ' + error.message, 'error');
        // Re-enable button
        saveBtn.disabled = false;
        saveText.textContent = 'Save Teacher';
    });
}

function deleteTeacher(teacherId) {
    if (!confirm('Are you sure you want to delete this teacher? This will also remove all subject assignments.')) {
        return;
    }
    
    fetch(`{{ route('admin.teachers.index') }}/${teacherId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to delete teacher', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting teacher:', error);
        showNotification('Failed to delete teacher', 'error');
    });
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-xl z-50 transform transition-all`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

@endsection
