@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <h1>Create Account.</h1>
        <p>Join the FORMLAB tennis community.</p>
        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif  

        <form action="{{ url('/register') }}" method="POST">
            @csrf

            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your full name">

            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email">

            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password">

            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm your password">

            <button type="submit">Create Account</button>
        </form>

        <p>Already have an account? <a href="/login">Login</a></p>
    </div>
</section>
@endsection