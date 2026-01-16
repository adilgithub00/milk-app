@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')


    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card settings-card shadow">

                    <div class="card-header">
                        Change Account Password
                    </div>

                    <div class="card-body p-4">

                        <form method="POST" action="{{ route('settings.update.password') }}">
                            @csrf

                            <!-- New Password -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    New Password
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-key-fill"></i>
                                    </span>

                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter new password" required>

                                    <span class="input-group-text toggle-password" data-target="password">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>

                                <!-- Strength Meter -->
                                <div class="strength-meter">
                                    <div id="strengthBar" class="strength-bar"></div>
                                </div>

                                <small id="strengthText" class="fw-semibold"></small>

                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Confirm Password
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-check2-circle"></i>
                                    </span>

                                    <input type="password" id="confirm_password" name="password_confirmation"
                                        class="form-control" placeholder="Re-enter password" required>

                                    <span class="input-group-text toggle-password" data-target="confirm_password">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>

                                <!-- Live Match Status -->
                                <small id="matchStatus" class="fw-semibold"></small>

                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <!-- Secret Code -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Secret Code
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>

                                    <input type="text" id="secret_code" name="secret_code" class="form-control"
                                        placeholder="Enter secret code" required>
                                </div>

                                @error('secret_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-success w-100 submit-btn" disabled>
                                Update Password
                            </button>

                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- JS -->
    <script>
        /* =========================
            SHOW / HIDE PASSWORD
        ========================== */

        document.querySelectorAll('.toggle-password').forEach(btn => {

            btn.addEventListener('click', function() {

                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                }

            });

        });


        /* =========================
            VARIABLES
        ========================== */

        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');

        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        const matchText = document.getElementById('matchStatus');
        const submitBtn = document.getElementById('submitBtn');

        let isStrong = false;
        let isMatch = false;


        /* =========================
            PASSWORD STRENGTH
        ========================== */

        passwordInput.addEventListener('input', function() {

            const value = this.value;
            let strength = 0;

            if (value.length >= 8) strength++;
            if (/[A-Z]/.test(value)) strength++;
            if (/[0-9]/.test(value)) strength++;
            if (/[^A-Za-z0-9]/.test(value)) strength++;

            bar.className = 'strength-bar';

            if (strength <= 1) {

                bar.style.width = '25%';
                bar.classList.add('strength-weak');
                text.innerText = 'Weak Password';
                text.className = 'text-danger fw-semibold';
                isStrong = false;

            } else if (strength === 2 || strength === 3) {

                bar.style.width = '65%';
                bar.classList.add('strength-medium');
                text.innerText = 'Medium Strength';
                text.className = 'text-warning fw-semibold';
                isStrong = false;

            } else {

                bar.style.width = '100%';
                bar.classList.add('strength-strong');
                text.innerText = 'Strong Password';
                text.className = 'text-success fw-semibold';
                isStrong = true;

            }

            validateMatch();
            validateForm();

        });


        /* =========================
            CONFIRM PASSWORD MATCH
        ========================== */

        confirmInput.addEventListener('input', function() {

            validateMatch();
            validateForm();

        });


        /* =========================
            MATCH VALIDATION
        ========================== */

        function validateMatch() {

            if (confirmInput.value === '') {

                matchText.innerText = '';
                isMatch = false;
                return;

            }

            if (confirmInput.value === passwordInput.value) {

                matchText.innerText = 'Passwords match';
                matchText.className = 'text-success fw-semibold';
                isMatch = true;

            } else {

                matchText.innerText = 'Passwords do not match';
                matchText.className = 'text-danger fw-semibold';
                isMatch = false;

            }

        }


        /* =========================
            FINAL FORM VALIDATION
        ========================== */

        function validateForm() {

            submitBtn.disabled = !(isStrong && isMatch);

        }
    </script>


@endsection
