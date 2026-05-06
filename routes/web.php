<?php

use App\Http\Controllers\Gerbang;
use App\Http\Controllers\Kendaraan;
use App\Http\Controllers\Parkir;
use App\Http\Controllers\Pembayaran;
use App\Http\Controllers\Pengguna_Parkir;
use App\Http\Controllers\QR_Code;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [Pengguna_Parkir::class, 'login'])->name('login.post');
Route::post('/register', [Pengguna_Parkir::class, 'register'])->name('register.post');
Route::post('/logout', [Pengguna_Parkir::class, 'logout'])->name('logout');

// Protected User Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('Parkir');
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('profile');

    Route::put('/profile', [Pengguna_Parkir::class, 'updateProfile'])->name('profile.update');

    // Vehicle Management
    Route::get('/vehicles', function () {
        return view('user.vehicles');
    })->name('vehicles');

    // Parking History
    Route::get('/parking-history', function () {
        return view('user.parking-history');
    })->name('parking.history');

    // Payment History
    Route::get('/payment-history', function () {
        return view('user.payment-history');
    })->name('payment.history');

    // Top-up
    Route::get('/topup', function () {
        return view('user.topup');
    })->name('topup');

    Route::post('/topup', [Pembayaran::class, 'topup'])->name('topup.post');

    // API Resources for AJAX calls
    Route::resource('pengguna-parkir', Pengguna_Parkir::class);
    Route::resource('kendaraan', Kendaraan::class);
    Route::resource('parkir', Parkir::class);
    Route::resource('pembayaran', Pembayaran::class);
    Route::resource('qr_code', QR_Code::class);
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // User Management
    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    // Parking Management
    Route::get('/parking', function () {
        return view('admin.parking');
    })->name('parking');

    // Gate Management
    Route::get('/gates', function () {
        return view('admin.gates');
    })->name('gates');

    // Reports
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');

    // Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');

    // API Resources for Admin
    Route::resource('gerbang', Gerbang::class);
});

// Public QR Scan Routes (for gate scanners)
Route::prefix('scan')->name('scan.')->group(function () {
    Route::post('/masuk/{qr_code}', [Parkir::class, 'scanMasuk'])->name('masuk');
    Route::post('/keluar/{qr_code}', [Parkir::class, 'scanKeluar'])->name('keluar');
});

// API Routes for mobile app integration
Route::prefix('api')->name('api.')->middleware(['auth'])->group(function () {
    Route::get('/qr-generate', [QR_Code::class, 'generate'])->name('qr.generate');
    Route::get('/balance', [Pengguna_Parkir::class, 'getBalance'])->name('balance');
    Route::post('/payment', [Pembayaran::class, 'processPayment'])->name('payment.process');
});
