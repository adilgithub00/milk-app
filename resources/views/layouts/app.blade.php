<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Milk Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon1.ico') }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="bg-light">

    @yield('content')


    {{-- Footer --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );

            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    {{-- Add payment confirmation popup --}}
    <script>
        function formatDateMMDDYYYY(dateStr) {
            const [y, M, d] = dateStr.split('-');
            return `${d}-${M}-${y}`;
        }

        const paymentDateInput = document.querySelector('input[name="payment_date"]');
        const amountInput = document.querySelector('input[name="amount"]');
        const form = document.querySelector('form[action="{{ route('payment.store') }}"]');

        document.getElementById('confirmPaymentModal')
            .addEventListener('show.bs.modal', function() {
                document.getElementById('confirmDate').innerText =
                    formatDateMMDDYYYY(paymentDateInput.value);

                document.getElementById('confirmAmount').innerText = amountInput.value;
            });

        function submitPaymentForm() {
            form.submit();
        }
    </script>


    {{-- Add payment confirmation popup from report section --}}
    <script>
        document.getElementById('paymentModal').addEventListener('show.bs.modal', function(event) {

            const button = event.relatedTarget;

            const remaining = parseInt(button.getAttribute('data-remaining'));
            const start = button.getAttribute('data-start');
            const end = button.getAttribute('data-end');

            const dateInput = document.getElementById('paymentDate');
            const amountInput = document.getElementById('paymentAmount');
            const submitBtn = document.querySelector('#paymentModal .btn-success');

            // Disable submit if remaining <= 0
            if (remaining <= 0) {
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }

            // Date restrictions
            dateInput.min = start;
            dateInput.max = end;

            const today = new Date().toISOString().split('T')[0];

            if (today >= start && today <= end) {
                dateInput.value = today;
            } else if (today > end) {
                dateInput.value = end;
            } else {
                dateInput.value = start;
            }

            // Amount logic
            amountInput.value = remaining;
            amountInput.oninput = function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (parseInt(this.value) > remaining) {
                    this.value = remaining;
                }
            };
        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');

            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000); // 5 seconds
            });
        });
    </script>

</body>

</html>
