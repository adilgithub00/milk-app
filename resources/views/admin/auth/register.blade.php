@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h4 class="mb-3 text-center">Register</h4>

                    <form method="POST" action="{{ route('register.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required autofocus>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
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

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="secret_code" class="form-label">Secret Code</label>
                            <input type="password" name="secret_code" class="form-control" required>
                            @error('secret_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        Already have an account?
                        <a href="{{ route('login') }}">
                            Login here
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
