<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkRateController extends Controller
{
    public function index()
    {
        $rates = MonthlyRate::orderByDesc('effective_from')->get();
        return view('admin.rates.index', compact('rates'));
    }

    public function create()
    {
        return view('admin.rates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'effective_from' => 'required|date',
            'rate_per_kg' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->boolean('is_active')) {
                MonthlyRate::where('is_active', true)
                    ->update(['is_active' => false]);
            }

            MonthlyRate::create([
                'effective_from' => $request->effective_from,
                'rate_per_kg' => $request->rate_per_kg,
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('rates.index')
            ->with('success', 'Milk rate added successfully.');
    }

    public function edit(MonthlyRate $rate)
    {
        return view('admin.rates.edit', compact('rate'));
    }

    public function update(Request $request, MonthlyRate $rate)
    {
        $request->validate([
            'effective_from' => 'required|date',
            'rate_per_kg' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $rate) {
            if ($request->boolean('is_active')) {
                MonthlyRate::where('is_active', true)
                    ->where('id', '!=', $rate->id)
                    ->update(['is_active' => false]);
            }

            $rate->update([
                'effective_from' => $request->effective_from,
                'rate_per_kg' => $request->rate_per_kg,
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('rates.index')
            ->with('success', 'Milk rate updated successfully.');
    }

    public function activate(MonthlyRate $rate)
    {
        DB::transaction(function () use ($rate) {
            MonthlyRate::where('is_active', true)
                ->update(['is_active' => false]);

            $rate->update(['is_active' => true]);
        });

        return redirect()
            ->route('rates.index')
            ->with('success', 'Rate activated successfully.');
    }

    public function destroy(MonthlyRate $rate)
    {
        if ($rate->is_active) {
            return back()->withErrors('Active rate cannot be deleted.');
        }

        $rate->delete();

        return redirect()
            ->route('rates.index')
            ->with('success', 'Rate deleted.');
    }
}
