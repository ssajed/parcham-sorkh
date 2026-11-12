<?php

use App\Modules\Admin\Http\Controllers\AdminAdministratorController;
use App\Modules\Admin\Http\Controllers\AdminPendingAccountController;
use App\Modules\Admin\Http\Controllers\AdminRoleController;
use App\Modules\Admin\Http\Controllers\AdminUserController;
use App\Modules\Auth\Http\Controllers\AdminLoginController;
use App\Modules\Auth\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Public homepage.
Route::view('/', 'pages.home')->name('home');

// Administrator authentication.
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

        // Administrator management.
        Route::get('/administrators', [AdminAdministratorController::class, 'index'])
            ->name('administrators.index');

        // Role management.
        Route::get('/roles', [AdminRoleController::class, 'index'])
            ->name('roles.index');

        // Pending account review.
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

// Public registration.
Route::view('/register', 'auth.register')->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.store');