<?php

use App\Http\Controllers\MyBookingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


//  █████╗ ██╗   ██╗████████╗██╗  ██╗
// ██╔══██╗██║   ██║╚══██╔══╝██║  ██║
// ███████║██║   ██║   ██║   ███████║
// ██╔══██║██║   ██║   ██║   ██╔══██║
// ██║  ██║╚██████╔╝   ██║   ██║  ██║
// ╚═╝  ╚═╝ ╚═════╝    ╚═╝   ╚═╝  ╚═╝
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    Route::get('/signup', [AuthController::class, 'create'])->name('create');
    Route::post('/signup', [UserController::class, 'store'])->name('store');

    Route::get('/forgot-password', [AuthController::class, 'forgotPass'])->name('forgotPass');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Player Routes
Route::prefix('player')->name('player.')->middleware('is_player')->group(function () {
    Route::get('/dashboard', [PlayerController::class, 'index'])->name('dashboard');
    Route::get('/book', [BookingController::class, 'index'])->name('book');
    Route::post('/book/confirm', [BookingController::class, 'confirm'])->name('book.confirm');
    Route::get('/my-bookings', [MyBookingsController::class, 'index'])->name('myBookings');
    Route::get('/my-bookings/{booking}', [MyBookingsController::class, 'show'])->name('bookings.show')->middleware('can:view,booking');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Court Routes
Route::prefix('courts')->name('courts.')->group(function () {
    Route::get('/', [CourtController::class, 'index'])->name('index');
    Route::get('/create', [CourtController::class, 'create'])->name('create');
    Route::get('/store', [CourtController::class, 'store'])->name('store');
});
