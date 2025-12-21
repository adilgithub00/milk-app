@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h4 class="mb-3 text-center">Change Password</h4>

                    <form method="POST" action="{{ route('settings.update.password') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="secret_code" class="form-label">Secret Code <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="secret_code" class="form-control" required>
                            @error('secret_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Change</button>
                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection
