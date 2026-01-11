@extends('layouts.app')

@section('title', 'Register')

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

        /* Floating Labels */
        .form-floating>label {
            color: #718096;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, .25);
        }
  
        .password-icon {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.1rem;
            color: #718096;
            z-index: 5;
        }

        .password-icon:hover {
            color: #667eea;
        }

        /* Password Strength */
        .strength-bar {
            height: 6px;
            border-radius: 5px;
            background: #e2e8f0;
            overflow: hidden;
            margin-top: 6px;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }

        .btn-primary {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.65rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
        }

        .error-text {
            font-size: 0.8rem;
            margin-top: 0.25rem;
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
                    <h4 class="fw-bold">Create Account</h4>
                    <p class="text-muted small">Securely register your account</p>
                </div>

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Name" required autofocus>
                        <label>Name</label>
                        @error('name')
                            <div class="text-danger error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <label>Email</label>
                        @error('email')
                            <div class="text-danger error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <div class="form-floating">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Password" required>
                            <label>Password</label>

                            <i class="bi bi-eye password-icon" onclick="togglePassword('password', this)"></i>
                        </div>

                        <div class="strength-bar">
                            <div id="strengthFill" class="strength-fill"></div>
                        </div>

                        @error('password')
                            <div class="text-danger error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password" required>
                        <label>Confirm Password</label>

                        <i class="bi bi-eye password-icon" onclick="togglePassword('password_confirmation', this)"></i>
                    </div>


                    <!-- Secret Code -->
                    <div class="form-floating mb-4">
                        <input type="password" name="secret_code" class="form-control" placeholder="Secret Code" required>
                        <label>Secret Code</label>
                        @error('secret_code')
                            <div class="text-danger error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Register
                    </button>
                </form>

                <div class="auth-footer text-center mt-4">
                    Already have an account?
                    <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');

        passwordInput.addEventListener('input', function() {
            const value = passwordInput.value;
            let strength = 0;

            if (value.length >= 8) strength++;
            if (/[A-Z]/.test(value)) strength++;
            if (/[0-9]/.test(value)) strength++;
            if (/[^A-Za-z0-9]/.test(value)) strength++;

            const percent = (strength / 4) * 100;
            strengthFill.style.width = percent + '%';

            if (strength <= 1) {
                strengthFill.style.background = '#e53e3e';
            } else if (strength === 2) {
                strengthFill.style.background = '#dd6b20';
            } else if (strength === 3) {
                strengthFill.style.background = '#38a169';
            } else {
                strengthFill.style.background = '#3182ce';
            }
        });
    </script>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>


@endsection
