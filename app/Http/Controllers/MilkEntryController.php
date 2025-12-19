<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
use App\Models\MonthlyRate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MilkEntryController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $entries = MilkEntry::whereMonth('entry_date', $currentMonth)
            ->whereYear('entry_date', $currentYear)
            ->get()
            ->keyBy(fn($item) => $item->entry_date->format('Y-m-d'));

        $totalKg = $entries->sum('quantity_kg');

        return view('milk.calendar', compact(
            'entries',
            'totalKg',
            'currentMonth',
            'currentYear'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date|before_or_equal:today',
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
