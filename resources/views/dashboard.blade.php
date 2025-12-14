@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <p>Welcome to your dashboard, {{ auth()->user()->name }}!</p>
    <p>Email: {{ auth()->user()->email }}</p>
    <p style="margin-top: 20px; color: #666;">You are now logged in and can access protected routes.</p>
@endsection
