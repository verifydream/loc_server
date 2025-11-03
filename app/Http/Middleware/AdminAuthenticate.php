<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login')->with('error', 'Please login to access the admin dashboard.');
        }

        // Check for session expiration
        $lastActivity = $request->session()->get('last_activity_time');
        $sessionLifetime = config('session.lifetime', 120) * 60; // Convert minutes to seconds

        if ($lastActivity && (time() - $lastActivity > $sessionLifetime)) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }

        // Update last activity time
        $request->session()->put('last_activity_time', time());

        return $next($request);
    }
}
