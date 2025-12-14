<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the regidter form
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Store a newly registered user
     */
    public function store(Request $request)
    {

        // validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Creating the user with hashed password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return
            redirect('/login')
            ->with('success', 'User registered successfuly');
    }
}
