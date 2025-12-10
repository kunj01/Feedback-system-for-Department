<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\StudentController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\EvaluationController;
use App\Http\Controllers\Web\PlacementController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\StudentImportController;

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

    // Company Management
    Route::resource('companies', CompanyController::class);

    // Project Management
    Route::resource('projects', ProjectController::class);

    // Evaluation Management
    Route::resource('evaluations', EvaluationController::class);

    // Placement Management
    Route::resource('placements', PlacementController::class);
    Route::post('placements/{placement}/confirm', [PlacementController::class, 'confirm'])->name('placements.confirm');

    // Reports & Analytics
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/placements', [ReportController::class, 'placements'])->name('reports.placements');
    Route::get('reports/projects', [ReportController::class, 'projects'])->name('reports.projects');
    Route::get('reports/evaluations', [ReportController::class, 'evaluations'])->name('reports.evaluations');
});
