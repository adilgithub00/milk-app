<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MilkEntryController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // 1️⃣ Plain collection (logic ke liye)
        $entriesCollection = MilkEntry::whereMonth('entry_date', $currentMonth)
            ->whereYear('entry_date', $currentYear)
            ->get();

        // 2️⃣ Keyed collection (Blade ke liye)
        $entries = $entriesCollection->keyBy(fn($item) =>
            $item->entry_date->format('Y-m-d'));

        $totalKg = $entriesCollection->sum('quantity_kg');

        $monthlyRate = MonthlyRate::where('is_active', true)->first();
        $activeRate = $monthlyRate?->rate_per_kg;

        // =========================
        // AUTO CARRY-OVER LOGIC
        // =========================

        $perDayKg = (int) Setting::get('milk_per_day_kg', 2);

        $coverageMap = [];
        $tooltips = [];

        $currentStockKg = 0;
        $currentSourceDay = null;

        $entriesByDate = $entriesCollection->groupBy(fn($e) =>
            $e->entry_date->format('Y-m-d'));

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');

            // Add milk of the day
            if ($entriesByDate->has($dateKey)) {
                foreach ($entriesByDate[$dateKey] as $entry) {
                    $currentStockKg += $entry->quantity_kg;
                    $currentSourceDay = $entry->entry_date->format('d M Y');
                }
            }

            // Consume milk
            if ($currentStockKg >= $perDayKg) {
                $coverageMap[$dateKey] = 'full';
                $tooltips[$dateKey] =
                    "Covered (2kg) from {$currentSourceDay}";
                $currentStockKg -= $perDayKg;
            } elseif ($currentStockKg > 0) {
                $coverageMap[$dateKey] = 'partial';
                $tooltips[$dateKey] =
                    "Partially covered ({$currentStockKg}kg) from {$currentSourceDay}";
                $currentStockKg = 0;
            }
        }

        return view('milk.calendar', compact(
            'entries',
            'entriesCollection',
            'totalKg',
            'currentMonth',
            'currentYear',
            'activeRate',
            'coverageMap',
            'tooltips'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->startOfMonth()->toDateString(),
                'before_or_equal:' . now()->toDateString(),
            ],
            'quantity_kg' => 'required|numeric|min:0.1'
        ]);

        $activeRate = MonthlyRate::where('is_active', true)->first();

        if (!$activeRate) {
            return back()->withErrors('No active milk rate found. Please set rate first.');
        }

        $entry = MilkEntry::where('entry_date', $request->entry_date)->first();

        if ($entry) {
            $entry->update([
                'quantity_kg' => $request->quantity_kg,
            ]);
        } else {
            MilkEntry::create([
                'entry_date' => $request->entry_date,
                'quantity_kg' => $request->quantity_kg,
                'rate_per_kg' => $activeRate->rate_per_kg,
            ]);
        }

        return redirect()->back();
    }
}
