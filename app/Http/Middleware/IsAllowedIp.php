<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class IsAllowedIp
{
    public function handle(Request $request, Closure $next)
    {

        // Allow localhost for dev
        if (app()->isLocal()) {
            return $next($request);
        }

        // Use Auth facade instead of helper
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        $allowedIps = array_map('trim', explode(',', config('admin.allowed_ips')));

        $clientIp =  $request->header('X-Real-IP') ?? $request->header('X-Forwarded-For');

        if (!in_array($request->ip(), $allowedIps)) {
            abort(403, 'This requests is restricted for you.' . $clientIp);
        }


        return $next($request);
    }
}
