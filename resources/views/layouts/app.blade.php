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
