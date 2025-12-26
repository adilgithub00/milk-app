<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class YearlyReportController extends Controller
{
    public function index()
    {
        $year = now()->year;
        $currentMonth = now()->month;

        $months = [];

        for ($month = 1; $month <= $currentMonth; $month++) {

            $entries = MilkEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->orderBy('entry_date')
                ->get();

            $totalKg = $entries->sum('quantity_kg');
            $totalAmount = $entries->sum(fn($e) => $e->quantity_kg * $e->rate_per_kg);

            $paid = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');

            $remaining = $totalAmount - $paid;

            // Group rates used in this month
            $ratesUsed = $entries->pluck('rate_per_kg')->unique()->values()->all();

            // Store daily breakdown too
            $dailyEntries = $entries->map(fn($e) => [
                'date' => $e->entry_date->format('d-m-Y'),
                'kg' => $e->quantity_kg,
                'rate' => $e->rate_per_kg,
                'amount' => $e->quantity_kg * $e->rate_per_kg,
            ]);

            $months[] = [
                'month' => Carbon::create($year, $month)->format('F'),
                'totalKg' => $totalKg,
                'totalAmount' => $totalAmount,
                'paid' => $paid,
                'remaining' => $remaining,
                'ratesUsed' => $ratesUsed,
                'dailyEntries' => $dailyEntries,
            ];
        }

        return view('milk.yearly-report', compact('months', 'year'));
    }
}
