<div class="card shadow-sm mt-3">
    <div class="card-body table-responsive">

        <h5 class="mb-3">Report Year — {{ $year }}</h5>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Month</th>
                    <th class="text-end">Total Milk (KG)</th>
                    <th class="text-end">Rates Used</th>
                    <th class="text-end">Total Amount</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Remaining</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($months as $index => $m)
                    <tr class="{{ $m['month_number'] == now()->month && $year == now()->year ? 'table-warning' : '' }}">
                        <td>{{ $m['month'] }}</td>
                        <td class="text-end">{{ $m['totalKg'] }}</td>
                        <td class="text-end">
                            {{ implode(', ', $m['ratesUsed']) }}
                        </td>
                        <td class="text-end">{{ number_format($m['totalAmount']) }}</td>
                        <td class="text-end">{{ number_format($m['paid']) }}</td>
                        <td class="text-end {{ $m['remaining'] > 0 ? 'text-danger' : '' }}">
                            {{ number_format($m['remaining']) }}</td>
                    </tr>

                    {{-- Daily breakdown (collapsible) --}}
                    @if (count($m['dailyEntries']) > 0)
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="d-grid gap-1">
                                    <button
                                        class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-between"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dailyEntries{{ $index }}" aria-expanded="false"
                                        aria-controls="dailyEntries{{ $index }}">
                                        <span>Daily Entries</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </button>

                                    <div class="collapse" id="dailyEntries{{ $index }}">
                                        <div class="table-responsive mt-1">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th class="text-end">Milk (KG)</th>
                                                        <th class="text-end">Rate</th>
                                                        <th class="text-end">Amount</th>
                                                        <th width="160" class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($m['dailyEntries'] as $d)
                                                        <tr id="row-{{ $d->id }}">
                                                            <td>{{ $d->entry_date->format('d-m-Y') }}</td>
                                                            <td class="text-end">{{ $d->quantity_kg }}</td>
                                                            <td class="text-end">{{ $d->rate_per_kg }}</td>
                                                            <td class="text-end">
                                                                {{ number_format($d->quantity_kg * $d->rate_per_kg) }}
                                                            </td>

                                                            <td>
                                                                <div class="d-flex gap-1 justify-content-center">
                                                                    <a href="{{ route('milk-entries.edit', $d) }}"
                                                                        class="btn btn-sm btn-warning">
                                                                        Edit
                                                                    </a>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#confirmDeleteModal"
                                                                        data-action="{{ route('milk-entries.destroy', $d) }}"
                                                                        data-row-id="row-{{ $d->id }}"
                                                                        data-date="{{ $d->entry_date->format('d M Y') }}"
                                                                        data-amount="{{ number_format($d->quantity_kg * $d->rate_per_kg) }}">
                                                                        Delete
                                                                    </button>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endif
                @endforeach

            </tbody>

        </table>

        <div class="toast-container position-fixed bottom-0 end-0 p-3">

            <div id="deleteToast" class="toast align-items-center text-bg-success border-0" role="alert">

                <div class="d-flex">
                    <div class="toast-body">
                        Entry deleted successfully.
                    </div>

                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>

            </div>

        </div>


    </div>
</div>
