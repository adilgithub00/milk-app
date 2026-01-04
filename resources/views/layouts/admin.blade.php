<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon1.ico') }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

</head>

<body>

    {{-- Mobile Toggle --}}
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-light" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <img src="{{ asset('logo.png') }}" height="50px" width="50px" alt="">
            {{-- <span class="navbar-brand">Admin Panel</span> --}}
        </div>
    </nav>

    {{-- Sidebar --}}
    <div class="sidebar position-fixed p-3" id="sidebar">
        <div class="mb-4 d-flex align-items-center gap-3">
            <img src="{{ asset('logo.png') }}" height="50" width="50" alt="Logo">

            <div>
                <div class="fw-semibold text-white">
                    {{ Auth::user()->name }}
                </div>
                <small class="text-secondary">Administrator</small>
            </div>
        </div>

        <ul class="nav nav-pills flex-column gap-1">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            {{-- Milk Rates --}}
            <li class="nav-item">
                <a href="{{ route('rates.index') }}"
                    class="nav-link {{ request()->is('admin/rates*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack me-2"></i> Milk Rates
                </a>
            </li>

            {{-- Milk Entries --}}
            <li class="nav-item">
                <a href="{{ route('milk-entries.index') }}"
                    class="nav-link {{ request()->is('admin/milk-entries*') ? 'active' : '' }}">
                    <i class="bi bi-droplet me-2"></i> Milk Entries
                </a>
            </li>

            {{-- Payments --}}
            <li class="nav-item">
                <a href="{{ route('payments.index') }}"
                    class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card me-2"></i> Payments
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('admin/settings*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#settingsMenu" role="button">
                    <span>
                        <i class="bi bi-gear me-2"></i> Settings
                    </span>
                    <i class="bi bi-chevron-down small"></i>
                </a>

                <div class="collapse {{ request()->is('admin/settings*') ? 'show' : '' }}" id="settingsMenu">
                    <ul class="nav flex-column ms-3 mt-1">

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.email') }}"
                                class="nav-link {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}">
                                <i class="bi bi-envelope me-2"></i> Change Email
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.password') }}"
                                class="nav-link {{ request()->routeIs('admin.settings.password') ? 'active' : '' }}">
                                <i class="bi bi-lock me-2"></i> Change Password
                            </a>
                        </li>

                              <li class="nav-item">
                            <a href="{{ route('admin.settings.milk.edit') }}"
                                class="nav-link {{ request()->routeIs('admin.settings.milk.edit') ? 'active' : '' }}">
                                <i class="bi bi-lock me-2"></i> Change Milk
                            </a>
                        </li>

                    </ul>
                </div>
            </li>


            <hr class="text-secondary">

            {{-- Logout --}}
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link text-start w-100 bg-transparent border-0">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>

        </ul>

    </div>

    {{-- Main Content --}}
    <div class="content">
        <main class="p-3">
            @yield('content')
        </main>
    </div>


    {{-- Footer --}}
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>

    {{-- Delete payment confirmation popup --}}
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {

                document.getElementById('confirmDate').innerText =
                    this.dataset.date;

                document.getElementById('confirmAmount').innerText =
                    this.dataset.amount;

                document.getElementById('deletePaymentForm').action =
                    this.dataset.action;
            });
        });
    </script>


    <script>
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Get input fields
                const dateInput = document.querySelector('input[name="payment_date"]') ??  document.querySelector('input[name="entry_date"]');
                const amountInput = document.querySelector('input[name="amount"]') ?? document.querySelector('input[name="quantity_kg"]');

                // Format date if needed (optional)
                const date = new Date(dateInput.value);
                const options = {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                };
                const formattedDate = date.toLocaleDateString('en-US', options); // 26 Dec 2025

                // Set modal text
                document.getElementById('confirmDate').innerText = formattedDate;
                document.getElementById('confirmAmount').innerText = amountInput.value;

                // Set confirm button to submit the form
                const form = dateInput.closest('form');
                document.getElementById('confirmUpdateBtn').onclick = function() {
                    form.submit();
                };
            });
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
