@extends('layouts.admin')

@section('title', 'Milk Settings')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">

                    <h4 class="mb-4 text-center fw-bold text-primary">Daily Milk Consumption</h4>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.milk.update') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="text" 
                                   name="milk_per_day_kg" 
                                   class="form-control @error('milk_per_day_kg') is-invalid @enderror" 
                                   id="milkPerDay" 
                                   value="{{ old('milk_per_day_kg', $perDayKg) }}" 
                                   placeholder="Milk per Day" 
                                   required 
                                   oninput="this.value = this.value.replace(/[^0-9]/g,'')">
                            <label for="milkPerDay">Milk per Day (kg)</label>

                            @error('milk_per_day_kg')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            Save Settings
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
