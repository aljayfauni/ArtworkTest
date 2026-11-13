<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->role) {
                // Prevent redirect loop by checking if the user is already on the change password page
                // dd($request->is('password_change'));
                Auth::logout();
                return redirect()->route('login')->with('error', 'Unauthorized Access! Please contact system admin or call local 202.'); // Redirect to change password page
            }
        }

        return $next($request);
    }
}
