@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <h1>Welcome Back.</h1>
        <p>Login to continue your FORMLAB journey.</p>
        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email">

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password">

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="/register">Register</a></p>
    </div>
</section>
@endsection
