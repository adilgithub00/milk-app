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
        // 🔑 Safe rolling 12 months window
        $end = now()->copy()->endOfMonth();
        $start = now()->copy()->startOfMonth()->subMonths(11);

        $months = [];
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();

            // Milk entries for this month
            $entries = MilkEntry::whereBetween('entry_date', [$monthStart, $monthEnd])
                ->orderBy('entry_date')
                ->get();

            $totalKg = $entries->sum('quantity_kg');
            $totalAmount = $entries->sum(fn($e) => $e->quantity_kg * $e->rate_per_kg);

            // Payments for this month
            $paid = Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $remaining = $totalAmount - $paid;

            // Rates used in this month
            $ratesUsed = $entries
                ->pluck('rate_per_kg')
                ->unique()
                ->values()
                ->all();

            // Daily breakdown
            $dailyEntries = $entries->map(fn($e) => [
                'date' => $e->entry_date->format('d-m-Y'),
                'kg' => $e->quantity_kg,
                'rate' => $e->rate_per_kg,
                'amount' => $e->quantity_kg * $e->rate_per_kg,
            ]);

            $months[] = [
                'month' => $cursor->format('F Y'),  // 👈 year included
                'totalKg' => $totalKg,
                'totalAmount' => $totalAmount,
                'paid' => $paid,
                'remaining' => $remaining,
                'ratesUsed' => $ratesUsed,
                'dailyEntries' => $dailyEntries,
            ];

            $cursor->addMonth();
        }

        $range = $start->format('M Y') . ' - ' . $end->format('M Y');

        return view('milk.yearly-report', compact('months', 'range'));
    }
}
