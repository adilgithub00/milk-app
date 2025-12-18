<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\MilkEntryController;
use App\Http\Controllers\YearlyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MilkEntryController::class, 'index']);
Route::post('/milk-entry', [MilkEntryController::class, 'store'])
    ->name('milk.store');

Route::get('/calculator', [CalculatorController::class, 'index']);
Route::post('/payment', [CalculatorController::class, 'storePayment'])->name('payment.store');

Route::get('/yearly-report', [YearlyReportController::class, 'index']);
