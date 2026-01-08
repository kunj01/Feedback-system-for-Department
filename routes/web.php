<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\StudentController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\EvaluationController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\Web\AcademicYearController;
use App\Http\Controllers\Web\SemesterController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\FeedbackTemplateController;
use App\Http\Controllers\Web\FeedbackAssignmentController;
use App\Http\Controllers\Web\FormController;
use App\Http\Controllers\Web\CurriculumFeedbackController;
use App\Http\Controllers\Admin\FeedbackAssignmentController as AdminFeedbackAssignmentController;
use App\Http\Controllers\Faculty\SpeakerController;
use App\Http\Controllers\Admin\SpeakerController as AdminSpeakerController;
use App\Http\Controllers\SpeakerFeedbackController;

// Guest routes (authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function () {
        // Placeholder for password reset email
        return back()->with('status', 'Password reset link will be sent to your email (feature coming soon).');
    })->name('password.email');
});

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // Profile & Settings
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');

    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');

    // User Management
    Route::resource('users', UserController::class);

    // Student Import Routes (Admin & TnP only) - MUST be before students resource route
    Route::prefix('students/import')->name('students.import.')->group(function () {
        Route::get('/', [StudentImportController::class, 'index'])->name('index');
        Route::get('/template', [StudentImportController::class, 'downloadTemplate'])->name('template');
        Route::post('/dry-run', [StudentImportController::class, 'dryRun'])->name('dry-run');
        Route::post('/execute', [StudentImportController::class, 'import'])->name('execute');
        Route::get('/logs', [StudentImportController::class, 'logs'])->name('logs');
        Route::get('/logs/{id}', [StudentImportController::class, 'show'])->name('show');
        Route::get('/logs/{id}/download', [StudentImportController::class, 'downloadReport'])->name('download');
    });

    // Student Management
    Route::resource('students', StudentController::class);

    // Department Management
    Route::resource('departments', DepartmentController::class);

    // Project Management
    Route::resource('projects', ProjectController::class);

    // Evaluation Management
    Route::resource('evaluations', EvaluationController::class);

    // Placement Management - removed (T&P module disabled)

    // SCFMS Academic Year Management
    Route::resource('academic-years', AcademicYearController::class);

    // SCFMS Semester Management
    Route::resource('semesters', SemesterController::class);
    Route::post('semesters/{semester}/activate', [SemesterController::class, 'activate'])->name('semesters.activate');

    // SCFMS Course Management
    Route::resource('courses', CourseController::class);
    Route::post('courses/{course}/assign-faculty', [CourseController::class, 'assignFaculty'])->name('courses.assign-faculty');
    Route::post('courses/{course}/assign-students', [CourseController::class, 'assignStudents'])->name('courses.assign-students');

    // SCFMS Feedback Template Management
    Route::resource('feedback-templates', FeedbackTemplateController::class);
    Route::post('feedback-templates/{feedbackTemplate}/clone', [FeedbackTemplateController::class, 'clone'])->name('feedback-templates.clone');

    // SCFMS Feedback Assignment Management
    Route::resource('feedback-assignments', FeedbackAssignmentController::class);
    Route::post('feedback-assignments/{feedbackAssignment}/extend-deadline', [FeedbackAssignmentController::class, 'extendDeadline'])->name('feedback-assignments.extend-deadline');

    // Forms Management
    Route::get('forms', [FormController::class, 'index'])->name('forms.index');
    Route::get('forms/create', [FormController::class, 'create'])->name('forms.create');
    Route::post('forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('forms/{filename}', [FormController::class, 'show'])->name('forms.show');
    Route::post('forms/{filename}/assign', [FormController::class, 'assign'])->name('forms.assign');
    Route::post('forms/{filename}/submit', [FormController::class, 'submit'])->name('forms.submit');
    Route::get('forms/download/{filename}', [FormController::class, 'download'])->name('forms.download');
    Route::delete('forms/{filename}', [FormController::class, 'destroy'])->name('forms.destroy');
    Route::post('forms/save-multi-teacher-config', [FormController::class, 'saveMultiTeacherConfig'])->name('forms.saveMultiTeacherConfig');

    // Curriculum Feedback Management (Academic-Teacher-Industry)
    Route::get('curriculum-feedback-welcome', function() {
        return view('admin.curriculum-feedback.welcome');
    })->name('curriculum-feedback.welcome');
    Route::resource('curriculum-feedback', CurriculumFeedbackController::class);
    Route::get('curriculum-feedback-analytics', [CurriculumFeedbackController::class, 'analytics'])->name('curriculum-feedback.analytics');
    Route::get('curriculum-feedback-export', [CurriculumFeedbackController::class, 'export'])->name('curriculum-feedback.export');
    Route::get('curriculum-feedback-thankyou', [CurriculumFeedbackController::class, 'thankyou'])->name('curriculum-feedback.thankyou');

    // Student Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/subject/{id}', function($id) { 
            return view('feedback.faculty-list', ['subjectId' => $id]); 
        })->name('subject');
        Route::get('/subject/{subjectId}/faculty/{facultyId}', function($subjectId, $facultyId) {
            return view('feedback.form', ['subjectId' => $subjectId, 'facultyId' => $facultyId]);
        })->name('form');
        Route::post('/submit', function(\Illuminate\Http\Request $request) {
            // Store feedback in session
            $completedFeedbacks = session()->get('completed_feedbacks', []);
            $key = $request->subject_id . '_' . $request->faculty_id;
            $completedFeedbacks[$key] = [
                'subject_id' => $request->subject_id,
                'faculty_id' => $request->faculty_id,
                'submitted_at' => now()->toDateTimeString(),
                'responses' => $request->except(['_token', 'subject_id', 'faculty_id'])
            ];
            session()->put('completed_feedbacks', $completedFeedbacks);
            
            return redirect()->route('dashboard')->with('success', 'Feedback submitted successfully!');
        })->name('submit');
    });

    // Admin Feedback Assignment Routes
    Route::prefix('admin/feedback')->name('admin.feedback.')->group(function () {
        Route::get('/assignments', [AdminFeedbackAssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/assignments', [AdminFeedbackAssignmentController::class, 'store'])->name('assignments.store');
        Route::delete('/assignments/{id}', [AdminFeedbackAssignmentController::class, 'destroy'])->name('assignments.destroy');
    });

    // Reports & Analytics
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/projects', [ReportController::class, 'projects'])->name('reports.projects');
    Route::get('reports/evaluations', [ReportController::class, 'evaluations'])->name('reports.evaluations');

    // Faculty Speaker Management
    Route::prefix('faculty/speakers')->name('faculty.speakers.')->group(function () {
        Route::get('/', [SpeakerController::class, 'index'])->name('index');
        Route::get('/create', [SpeakerController::class, 'create'])->name('create');
        Route::post('/', [SpeakerController::class, 'store'])->name('store');
        Route::get('/{speaker}', [SpeakerController::class, 'show'])->name('show');
        Route::get('/{speaker}/edit', [SpeakerController::class, 'edit'])->name('edit');
        Route::put('/{speaker}', [SpeakerController::class, 'update'])->name('update');
        Route::delete('/{speaker}', [SpeakerController::class, 'destroy'])->name('destroy');
    });

    // Admin Speaker Management
    Route::prefix('admin/speakers')->name('admin.speakers.')->group(function () {
        Route::get('/', [AdminSpeakerController::class, 'index'])->name('index');
        Route::get('/create', [AdminSpeakerController::class, 'create'])->name('create');
        Route::post('/', [AdminSpeakerController::class, 'store'])->name('store');
        Route::get('/{speaker}', [AdminSpeakerController::class, 'show'])->name('show');
        Route::post('/{speaker}/approve', [AdminSpeakerController::class, 'approve'])->name('approve');
        Route::post('/{speaker}/reject', [AdminSpeakerController::class, 'reject'])->name('reject');
        Route::delete('/{speaker}', [AdminSpeakerController::class, 'destroy'])->name('destroy');
    });
});

// Public Speaker Feedback Routes (outside auth middleware - accessible with token only)
Route::prefix('speaker/feedback')->name('speaker.feedback.')->group(function () {
    Route::get('/{token}', [SpeakerFeedbackController::class, 'show'])->name('show');
    Route::post('/{token}', [SpeakerFeedbackController::class, 'store'])->name('store');
});
