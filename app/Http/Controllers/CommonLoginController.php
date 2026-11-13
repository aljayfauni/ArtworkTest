<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.commonlogin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if(!$user) {
            return redirect()->back()->with('error', 'Invalid username. Please contact system admin.')->withInput();
        }

        if($user->guid && $user->domain) {
            return redirect()->back()->with('error', 'Unable to login as Common user. Please use User login.')->withInput();
        }
        
        if($user->active != 1) {
            return redirect()->back()->with('error', 'Your account is inactive. Please contact system admin.')->withInput();
        }

        // Check if user exists and verify password
        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user); // Log the user in
            return redirect(route('home')); // Redirect to intended page
        } else {
            return redirect()->back()->with('error', 'Invalid password')->withInput();
        }
    }
}
