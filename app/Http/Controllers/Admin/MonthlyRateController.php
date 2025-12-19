<?php

namespace App\Http\Controllers;

use App\Models\MonthlyRate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonthlyRateController extends Controller
{
    public function index()
    {
        $rates = MonthlyRate::orderBy('effective_from', 'desc')->get();

        return view('admin.monthly_rates.index', compact('rates'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'effective_from' => 'required|date|unique:monthly_rates,effective_from',
            'rate_per_kg' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            MonthlyRate::where('is_active', true)->update(['is_active' => false]);

            MonthlyRate::create([
                'effective_from' => $request->effective_from,
                'rate_per_kg' => $request->rate_per_kg,
                'is_active' => true,
            ]);
        });

        return redirect()->route('admin.monthly_rates.index');
    }
}
