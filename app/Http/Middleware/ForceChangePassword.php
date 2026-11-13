<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ForceChangePassword
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
            if ($user->password_reset == 1 && !$user->guid && !$user->domain) {
                // Prevent redirect loop by checking if the user is already on the change password page
                // dd($request->is('password_change'));
                if ($request->is('password_change')) {
                    return $next($request); // Allow access to change password page
                }
                Session::flash('error', 'Password has been reset. Please change your password.');
                return redirect()->route('password.index')->with('error', 'Password has been reset. Please change your password.'); // Redirect to change password page
            }
        }

        return $next($request);
    }
}
