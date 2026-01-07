@extends('layouts.app')

@section('title', 'Yearly Payments')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="text-center text-md-start">
                    Payments Report (Last 12 Months)
                </h3>
                <p class="text-muted">{{ $range }}</p>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <div class="d-grid d-md-inline gap-2">

                    <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">
                        📆 Calendar
                    </a>

                    <a href="{{ url('/calculator') }}" class="btn btn-outline-primary btn-sm">
                        🧮 Calculator
                    </a>

                    <a href="{{ url('/yearly-report') }}" class="btn btn-outline-primary btn-sm">
                        📊 Yearly Report
                    </a>

                </div>
            </div>
        </div>

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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark small">
                        <tr>
                            <th>Month</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($months as $index => $m)
                            <tr>
                                <td>{{ $m['month'] }}</td>

                                <td class="text-end">
                                    {{ number_format($m['totalAmount']) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($m['paid']) }}
                                </td>

                                <td class="text-center">
                                    @if ($m['remaining'] > 0)
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#paymentModal" data-remaining="{{ $m['remaining'] }}"
                                            data-start="{{ \Carbon\Carbon::parse('01 ' . $m['month'])->startOfMonth()->toDateString() }}"
                                            data-end="{{ \Carbon\Carbon::parse('01 ' . $m['month'])->endOfMonth()->toDateString() }}">
                                            Add Payment
                                        </button>
                                    @elseif ($m['totalAmount'] > 0)
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Collapsible payments --}}
                            @if (count($m['individualEntries']) > 0)
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <div class="d-grid gap-1">
                                            <button
                                                class="btn btn-sm btn-outline-secondary w-100 d-flex justify-content-between align-items-center collapse-toggle"
                                                data-bs-toggle="collapse" data-bs-target="#payments{{ $index }}"
                                                aria-expanded="false">
                                                <span>📂 View Payments</span>
                                                <i class="bi bi-chevron-down"></i>
                                            </button>

                                            <div class="collapse" id="payments{{ $index }}">
                                                <table class="table table-sm table-bordered border-dashed">
                                                    <thead class="table-light small text-muted">
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Day</th>
                                                            <th class="text-end">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($m['individualEntries'] as $d)
                                                            <tr>
                                                                <td>{{ $d['date'] }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($d['date'])->format('D') }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($d['amount']) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                {{-- Popup Model Start --}}
                <div class="modal fade" id="paymentModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('payment.store') }}" class="modal-content">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">Add Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Payment Date</label>
                                    <input type="date" name="payment_date" id="paymentDate" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="text" name="amount" id="paymentAmount" class="form-control" required>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-success">Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Popup Model End --}}

            </div>
        </div>
    </div>

@endsection
