@extends('layouts.admin')

@section('title', 'Edit Payment')

@section('content')
    <div class="container py-4">

        <h4 class="mb-3">Edit Payment</h4>

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
                        <input type="text" name="amount" value="{{ $payment->amount }}" class="form-control" required
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>

                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>

                </form>

            </div>
        </div>

    </div>
@endsection
