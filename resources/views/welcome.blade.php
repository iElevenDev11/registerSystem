<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - {{ config('app.name', 'registerSystem') }}</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body>
    @if (auth()->check())
        <nav>
            <h2>{{ config('app.name', 'registerSystem') }}</h2>
            <nav-right>
                <span>Welcome, {{ auth()->user()->name }}! 👋</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </nav-right>
        </nav>
    @endif

    <div class="welcome-container">
        <h1>{{ config('app.name', 'registerSystem') }}</h1>
        <p>A simple and elegant authentication system built with Laravel, Blade, and SQLite.</p>

        @if (auth()->check())
            <p style="color: #94a3b8; font-size: 14px;">You're logged in and ready to go! 🚀</p>
            <div class="btn-group">
                <a href="/dashboard" class="btn-login">Go to Dashboard</a>
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
