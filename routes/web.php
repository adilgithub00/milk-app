<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MilkController;
use App\Http\Controllers\Admin\MilkRateController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\MilkEntryController;
use App\Http\Controllers\YearlyReportController;
use App\Http\Controllers\YearlyPaymentController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAllowedIp;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [MilkEntryController::class, 'index']);
Route::post('/milk-entry', [MilkEntryController::class, 'store'])->name('milk.store')->middleware(IsAllowedIp::class);
Route::get('/calculator', [CalculatorController::class, 'index']);
Route::post('/payment', [CalculatorController::class, 'storePayment'])->name('payment.store')->middleware(IsAllowedIp::class);
Route::get('/yearly-report', [YearlyReportController::class, 'index']);
Route::get('/yearly-payments', [YearlyPaymentController::class, 'index']);


Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.submit');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', IsAdmin::class, PreventBackHistory::class])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::resource('rates', MilkRateController::class);
    Route::patch('rates/{rate}/activate', [MilkRateController::class, 'activate'])
        ->name('rates.activate');
    Route::resource('milk-entries', MilkController::class)
        ->except(['show']);
    Route::resource('payments', AdminPaymentController::class)
        ->except(['show']);

    Route::prefix('/settings')->group(function () {
        Route::get('email', [AdminSettingsController::class, 'editEmail'])
            ->name('admin.settings.email');

        Route::post('email', [AdminSettingsController::class, 'updateEmail'])->name('settings.update.email');

        Route::get('password', [AdminSettingsController::class, 'editPassword'])
            ->name('admin.settings.password');

        Route::post('password', [AdminSettingsController::class, 'updatePassword'])->name('settings.update.password');
    });
});

// Route::get('/drop-sessions', function () {
//     try {
//         DB::statement('DROP TABLE IF EXISTS sessions');
//         return "Sessions table dropped successfully.";
//     } catch (\Exception $e) {
//         return $e->getMessage();
//     }
// });

Route::get('/migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migrations run successfully';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/debug', function () {
    if (Auth::check()) {
        $user = Auth::user();  // currently logged-in user model
        dd([
            'User ID' => $user->id,
            'Email' => $user->email,
            'Is Admin?' => $user->is_admin,
            'Session Data' => session()->all(),
        ]);
    } else {
        dd('User not logged in');
    }
});
