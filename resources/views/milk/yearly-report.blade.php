@extends('layouts.app')

@section('title', 'Yearly Report')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="text-center text-md-start">
                    Yearly Milk Report — {{ $year }}
                </h3>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <div class="d-grid d-md-inline gap-2">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm">
                        Monthly Calendar
                    </a>
                    <a href="{{ url('/calculator') }}" class="btn btn-primary btn-sm">
                        Monthly Calculator
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
                            <th>Total Milk (KG)</th>
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
                                <td>{{ number_format($m['remaining']) }}</td>
                            </tr>

                            {{-- Daily breakdown (collapsible) --}}
                            @if (count($m['dailyEntries']) > 0)
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <div class="d-grid gap-1">
                                            <button
                                                class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-between"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#dailyEntries{{ $index }}" aria-expanded="false"
                                                aria-controls="dailyEntries{{ $index }}">
                                                <span>Daily Entries</span>
                                                <i class="bi bi-chevron-down"></i>
                                            </button>

                                            <div class="collapse" id="dailyEntries{{ $index }}">
                                                <div class="table-responsive mt-1">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Milk (KG)</th>
                                                                <th>Rate</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($m['dailyEntries'] as $d)
                                                                <tr>
                                                                    <td>{{ $d['date'] }}</td>
                                                                    <td>{{ $d['kg'] }}</td>
                                                                    <td>{{ $d['rate'] }}</td>
                                                                    <td>{{ number_format($d['amount']) }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
