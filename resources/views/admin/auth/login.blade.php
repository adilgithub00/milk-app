@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h4 class="mb-3 text-center">Login</h4>

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="mt-3 text-center">
                        Not registered yet?
                        <a href="{{ route('register') }}">
                            Register here
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
