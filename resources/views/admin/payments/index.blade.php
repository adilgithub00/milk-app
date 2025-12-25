@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="mb-4"> Payments — {{ $month->format('F Y') }}</h3>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">

            <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                Add Payment
            </a>

            <form method="GET" class="d-flex gap-2">
                <input type="month" name="month" value="{{ $month->format('Y-m') }}"
                    class="form-control form-control-sm">
                <button class="btn btn-sm btn-secondary">
                    Filter
                </button>
            </form>

        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
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
                                <td class="payment-date">{{ $p->payment_date->format('d M Y') }}</td>
                                <td class="amount">{{ number_format($p->amount) }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('payments.edit', $p) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('payments.destroy', $p) }}"
                                            onsubmit="return confirm('Delete payment?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                data-date="{{ $p->payment_date->format('d M Y') }}"
                                                data-amount="{{ number_format($p->amount) }}"
                                                data-action="{{ route('payments.destroy', $p) }}">
                                                Delete
                                            </button>

                                        </form>

                                        {{-- Popup Model Start --}}
                                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
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
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>

                                                        <form id="deletePaymentForm" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger">Confirm</button>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- Popup Model End --}}

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
