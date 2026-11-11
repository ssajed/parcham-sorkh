<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

// Temporary admin dashboard preview.
Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');