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

                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm">
                        Monthly Calendar
                    </a>

                    <a href="{{ url('/calculator') }}" class="btn btn-primary btn-sm">
                        Monthly Calculator
                    </a>

                    <a href="{{ url('/yearly-report') }}" class="btn btn-primary btn-sm">
                        Yearly Report
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
                            <th>Total Amount</th>
                            <th>Paid</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody style="background-color:black !important">
                        @foreach ($months as $index => $m)
                            <tr
                                class="{{ strtolower($m['month']) == strtolower(now()->format('F')) ? 'table-warning' : '' }}">
                                <td>{{ $m['month'] }}</td>
                                <td>{{ number_format($m['totalAmount']) }}</td>
                                <td>{{ number_format($m['paid']) }}
                                    {{-- <small class="text-muted" style="size:1px; color:red">Remaining: {{ $m['remaining'] }}</small> --}}
                                </td>
                                <td>
                                    @if ($m['remaining'] > 0 || ($m['remaining'] > 0 && $lastMonth === $m['month']))
                                        <button type="button" class="btn btn-sm btn-primary payment-btn"
                                            data-bs-toggle="modal" data-bs-target="#confirmPaymenteModal"
                                            data-date="{{ $m['month'] }}"
                                            data-amount="{{ number_format($m['remaining']) }}"
                                            data-action="{{ route('payment.store') }}">
                                            Add Payment
                                        </button>
                                    @else
                                        <small class="text-muted">Not Available</small>
                                    @endif
                                </td>
                            </tr>

                            {{-- Daily breakdown (collapsible) --}}
                            @if (count($m['individualEntries']) > 0)
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <div class="d-grid gap-1">
                                            <button
                                                class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-between"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#dailyEntries{{ $index }}" aria-expanded="false"
                                                aria-controls="dailyEntries{{ $index }}">
                                                <span>All Entries</span>
                                                <i class="bi bi-chevron-down"></i>
                                            </button>

                                            <div class="collapse" id="dailyEntries{{ $index }}">
                                                <div class="table-responsive mt-1">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Day</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($m['individualEntries'] as $d)
                                                                <tr>
                                                                    <td>{{ $d['date'] }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($d['date'])->translatedFormat('D') }}
                                                                    </td>
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

                {{-- Popup Model Start --}}
                <div class="modal fade" id="confirmPaymenteModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>Are you sure you want to delete this payment?</p>
                                <ul>
                                    <li><strong>Date:</strong> <span id="confirmDate"></span></li>
                                    <li><strong>Amount:</strong> <span id="confirmAmount"></span>
                                    </li>
                                </ul>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                <form id="addPaymentForm" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button class="btn btn-danger">Confirm</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- Popup Model End --}}

            </div>
        </div>
    </div>

@endsection
