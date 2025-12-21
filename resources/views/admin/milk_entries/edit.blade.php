@extends('layouts.admin')

@section('title', 'Edit Milk Entry')

@section('content')
    <div class="container py-4">

        <h4 class="mb-3">Edit Milk Entry</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('milk-entries.update', $milk_entry) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="entry_date" class="form-control"
                            value="{{ $milk_entry->entry_date->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Milk (KG)</label>
                        <input type="number" name="quantity_kg" class="form-control" min="1"
                            value="{{ $milk_entry->quantity_kg }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rate (Locked)</label>
                        <input type="text" class="form-control" value="{{ number_format($milk_entry->rate_per_kg, 2) }}"
                            disabled>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('milk-entries.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
