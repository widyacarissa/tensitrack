<?php

use App\Http\Controllers\Admin\BerandaController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ShowPdfController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('index');

Route::get('/login-as-guest', GuestController::class)->name('login-as-guest')->middleware('guest');

Route::prefix('admin')->group(function () {
    Route::get('beranda', [BerandaController::class, 'index'])->name('admin.beranda');
    Route::prefix('tingkat-risiko')->group(function () {
        Route::middleware(['auth', 'can:asAdmin'])->group(function () {
            Route::post('store', [\App\Http\Controllers\Admin\TingkatRisikoController::class, 'store'])->name('admin.tingkat-risiko.store');
            Route::put('update/{id}', [\App\Http\Controllers\Admin\TingkatRisikoController::class, 'update'])->name('admin.tingkat-risiko.update');
            Route::delete('destroy/{id}', [\App\Http\Controllers\Admin\TingkatRisikoController::class, 'destroy'])->name('admin.tingkat-risiko.destroy');
            Route::get('tambah', [\App\Http\Controllers\Admin\TingkatRisikoController::class, 'create'])->name('admin.tingkat-risiko.tambah');
        });
        Route::get('/', [\App\Http\Controllers\Admin\TingkatRisikoController::class, 'index'])->name('admin.tingkat-risiko');
        Route::get('edit/{id}', [\App\Http\Controllers\Admin\TingkatRisikoController::class, 'edit'])->name('admin.tingkat-risiko.edit');
        Route::get('pdf', [ShowPdfController::class, 'tingkatRisikoPdf'])->name('tingkat-risiko.pdf');
    });
    Route::prefix('faktor-risiko')->group(function () {
        Route::middleware(['auth', 'can:asAdmin'])->group(function () {
            Route::post('store', [\App\Http\Controllers\Admin\FaktorRisikoController::class, 'store'])->name('admin.faktor-risiko.store');
            Route::put('update/{id}', [\App\Http\Controllers\Admin\FaktorRisikoController::class, 'update'])->name('admin.faktor-risiko.update');
            Route::delete('destroy/{id}', [\App\Http\Controllers\Admin\FaktorRisikoController::class, 'destroy'])->name('admin.faktor-risiko.destroy');
        });
        Route::get('/', [\App\Http\Controllers\Admin\FaktorRisikoController::class, 'index'])->name('admin.faktor-risiko');
        Route::get('tambah', [\App\Http\Controllers\Admin\FaktorRisikoController::class, 'create'])->name('admin.faktor-risiko.tambah');
        Route::get('edit/{id}', [\App\Http\Controllers\Admin\FaktorRisikoController::class, 'edit'])->name('admin.faktor-risiko.edit');
        Route::get('pdf', [ShowPdfController::class, 'faktorRisikoPdf'])->name('faktor-risiko.pdf');
    });
    Route::prefix('rule')->group(function () {
        Route::middleware(['auth', 'can:asAdmin'])->group(function () {
            Route::post('store', [\App\Http\Controllers\Admin\RuleController::class, 'store'])->name('admin.rule.store');
            Route::put('update/{tingkatRisiko}', [\App\Http\Controllers\Admin\RuleController::class, 'update'])->name('admin.rule.update');
            Route::delete('destroy/{tingkatRisiko}', [\App\Http\Controllers\Admin\RuleController::class, 'destroy'])->name('admin.rule.destroy');
        });
        Route::get('/', [\App\Http\Controllers\Admin\RuleController::class, 'index'])->name('admin.rule');
        Route::get('tambah', [\App\Http\Controllers\Admin\RuleController::class, 'create'])->name('admin.rule.tambah');
        Route::get('edit/{tingkatRisiko}', [\App\Http\Controllers\Admin\RuleController::class, 'edit'])->name('admin.rule.edit');
        Route::get('pdf', [ShowPdfController::class, 'rulePdf'])->name('rule.pdf');
    });
    Route::prefix('histori-diagnosis')->group(function () {
        Route::middleware(['auth', 'can:asAdmin'])->group(function () {
            Route::delete('destroy', [\App\Http\Controllers\Admin\HistoriDiagnosisController::class, 'destroy'])->name('admin.diagnosis.destroy');
        });
        Route::get('/', [\App\Http\Controllers\Admin\HistoriDiagnosisController::class, 'index'])->name('admin.histori.diagnosis');
        Route::get('detail/{id}', [\App\Http\Controllers\Admin\HistoriDiagnosisController::class, 'detail'])->name('admin.histori.diagnosis.detail');
        Route::get('pdf', [ShowPdfController::class, 'historiDiagnosisPdf'])->name('histori.diagnosis.pdf');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('home', [\App\Http\Controllers\Controller::class, 'authenticated'])->name('home');

    Route::middleware('can:asUser')->group(function () {
        Route::post('diagnosis', [DiagnosisController::class, 'diagnosis'])
            ->middleware('can:hasUserProfile')
            ->name('user.diagnosis');
        Route::put('edit-profile', [\App\Http\Controllers\UserProfileController::class, 'updateUser'])->name('update-profile');
        Route::delete('histori-diagnosis-user', [\App\Http\Controllers\UserController::class, 'historiDiagnosis'])->name('histori-diagnosis-user.delete');
        Route::middleware('check.direct.access')->group(function () {
            Route::middleware('can:hasUserProfile')->group(function () {
                Route::get('get-faktor-risiko', [UserController::class, 'getFaktorRisiko'])->name('get-faktor-risiko');
                Route::get('detail-diagnosis', [UserController::class, 'detailDiagnosis'])->name('detail-diagnosis');
                Route::get('chart-diagnosis-tingkat-risiko', [UserController::class, 'chartDiagnosisTingkatRisiko'])->name('chart-diagnosis-tingkat-risiko');
                Route::get('get-rule-data', [UserController::class, 'getRuleData'])->name('get-rule-data');
            });
            Route::get('histori-diagnosis-user', [\App\Http\Controllers\UserController::class, 'historiDiagnosis'])->name('histori-diagnosis-user');
            Route::get('edit-profile', [\App\Http\Controllers\UserProfileController::class, 'index'])->name('edit-profile');
            Route::get('provinsi', [\App\Http\Controllers\KotaProvinsiController::class, 'indexProvince'])->name('provinsi');
            Route::get('edit-profile/lokasi/kota/{id}', [\App\Http\Controllers\KotaProvinsiController::class, 'indexCity'])->name('kota');
        });
    });
});

Route::post('/auth/google', [SocialAuthController::class, 'redirectToProvider'])->name('google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('google.callback');
