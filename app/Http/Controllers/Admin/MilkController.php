<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkEntry;
use Illuminate\Http\Request;

class MilkController extends Controller
{
    public function index()
    {
        $entries = MilkEntry::orderByDesc('entry_date')->paginate(15);

        return view('admin.milk_entries.index', compact('entries'));
    }

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
