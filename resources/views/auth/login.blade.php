@extends('layouts.blank')
@section('title', 'Login')
@section('contents')
    <a href="{{ url('/') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
        <img src="{{ asset('/assets/images/logos/logo.jpeg') }}" width="180" alt="">
    </a>
    <p class="text-center">Title: Social Campaigns</p>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required autofocus autocomplete="email" aria-describedby="emailHelp">
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
            @error('password')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input primary" type="checkbox" name="remember" id="remember_me" checked>
                <label class="form-check-label text-dark" for="remember_me">
                    Remeber this Device
                </label>
            </div>
        </div>
        <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Log In</button>
    </form>
@endsection
