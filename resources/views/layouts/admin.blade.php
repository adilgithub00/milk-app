<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #212529;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: #343a40;
            color: #fff;
        }

        .content {
            margin-left: 250px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                transition: all 0.3s;
                z-index: 1000;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Mobile Toggle --}}
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-light" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand">Admin Panel</span>
        </div>
    </nav>

    {{-- Sidebar --}}
    <div class="sidebar position-fixed p-3" id="sidebar">
        <div class="mb-4">
            <div class="fw-semibold text-white">
                {{ Auth::user()->name }}
            </div>
            <small class="text-secondary">Administrator</small>
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

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
