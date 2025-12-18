<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\MilkEntryController;
use App\Http\Controllers\YearlyReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [MilkEntryController::class, 'index']);
Route::post('/milk-entry', [MilkEntryController::class, 'store'])
    ->name('milk.store');

Route::get('/calculator', [CalculatorController::class, 'index']);
Route::post('/payment', [CalculatorController::class, 'storePayment'])->name('payment.store');

Route::get('/yearly-report', [YearlyReportController::class, 'index']);

Route::get('/migrate-now', function() {
    try {
        Artisan::call('migrate', ["--force" => true]);
        return "Migrations run successfully";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, \Illuminate\Session\Middleware\StartSession::class]);