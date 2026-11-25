@extends('layouts.admin.app')

@section('title', 'Stock Management')
@section('page-heading', 'Stock Management')

@section('content')

<style>
    .stock-page {
        --st-cream: #f9efe3;
        --st-paper: #fffaf4;
        --st-brown: #4A2616;
        --st-brown-light: #6b3a24;
        --st-text: #4a2f26;
        --st-muted: #8d7468;
        --st-line: #ead8c8;
        color: var(--st-text);
        font-family: 'DM Sans', sans-serif;
    }

    .stock-page * { box-sizing: border-box; }

    .stock-hero {
        background: linear-gradient(130deg, #4A2616, #75452f);
        color: #fffaf4;
        border-radius: 22px;
        padding: 30px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .stock-hero::after {
        content: "♧";
        position: absolute;
        right: 35px;
        top: -25px;
        font-size: 150px;
        opacity: .10;
        transform: rotate(-20deg);
    }

    .stock-hero h2 {
        color: #fffaf4;
        font-size: 2rem;
        margin: 0 0 8px;
        position: relative;
        z-index: 1;
    }

    .stock-hero p {
        color: rgba(255,255,255,.8);
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .stock-hero .stock-date {
        display: inline-block;
        margin-top: 18px;
        padding: 7px 14px;
        border: 1px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.10);
        border-radius: 30px;
        font-size: .85rem;
    }

    .stock-stat {
        height: 100%;
        padding: 22px;
        border: 1px solid var(--st-line);
        border-radius: 20px;
        background: var(--st-paper);
        box-shadow: 0 7px 20px rgba(74,38,22,.06);
        transition: transform .2s ease;
    }

    .stock-stat:hover { transform: translateY(-3px); }

    .stock-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .stock-stat-label {
        color: var(--st-muted);
        font-size: .88rem;
        font-weight: 600;
    }

    .stock-stat-icon {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: #f3e4d3;
        color: var(--st-brown);
        font-size: 1.25rem;
    }

    .stock-stat-value {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 2.15rem;
        line-height: 1.1;
        color: var(--st-brown);
        overflow-wrap: anywhere;
    }

    .stock-stat-foot {
        margin-top: 9px;
        color: var(--st-muted);
        font-size: .8rem;
    }

    .stock-panel {
        background: var(--st-paper);
        border: 1px solid var(--st-line);
        border-radius: 20px;
        box-shadow: 0 7px 20px rgba(74,38,22,.05);
        overflow: hidden;
        margin-top: 24px;
    }

    .stock-panel-head {
        padding: 22px 24px;
        border-bottom: 1px solid var(--st-line);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .stock-panel-head h4 {
        color: var(--st-brown);
        font-size: 1.45rem;
        margin: 0;
    }

    .stock-panel-head p {
        color: var(--st-muted);
        font-size: .85rem;
        margin: 5px 0 0;
    }

    .stock-filter {
        min-width: 170px;
        max-width: 100%;
        border: 1px solid var(--st-line);
        border-radius: 10px;
        padding: 9px 12px;
        background: #fff;
        color: var(--st-text);
        outline: none;
    }

    .stock-filter:focus {
        border-color: var(--st-brown-light);
        box-shadow: 0 0 0 3px rgba(107,58,36,.10);
    }

    .stock-table-wrap { overflow-x: auto; }

    .stock-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .stock-table thead { background: #f3e4d3; }

    .stock-table th {
        color: var(--st-brown);
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 15px 18px;
        white-space: nowrap;
    }

    .stock-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #f0e4d9;
        font-size: .88rem;
        vertical-align: middle;
    }

    .stock-table tbody tr:hover { background: #fff5eb; }

    .stock-product-name {
        color: var(--st-brown);
        font-weight: 700;
    }

    .stock-product-meta {
        display: block;
        color: var(--st-muted);
        font-size: .77rem;
        margin-top: 3px;
    }

    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 30px;
        font-size: .75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .stock-status.available { background: #e3f2e7; color: #27643a; }
    .stock-status.low { background: #fff0d5; color: #98620a; }
    .stock-status.out { background: #fbe3df; color: #a6382a; }

    .stock-empty {
        text-align: center;
        padding: 48px 20px;
        color: var(--st-muted);
    }

    .stock-empty i {
        display: block;
        font-size: 2.6rem;
        color: #c7a58c;
        margin-bottom: 12px;
    }

    .stock-empty h5 {
        color: var(--st-brown);
        font-size: 1.3rem;
    }

    .stock-alert-note {
        padding: 16px 20px;
        border-radius: 14px;
        background: #fff0d5;
        color: #80520a;
        font-size: .86rem;
        margin-top: 20px;
    }

    .stock-alert-note i { margin-right: 7px; }

    @media(max-width: 767px) {
        .stock-hero { padding: 24px 20px; }
        .stock-hero h2 { font-size: 1.65rem; }
        .stock-stat-value { font-size: 1.8rem; }
        .stock-panel-head { padding: 18px; }
    }
</style>

<div class="stock-page">

    {{-- HERO --}}
    <section class="stock-hero">
        <h2>Inventory at a Glance</h2>
        <p>Monitor your bakery products, stock levels and inventory value in one place.</p>
        <span class="stock-date">
            <i class="bi bi-calendar3 me-2"></i>
            {{ now()->format('d M Y') }}
        </span>
    </section>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stock-stat">
                <div class="stock-stat-top">
                    <span class="stock-stat-label">Product Variants</span>
                    <span class="stock-stat-icon"><i class="bi bi-box-seam"></i></span>
                </div>
                <div class="stock-stat-value">{{ number_format($totalVariants) }}</div>
                <div class="stock-stat-foot">Products assigned to this branch</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stock-stat">
                <div class="stock-stat-top">
                    <span class="stock-stat-label">Total Stock</span>
                    <span class="stock-stat-icon"><i class="bi bi-boxes"></i></span>
                </div>
                <div class="stock-stat-value">{{ number_format($totalStock, 3) }}</div>
                <div class="stock-stat-foot">Combined quantity across variants</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stock-stat">
                <div class="stock-stat-top">
                    <span class="stock-stat-label">Low Stock Items</span>
                    <span class="stock-stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
                </div>
                <div class="stock-stat-value">{{ number_format($lowStockItems->count()) }}</div>
                <div class="stock-stat-foot">Items at or below minimum level</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stock-stat">
                <div class="stock-stat-top">
                    <span class="stock-stat-label">Inventory Value</span>
                    <span class="stock-stat-icon"><i class="bi bi-currency-rupee"></i></span>
                </div>
                <div class="stock-stat-value">₹{{ number_format($inventoryValue, 2) }}</div>
                <div class="stock-stat-foot">Calculated using product cost prices</div>
            </div>
        </div>

    </div>

    {{-- ALERT --}}
    @if($lowStockItems->count() > 0 || $outOfStockItems->count() > 0)
        <div class="stock-alert-note">
            <i class="bi bi-info-circle-fill"></i>
            <strong>Stock attention required:</strong>
            {{ $lowStockItems->count() }} low-stock item(s) and
            {{ $outOfStockItems->count() }} out-of-stock item(s) need attention.
        </div>
    @endif

    {{-- STOCK TABLE --}}
    <section class="stock-panel">

        <div class="stock-panel-head">
            <div>
                <h4>Product Stock Details</h4>
                <p>Current quantities, minimum levels and stock valuation</p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <input
                    type="search"
                    id="stockSearch"
                    class="stock-filter"
                    placeholder="Search product..."
                    aria-label="Search products"
                >

                <select id="stockStatusFilter" class="stock-filter" aria-label="Filter by stock status">
                    <option value="all">All stock</option>
                    <option value="available">Available</option>
                    <option value="low">Low stock</option>
                    <option value="out">Out of stock</option>
                </select>
            </div>
        </div>

        <div class="stock-table-wrap">
            <table class="stock-table" id="stockTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU / Barcode</th>
                        <th>Branch</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Cost Price</th>
                        <th>Stock Value</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($stockItems as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant?->product;
                            $unit = $variant?->unit;

                            $quantity = (float) $item->stock_quantity;
                            $minimum = (float) $item->min_stock;
                            $costPrice = (float) ($variant?->cost_price ?? 0);
                            $value = $quantity * $costPrice;

                            if ($quantity <= 0) {
                                $status = 'out';
                                $statusLabel = 'Out of Stock';
                            } elseif ($quantity <= $minimum) {
                                $status = 'low';
                                $statusLabel = 'Low Stock';
                            } else {
                                $status = 'available';
                                $statusLabel = 'Available';
                            }
                        @endphp

                        <tr
                            class="stock-row"
                            data-search="{{ strtolower(($product?->name ?? '') . ' ' . ($variant?->variant_name ?? '') . ' ' . ($variant?->sku ?? '') . ' ' . ($variant?->barcode ?? '')) }}"
                            data-status="{{ $status }}"
                        >
                            <td>
                                <span class="stock-product-name">{{ $product?->name ?? 'Unknown Product' }}</span>
                                <span class="stock-product-meta">{{ $variant?->variant_name ?? 'Standard' }}</span>
                            </td>

                            <td>
                                {{ $variant?->sku ?? '—' }}
                                <span class="stock-product-meta">{{ $variant?->barcode ?? 'No barcode' }}</span>
                            </td>

                            <td>{{ $item->branch?->branch_name ?? '—' }}</td>

                            <td>
                                <strong>{{ number_format($quantity, 3) }}</strong>
                                {{ $unit?->abbreviation ?? '' }}
                            </td>

                            <td>
                                {{ number_format($minimum, 3) }}
                                {{ $unit?->abbreviation ?? '' }}
                            </td>

                            <td>₹{{ number_format($costPrice, 2) }}</td>

                            <td><strong>₹{{ number_format($value, 2) }}</strong></td>

                            <td>
                                <span class="stock-status {{ $status }}">
                                    <i class="bi bi-circle-fill" style="font-size:6px"></i>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="stock-empty">
                                    <i class="bi bi-box-seam"></i>
                                    <h5>No stock records found</h5>
                                    <p>No product variants have been assigned to this branch yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3" style="color:#8d7468;font-size:.8rem;">
            Showing {{ $totalVariants }} product variant(s)
        </div>

    </section>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('stockSearch');
    const statusFilter = document.getElementById('stockStatusFilter');
    const rows = document.querySelectorAll('.stock-row');

    function filterStockRows() {
        const search = searchInput.value.trim().toLowerCase();
        const status = statusFilter.value;

        rows.forEach(function (row) {
            const matchesSearch = row.dataset.search.includes(search);
            const matchesStatus = status === 'all' || row.dataset.status === status;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterStockRows);
    statusFilter.addEventListener('change', filterStockRows);
});
</script>
@endpush