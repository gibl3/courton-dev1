<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCourtController;
use App\Http\Controllers\AdminUsersController;
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
    Route::get('/login/player', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:login')->name('authenticate');

    Route::get('/login/admin', [AuthController::class, 'adminLogin'])->name('admin.login');

    Route::get('/signup', [AuthController::class, 'create'])->name('create');
    Route::post('/signup', [UserController::class, 'store'])->name('store');

    Route::get('/forgot-password', [AuthController::class, 'forgotPass'])->name('forgotPass');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ██████╗ ██╗      █████╗ ██╗   ██╗███████╗██████╗ 
// ██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝██╔════╝██╔══██╗
// ██████╔╝██║     ███████║ ╚████╔╝ █████╗  ██████╔╝
// ██╔═══╝ ██║     ██╔══██║  ╚██╔╝  ██╔══╝  ██╔══██╗
// ██║     ███████╗██║  ██║   ██║   ███████╗██║  ██║
// ╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝   ╚══════╝╚═╝  ╚═╝
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

//  ██████╗ ██████╗ ██╗   ██╗██████╗ ████████╗
// ██╔════╝██╔═══██╗██║   ██║██╔══██╗╚══██╔══╝
// ██║     ██║   ██║██║   ██║██████╔╝   ██║   
// ██║     ██║   ██║██║   ██║██╔══██╗   ██║   
// ╚██████╗╚██████╔╝╚██████╔╝██║  ██║   ██║   
//  ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═╝   ╚═╝   
Route::prefix('courts')->name('courts.')->group(function () {
    Route::get('/', [CourtController::class, 'index'])->name('index');
    Route::get('/create', [CourtController::class, 'create'])->name('create');
    Route::get('/store', [CourtController::class, 'store'])->name('store');
});


//  █████╗ ██████╗ ███╗   ███╗██╗███╗   ██╗
// ██╔══██╗██╔══██╗████╗ ████║██║████╗  ██║
// ███████║██║  ██║██╔████╔██║██║██╔██╗ ██║
// ██╔══██║██║  ██║██║╚██╔╝██║██║██║╚██╗██║
// ██║  ██║██████╔╝██║ ╚═╝ ██║██║██║ ╚████║
// ╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('index');

    // Courts
    Route::get('/courts', [AdminCourtController::class, 'index'])->name('courts.index');
    Route::get('/courts/create', [AdminCourtController::class, 'create'])->name('courts.create');
    Route::post('/courts', [AdminCourtController::class, 'store'])->name('courts.store');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/pending', [AdminBookingController::class, 'pending'])->name('bookings.pending');
    Route::get('/bookings/pending-payments', [AdminBookingController::class, 'pendingPayments'])->name('bookings.pendingPayments');
    Route::put('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::put('/bookings/{booking}/payment-status', [AdminBookingController::class, 'updatePaymentStatus'])->name('bookings.updatePaymentStatus');

    Route::get('/users', [AdminUsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUsersController::class, 'create'])->name('users.create');

    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
