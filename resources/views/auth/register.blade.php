@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h1>Create Account</h1>

    <form action="/register" method="POST">
        @csrf

        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="john@example.com"
                required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Minimum 8 characters" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Confirmation Field -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Confirm your password" required>
        </div>

        <!-- Submit Button -->
        <button type="submit">Create Account</button>

        <!-- Login Link -->
        <div class="link">
            Already have an account? <a href="/login">Login here</a>
        </div>
    </form>
@endsection
