@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 id="monthTitle" class="mb-4">
                    Monthly Stats — {{ \Carbon\Carbon::create($yearInput, $monthInput)->format('F Y') }}
                </h3>
            </div>

        </div>

        <form id="filterForm" class="row g-2 align-items-center mb-3">
            @php
                $currentYear = now()->year;
                $currentMonth = now()->month;
            @endphp

            <div class="col-auto">
                <select name="month" class="form-select form-select-sm">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == $monthInput ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <select name="year" class="form-select form-select-sm">
                    @foreach (range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}" {{ $y == $yearInput ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
            </div>
        </form>



        {{-- SUMMARY CARDS --}}
        <div class="row g-3 mb-4">

            <div class="alert alert-light border mt-3">
                <strong>Insights:</strong>
                <ul class="mb-0">
                    <li>Average per day: {{ $monthInput > 0 ? number_format($totalKg / now()->day, 2) : 0 }} KG</li>
                    <li>Payment Coverage: {{ $totalAmount > 0 ? round(($paid / $totalAmount) * 100) : 0 }}%</li>
                    <li>Status:
                        @if ($remaining > 0)
                            <span class="text-danger">Pending Dues</span>
                        @else
                            <span class="text-success">All Clear</span>
                        @endif
                    </li>
                </ul>
            </div>


            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Active Rate</small>
                        <h5 class="mt-2">
                            {{ $activeRate ? number_format($activeRate->rate_per_kg, 2) : '—' }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">This Month Milk</small>
                        <h5 id="totalKg" class="mt-2">{{ number_format($totalKg, 2) }} KG</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Amount</small>
                        <h5 id="totalAmount" class="mt-2">{{ number_format($totalAmount, 2) }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Remaining Dues</small>
                        <h5 id="remainingAmount" class="mt-2 {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format(max($remaining, 0), 2) }}
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        {{-- MILK TREND GRAPH --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Daily Milk Trend (KG)</h6>
                        <canvas id="milkChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            {{-- MONTHLY COMPARISON CHART --}}
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Last 6 Months Overview</h6>

                        <canvas id="monthlyComparisonChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment COMPARISON CHART --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Paid vs Remaining (Last 6 Months)</h6>
                        <canvas id="paymentStackedChart" height="110"></canvas>
                    </div>
                </div>
            </div>
        </div>


        {{-- RECENT ACTIVITY --}}
        <div class="row g-3">

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Recent Milk Entries</h6>
                        <table class="table table-sm mt-2">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>KG</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMilk as $m)
                                    <tr>
                                        <td>{{ $m->entry_date->format('d M') }}</td>
                                        <td>{{ $m->quantity_kg }}</td>
                                        <td>{{ number_format($m->rate_per_kg, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No entries
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>Recent Payments</h6>
                        <table class="table table-sm mt-2">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $p)
                                    <tr>
                                        <td>{{ $p->payment_date->format('d M') }}</td>
                                        <td>{{ number_format($p->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">
                                            No payments
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="dashboardLoader"
        class="position-fixed top-0 start-0 w-100 h-100 d-none d-flex align-items-center justify-content-center"
        style="background: rgba(255,255,255,.6); z-index:1050">
        <div class="spinner-border text-primary" role="status"></div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Global Chart.js animation settings
        Chart.defaults.animation.duration = 600;
        Chart.defaults.animation.easing = 'easeOutQuart';
    </script>

    <script>
        const loader = document.getElementById('dashboardLoader');

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            loader.classList.remove('d-none');

            const month = document.querySelector('select[name="month"]').value;
            const year = document.querySelector('select[name="year"]').value;

            fetch(`{{ route('admin.dashboard.filter') }}?month=${month}&year=${year}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server error');
                    }
                    return response.json();
                })
                .then(data => {

                    // TITLE
                    document.getElementById('monthTitle').innerText =
                        `Monthly Stats — ${data.monthLabel}`;

                    // CARDS
                    document.getElementById('totalKg').innerText = data.totalKg + ' KG';
                    document.getElementById('totalAmount').innerText =
                        Number(data.totalAmount).toLocaleString();
                    document.getElementById('remainingAmount').innerText =
                        Number(data.remaining).toLocaleString();

                    // DAILY MILK CHART
                    milkChart.data.labels = data.dailyStats.map(d => d.day);
                    milkChart.data.datasets[0].data = data.dailyStats.map(d => d.total_kg);
                    milkChart.update();

                    // MONTHLY COMPARISON CHART
                    monthlyChart.data.labels = data.monthlyComparison.map(m => m.label);
                    monthlyChart.data.datasets[0].data = data.monthlyComparison.map(m => m.kg);
                    monthlyChart.data.datasets[1].data = data.monthlyComparison.map(m => m.amount);
                    monthlyChart.update();

                    // PAYMENT STACKED CHART
                    paymentChart.data.labels = data.paymentComparison.map(p => p.label);
                    paymentChart.data.datasets[0].data = data.paymentComparison.map(p => p.paid);
                    paymentChart.data.datasets[1].data = data.paymentComparison.map(p => p.remaining);
                    paymentChart.update();
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to load dashboard data.');
                })
                .finally(() => {
                    // ALWAYS hide loader
                    loader.classList.add('d-none');
                });
        });
    </script>

    <script>
        const dailyLabels = {!! json_encode($dailyStats->pluck('day')) !!};
        const dailyData = {!! json_encode($dailyStats->pluck('total_kg')) !!};

        const milkChart = new Chart(document.getElementById('milkChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Milk (KG)',
                    data: dailyData,
                    borderWidth: 2,
                    tension: 0.3
                }]
            }
        });
    </script>


    <script>
        const monthlyData = @json($monthlyComparison);

        const monthLabels = monthlyData.map(item => item.label);
        const milkKgData = monthlyData.map(item => item.kg);
        const amountData = monthlyData.map(item => item.amount);

        const monthlyChart = new Chart(
            document.getElementById('monthlyComparisonChart'), {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                            label: 'Milk (KG)',
                            data: milkKgData,
                            yAxisID: 'yMilk',
                            backgroundColor: 'rgba(13, 110, 253, 0.7)', // Bootstrap primary
                            borderRadius: 4,
                        },
                        {
                            label: 'Amount',
                            data: amountData,
                            yAxisID: 'yAmount',
                            backgroundColor: 'rgba(25, 135, 84, 0.7)', // Bootstrap success
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        yMilk: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Milk (KG)'
                            }
                        },
                        yAmount: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount'
                            },
                            grid: {
                                drawOnChartArea: false // prevents grid overlap
                            }
                        }
                    }
                }
            });
    </script>

    <script>
        const paymentData = @json($paymentComparison);

        const paymentLabels = paymentData.map(item => item.label);
        const paidData = paymentData.map(item => item.paid);
        const remainingData = paymentData.map(item => item.remaining);

        const paymentChart = new Chart(
            document.getElementById('paymentStackedChart'), {
                type: 'bar',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                            label: 'Paid',
                            data: paidData,
                            backgroundColor: 'rgba(25, 135, 84, 0.8)', // Bootstrap success
                        },
                        {
                            label: 'Remaining',
                            data: remainingData,
                            backgroundColor: 'rgba(220, 53, 69, 0.8)', // Bootstrap danger
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });
    </script>



@endsection
