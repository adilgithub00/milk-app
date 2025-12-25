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

                    <button type="button" class="btn btn-primary update-btn" data-bs-toggle="modal"
                        data-bs-target="#confirmPaymentModal">
                        Update
                    </button>


                    <a href="{{ route('milk-entries.index') }}" class="btn btn-secondary">Cancel</a>

                </form>

                {{-- Popup Model Start --}}
                <div class="modal fade" id="confirmPaymentModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Update</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>Are you sure you want to update this Entry?</p>
                                <ul>
                                    <li><strong>Date:</strong> <span id="confirmDate"></span></li>
                                    <li><strong>Quantity:</strong> <span id="confirmAmount"></span> KG</li>
                                </ul>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="confirmUpdateBtn">Confirm</button>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- Popup Model End --}}

            </div>
        </div>

    </div>
@endsection
