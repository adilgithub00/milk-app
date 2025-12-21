@extends('layouts.admin')

@section('title', 'Add Milk Rate')

@section('content')
    <div class="container py-4">

        <h4 class="mb-3">Add Milk Rate</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('rates.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Effective From</label>
                        <input type="date" name="effective_from" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rate per KG</label>
                        <input type="number" step="0.01" name="rate_per_kg" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="activeRate">
                        <label class="form-check-label" for="activeRate">
                            Set as active rate
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            Save
                        </button>
                        <a href="{{ route('rates.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
