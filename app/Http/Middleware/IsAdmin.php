<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // <- Import this
use Closure;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Use Auth facade instead of helper
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
