# Complete Laravel Authentication Code

---

## 1️⃣ DATABASE MIGRATION

**File:** `database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`

⚠️ **NO EDIT NEEDED** - Your migration file is already properly configured with all necessary tables (users, password_reset_tokens, sessions). Keep it as is!

The file already includes:

-Users table with all required fields
-Password reset tokens table
-Sessions table for managing user sessions

Just use it as-is and run: `php artisan migrate`

---

## 2️⃣ USER MODEL

**File:** `app/Models/User.php`

⚠️ **NO EDIT NEEDED** - Your User model is already perfectly configured with all necessary traits and attributes. Keep it as is!

Your model already includes:

-`HasFactory` and `Notifiable` traits
-Proper `$fillable` array with name, email, password
-`$hidden` array protecting password and remember_token
-Modern `casts()` method with hashed password and datetime casting

Just keep it exactly as you have it!

---

## 3️⃣ REGISTER CONTROLLER

**File:** `app/Http/Controllers/RegisterController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    /**
     * Show the registration form
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
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user with hashed password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect to login with success message
        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}
```

---

## 4️⃣ LOGIN CONTROLLER

**File:** `app/Http/Controllers/LoginController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function store(Request $request)
    {
        // Validate the input
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'Login successful!');
        }

        // If authentication fails, redirect back with error
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }
}
```

---

## 5️⃣ LOGOUT CONTROLLER

**File:** `app/Http/Controllers/LogoutController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
```

---

## 6️⃣ AUTHENTICATE MIDDLEWARE

**File:** `app/Http/Middleware/Authenticate.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not authenticated, redirect to login
        if (!auth()->check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
```

---

## 7️⃣ ROUTES

**File:** `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Registration Routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login Routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

// Logout Route (Protected)
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
```

---

## 8️⃣ BASE LAYOUT TEMPLATE

**File:** `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name', 'registerSystem') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
        }

        .error-message {
            color: #d32f2f;
            font-size: 13px;
            margin-top: 5px;
        }

        .success-message {
            background: #4caf50;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .link a:hover {
            text-decoration: underline;
        }

        nav {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            margin: 0 15px;
        }

        nav a:hover {
            color: #667eea;
        }

        .dashboard-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 30px auto;
        }

        .dashboard-container h1 {
            margin-bottom: 20px;
        }

        .logout-btn {
            background: #d32f2f;
            padding: 10px 20px;
            display: inline-block;
            width: auto;
        }

        .logout-btn:hover {
            background: #b71c1c;
        }
    </style>
</head>
<body>
    @if (auth()->check())
        <nav>
            <h2>Laravel Auth</h2>
            <div>
                <span>Welcome, {{ auth()->user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </nav>
    @endif

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if (!auth()->check() || request()->path() != 'dashboard')
        <div class="container">
            @yield('content')
        </div>
    @else
        <div class="dashboard-container">
            @yield('content')
        </div>
    @endif
</body>
</html>
```

---

## 9️⃣ REGISTER BLADE TEMPLATE

**File:** `resources/views/auth/register.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h1>Create Account</h1>

    <form action="/register" method="POST">
        @csrf

        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="John Doe"
                required
            >
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="john@example.com"
                required
            >
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimum 8 characters"
                required
            >
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Confirmation Field -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Confirm your password"
                required
            >
        </div>

        <!-- Submit Button -->
        <button type="submit">Create Account</button>

        <!-- Login Link -->
        <div class="link">
            Already have an account? <a href="/login">Login here</a>
        </div>
    </form>
@endsection
```

---

## 🔟 LOGIN BLADE TEMPLATE

**File:** `resources/views/auth/login.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1>Login</h1>

    <form action="/login" method="POST">
        @csrf

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="john@example.com"
                required
                autofocus
            >
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit">Login</button>

        <!-- Register Link -->
        <div class="link">
            Don't have an account? <a href="/register">Register here</a>
        </div>
    </form>
@endsection
```

---

## 1️⃣1️⃣ DASHBOARD VIEW

**File:** `resources/views/dashboard.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <p>Welcome to your dashboard, {{ auth()->user()->name }}!</p>
    <p>Email: {{ auth()->user()->email }}</p>
    <p style="margin-top: 20px; color: #666;">You are now logged in and can access protected routes.</p>
@endsection
```

---

## 1️⃣2️⃣ WELCOME PAGE

**File:** `resources/views/welcome.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - {{ config('app.name', 'registerSystem') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .welcome-container {
            background: white;
            padding: 60px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
            font-size: 32px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        a {
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
            display: inline-block;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-register {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #667eea;
        }

        a:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome to {{ config('app.name', 'registerSystem') }}</h1>
        <p>A simple and elegant authentication system built with Laravel, Blade, and SQLite for {{ config('app.name', 'registerSystem') }}.</p>

        @if (auth()->check())
            <p>Welcome back, <strong>{{ auth()->user()->name }}</strong>!</p>
            <div class="btn-group">
                <a href="/dashboard" class="btn-login">Go to Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: #d32f2f; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">Logout</button>
                </form>
            </div>
        @else
            <div class="btn-group">
                <a href="/login" class="btn-login">Login</a>
                <a href="/register" class="btn-register">Register</a>
            </div>
        @endif
    </div>
</body>
</html>
```

---

## 1️⃣3️⃣ ENVIRONMENT FILE

**File:** `.env`

Make sure these are set:

```php
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
APP_NAME="registerSystem"
APP_URL=http://localhost:8000
```

---

## 🚀 SETUP COMMANDS (In Order)

```bash
# 1. Create files with artisan
php artisan make:controller RegisterController
php artisan make:controller LoginController
php artisan make:controller LogoutController
php artisan make:middleware Authenticate
php artisan make:migration create_users_table

# 2. Copy all the code above into respective files

# 3. Run migrations
php artisan migrate

# 4. Start development server
php artisan serve

# 5. Visit http://localhost:8000 in your browser
```

---

## ✨ FEATURES

✅ User Registration with validation
✅ Secure Password Hashing
✅ User Login with session management
✅ Logout functionality
✅ Protected routes with middleware
✅ Beautiful UI with gradients
✅ Error messages display
✅ Success flash messages
✅ Old input preservation on errors
✅ Beginner-friendly comments
