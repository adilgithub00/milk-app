<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    // Show change email form

    public function editEmail()
    {
        return view('admin.settings.email');  // resources/views/settings/email.blade.php
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'current_email' => ['required', 'email'],
            'new_email' => ['required', 'email', 'unique:users,email'],
            'secret_code' => 'required|string|min:4|max:4',
        ]);

        if ($request->secret_code !== config('admin.secret_key')) {
            return back()->withErrors([
                'secret_code' => 'The provided secret code is incorrect.',
            ])->withInput();
        }

        // Check current email
        if ($request->current_email !== $user->email) {
            return back()->withErrors([
                'current_email' => 'Current email does not match.',
            ])->withInput();
        }

        // Update email
        User::where('id', $user->id)->update([
            'email' => $request->new_email,
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.settings.email')
            ->with('success', 'Email changed successfully.');
    }

    // Show change password form

    public function editPassword()
    {
        return view('admin.settings.password');  // resources/views/settings/password.blade.php
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'secret_code' => 'required|string|min:4|max:4',
        ]);

        if ($request->secret_code !== config('admin.secret_key')) {
            return back()->withErrors([
                'secret_code' => 'The provided secret code is incorrect.',
            ])->withInput();
        }

        // Update password
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.settings.password')
            ->with('success', 'Password changed successfully.');
    }
}
