@extends('layouts.admin')

@section('title', 'Yearly Report')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="text-center text-md-start">
                    Yearly Milk Report — {{ $year }}
                </h3>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm mt-4">
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
                                        {{ implode(', ', $m['ratesUsed']) }}
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
                                                                    <th width="160">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($m['dailyEntries'] as $d)
                                                                    <tr>
                                                                        <td>{{ $d->entry_date->format('d-m-Y') }}</td>
                                                                        <td>{{ $d->quantity_kg }}</td>
                                                                        <td>{{ $d->rate_per_kg }}</td>
                                                                        <td>{{ number_format($d->quantity_kg * $d->rate_per_kg) }}
                                                                        </td>

                                                                        <td>
                                                                            <div class="d-flex gap-1">
                                                                                <a href="{{ route('milk-entries.edit', $d) }}"
                                                                                    class="btn btn-sm btn-warning">
                                                                                    Edit
                                                                                </a>

                                                                                <form method="POST"
                                                                                    action="{{ route('milk-entries.destroy', $d) }}"
                                                                                    onsubmit="return confirm('Delete this entry?')">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button class="btn btn-sm btn-danger">
                                                                                        Delete
                                                                                    </button>
                                                                                </form>
                                                                            </div>
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
