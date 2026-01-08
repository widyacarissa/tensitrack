<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Custom Direct Password Reset Flow (Insecure but Requested) ---
// Bypassing standard Fortify email verification link
Route::get('/forgot-password', [\App\Http\Controllers\Auth\DirectPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\DirectPasswordResetController::class, 'checkEmail'])->name('password.check');
Route::post('/reset-password-direct', [\App\Http\Controllers\Auth\DirectPasswordResetController::class, 'update'])->name('password.update.direct');


// 2. Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // --- Dashboard Redirect Logic ---
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');


    // =========================================================================
    // FITUR ADMIN
    // =========================================================================
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin
        Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

        // Master Data: Tingkat Risiko
        Route::get('risk-levels/print', [\App\Http\Controllers\Admin\RiskLevelController::class, 'print'])->name('risk-levels.print');
        Route::resource('risk-levels', \App\Http\Controllers\Admin\RiskLevelController::class);

        // Master Data: Faktor Risiko
        Route::get('risk-factors/print', [\App\Http\Controllers\Admin\RiskFactorController::class, 'print'])->name('risk-factors.print');
        Route::resource('risk-factors', \App\Http\Controllers\Admin\RiskFactorController::class);

        // Master Data: Aturan
        Route::get('rules/print', [\App\Http\Controllers\Admin\RuleController::class, 'print'])->name('rules.print');
        Route::resource('rules', \App\Http\Controllers\Admin\RuleController::class);

        // Manajemen Pengguna
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        
        // Laporan Riwayat
        // Print route harus didefinisikan SEBELUM resource route agar tidak dianggap sebagai {id} (show)
        Route::get('history/print', [\App\Http\Controllers\Admin\HistoryController::class, 'print'])->name('history.print');
        Route::get('history/export', [\App\Http\Controllers\Admin\HistoryController::class, 'export'])->name('history.export');
        Route::get('history/pdf/{id}/{action?}', [\App\Http\Controllers\Admin\HistoryController::class, 'printPdf'])->name('history.pdf');
        Route::resource('history', \App\Http\Controllers\Admin\HistoryController::class)->only(['index', 'show', 'destroy']);
    });


    // =========================================================================
    // FITUR CLIENT
    // =========================================================================
    Route::prefix('client')->name('client.')->group(function () {
        
        // Profil Pengguna
        Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');

        // Skrining Hipertensi
        Route::get('/screening', [\App\Http\Controllers\Client\ScreeningController::class, 'index'])->name('screening.index');
        Route::post('/screening', [\App\Http\Controllers\Client\ScreeningController::class, 'result'])->name('screening.store');
        
        // Detail Hasil Riwayat
        Route::get('/detail/{id}', [\App\Http\Controllers\Client\ProfileController::class, 'detail'])->name('profile.detail');
        Route::get('/detail/pdf/{id}/{action?}', [\App\Http\Controllers\Client\ProfileController::class, 'printPdf'])->name('pdf.print');
    });

});