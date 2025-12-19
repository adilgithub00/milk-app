@extends('layouts.app')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@section('title', 'Monthly Calculator')
@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="text-center text-md-start">
                    {{ \Carbon\Carbon::create($year, $month)->format('F Y') }} - Milk Calculator
                </h3>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <div class="d-grid d-md-inline gap-2">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm">
                        Back to Calendar
                    </a>
                    <a href="{{ url('/yearly-report') }}" class="btn btn-primary btn-sm">
                        Yearly Report
                    </a>
                </div>
            </div>
        </div>

        <div class="row my-3 g-3">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5>Total Milk (KG)</h5>
                        <p class="fs-4">{{ $totalKg }} kg</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5>Rate per KG</h5>
                        <small class="text-muted">
                            @if ($effectiveFrom)
                                (Effective from: {{ \Carbon\Carbon::create($effectiveFrom)->format('d-m-Y') }})
                            @endif
                        </small>
                        <p class="fs-4">{{ $rate }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5>Total Amount</h5>
                        <p class="fs-4">{{ number_format($totalAmount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Paid</h5>
                        <p class="fs-4"> {{ number_format($paid) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Remaining</h5>
                        <p class="fs-4"> {{ number_format($remaining) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Form --}}
        <div class="card my-4 shadow-sm">
            <div class="card-body">
                @php
                    $today = now()->format('Y-m-d');
                @endphp

                <h5>Add Payment</h5>
                <form method="POST" action="{{ route('payment.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label">Payment Date</label>

                        <input type="date" name="payment_date" class="form-control" max="{{ $today }}"
                            value="{{ old('entry_date', $today) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount</label>
                        @if ($remaining > 0)
                            <small class="text-muted">Max: {{ $remaining }}</small>
                        @endif
                        <input type="text" name="amount" class="form-control" value="{{ $remaining }}"
                            placeholder="Enter payment amount" required
                            oninput="this.value = this.value.replace(/[^0-9]/g,'');
                    if(parseInt(this.value) > {{ $remaining }}) this.value = {{ $remaining }};">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100" type="submit"
                            @if ($remaining <= 0) disabled @endif>
                            Add Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Milk & Rate History --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5>Milk & Rate History</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Milk (KG)</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($milkEntries as $e)
                                <tr>
                                    <td>{{ $e->entry_date->format('d-m-Y') }}</td>
                                    <td>{{ $e->quantity_kg }}</td>
                                    <td>{{ $e->rate_per_kg }}</td>
                                    <td>{{ number_format($e->quantity_kg * $e->rate_per_kg) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        {{-- Payments Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Payments History</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                                <tr>
                                    <td>{{ $p->payment_date->format('d-m-Y') }}</td>
                                    <td>{{ $p->amount }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No payments yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection
