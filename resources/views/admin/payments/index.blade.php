@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="mb-4"> Payments — {{ $month->format('F Y') }}</h3>
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                Add Payment
            </a>

            <form method="GET" class="d-flex gap-2">
                <input type="month" name="month" value="{{ $month->format('Y-m') }}"
                    class="form-control form-control-sm">
                <button class="btn btn-sm btn-secondary">Filter</button>
            </form>
        </div>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $p)
                            <tr>
                                <td>{{ $p->payment_date->format('d M Y') }}</td>
                                <td>{{ number_format($p->amount) }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('payments.edit', $p) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('payments.destroy', $p) }}"
                                            onsubmit="return confirm('Delete payment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($payments->count())
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th>{{ number_format($total) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    @endif
                </table>

            </div>
        </div>

    </div>
@endsection
