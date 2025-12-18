<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Milk Calendar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-day {
            min-height: 110px;
            border-radius: 10px;
            padding: 8px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 14px;
        }

        .calendar-day.has-milk {
            background: #d1f7d6;
            border-color: #198754;
        }

        .day-number {
            font-weight: bold;
            font-size: 15px;
        }

        .kg-badge {
            font-size: 0.8rem;
            display: inline-block;
            margin-top: 5px;
        }

        /* ---------------- MOBILE ---------------- */
        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .calendar-day {
                min-height: 90px;
                padding: 6px;
                font-size: 13px;
            }

            .day-number {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .calendar-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .calendar-day {
                min-height: 80px;
                padding: 6px;
            }
        }
    </style>

</head>

<body class="bg-light">

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <h4 class="text-center text-md-start">
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                </h4>
            </div>

            <div class="col-md-4 text-center mb-2 mb-md-0">
                <span class="badge bg-primary fs-6 py-2 px-3">
                    Total Milk: {{ $totalKg }} kg
                </span>
            </div>

            <div class="col-md-4 text-center text-md-end">
                <div class="d-grid d-md-inline gap-2">
                    <a href="{{ url('/calculator') }}" class="btn btn-primary btn-sm">
                        Monthly Calculator
                    </a>
                    <a href="{{ url('/yearly-report') }}" class="btn btn-primary btn-sm">
                        Yearly Report
                    </a>
                </div>
            </div>
        </div>

        {{-- Milk Entry Form --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                @php
                    $today = now()->format('Y-m-d');
                @endphp
                <form method="POST" action="{{ route('milk.store') }}" class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-4">

                        <label class="form-label">Date</label>
                        <input type="date" name="entry_date" class="form-control" max="{{ $today }}"
                            value="{{ old('entry_date', $today) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Milk (KG)</label>
                        <input type="text" name="quantity_kg" class="form-control" placeholder="Enter milk in KG"
                            required oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-success w-100">
                            Save Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Calendar --}}
        <div class="calendar-grid">
            @php
                $start = now()->startOfMonth();
                $days = now()->daysInMonth;
            @endphp

            @for ($day = 1; $day <= $days; $day++)
                @php
                    $date = $start
                        ->copy()
                        ->addDays($day - 1)
                        ->format('Y-m-d');
                    $entry = $entries[$date] ?? null;
                @endphp

                <div class="calendar-day {{ $entry ? 'has-milk' : '' }}">
                    <div class="day-number">{{ $day }}</div>

                    @if ($entry)
                        <span class="badge bg-success kg-badge mt-2">
                            {{ $entry->quantity_kg }} kg
                        </span>
                    @else
                        <span class="text-muted small">No milk</span>
                    @endif
                </div>
            @endfor
        </div>

    </div>

</body>

</html>
