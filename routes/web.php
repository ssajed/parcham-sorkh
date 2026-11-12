<?php

use App\Modules\Admin\Http\Controllers\AdminAdministratorController;
use App\Modules\Admin\Http\Controllers\AdminPendingAccountController;
use App\Modules\Admin\Http\Controllers\AdminRoleController;
use App\Modules\Admin\Http\Controllers\AdminUserController;
use App\Modules\Auth\Http\Controllers\AdminLoginController;
use App\Modules\Auth\Http\Controllers\CompanionSeekerLoginController;
use App\Modules\Auth\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

// Administrator authentication and protected panel.
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminLoginController::class, 'create'])
            ->name('login');

        Route::post('/login', [AdminLoginController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::post('/logout', [AdminLoginController::class, 'destroy'])
        ->middleware(['auth', 'admin'])
        ->name('logout');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::view('/', 'admin.dashboard')->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/administrators', [AdminAdministratorController::class, 'index'])
            ->name('administrators.index');

        Route::get('/roles', [AdminRoleController::class, 'index'])
            ->name('roles.index');

        Route::get('/accounts/pending', [AdminPendingAccountController::class, 'index'])
            ->name('accounts.pending');

        Route::post('/accounts/{user}/approve', [AdminPendingAccountController::class, 'approve'])
            ->whereNumber('user')
            ->name('accounts.approve');

        Route::post('/accounts/{user}/reject', [AdminPendingAccountController::class, 'reject'])
            ->whereNumber('user')
            ->name('accounts.reject');
    });
});

// Shared sign-in for companion seekers and companion helpers.
Route::prefix('companion')->name('companion-seeker.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [CompanionSeekerLoginController::class, 'create'])
            ->name('login');

        Route::post('/login', [CompanionSeekerLoginController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::post('/logout', [CompanionSeekerLoginController::class, 'destroy'])
        ->middleware(['auth', 'companion-access'])
        ->name('logout');

    Route::middleware(['auth', 'companion-access'])->group(function (): void {
        Route::view('/dashboard', 'companion-seeker.dashboard')
            ->name('dashboard');
    });
});

// Public registration.
Route::view('/register', 'auth.register')->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.store');
