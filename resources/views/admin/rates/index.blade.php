@extends('layouts.admin')

@section('title', 'Milk Rates')

@section('content')
    <div class="container py-4">

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h3 class="mb-4">Milk Rates</h3>
            </div>
        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('rates.create') }}" class="btn btn-primary btn-sm">
               <i class="bi bi-file-earmark-plus"></i>  Add New Rate
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Effective From</th>
                            <th class="text-end">Rate / KG</th>
                            <th class="text-center">Status</th>
                            <th width="220" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rates as $rate)
                            <tr>
                                <td>{{ $rate->effective_from->format('d M Y') }}</td>
                                <td class="text-end">{{ number_format($rate->rate_per_kg, 2) }}</td>
                                <td class="text-center">
                                    @if ($rate->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('rates.edit', $rate) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        @if (!$rate->is_active)
                                            <form action="{{ route('rates.activate', $rate) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-success">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif

                                        @if (!$rate->is_active)
                                            <form action="{{ route('rates.destroy', $rate) }}" method="POST"
                                                onsubmit="return confirm('Delete this rate?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No rates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>
@endsection
