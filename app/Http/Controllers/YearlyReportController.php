<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use App\Models\Payment;
use Carbon\Carbon;

class YearlyReportController extends Controller
{
    public function index()
    {
        $year = now()->year;
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $totalKg = MilkEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->sum('quantity_kg');

            $rate = MonthlyRate::where('year', $year)
                ->where('month', $month)
                ->value('rate_per_kg') ?? 0;

            $totalAmount = $totalKg * $rate;

            $paid = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');

            $remaining = $totalAmount - $paid;

            $months[] = [
                'month' => Carbon::create($year, $month)->format('F'),
                'kg' => $totalKg,
                'rate' => $rate,
                'total' => $totalAmount,
                'paid' => $paid,
                'remaining' => $remaining,
            ];
        }

        return view('milk.yearly-report', compact('months', 'year'));
    }
}
