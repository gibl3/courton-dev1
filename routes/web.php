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
use App\Http\Controllers\RansAuthController;
// use App\Http\Controllers\ReportController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ForgotPasswordController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


//  █████╗ ██╗   ██╗████████╗██╗  ██╗
// ██╔══██╗██║   ██║╚══██╔══╝██║  ██║
// ███████║██║   ██║   ██║   ███████║
// ██╔══██║██║   ██║   ██║   ██╔══██║
// ██║  ██║╚██████╔╝   ██║   ██║  ██║
// ╚═╝  ╚═╝ ╚═════╝    ╚═╝   ╚═╝  ╚═╝
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/', fn() => redirect()->route('auth.login'));

    Route::get('/login/player', [AuthController::class, 'login'])->name('login');
    
    Route::get('/login/admin', [AuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('/login/admin', [AuthController::class, 'authenticate'])->middleware('throttle:login')->name('admin.authenticate');

    Route::get('/signup', [AuthController::class, 'create'])->name('create');
    // Route::post('/signup', [UserController::class, 'store'])->name('store');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgotPass');
    Route::post('/forgot-password/send', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'reset'])->name('forgot-password.reset');

    // Google Login Routes
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');

    // // RANS AUTH ROUTES
    Route::post('/login', [RansAuthController::class, 'authenticate'])->name('authenticate');

    Route::post('/signup', [RansAuthController::class, 'register'])->name('store');

    // Route::middleware(['verify.otp'])->group(function () {
    Route::get('/otp-verification', [RansAuthController::class, 'showOTP'])->name('showOTP');
    Route::post('/verify-otp', [RansAuthController::class, 'verifyOTP'])->name('verifyOTP');
    Route::post('/resend-otp', [RansAuthController::class, 'resendOTP'])->name('resendOTP');
    // });
});

// ██████╗ ██╗      █████╗ ██╗   ██╗███████╗██████╗ 
// ██╔══██╗██║     ██╔══██╗╚██╗ ██╔╝██╔════╝██╔══██╗
// ██████╔╝██║     ███████║ ╚████╔╝ █████╗  ██████╔╝
// ██╔═══╝ ██║     ██╔══██║  ╚██╔╝  ██╔══╝  ██╔══██╗
// ██║     ███████╗██║  ██║   ██║   ███████╗██║  ██║
// ╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝   ╚══════╝╚═╝  ╚═╝
Route::prefix('player')->name('player.')->middleware('is_player')->group(function () {
    Route::get('/dashboard', [PlayerController::class, 'index'])->name('dashboard');
    Route::get('/', fn() => redirect()->route('player.dashboard'));

    // ██████╗  ██████╗  ██████╗ ██╗  ██╗██╗███╗   ██╗ ██████╗ ███████╗
    // ██╔══██╗██╔═══██╗██╔═══██╗██║ ██╔╝██║████╗  ██║██╔════╝ ██╔════╝
    // ██████╔╝██║   ██║██║   ██║█████╔╝ ██║██╔██╗ ██║██║  ███╗███████╗
    // ██╔══██╗██║   ██║██║   ██║██╔═██╗ ██║██║╚██╗██║██║   ██║╚════██║
    // ██████╔╝╚██████╔╝╚██████╔╝██║  ██╗██║██║ ╚████║╚██████╔╝███████║
    // ╚═════╝  ╚═════╝  ╚═════╝ ╚═╝  ╚═╝╚═╝╚═╝  ╚═══╝ ╚═════╝ ╚══════╝
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::post('/confirm', [BookingController::class, 'confirm'])->name('confirm');
        Route::get('/my', [MyBookingsController::class, 'index'])->name('my');
        Route::get('/my/{booking}', [MyBookingsController::class, 'show'])->name('show')->middleware('can:view,booking');
        Route::post('/my/{booking}/cancel', [MyBookingsController::class, 'cancel'])->name('cancel')->middleware('can:update,booking');
        Route::delete('/my/{booking}', [MyBookingsController::class, 'destroy'])->name('destroy')->middleware('can:delete,booking');
    });

    // Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/profile', [ProfileController::class, 'update'])->name('update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password');
        // Route::delete('/', [ProfileController::class, 'delete'])->name('delete');


        // Password change route
        Route::post('/change-password', [RansAuthController::class, 'changePassword'])
            ->name('change-password');

        Route::post('/delete-account', [RansAuthController::class, 'deleteUser'])->name('deleteUser');
    });
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
Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.index'));

    Route::get('/dashboard', [AdminController::class, 'index'])->name('index');

    // Courts
    Route::prefix('courts')->name('courts.')->group(function () {
        Route::get('/', [AdminCourtController::class, 'index'])->name('index');
        Route::get('/create', [AdminCourtController::class, 'create'])->name('create');
        Route::post('/', [AdminCourtController::class, 'store'])->name('store');
        Route::get('/{court}/edit', [AdminCourtController::class, 'edit'])->name('edit');
        Route::put('/{court}', [AdminCourtController::class, 'update'])->name('update');
        Route::delete('/{court}', [AdminCourtController::class, 'destroy'])->name('destroy');
    });

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::get('/create', [AdminBookingController::class, 'create'])->name('create');
        Route::post('/', [AdminBookingController::class, 'store'])->name('store');
        Route::get('/pending', [AdminBookingController::class, 'pending'])->name('pending');
        Route::get('/pending-payments', [AdminBookingController::class, 'pendingPayments'])->name('pendingPayments');
        Route::get('/{booking}/edit', [AdminBookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [AdminBookingController::class, 'update'])->name('update');
        Route::delete('/{booking}', [AdminBookingController::class, 'destroy'])->name('destroy');
        Route::put('/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{booking}/payment-status', [AdminBookingController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        Route::post('/bulk-update', [AdminBookingController::class, 'bulkUpdate'])->name('bulkUpdate');
        Route::post('/bulk-update-payment', [AdminBookingController::class, 'bulkUpdatePayment'])->name('bulkUpdatePayment');
    });

    // Players
    Route::prefix('players')->name('users.')->group(function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('index');
        Route::get('/create', [AdminUsersController::class, 'create'])->name('create');
        Route::post('/create', [AdminUsersController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUsersController::class, 'update'])->name('update');
        Route::get('/search', [AdminUsersController::class, 'search'])->name('search');
    });

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // Reports (commented out for now)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/', [ReportController::class, 'index'])->name('index');
    //     Route::get('/data', [ReportController::class, 'getData'])->name('data');
    // });
});

Route::get('/create-admin', [AdminController::class, 'storeAdmin'])->name('store.admin');


Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
