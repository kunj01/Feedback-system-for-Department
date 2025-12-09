<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\PlacementController;
use App\Http\Controllers\Api\ReportLogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::apiResource('users', UserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy',
    ]);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('api.users.toggle-active');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('api.users.assign-role');

    // Departments
    Route::apiResource('departments', DepartmentController::class)->names([
        'index' => 'api.departments.index',
        'store' => 'api.departments.store',
        'show' => 'api.departments.show',
        'update' => 'api.departments.update',
        'destroy' => 'api.departments.destroy',
    ]);

    // Companies
    Route::apiResource('companies', CompanyController::class)->names([
        'index' => 'api.companies.index',
        'store' => 'api.companies.store',
        'show' => 'api.companies.show',
        'update' => 'api.companies.update',
        'destroy' => 'api.companies.destroy',
    ]);

    // Students
    Route::apiResource('students', StudentController::class)->names([
        'index' => 'api.students.index',
        'store' => 'api.students.store',
        'show' => 'api.students.show',
        'update' => 'api.students.update',
        'destroy' => 'api.students.destroy',
    ]);

    // Projects
    Route::apiResource('projects', ProjectController::class)->names([
        'index' => 'api.projects.index',
        'store' => 'api.projects.store',
        'show' => 'api.projects.show',
        'update' => 'api.projects.update',
        'destroy' => 'api.projects.destroy',
    ]);
    Route::post('projects/{project}/assign-students', [ProjectController::class, 'assignStudents'])->name('api.projects.assign-students');
    Route::delete('projects/{project}/students/{student}', [ProjectController::class, 'removeStudent'])->name('api.projects.remove-student');
    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('api.projects.update-status');

    // Evaluations
    Route::apiResource('evaluations', EvaluationController::class)->names([
        'index' => 'api.evaluations.index',
        'store' => 'api.evaluations.store',
        'show' => 'api.evaluations.show',
        'update' => 'api.evaluations.update',
        'destroy' => 'api.evaluations.destroy',
    ]);
    Route::get('evaluations/project/{projectId}/stats', [EvaluationController::class, 'projectStats'])->name('api.evaluations.project-stats');
    Route::get('evaluations/student/{studentId}/stats', [EvaluationController::class, 'studentStats'])->name('api.evaluations.student-stats');

    // Placements
    Route::apiResource('placements', PlacementController::class)->names([
        'index' => 'api.placements.index',
        'store' => 'api.placements.store',
        'show' => 'api.placements.show',
        'update' => 'api.placements.update',
        'destroy' => 'api.placements.destroy',
    ]);
    Route::post('placements/{placement}/confirm', [PlacementController::class, 'confirmPlacement'])->name('api.placements.confirm');
    Route::get('placements/stats', [PlacementController::class, 'stats'])->name('api.placements.stats');
    Route::get('placements/student/{studentId}', [PlacementController::class, 'studentPlacements'])->name('api.placements.student');
    Route::get('placements/company/{companyId}', [PlacementController::class, 'companyPlacements'])->name('api.placements.company');

    // Reports & Logs
    Route::apiResource('reports', ReportLogController::class)->names([
        'index' => 'api.reports.index',
        'store' => 'api.reports.store',
        'show' => 'api.reports.show',
        'update' => 'api.reports.update',
        'destroy' => 'api.reports.destroy',
    ]);
    Route::post('reports/{reportLog}/review', [ReportLogController::class, 'review'])->name('api.reports.review');
    Route::get('reports/{reportLog}/download', [ReportLogController::class, 'download'])->name('api.reports.download');
    Route::get('reports/stats', [ReportLogController::class, 'stats'])->name('api.reports.stats');

    // Notifications
    Route::apiResource('notifications', NotificationController::class)->names([
        'index' => 'api.notifications.index',
        'store' => 'api.notifications.store',
        'show' => 'api.notifications.show',
        'update' => 'api.notifications.update',
        'destroy' => 'api.notifications.destroy',
    ]);
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('api.notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    Route::delete('notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('api.notifications.delete-all');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('api.dashboard');
});

