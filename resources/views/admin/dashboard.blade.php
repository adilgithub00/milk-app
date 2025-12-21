@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="mb-4">
                    Monthly Stats — {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                </h3>
            </div>

        </div>


        {{-- SUMMARY CARDS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Active Rate</small>
                        <h5 class="mt-2">
                            {{ $activeRate ? number_format($activeRate->rate_per_kg, 2) : '—' }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">This Month Milk</small>
                        <h5 class="mt-2">{{ number_format($totalKg, 2) }} KG</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Amount</small>
                        <h5 class="mt-2">{{ number_format($totalAmount, 2) }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Remaining Dues</small>
                        <h5 class="mt-2 {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format(max($remaining, 0), 2) }}
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        {{-- RECENT ACTIVITY --}}
        <div class="row g-3">

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Recent Milk Entries</h6>
                        <table class="table table-sm mt-2">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>KG</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMilk as $m)
                                    <tr>
                                        <td>{{ $m->entry_date->format('d M') }}</td>
                                        <td>{{ $m->quantity_kg }}</td>
                                        <td>{{ number_format($m->rate_per_kg, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No entries
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Recent Payments</h6>
                        <table class="table table-sm mt-2">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $p)
                                    <tr>
                                        <td>{{ $p->payment_date->format('d M') }}</td>
                                        <td>{{ number_format($p->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">
                                            No payments
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
