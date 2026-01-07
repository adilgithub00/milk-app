@extends('layouts.app')

@section('title', 'Yearly Report')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="text-center text-md-start">
                    Milk Report (Last 12 Months)
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

                    <a href="{{ url('/yearly-payments') }}" class="btn btn-outline-primary btn-sm">
                        💳 Payments
                    </a>

                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Month</th>
                            <th style="">Total Milk (KG)</th>
                            <th>Rates Used</th>
                            <th>Total Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($months as $index => $m)
                            <tr
                                class="{{ strtolower($m['month']) == strtolower(now()->format('F')) ? 'table-warning' : '' }}">
                                <td>{{ $m['month'] }}</td>
                                <td>{{ $m['totalKg'] }}</td>
                                <td>
                                    @if (count($m['ratesUsed']) === 0)
                                        <span class="text-muted">—</span>
                                    @elseif(count($m['ratesUsed']) === 1)
                                        {{ $m['ratesUsed'][0] }}
                                    @else
                                        <span class="badge bg-warning text-dark">Multiple</span>
                                    @endif
                                </td>
                                <td>{{ number_format($m['totalAmount']) }}</td>
                                <td>{{ number_format($m['paid']) }}</td>
                                <td>
                                    @if ($m['remaining'] > 0)
                                        <span class="fw-semibold text-danger">
                                            {{ number_format($m['remaining']) }}
                                        </span>
                                    @else
                                        <span class="text-success fw-semibold">0</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Daily breakdown (collapsible) --}}
                            @if (count($m['dailyEntries']) > 0)
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <div class="d-grid gap-1">
                                            <button
                                                class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-between collapse-toggle"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#dailyEntries{{ $index }}" aria-expanded="false"
                                                aria-controls="dailyEntries{{ $index }}">
                                                <span>Daily Entries</span>
                                                <i class="bi bi-chevron-down"></i>
                                            </button>

                                            <div class="collapse" id="dailyEntries{{ $index }}">
                                                <div class="table-responsive mt-1">
                                                    <table class="table table-sm table-bordered border-dashed">
                                                        <thead class="table-light small text-muted">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Day</th>
                                                                <th class="text-end">KG</th>
                                                                <th class="text-end">Rate</th>
                                                                <th class="text-end">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($m['dailyEntries'] as $d)
                                                                <tr>
                                                                    <td>{{ $d['date'] }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($d['date'])->translatedFormat('D') }}
                                                                    </td>
                                                                    <td class="text-end">{{ $d['kg'] }}</td>
                                                                    <td class="text-end">{{ number_format($d['rate'], 2) }}
                                                                    </td>
                                                                    <td class="text-end">{{ number_format($d['amount']) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endif
                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>
    </div>

@endsection
