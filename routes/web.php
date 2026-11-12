<?php

use App\Modules\Admin\Http\Controllers\AdminAdministratorController;
use App\Modules\Admin\Http\Controllers\AdminDashboardController;
use App\Modules\Admin\Http\Controllers\AdminPendingAccountController;
use App\Modules\Admin\Http\Controllers\AdminRoleController;
use App\Modules\Admin\Http\Controllers\AdminUserController;
use App\Modules\Auth\Http\Controllers\AdminLoginController;
use App\Modules\Auth\Http\Controllers\CompanionSeekerLoginController;
use App\Modules\Auth\Http\Controllers\RegisterController;
use App\Modules\Accounts\Http\Controllers\CompanionProfileController;
use App\Modules\Accompaniment\Http\Controllers\AccompanimentRequestController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    });

    Route::post('/logout', [AdminLoginController::class, 'destroy'])->middleware(['auth', 'admin'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->whereNumber('user')->name('users.block');
        Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->whereNumber('user')->name('users.unblock');
        Route::get('/administrators', [AdminAdministratorController::class, 'index'])->name('administrators.index');
        Route::post('/administrators', [AdminAdministratorController::class, 'store'])->name('administrators.store');
        Route::post('/administrators/{user}/revoke', [AdminAdministratorController::class, 'destroy'])->whereNumber('user')->name('administrators.revoke');
        Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
        Route::get('/accounts/pending', [AdminPendingAccountController::class, 'index'])->name('accounts.pending');
        Route::post('/accounts/{user}/approve', [AdminPendingAccountController::class, 'approve'])->whereNumber('user')->name('accounts.approve');
        Route::post('/accounts/{user}/reject', [AdminPendingAccountController::class, 'reject'])->whereNumber('user')->name('accounts.reject');
    });
});

Route::prefix('companion')->name('companion-seeker.')->group(function (): void {
    Route::get('/login', [CompanionSeekerLoginController::class, 'create'])->name('login');
    Route::post('/login', [CompanionSeekerLoginController::class, 'store'])->middleware(['guest', 'throttle:5,1'])->name('login.store');
    Route::post('/logout', [CompanionSeekerLoginController::class, 'destroy'])->middleware('auth')->name('logout');
    Route::get('/account-blocked', fn () => view('companion-seeker.account-blocked'))->middleware('auth')->name('account-blocked');

    Route::middleware(['auth', 'companion-access'])->group(function (): void {
        Route::view('/dashboard', 'companion-seeker.dashboard')->name('dashboard');
        Route::get('/profile/seeker', [CompanionProfileController::class, 'seeker'])->name('profile.seeker');
        Route::get('/profile/helper', [CompanionProfileController::class, 'helper'])->name('profile.helper');

        Route::get('/requests', [AccompanimentRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [AccompanimentRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [AccompanimentRequestController::class, 'store'])->middleware('throttle:10,1')->name('requests.store');
        Route::post('/requests/{request}/cancel', [AccompanimentRequestController::class, 'cancelRequest'])->whereNumber('request')->name('requests.cancel');
        Route::post('/requests/{request}/offers/{offer}/accept', [AccompanimentRequestController::class, 'acceptOffer'])->whereNumber('request')->whereNumber('offer')->name('requests.offers.accept');
        Route::post('/requests/{request}/offers/{offer}/reject', [AccompanimentRequestController::class, 'rejectOffer'])->whereNumber('request')->whereNumber('offer')->name('requests.offers.reject');

        Route::get('/helper/requests', [AccompanimentRequestController::class, 'helperIndex'])->name('helper.requests.index');
        Route::get('/helper/offers', [AccompanimentRequestController::class, 'helperOffers'])->name('helper.offers.index');
        Route::post('/helper/requests/{request}/offer', [AccompanimentRequestController::class, 'makeOffer'])->whereNumber('request')->name('helper.requests.offer');
        Route::post('/helper/offers/{offer}/withdraw', [AccompanimentRequestController::class, 'withdrawOffer'])->whereNumber('offer')->name('helper.offers.withdraw');
    });
});

Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1')->name('register.store');
