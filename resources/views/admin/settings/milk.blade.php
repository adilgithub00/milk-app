@extends('layouts.admin')

@section('title', 'Milk Settings')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="mb-3 text-center">Daily Milk Consumption</h5>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.settings.milk.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">
                                    Milk per Day (kg)
                                </label>


                                <input type="text" name="milk_per_day_kg" class="form-control"
                                    value="{{ old('milk_per_day_kg', $perDayKg) }}" placeholder="Enter payment amount"
                                    required oninput="this.value = this.value.replace(/[^0-9]/g,'')">



                                {{-- <input type="number" step="0.1" name="milk_per_day_kg" class="form-control"
                                    value="{{ old('milk_per_day_kg', $perDayKg) }}" required> --}}

                                @error('milk_per_day_kg')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100">
                                Save
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
