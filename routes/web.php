<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Web\UserController;

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

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // User Management
    Route::resource('users', UserController::class);
});
