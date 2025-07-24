<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController; // Pastikan ini ada
use App\Http\Controllers\Admin\ScoringParameterController; // Pastikan ini ada
use App\Http\Controllers\Teller\CreditApplicationController as TellerCreditApplicationController; // Alias
use App\Http\Controllers\Kabag\CreditApplicationController as KabagCreditApplicationController; // Alias
use App\Http\Controllers\Direksi\CreditApplicationController as DireksiCreditApplicationController; // Alias
use App\Http\Controllers\DashboardController; // Tambahkan ini

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Ubah rute dashboard ini
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('can:manage-users')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('scoring-parameters', ScoringParameterController::class);
    });

    // Teller Routes
    Route::middleware('can:access-teller-features')->prefix('teller')->name('teller.')->group(function () {
        Route::get('applications', [TellerCreditApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/create', [TellerCreditApplicationController::class, 'create'])->name('applications.create');
        Route::post('applications', [TellerCreditApplicationController::class, 'store'])->name('applications.store');
    });

    // Kabag Routes
    Route::middleware('can:access-kabag-features')->prefix('kabag')->name('kabag.')->group(function () {
        Route::get('applications', [KabagCreditApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{creditApplication}', [KabagCreditApplicationController::class, 'show'])->name('applications.show');
        Route::put('applications/{creditApplication}/review', [KabagCreditApplicationController::class, 'review'])->name('applications.review');
        Route::put('applications/{creditApplication}/reject', [KabagCreditApplicationController::class, 'reject'])->name('applications.reject');
    });

    // Direksi Routes
    Route::middleware('can:access-direksi-features')->prefix('direksi')->name('direksi.')->group(function () {
        Route::get('applications', [DireksiCreditApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{creditApplication}', [DireksiCreditApplicationController::class, 'show'])->name('applications.show');
        Route::put('applications/{creditApplication}/approve', [DireksiCreditApplicationController::class, 'approve'])->name('applications.approve');
        Route::put('applications/{creditApplication}/reject', [DireksiCreditApplicationController::class, 'reject'])->name('applications.reject');
    });
});

require __DIR__.'/auth.php';