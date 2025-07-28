<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Registration success page
Route::get('/pendaftaran-berhasil', function () {
    return view('auth.registration-success');
})->name('registration.success');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'active.user'])
    ->name('dashboard');

Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Savings routes
    Route::get('/simpanan', [SavingsController::class, 'index'])->name('savings.index');

    // Loan routes
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/pinjaman/{loan}', [LoanController::class, 'show'])->name('loans.show');
});

require __DIR__ . '/auth.php';
