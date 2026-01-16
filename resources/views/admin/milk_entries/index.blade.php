@extends('layouts.admin')

@section('title', 'Yearly Report')

@section('content')

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="text-center text-md-start">
                    Yearly Milk Report
                </h3>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mt-3">

                <div class="col-md-2">
                    <select id="yearSelect" class="form-select  form-select-sm">
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

            </div>

            <div id="filterLoader" class="text-center my-3 d-none">
                <div class="spinner-border"></div>
                <div class="small text-muted mt-1">Loading Report ...</div>
            </div>

            <div id="yearlyReportContainer">
                @include('admin.milk_entries.partials.year_table')
            </div>

            @include('admin.milk_entries.partials.delete_modal')

        </div>

        <script>
            document.getElementById('yearSelect').addEventListener('change', function() {

                let year = this.value;

                document.getElementById('filterLoader').classList.remove('d-none');

                fetch(`{{ route('milk-entries.index') }}?year=${year}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {

                        document.getElementById('yearlyReportContainer').innerHTML = html;

                    }).finally(() => {

                        document.getElementById('filterLoader').classList.add('d-none');

                    });

            });
        </script>


    @endsection
