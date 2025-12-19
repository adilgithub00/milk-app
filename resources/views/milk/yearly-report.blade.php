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
                        Back to Calendar
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
                        @foreach ($months as $m)
                            <tr @if (strtolower($m['month']) == strtolower(now()->format('F'))) class="table-warning" @endif>
                                <td>{{ $m['month'] }}</td>
                                <td>{{ $m['kg'] }}</td>
                                <td>
                                    @if (count($m['rates']) === 0)
                                        <span class="text-muted">—</span>
                                    @elseif(count($m['rates']) === 1)
                                        {{ number_format($m['rates'][0], 2) }}
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Multiple
                                        </span>
                                        <small class="text-muted d-block">
                                            {{ implode(', ', $m['rates']) }}
                                        </small>
                                    @endif
                                </td>
                                <td>{{ number_format($m['total']) }}</td>
                                <td>{{ number_format($m['paid']) }}</td>
                                <td>
                                    @if ($m['remaining'] > 0)
                                        <span class="text-danger">{{ number_format($m['remaining']) }}</span>
                                    @else
                                        <span class="text-success">0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

@endsection
