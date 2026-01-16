@extends('layouts.admin')

@section('title', 'Change Email')

@section('content')

    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card settings-change-email-card shadow">

                    <!-- Header -->
                    <div class="card-header">
                        Change Account Email
                    </div>

                    <div class="card-body p-4">

                        <form method="POST" action="{{ route('settings.update.email') }}">
                            @csrf

                            <!-- Current Email -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Current Email
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>

                                    <input type="email" name="current_email" class="form-control"
                                        placeholder="Enter your current email" required>
                                </div>

                                @error('current_email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- New Email -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    New Email
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope-open-fill"></i>
                                    </span>

                                    <input type="email" name="new_email" class="form-control"
                                        placeholder="Enter new email address" required>
                                </div>

                                @error('new_email')
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

                                    <input type="text" name="secret_code" class="form-control"
                                        placeholder="Enter your secret code" required>
                                </div>

                                @error('secret_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary w-100 submit-btn">
                                Update Email
                            </button>

                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
