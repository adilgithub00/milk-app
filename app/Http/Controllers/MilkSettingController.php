<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class MilkSettingController extends Controller
{
    public function edit()
    {
        $perDayKg = Setting::get('milk_per_day_kg', 2);

        return view('admin.settings.milk', compact('perDayKg'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'milk_per_day_kg' => ['required', 'numeric', 'min:0.1'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'milk_per_day_kg'],
            ['value' => $request->milk_per_day_kg]
        );

        return back()->with('success', 'Daily milk consumption updated successfully.');
    }
}
