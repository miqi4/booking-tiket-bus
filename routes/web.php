<?php

use App\Http\Controllers\Operator\BoardingController;
use App\Http\Controllers\Passenger\AuthController;
use App\Http\Controllers\Passenger\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/jadwal', [PageController::class, 'schedules'])->name('schedules.index');
Route::get('/jadwal/{schedule}/kursi', [PageController::class, 'seats'])->middleware('auth')->name('schedules.seats');
Route::post('/jadwal/{schedule}/kursi', [PageController::class, 'storeSeats'])->middleware('auth')->name('schedules.seats.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.store');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/booking/isi-data', [PageController::class, 'passengerForm'])->name('booking.passengers');
    Route::post('/booking/isi-data', [PageController::class, 'storePassengers'])->name('booking.passengers.store');
    Route::get('/booking/{booking}/konfirmasi', [PageController::class, 'confirmation'])->name('booking.confirmation');
    Route::post('/booking/{booking}/pay', [PageController::class, 'pay'])->name('booking.pay');
    Route::get('/booking/{code}/sukses', [PageController::class, 'success'])->name('booking.success');
    Route::get('/booking/{code}/pending', [PageController::class, 'pending'])->name('booking.pending');
    Route::get('/dashboard/pemesanan', [PageController::class, 'history'])->name('dashboard.bookings');
    Route::get('/dashboard/profil', [PageController::class, 'profile'])->name('dashboard.profile');

    // Operator & Admin Boarding Scanner
    Route::get('/boarding/scan', [BoardingController::class, 'scanner'])->name('operator.boarding.scan');
    Route::post('/boarding/scan', [BoardingController::class, 'process'])->name('operator.boarding.process');
});
