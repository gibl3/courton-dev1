<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');

    Route::get('/forgot-password', [AuthController::class, 'forgotPass'])->name('forgotPass');
});
