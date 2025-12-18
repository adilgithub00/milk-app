<?php

namespace App\Http\Controllers;

use App\Models\MilkEntry;
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

        MilkEntry::updateOrCreate(
            ['entry_date' => $request->entry_date],
            ['quantity_kg' => $request->quantity_kg]
        );

        return redirect()->back();
    }
}
