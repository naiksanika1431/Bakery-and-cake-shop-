@extends('layouts.admin.app')

@section('title', 'Stock Ledger')
@section('page-heading', 'Stock Ledger')

@section('content')

<style>
    .ledger-page {
        --brown: #4A2616;
        --brown-light: #75452f;
        --cream: #f9efe3;
        --paper: #fffaf4;
        --text: #4a2f26;
        --muted: #8d7468;
        --line: #ead9ca;
        color: var(--text);
    }

    .ledger-page .ledger-hero {
        background: linear-gradient(120deg, #75452f, #3b2015);
        color: #fffaf4;
        border-radius: 18px;
        padding: 26px 30px;
        margin-bottom: 22px;
        box-shadow: 0 8px 22px rgba(74, 38, 22, .12);
    }

    .ledger-page .ledger-hero h2 {
        font-size: 25px;
        font-weight: 750;
        margin: 0 0 7px;
    }

    .ledger-page .ledger-hero p {
        margin: 0;
        color: #f0ded0;
        font-size: 13px;
    }

    .ledger-page .ledger-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 15px;
        padding: 19px;
        height: 100%;
        box-shadow: 0 4px 14px rgba(74, 38, 22, .04);
    }

    .ledger-page .card-label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .ledger-page .card-value {
        color: var(--brown);
        font-size: 27px;
        font-weight: 800;
        margin-top: 8px;
    }

    .ledger-page .card-icon {
        width: 43px;
        height: 43px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--cream);
        color: var(--brown);
        font-size: 20px;
    }

    .ledger-page .ledger-panel {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
    }

    .ledger-page .panel-header {
        padding: 20px 22px;
        border-bottom: 1px solid var(--line);
    }

    .ledger-page .panel-header h5 {
        color: var(--brown);
        font-weight: 750;
        margin: 0;
    }

    .ledger-page .form-control,
    .ledger-page .form-select {
        border: 1px solid var(--line);
        border-radius: 9px;
        padding: 10px 12px;
        font-size: 13px;
        background-color: #fff;
    }

    .ledger-page .form-control:focus,
    .ledger-page .form-select:focus {
        border-color: var(--brown-light);
        box-shadow: 0 0 0 3px rgba(117, 69, 47, .10);
    }

    .ledger-page .btn-filter {
        background: var(--brown);
        color: white;
        border: 0;
        border-radius: 9px;
        padding: 10px 17px;
        font-size: 13px;
        font-weight: 650;
    }

    .ledger-page .btn-reset {
        background: var(--cream);
        color: var(--brown);
        border-radius: 9px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
    }

    .ledger-page .table {
        margin: 0;
        vertical-align: middle;
    }

    .ledger-page .table thead th {
        background: #f6eadf;
        color: #725343;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 14px 15px;
        border-bottom: 1px solid var(--line);
        white-space: nowrap;
    }

    .ledger-page .table tbody td {
        padding: 14px 15px;
        border-color: #f0e5dc;
        font-size: 13px;
        color: var(--text);
    }

    .ledger-page .item-name {
        font-weight: 750;
        color: var(--brown);
    }

    .ledger-page .item-meta {
        color: var(--muted);
        font-size: 11px;
        margin-top: 3px;
    }

    .ledger-page .quantity {
        font-weight: 800;
        white-space: nowrap;
    }

    .ledger-page .movement-badge {
        display: inline-block;
        border-radius: 30px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 750;
        background: #f2e8df;
        color: #70462f;
        white-space: nowrap;
    }

    .ledger-page .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: var(--muted);
    }

    .ledger-page .empty-state i {
        font-size: 38px;
        color: #c9ad99;
        display: block;
        margin-bottom: 12px;
    }

    .ledger-page .pagination {
        margin: 0;
    }

    .ledger-page .pagination .page-link {
        color: var(--brown);
        border-color: var(--line);
    }

    .ledger-page .pagination .active .page-link {
        background: var(--brown);
        border-color: var(--brown);
        color: white;
    }
</style>

<div class="ledger-page">

    {{-- Hero --}}
    <div class="ledger-hero">
        <h2><i class="bi bi-journal-text me-2"></i>Stock Ledger</h2>
        <p>Track every recorded stock movement, quantity change and transaction history.</p>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="ledger-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-label">Total Transactions</div>
                        <div class="card-value">{{ number_format($totalMovements) }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="ledger-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-label">Total Quantity Moved</div>
                        <div class="card-value">{{ number_format((float) $totalQuantityMoved, 3) }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="ledger-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-label">Affected Stock Items</div>
                        <div class="card-value">{{ number_format($affectedStockItems) }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="bi bi-boxes"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Filters --}}
    <div class="ledger-panel mb-4">

        <div class="panel-header">
            <h5><i class="bi bi-funnel me-2"></i>Filter Stock Movements</h5>
        </div>

        <div class="p-3 p-md-4">
            <form method="GET" action="{{ route('admin.stock.ledger') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold small">Search Product</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Product name, SKU or barcode"
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold small">Movement Type</label>
                        <select name="movement_type" class="form-select">
                            <option value="">All Movements</option>
                            @foreach($movementTypes as $type)
                                <option value="{{ $type }}"
                                    {{ request('movement_type') === $type ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold small">From Date</label>
                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold small">To Date</label>
                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn-filter flex-grow-1">
                            <i class="bi bi-search me-1"></i> Apply
                        </button>

                        <a href="{{ route('admin.stock.ledger') }}" class="btn-reset">
                            Reset
                        </a>
                    </div>

                </div>

            </form>
        </div>
    </div>

    {{-- Ledger Table --}}
    <div class="ledger-panel">

        <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5>Stock Movement History</h5>
                <small class="text-muted">Recorded inventory transactions</small>
            </div>

            <span class="badge rounded-pill px-3 py-2"
                  style="background:#f9efe3;color:#4A2616;">
                {{ $movements->total() }} Records
            </span>
        </div>

        <div class="table-responsive">

            <table class="table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Branch</th>
                        <th>Movement</th>
                        <th>Quantity</th>
                        <th>Stock Before</th>
                        <th>Stock After</th>
                        <th>Reference</th>
                        <th>Performed By</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($movements as $movement)
                        @php
                            $branchProduct = $movement->branchProduct;
                            $variant = $branchProduct?->productVariant;
                            $product = $variant?->product;
                            $unit = $variant?->unit;
                        @endphp

                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $movement->created_at?->format('d M Y') }}
                                </div>
                                <div class="item-meta">
                                    {{ $movement->created_at?->format('h:i A') }}
                                </div>
                            </td>

                            <td>
                                <div class="item-name">
                                    {{ $product?->name ?? 'Product unavailable' }}
                                </div>
                                <div class="item-meta">
                                    SKU: {{ $variant?->sku ?? $product?->sku ?? '—' }}
                                </div>
                            </td>

                            <td>
                                {{ $branchProduct?->branch?->branch_name ?? '—' }}
                            </td>

                            <td>
                                <span class="movement-badge">
                                    {{ ucwords(str_replace('_', ' ', $movement->movement_type)) }}
                                </span>
                            </td>

                            <td>
                                <span class="quantity">
                                    {{ number_format((float) $movement->quantity, 3) }}
                                    {{ $unit?->abbreviation ?? '' }}
                                </span>
                            </td>

                            <td>
                                {{ number_format((float) $movement->stock_before, 3) }}
                            </td>

                            <td>
                                <span class="quantity">
                                    {{ number_format((float) $movement->stock_after, 3) }}
                                </span>
                            </td>

                            <td>
                                @if($movement->reference_type || $movement->reference_id)
                                    <div class="fw-semibold">
                                        {{ $movement->reference_type ?? 'Reference' }}
                                    </div>
                                    <div class="item-meta">
                                        ID: {{ $movement->reference_id ?? '—' }}
                                    </div>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                {{ $movement->user?->name ?? 'System / Unknown' }}
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <h6 class="fw-bold">No Stock Movements Found</h6>
                                    <p class="mb-0">
                                        No recorded stock transactions match your filters.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        @if($movements->hasPages())
            <div class="p-3 border-top" style="border-color:#ead9ca !important;">
                {{ $movements->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
