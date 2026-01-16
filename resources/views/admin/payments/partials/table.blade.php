<div class="card shadow-sm">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th class="text-end">Amount</th>
                    <th width="160" class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $p)
                    <tr id="row-{{ $p->id }}">
                        <td>{{ $p->payment_date->format('d M Y') }}</td>
                        <td class="payment-amount" data-amount="{{ $p->amount }}">
                            {{ number_format($p->amount) }}
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('payments.edit', $p) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal" data-row-id="row-{{ $p->id }}"
                                    data-action="{{ route('payments.destroy', $p) }}"
                                    data-date="{{ $p->payment_date->format('d M Y') }}"
                                    data-amount="{{ number_format($p->amount) }}">
                                    Delete
                                </button>

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
                        <th  id="totalAmount" class="text-end">{{ number_format($total) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            @endif
        </table>

    </div>
</div>
