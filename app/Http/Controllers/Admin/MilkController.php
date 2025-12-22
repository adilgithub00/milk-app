<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkEntry;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MilkController extends Controller
{
    public function index()
    {
        $year = now()->year;
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $entries = MilkEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->orderBy('entry_date')
                ->get();

            $totalKg = $entries->sum('quantity_kg');
            $totalAmount = $entries->sum(fn($e) => $e->quantity_kg * $e->rate_per_kg);

            $paid = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');

            $remaining = $totalAmount - $paid;

            // Group rates used in this month
            $ratesUsed = $entries->pluck('rate_per_kg')->unique()->values()->all();

            $months[] = [
                'month' => Carbon::create($year, $month)->format('F'),
                'totalKg' => $totalKg,
                'totalAmount' => $totalAmount,
                'paid' => $paid,
                'remaining' => $remaining,
                'ratesUsed' => $ratesUsed,
                'dailyEntries' => $entries,
            ];
        }

        return view('admin.milk_entries.index', compact('months', 'year'));
    }

    // public function index()
    // {
    //     $entries = MilkEntry::orderByDesc('entry_date')->paginate(15);

    //     return view('admin.milk_entries.index', compact('entries'));
    // }

    public function edit(MilkEntry $milk_entry)
    {
        return view('admin.milk_entries.edit', compact('milk_entry'));
    }

    public function update(Request $request, MilkEntry $milk_entry)
    {
        $request->validate([
            'entry_date' => 'required|date|before_or_equal:today|unique:milk_entries,entry_date,' . $milk_entry->id,
            'quantity_kg' => 'required|numeric|min:1'
        ]);

        $milk_entry->update([
            'entry_date' => $request->entry_date,
            'quantity_kg' => $request->quantity_kg
            // rate_per_kg intentionally NOT editable
        ]);

        return redirect()
            ->route('milk-entries.index')
            ->with('success', 'Milk entry updated successfully.');
    }

    public function destroy(MilkEntry $milk_entry)
    {
        $milk_entry->delete();

        return redirect()
            ->route('milk-entries.index')
            ->with('success', 'Milk entry deleted.');
    }
}
