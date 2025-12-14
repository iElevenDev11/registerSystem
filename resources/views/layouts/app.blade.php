<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name', 'registerSystem') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if (auth()->check())
        <nav>
            <h2>{{ config('app.name', 'registerSystem') }}</h2>
            <div>
                <span>Welcome, {{ auth()->user()->name }}! 👋</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </nav>
    @endif

    @if (!auth()->check())
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

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
