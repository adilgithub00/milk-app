<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

            $ratesUsed = MilkEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->select('rate_per_kg')
                ->distinct()
                ->pluck('rate_per_kg')
                ->toArray();

            $totalAmount = MilkEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->sum(DB::raw('quantity_kg * rate_per_kg'));

            $paid = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');

            $remaining = $totalAmount - $paid;

            $months[] = [
                'month' => Carbon::create($year, $month)->format('F'),
                'kg' => $totalKg,
                'rates' => $ratesUsed,
                'total' => $totalAmount,
                'paid' => $paid,
                'remaining' => $remaining,
            ];
        }

        return view('milk.yearly-report', compact('months', 'year'));
    }
}
