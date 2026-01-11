@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 18px;
            border: none;
            background: #ffffff;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, .25);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 5;
        }

        .password-toggle i {
            font-size: 1.15rem;
            color: #718096;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .password-toggle:hover i {
            color: #667eea;
            transform: scale(1.15);
        }

        .btn-primary {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.65rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
        }

        .auth-footer {
            font-size: 0.85rem;
            color: #4a5568;
        }

        .auth-footer a {
            font-weight: 600;
            color: #667eea;
            text-decoration: none;
        }
    </style>

    <div class="container d-flex align-items-center justify-content-center py-5">
        <div class="col-md-5 col-lg-4">
            <div class="card auth-card p-4">

                <div class="text-center mb-4">
                    <h4 class="fw-bold">Welcome Back</h4>
                    <p class="text-muted small">Login to continue</p>
                </div>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                        <label>Email</label>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-4 password-wrapper">
                        <input type="password" id="login_password" name="password" class="form-control"
                            placeholder="Password" required>
                        <label>Password</label>

                        <button type="button" class="password-toggle" aria-label="Show password" aria-pressed="false"
                            onclick="togglePassword('login_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>

                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <div class="auth-footer text-center mt-4">
                    Not registered yet?
                    <a href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            const isVisible = input.type === 'text';

            input.type = isVisible ? 'password' : 'text';
            button.setAttribute('aria-pressed', String(!isVisible));
            button.setAttribute(
                'aria-label',
                isVisible ? 'Show password' : 'Hide password'
            );

            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }
    </script>
@endsection
