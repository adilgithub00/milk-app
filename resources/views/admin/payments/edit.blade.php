@extends('layouts.admin')

@section('title', 'Edit Payment')

@section('content')
    <div class="container py-4">

        <h4 class="mb-3">Edit Payment</h4>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('payments.update', $payment) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ $payment->payment_date->format('Y-m-d') }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" name="amount" value="{{ number_format($payment->amount) }}"
                            class="form-control" placeholder="Enter amount is rupees" required
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>

                    <button type="button" class="btn btn-primary update-btn" data-bs-toggle="modal"
                        data-bs-target="#confirmPaymentModal">
                        Update
                    </button>

                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>

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
                                <p>Are you sure you want to update this payment?</p>
                                <ul>
                                    <li><strong>Date:</strong> <span id="confirmDate"></span></li>
                                    <li><strong>Amount:</strong> <span id="confirmAmount"></span></li>
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
