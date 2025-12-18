<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        $month = now()->month;
        $year = now()->year;

        $totalKg = MilkEntry::whereMonth('entry_date', $month)
            ->whereYear('entry_date', $year)
            ->sum('quantity_kg');

        $rate = MonthlyRate::where('month', $month)
            ->where('year', $year)
            ->value('rate_per_kg') ?? 0;

        $totalAmount = $totalKg * $rate;

        $paid = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->sum('amount');

        $remaining = $totalAmount - $paid;

        $payments = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->orderBy('payment_date', 'asc')
            ->get();

        return view('milk.calculator', compact(
            'totalKg', 'rate', 'totalAmount', 'paid', 'remaining', 'payments', 'month', 'year'
        ));
    }

    public function storePayment(Request $request)
    {
        $month = now()->month;
        $year = now()->year;

        // Calculate total milk amount
        $totalKg = MilkEntry::whereMonth('entry_date', $month)
            ->whereYear('entry_date', $year)
            ->sum('quantity_kg');

        $rate = MonthlyRate::where('month', $month)
            ->where('year', $year)
            ->value('rate_per_kg') ?? 0;

        $totalAmount = $totalKg * $rate;

        $paid = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->sum('amount');

        // Remaining amount
        $remaining = $totalAmount - $paid;

        // Backend validation
        $request->validate([
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($remaining) {
                if ($value > $remaining) {
                    $fail('You cannot pay more than remaining amount: ' . $remaining);
                }
            }]
        ]);

        Payment::create($request->only(['payment_date', 'amount']));

        return redirect()->back()->with('success', 'Payment recorded.');
    }
}
