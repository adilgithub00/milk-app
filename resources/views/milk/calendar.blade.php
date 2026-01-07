@extends('layouts.app')

@section('title', 'Milk Calendar')
@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h4 class="text-center text-md-start">
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                </h4>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <div class="d-grid d-md-inline gap-2">

                    <a href="{{ url('/calculator') }}" class="btn btn-outline-primary btn-sm">
                        🧮 Calculator
                    </a>

                    <a href="{{ url('/yearly-report') }}" class="btn btn-outline-primary btn-sm">
                        📊 Yearly Report
                    </a>

                    <a href="{{ url('/yearly-payments') }}" class="btn btn-outline-primary btn-sm">
                        💳 Payments
                    </a>

                </div>
            </div>
        </div>


        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Total Milk</small>
                        <h4 class="mb-0">{{ $totalKg }} kg</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Daily Consumption Avg</small>
                        <h4 class="mb-0">{{ $perDayKg }} kg</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Active Rate</small>
                        <h4 class="mb-0">
                            {{ $activeRate ? $activeRate . ' /kg' : 'Not set' }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>



        {{-- Milk Entry Form --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                @php
                    $today = now()->format('Y-m-d');
                    $monthStart = now()->startOfMonth()->format('Y-m-d');
                    $monthEnd = now()->endOfMonth()->format('Y-m-d');
                @endphp
                <form method="POST" action="{{ route('milk.store') }}" class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="entry_date" class="form-control" min="{{ $monthStart }}"
                            max="{{ $today }}" value="{{ old('entry_date', $today) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Milk (KG)</label>
                        <input type="text" name="quantity_kg" class="form-control" placeholder="Enter milk in KG"
                            required oninput="this.value = this.value.replace(/[^0-9.]/g,'');">
                    </div>


                    <div class="col-md-3">
                        <label class="form-label">Rate/KG</label>
                        <input type="text" name="quantity_kg" class="form-control" disabled
                            value="{{ $activeRate ?? 'Rate not set' }}">
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-success w-100"
                            @if (!$activeRate) disabled title="Set milk rate first" @endif>
                            Add Milk Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="d-flex gap-3 flex-wrap mb-3 small align-items-center">
            <span>
                <span class="badge badge-dot badge-milk-added"></span>
                Milk Added
            </span>

            <span>
                <span class="badge badge-dot badge-covered"></span>
                Covered
            </span>

            <span>
                <span class="badge badge-dot badge-partial"></span>
                Partial
            </span>

            <span>
                <span class="badge badge-dot badge-no-milk"></span>
                No Milk
            </span>
        </div>


        {{-- Calendar --}}
        <div class="calendar-grid">
            @php
                $start = now()->startOfMonth();
                $days = now()->daysInMonth;
                $today = now()->day;
            @endphp

            @for ($day = 1; $day <= $days; $day++)
                @php
                    $date = $start
                        ->copy()
                        ->addDays($day - 1)
                        ->format('Y-m-d');
                    $dayName = \Carbon\Carbon::parse($date)->translatedFormat('D');
                    $entry = $entries[$date] ?? null;
                    $coverage = $coverageMap[$date] ?? null;
                    $tooltip = $tooltips[$date] ?? null;
                @endphp

                <div class="calendar-day {{ $entry ? 'has-milk' : '' }} {{ !$entry && $coverage === 'full' ? 'milk-covered' : '' }} {{ !$entry && $coverage === 'partial' ? 'milk-partial' : '' }} {{ $day === $today ? 'today-highlight' : '' }}"
                    @if ($tooltip) data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="{{ $tooltip }}" @endif>

                    <div class="day-number">{{ $day }}</div>
                    <div class="day-name">{{ $dayName }}</div>

                    @if ($entry)
                        <span class="badge bg-success kg-badge mt-2">
                            {{ round($entry->quantity_kg) }} kg
                        </span>
                    @elseif($coverage === 'full')
                        <span class="is-coverede text-success small"></span>
                    @elseif($coverage === 'partial')
                        <span class="is-coverede text-success small"></span>
                    @endif
                </div>
            @endfor
        </div>

    </div>

@endsection
