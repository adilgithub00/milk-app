<!DOCTYPE html>
<html lang="en">

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

<head>
    <meta charset="UTF-8">
    <title>Monthly Calculator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @media (max-width: 768px) {
            h3 {
                text-align: center;
            }

            .btn {
                width: 100%;
                margin-bottom: 8px;
            }

            .card {
                margin-bottom: 12px;
            }

            table {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            h3 {
                font-size: 20px;
            }

            .fs-4 {
                font-size: 18px !important;
            }

            table {
                font-size: 13px;
            }
        }
    </style>

</head>

<body class="bg-light">

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
                        <p class="fs-4">{{ $rate }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5>Total Amount</h5>
                        <p class="fs-4">{{ $totalAmount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Paid</h5>
                        <p class="fs-4">{{ $paid }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Remaining</h5>
                        <p class="fs-4">{{ $remaining }}</p>
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

</body>

</html>
