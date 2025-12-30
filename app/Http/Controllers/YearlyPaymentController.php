<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class YearlyPaymentController extends Controller
{
    public function index()
    {
        $end = now()->copy()->endOfMonth();  // current month end
        $start = now()->copy()->startOfMonth()->subMonths(11);  // 12 months window
        $lastMonth = now()->subMonth()->format('F Y');

        // Log::info('endstartlast', ['end' => $end, 'start' => $start, 'last' => $last]);
        $months = [];

        // iterate month by month
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $payments = Payment::whereBetween('payment_date', [
                $cursor->copy()->startOfMonth(),
                $cursor->copy()->endOfMonth(),
            ])
                ->orderBy('payment_date')
                ->get();

            $paid = $payments->sum('amount');

            $entries = MilkEntry::whereBetween('entry_date', [
                $cursor->copy()->startOfMonth(),
                $cursor->copy()->endOfMonth(),
            ])
                ->orderBy('entry_date')
                ->get();

            $totalAmount = $entries->sum(fn($e) => $e->quantity_kg * $e->rate_per_kg);

            $remaining = $totalAmount - $paid;

            $individualEntries = $payments->map(fn($p) => [
                'date' => $p->payment_date->format('d-m-Y'),
                'amount' => $p->amount,
            ]);

            $months[] = [
                'month' => $cursor->format('F Y'),  // important (year included)
                'totalAmount' => $totalAmount,
                'paid' => $paid,
                'remaining' => $remaining,
                'individualEntries' => $individualEntries,
            ];

            $cursor->addMonth();
        }

        return view('milk.yearly-payments', [
            'months' => $months,
            'lastMonth' => $lastMonth,
            'range' => $start->format('M Y') . ' - ' . $end->format('M Y'),
        ]);
    }
}
