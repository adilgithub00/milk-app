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

                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">
                        📆 Calendar
                    </a>

                    <a href="{{ url('/yearly-report') }}" class="btn btn-outline-primary btn-sm">
                        📊 Yearly Report
                    </a>

                    <a href="{{ url('/yearly-payments') }}" class="btn btn-outline-primary btn-sm">
                        💳 Payments
                    </a>

                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row my-3 g-3">

            <div class="col-md-3 top-cal-card">
                <div class="card text-center shadow-sm border-info">
                    <div class="card-body">
                        <small class="text-muted">Total Milk</small>
                        <h4 class="mb-0">{{ $totalKg }} kg</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3 top-cal-card">
                <div class="card text-center shadow-sm border-primary">
                    <div class="card-body">
                        <small class="text-muted">Rate / KG</small>
                        <h4 class="mb-0">{{ $rate }}</h4>
                        @if ($effectiveFrom)
                            <small class="text-muted">
                                From {{ \Carbon\Carbon::parse($effectiveFrom)->format('d M Y') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3 top-cal-card">
                <div class="card text-center shadow-sm border-info">
                    <div class="card-body">
                        <small class="text-muted">Total Amount</small>
                        <h4 class="mb-0">{{ number_format($totalAmount) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3 top-cal-card">
                <div class="card text-center shadow-sm border-danger">
                    <div class="card-body">
                        <small class="text-muted">Remaining</small>
                        <h4 class="mb-0">{{ number_format($remaining) }}</h4>
                    </div>
                </div>
            </div>


            {{-- Payment Form --}}
            <div class="col-12">
                <div class="card my-4 shadow-sm">
                    <div class="card-body">
                        @php
                            $today = now()->format('Y-m-d');
                            $monthStart = now()->startOfMonth()->format('Y-m-d');
                            $monthEnd = now()->endOfMonth()->format('Y-m-d');
                        @endphp

                        <h5>Add Payment</h5>
                        <form method="POST" action="{{ route('payment.store') }}" class="row g-3">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" min="{{ $today }}"
                                    max="{{ $today }}" value="{{ old('entry_date', $today) }}" required>
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

                            <div class="col-md-4" style="margin-top: 46px">
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#confirmPaymentModal" @if ($remaining <= 0) disabled @endif>
                                    <i class="bi bi-credit-card-fill"></i> Add Payment
                                </button>
                            </div>
                        </form>

                        {{-- Popup Model Start --}}
                        <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p class="mb-2">Are you sure you want to add this payment?</p>

                                        <ul class="mb-0">
                                            <li><strong>Date:</strong> <span id="confirmDate"></span></li>
                                            <li><strong>Amount:</strong> <span id="confirmAmount"></span></li>
                                        </ul>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="button" class="btn btn-primary" onclick="submitPaymentForm()">
                                            Confirm
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Popup Model End --}}

                    </div>
                </div>
            </div>

            {{-- Milk & Rate History --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5>Milk & Rate History</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Milk (KG)</th>
                                    <th>Rate/KG</th>
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
                        <table class="table table-bordered table-hover align-middle">
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
