@extends('layouts.admin')

@section('title', 'Add Payment')

@section('content')
    <div class="container py-4">

        <h4 class="mb-3">Add Payment</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" max="{{ now()->format('Y-m-d') }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" name="amount" class="form-control" required
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>

                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>

                </form>

            </div>
        </div>

    </div>
@endsection
