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

            <form onsubmit="return false" class="d-flex gap-2">
                <input type="month" name="month" value="{{ $month->format('Y-m') }}"
                    class="form-control form-control-sm">
                {{-- <button class="btn btn-sm btn-secondary">
                    Filter
                </button> --}}
            </form>

        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="filterLoader" class="text-center my-3 d-none">
            <div class="spinner-border"></div>
            <div class="small text-muted mt-1">Loading payments...</div>
        </div>


        <div id="paymentsContainer">
            @include('admin.payments.partials.table')
        </div>

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
                            <li><strong>Amount:</strong> <span id="confirmAmount"></span></li>
                        </ul>
                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button id="confirmDeleteBtn" class="btn btn-danger">
                            <span class="btn-text">Confirm Delete</span>
                            <span id="deleteSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                    </div>

                </div>
            </div>
        </div>


        <div class="toast-container position-fixed bottom-0 end-0 p-3">

            <div id="deleteToast" class="toast text-bg-success border-0">
                <div class="d-flex">
                    <div class="toast-body">
                        Payment deleted successfully.
                    </div>

                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>

        </div>



    </div>


    <script>
        const monthInput = document.querySelector('input[name="month"]');

        monthInput.addEventListener('change', function() {

            let month = this.value;

            document.getElementById('filterLoader').classList.remove('d-none');

            fetch(`{{ route('payments.index') }}?month=${month}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {

                    document.getElementById('paymentsContainer').innerHTML = html;

                    recalculateTotals();
                })
                .finally(() => {

                    document.getElementById('filterLoader').classList.add('d-none');

                });

        });
    </script>

    <script>
        let deleteUrl = null;
        let deleteRowId = null;


        // Capture delete button click
        document.addEventListener('click', function(e) {

            const btn = e.target.closest('.delete-btn');
            if (!btn) return;

            deleteUrl = btn.dataset.action;
            deleteRowId = btn.dataset.rowId;

            document.getElementById('confirmDate').innerText = btn.dataset.date;
            document.getElementById('confirmAmount').innerText = btn.dataset.amount;

        });


        // Confirm delete handler
        document.addEventListener('click', function(e) {

            if (!e.target.closest('#confirmDeleteBtn')) return;
            if (!deleteUrl) return;

            let spinner = document.getElementById('deleteSpinner');
            let text = document.querySelector('#confirmDeleteBtn .btn-text');

            spinner.classList.remove('d-none');
            text.classList.add('d-none');

            fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                .then(res => res.json())
                .then(() => {

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('confirmDeleteModal')
                    ).hide();

                    let row = document.getElementById(deleteRowId);

                    if (row) {

                        row.classList.add('row-highlight');

                        setTimeout(() => {

                            row.classList.add('slide-remove');

                            setTimeout(() => {
                                row.remove();
                                recalculateTotals();
                            }, 350);

                        }, 100);

                    }


                    new bootstrap.Toast(
                        document.getElementById('deleteToast')
                    ).show();

                })
                .finally(() => {

                    spinner.classList.add('d-none');
                    text.classList.remove('d-none');

                    deleteUrl = null;
                    deleteRowId = null;

                });

        });




        function recalculateTotals() {
            let sum = 0;

            document.querySelectorAll('.payment-amount').forEach(el => {
                sum += parseFloat(el.dataset.amount);
            });

            document.getElementById('totalAmount').innerText =
                sum.toLocaleString();
        }
    </script>



@endsection
