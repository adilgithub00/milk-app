<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Yearly Milk Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @media (max-width: 768px) {
            h3 {
                text-align: center;
                font-size: 22px;
            }

            .btn {
                width: 100%;
                margin-bottom: 8px;
            }

            table {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            h3 {
                font-size: 20px;
            }

            table {
                font-size: 13px;
            }
        }
    </style>

</head>

<body class="bg-light">

    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="text-center text-md-start">
                    Yearly Milk Report — {{ $year }}
                </h3>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <div class="d-grid d-md-inline gap-2">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm">
                        Back to Calendar
                    </a>
                    <a href="{{ url('/calculator') }}" class="btn btn-primary btn-sm">
                        Monthly Calculator
                    </a>
                </div>
            </div>
        </div>


        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Month</th>
                            <th>Total Milk (KG)</th>
                            <th>Rate</th>
                            <th>Total Amount</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($months as $m)
                            <tr @if (strtolower($m['month']) == strtolower(now()->format('F'))) class="table-warning" @endif>
                                <td>{{ $m['month'] }}</td>
                                <td>{{ $m['kg'] }}</td>
                                <td>{{ $m['rate'] }}</td>
                                <td>{{ $m['total'] }}</td>
                                <td>{{ $m['paid'] }}</td>
                                <td>
                                    @if ($m['remaining'] > 0)
                                        <span class="text-danger">{{ $m['remaining'] }}</span>
                                    @else
                                        <span class="text-success">0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</body>

</html>
