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

            <div class="alert alert-info border mt-3">
                <strong>Insights:</strong>
                <ul class="mb-0">
                    <li>Average per day: {{ $perDayKg }} KG</li>
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

                        <div class="row">

                            <div class="col-md-6">
                                <h6 class="mb-3">Last 6 Months Overview</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex justify-content-end gap-2 mb-2">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="exportPNG(monthlyChart)">
                                        Export PNG
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="exportPDF(monthlyChart, 'Monthly Report')">
                                        Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>

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
                        <canvas id="paymentStackedChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Milk Rate History (PKR / KG)</h6>
                        <canvas id="rateChart" height="120"></canvas>
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


    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Global Chart.js animation settings
        Chart.defaults.animation.duration = 600;
        Chart.defaults.animation.easing = 'easeOutQuart';
    </script>

    <script>
        function currencyTooltip(label, value) {
            return `${label}: ${Number(value).toLocaleString()} PKR`;
        }

        function kgTooltip(label, value) {
            return `${label}: ${Number(value).toLocaleString()} KG`;
        }
    </script>

    <script>
        function createLineChart(canvasId, labels, data, label, tooltipFormatter) {
            return new Chart(document.getElementById(canvasId), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data,
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return tooltipFormatter(label, context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });
        }

        function createBarChart(canvasId, labels, datasets, options = {}) {
            return new Chart(document.getElementById(canvasId), {
                type: 'bar',
                data: {
                    labels,
                    datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: options.tooltip
                    },
                    scales: options.scales
                }
            });
        }
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
        const milkChart = createLineChart(
            'milkChart',
            @json($dailyStats->pluck('day')),
            @json($dailyStats->pluck('total_kg')),
            'Milk (KG)',
            kgTooltip
        );
    </script>


    <script>
        const monthlyChart = createBarChart(
            'monthlyComparisonChart',
            @json(collect($monthlyComparison)->pluck('label')),
            [{
                    label: 'Milk (KG)',
                    data: @json(collect($monthlyComparison)->pluck('kg')),
                    yAxisID: 'yMilk',
                    backgroundColor: 'rgba(13,110,253,.7)',
                    borderRadius: 4
                },
                {
                    label: 'Amount',
                    data: @json(collect($monthlyComparison)->pluck('amount')),
                    yAxisID: 'yAmount',
                    backgroundColor: 'rgba(25,135,84,.7)',
                    borderRadius: 4
                }
            ], {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label === 'Milk (KG)' ?
                                kgTooltip(context.dataset.label, context.parsed.y) :
                                currencyTooltip(context.dataset.label, context.parsed.y);
                        }
                    }
                },
                scales: {
                    yMilk: {
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Milk (KG)'
                        }
                    },
                    yAmount: {
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        );
    </script>


    <script>
        const paymentChart = createBarChart(
            'paymentStackedChart',
            @json(collect($paymentComparison)->pluck('label')),
            [{
                    label: 'Paid',
                    data: @json(collect($paymentComparison)->pluck('paid')),
                    backgroundColor: 'rgba(25,135,84,.8)'
                },
                {
                    label: 'Remaining',
                    data: @json(collect($paymentComparison)->pluck('remaining')),
                    backgroundColor: 'rgba(220,53,69,.8)'
                }
            ], {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return currencyTooltip(context.dataset.label, context.parsed.y);
                        }
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
        );
    </script>

    <script>
        const rateChart = createLineChart(
            'rateChart',
            @json($rateHistory->pluck('label')),
            @json($rateHistory->pluck('rate_per_kg')),
            'Rate (PKR / KG)',
            currencyTooltip
        );
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        function exportPNG(chart) {
            const link = document.createElement('a');
            link.href = chart.toBase64Image();
            link.download = 'chart.png';
            link.click();
        }

        function exportPDF(chart, title = 'Chart') {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF();

            pdf.setFontSize(14);
            pdf.text(title, 14, 15);

            pdf.addImage(
                chart.toBase64Image(),
                'PNG',
                10,
                25,
                190,
                90
            );

            pdf.save(`${title}.pdf`);
        }
    </script>


@endsection
