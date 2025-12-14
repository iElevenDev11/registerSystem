<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * show login form
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle login logic
     */
    public function store(Request $request)
    {

        // validate the input
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Attempt to Autherticate the user

        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'Login Successful');
        }

        // if authentication failed, redirect back with error
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }
}
