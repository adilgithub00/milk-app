@extends('layouts.admin')

@section('title', 'Milk Entries')

@section('content')
    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="mb-4">Milk Entries</h3>
            </div>
        </div>



        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Milk (KG)</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($entries as $e)
                            <tr>
                                <td>{{ $e->entry_date->format('d M Y') }}</td>
                                <td>{{ $e->quantity_kg }}</td>
                                <td>{{ number_format($e->rate_per_kg, 2) }}</td>
                                <td>{{ number_format($e->quantity_kg * $e->rate_per_kg, 2) }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('milk-entries.edit', $e) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('milk-entries.destroy', $e) }}"
                                            onsubmit="return confirm('Delete this entry?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No milk entries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                {{ $entries->links() }}

            </div>
        </div>

    </div>
@endsection
