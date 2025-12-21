<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Active rate
        $activeRate = MonthlyRate::where('is_active', true)->first();

        // Current month milk
        $totalKg = MilkEntry::whereYear('entry_date', $currentYear)
            ->whereMonth('entry_date', $currentMonth)
            ->sum('quantity_kg');

        // Current month total amount (important: rate stored in milk_entries)
        $totalAmount = MilkEntry::whereYear('entry_date', $currentYear)
            ->whereMonth('entry_date', $currentMonth)
            ->sum(DB::raw('quantity_kg * rate_per_kg'));

        // Paid this month
        $paid = Payment::whereYear('payment_date', $currentYear)
            ->whereMonth('payment_date', $currentMonth)
            ->sum('amount');

        $remaining = $totalAmount - $paid;

        // Recent activity
        $recentMilk = MilkEntry::latest('entry_date')->limit(7)->get();
        $recentPayments = Payment::latest('payment_date')->limit(5)->get();

        return view('admin.dashboard', compact(
            'currentMonth',
            'currentYear',
            'activeRate',
            'totalKg',
            'totalAmount',
            'paid',
            'remaining',
            'recentMilk',
            'recentPayments'
        ));
    }
}
