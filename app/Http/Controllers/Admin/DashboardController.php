<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $monthInput = request('month', now()->month);
        $yearInput = request('year', now()->year);

        // $monthInput = now()->month;
        // $yearInput = now()->year;

        // Active rate
        $activeRate = MonthlyRate::where('is_active', true)->first();

        // Current month milk
        $totalKg = MilkEntry::whereYear('entry_date', $yearInput)
            ->whereMonth('entry_date', $monthInput)
            ->sum('quantity_kg');

        // Current month total amount (important: rate stored in milk_entries)
        $totalAmount = MilkEntry::whereYear('entry_date', $yearInput)
            ->whereMonth('entry_date', $monthInput)
            ->sum(DB::raw('quantity_kg * rate_per_kg'));

        // Paid this month
        $paid = Payment::whereYear('payment_date', $yearInput)
            ->whereMonth('payment_date', $monthInput)
            ->sum('amount');

        $remaining = $totalAmount - $paid;

        // Recent activity
        $recentMilk = MilkEntry::latest('entry_date')->limit(7)->get();
        $recentPayments = Payment::latest('payment_date')->limit(5)->get();

        $dailyStats = MilkEntry::selectRaw('DAY(entry_date) as day, SUM(quantity_kg) as total_kg')
            ->whereYear('entry_date', $yearInput)
            ->whereMonth('entry_date', $monthInput)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $milkByMonth = MilkEntry::selectRaw('YEAR(entry_date) y, MONTH(entry_date) m,
    SUM(quantity_kg) kg,
    SUM(quantity_kg * rate_per_kg) amount')
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn($r) => "{$r->y}-{$r->m}");

        $paymentsByMonth = Payment::selectRaw('YEAR(payment_date) y, MONTH(payment_date) m,
    SUM(amount) paid')
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn($r) => "{$r->y}-{$r->m}");

        // Last 6 months comparison
        $monthlyComparison = collect();
        $paymentComparison = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::create($yearInput, $monthInput)->subMonths($i);
            $key = "{$date->year}-{$date->month}";

            $milk = $milkByMonth[$key] ?? null;
            $paid = $paymentsByMonth[$key]->paid ?? 0;

            $monthlyComparison->push([
                'label' => $date->format('M Y'),
                'kg' => (float) ($milk->kg ?? 0),
                'amount' => (float) ($milk->amount ?? 0),
            ]);

            $paymentComparison->push([
                'label' => $date->format('M Y'),
                'paid' => (float) $paid,
                'remaining' => max(($milk->amount ?? 0) - $paid, 0),
            ]);
        }

        return view('admin.dashboard', compact(
            'monthInput',
            'yearInput',
            'activeRate',
            'totalKg',
            'totalAmount',
            'paid',
            'remaining',
            'recentMilk',
            'recentPayments',
            'dailyStats',
            'monthInput',
            'monthlyComparison',
            'paymentComparison'
        ));
    }

    public function filter()
    {
        $month = request('month');
        $year = request('year');

        $activeRate = MonthlyRate::where('is_active', true)->first();

        $totalKg = MilkEntry::whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month)
            ->sum('quantity_kg');

        $totalAmount = MilkEntry::whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month)
            ->sum(DB::raw('quantity_kg * rate_per_kg'));

        $paid = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->sum('amount');

        $remaining = $totalAmount - $paid;

        $dailyStats = MilkEntry::selectRaw('DAY(entry_date) as day, SUM(quantity_kg) as total_kg')
            ->whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $milkByMonth = MilkEntry::selectRaw('YEAR(entry_date) y, MONTH(entry_date) m,
    SUM(quantity_kg) kg,
    SUM(quantity_kg * rate_per_kg) amount')
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn($r) => "{$r->y}-{$r->m}");

        $paymentsByMonth = Payment::selectRaw('YEAR(payment_date) y, MONTH(payment_date) m,
    SUM(amount) paid')
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn($r) => "{$r->y}-{$r->m}");

        // Last 6 months comparison
        $monthlyComparison = collect();
        $paymentComparison = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::create($year, $month)->subMonths($i);
            $key = "{$date->year}-{$date->month}";

            $milk = $milkByMonth[$key] ?? null;
            $paid = $paymentsByMonth[$key]->paid ?? 0;

            $monthlyComparison->push([
                'label' => $date->format('M Y'),
                'kg' => (float) ($milk->kg ?? 0),
                'amount' => (float) ($milk->amount ?? 0),
            ]);

            $paymentComparison->push([
                'label' => $date->format('M Y'),
                'paid' => (float) $paid,
                'remaining' => max(($milk->amount ?? 0) - $paid, 0),
            ]);
        }

        return response()->json([
            'activeRate' => $activeRate?->rate_per_kg,
            'totalKg' => $totalKg,
            'totalAmount' => $totalAmount,
            'paid' => $paid,
            'remaining' => max($remaining, 0),
            'dailyStats' => $dailyStats,
            'monthLabel' => Carbon::create($year, $month)->format('F Y'),
            'coverage' => $totalAmount > 0 ? round(($paid / $totalAmount) * 100) : 0,
            'monthlyComparison' => $monthlyComparison,
            'paymentComparison' => $paymentComparison,
        ]);
    }
}
