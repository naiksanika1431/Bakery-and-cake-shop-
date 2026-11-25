@extends('layouts.admin.app')

@section('title', 'Finished Goods Stock')
@section('page-heading', 'Finished Goods Stock')

@section('content')

<style>
    .fg-page {
        --brown: #4A2616;
        --brown-light: #75452f;
        --cream: #f9efe3;
        --paper: #fffaf4;
        --text: #4a2f26;
        --muted: #8d7468;
        --line: #ead9ca;
        color: var(--text);
    }

    .fg-page .fg-hero {
        background: linear-gradient(120deg, #75452f, #3b2015);
        color: #fffaf4;
        border-radius: 18px;
        padding: 26px 30px;
        margin-bottom: 22px;
        box-shadow: 0 8px 22px rgba(74, 38, 22, .12);
    }

    .fg-page .fg-hero h2 {
        font-size: 25px;
        font-weight: 750;
        margin: 0 0 7px;
    }

    .fg-page .fg-hero p {
        margin: 0;
        color: #f0ded0;
        font-size: 13px;
    }

    .fg-page .fg-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 15px;
        padding: 19px;
        height: 100%;
        box-shadow: 0 4px 14px rgba(74, 38, 22, .04);
    }

    .fg-page .fg-card-label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .fg-page .fg-card-value {
        color: var(--brown);
        font-size: 27px;
        font-weight: 800;
        margin-top: 8px;
    }

    .fg-page .fg-icon {
        width: 43px;
        height: 43px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--cream);
        color: var(--brown);
        font-size: 20px;
    }

    .fg-page .fg-panel {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
    }

    .fg-page .fg-panel-header {
        padding: 20px 22px;
        border-bottom: 1px solid var(--line);
    }

    .fg-page .fg-panel-header h5 {
        color: var(--brown);
        font-weight: 750;
        margin: 0;
    }

    .fg-page .form-control,
    .fg-page .form-select {
        border: 1px solid var(--line);
        border-radius: 9px;
        padding: 10px 12px;
        font-size: 13px;
        background-color: #fff;
    }

    .fg-page .form-control:focus,
    .fg-page .form-select:focus {
        border-color: var(--brown-light);
        box-shadow: 0 0 0 3px rgba(117, 69, 47, .10);
    }

    .fg-page .btn-filter {
        background: var(--brown);
        color: white;
        border: 0;
        border-radius: 9px;
        padding: 10px 17px;
        font-size: 13px;
        font-weight: 650;
    }

    .fg-page .btn-reset {
        background: var(--cream);
        color: var(--brown);
        border-radius: 9px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
    }

    .fg-page .table {
        margin: 0;
        vertical-align: middle;
    }

    .fg-page .table thead th {
        background: #f6eadf;
        color: #725343;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        white-space: nowrap;
    }

    .fg-page .table tbody td {
        padding: 15px 16px;
        border-color: #f0e5dc;
        font-size: 13px;
        color: var(--text);
    }

    .fg-page .product-name {
        font-weight: 750;
        color: var(--brown);
    }

    .fg-page .product-sku {
        color: var(--muted);
        font-size: 11px;
        margin-top: 3px;
    }

    .fg-page .stock-number {
        font-weight: 800;
        font-size: 14px;
    }

    .fg-page .stock-badge {
        display: inline-block;
        border-radius: 30px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .fg-page .badge-in {
        background: #e5f3e8;
        color: #26713b;
    }

    .fg-page .badge-low {
        background: #fff0d7;
        color: #98610c;
    }

    .fg-page .badge-out {
        background: #fbe5e2;
        color: #b23b30;
    }

    .fg-page .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: var(--muted);
    }

    .fg-page .empty-state i {
        font-size: 35px;
        color: #b99882;
        display: block;
        margin-bottom: 12px;
    }

    .fg-page .pagination {
        margin-bottom: 0;
    }

    .fg-page .page-link {
        color: var(--brown);
    }

    .fg-page .page-item.active .page-link {
        background: var(--brown);
        border-color: var(--brown);
        color: white;
    }

    @media(max-width: 767px) {
        .fg-page .fg-hero {
            padding: 22px;
        }

        .fg-page .fg-hero h2 {
            font-size: 21px;
        }
    }
</style>

<div class="fg-page">

    {{-- HERO --}}
    <div class="fg-hero">
        <h2><i class="bi bi-box-seam me-2"></i>Finished Goods Stock</h2>
        <p>Monitor ready-to-sell bakery products and branch-wise stock availability.</p>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="fg-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fg-card-label">Total Stock Items</div>
                        <div class="fg-card-value">{{ number_format($totalProducts) }}</div>
                    </div>
                    <div class="fg-icon"><i class="bi bi-boxes"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="fg-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fg-card-label">Total Available Quantity</div>
                        <div class="fg-card-value">{{ number_format($totalStock, 3) }}</div>
                    </div>
                    <div class="fg-icon"><i class="bi bi-clipboard-data"></i></div>
                </div>
            </div>
        </div>

    </div>

    {{-- STOCK TABLE --}}
    <div class="fg-panel">

        <div class="fg-panel-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5>Finished Product Inventory</h5>
                    <small class="text-muted">Branch-wise finished goods availability</small>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.stock.finished-goods') }}"
                  class="row g-2 mt-3">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search product name or SKU..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Stock Status</option>
                        <option value="in_stock" @selected(request('status') === 'in_stock')>In Stock</option>
                        <option value="low_stock" @selected(request('status') === 'low_stock')>Low Stock</option>
                        <option value="out_of_stock" @selected(request('status') === 'out_of_stock')>Out of Stock</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-filter flex-grow-1">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    <a href="{{ route('admin.stock.finished-goods') }}" class="btn-reset">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Available Stock</th>
                        <th>Minimum Stock</th>
                        <th>Unit</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($stockItems as $index => $item)

                        @php
                            $quantity = (float) $item->stock_quantity;
                            $minimum = (float) $item->min_stock;

                            if ($quantity <= 0) {
                                $status = 'Out of Stock';
                                $badge = 'badge-out';
                            } elseif ($quantity <= $minimum) {
                                $status = 'Low Stock';
                                $badge = 'badge-low';
                            } else {
                                $status = 'In Stock';
                                $badge = 'badge-in';
                            }

                            $product = $item->productVariant?->product;
                        @endphp

                        <tr>
                            <td>{{ $stockItems->firstItem() + $index }}</td>

                            <td>
                                <div class="product-name">
                                    {{ $product?->name ?? 'Unknown Product' }}
                                </div>
                                <div class="product-sku">
                                    SKU: {{ $product?->sku ?? 'N/A' }}
                                </div>
                            </td>

                            <td>{{ $product?->category?->name ?? 'N/A' }}</td>

                            <td>{{ $item->branch?->branch_name ?? 'N/A' }}</td>

                            <td>
                                <span class="stock-number">
                                    {{ number_format($quantity, 3) }}
                                </span>
                            </td>

                            <td>{{ number_format($minimum, 3) }}</td>

                            <td>
                                {{ $item->productVariant?->unit?->abbreviation ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="stock-badge {{ $badge }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-inboxes"></i>
                                    <h6 class="fw-bold">No Finished Goods Found</h6>
                                    <p class="mb-0">
                                        No finished product stock is available for the selected branch or filters.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stockItems->hasPages())
            <div class="p-3 border-top">
                {{ $stockItems->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
