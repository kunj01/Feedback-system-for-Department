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
    Route::apiResource('users', UserController::class);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive']);
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole']);

    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Companies
    Route::apiResource('companies', CompanyController::class);

    // Students
    Route::apiResource('students', StudentController::class);

    // Projects
    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{project}/assign-students', [ProjectController::class, 'assignStudents']);
    Route::delete('projects/{project}/students/{student}', [ProjectController::class, 'removeStudent']);
    Route::patch('projects/{project}/status', [ProjectController::class, 'updateStatus']);

    // Evaluations
    Route::apiResource('evaluations', EvaluationController::class);
    Route::get('evaluations/project/{projectId}/stats', [EvaluationController::class, 'projectStats']);
    Route::get('evaluations/student/{studentId}/stats', [EvaluationController::class, 'studentStats']);

    // Placements
    Route::apiResource('placements', PlacementController::class);
    Route::post('placements/{placement}/confirm', [PlacementController::class, 'confirmPlacement']);
    Route::get('placements/stats', [PlacementController::class, 'stats']);
    Route::get('placements/student/{studentId}', [PlacementController::class, 'studentPlacements']);
    Route::get('placements/company/{companyId}', [PlacementController::class, 'companyPlacements']);

    // Reports & Logs
    Route::apiResource('reports', ReportLogController::class);
    Route::post('reports/{reportLog}/review', [ReportLogController::class, 'review']);
    Route::get('reports/{reportLog}/download', [ReportLogController::class, 'download']);
    Route::get('reports/stats', [ReportLogController::class, 'stats']);

    // Notifications
    Route::apiResource('notifications', NotificationController::class);
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::delete('notifications/delete-all', [NotificationController::class, 'deleteAll']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
});

