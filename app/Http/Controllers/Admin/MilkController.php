<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkEntry;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MilkController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        $months = $this->buildYearReport($year);

        // AJAX request → return partial HTML only
        if ($request->ajax()) {
            return view('admin.milk_entries.partials.year_table', compact('months', 'year'))->render();
        }

        // Normal page load
        return view('admin.milk_entries.index', compact('months', 'year'));
    }

    private function buildYearReport($year)
    {
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $entries = MilkEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->orderBy('entry_date')
                ->get();

            $totalKg = $entries->sum('quantity_kg');

            $totalAmount = $entries->sum(function ($e) {
                return $e->quantity_kg * $e->rate_per_kg;
            });

            $paid = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');

            $ratesUsed = $entries
                ->pluck('rate_per_kg')
                ->unique()
                ->values()
                ->all();

            $months[] = [
                'month' => Carbon::create($year, $month)->format('F'),
                'month_number' => $month,
                'totalKg' => $totalKg,
                'totalAmount' => $totalAmount,
                'paid' => $paid,
                'remaining' => $totalAmount - $paid,
                'ratesUsed' => $ratesUsed,
                'dailyEntries' => $entries,
            ];
        }

        return $months;
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

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('milk-entries.index')
            ->with('success', 'Milk entry deleted.');
    }
}
