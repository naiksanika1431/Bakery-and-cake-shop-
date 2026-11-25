@extends('layouts.admin.app')

@section('title', 'Raw Materials')
@section('page-heading', 'Raw Materials')

@section('content')

<style>
    .raw-page {
        --brown: #4A2616;
        --brown-light: #75452f;
        --cream: #f9efe3;
        --paper: #fffaf4;
        --text: #4a2f26;
        --muted: #8d7468;
        --line: #ead8c8;
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
    }

    .raw-page * {
        box-sizing: border-box;
    }

    .raw-top-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 14px;
    }

    .raw-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 11px 20px;
        background: linear-gradient(135deg, #75452f, #4A2616);
        border: 1px solid rgba(74, 38, 22, .15);
        border-radius: 10px;
        color: #fffaf4;
        font-size: .9rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: 0 5px 14px rgba(74, 38, 22, .18);
        transition: all .2s ease;
    }

    .raw-add-btn:hover {
        background: linear-gradient(135deg, #8a573e, #603923);
        color: #fffaf4;
        transform: translateY(-2px);
    }

    .raw-hero {
        background: linear-gradient(130deg, #4A2616, #75452f);
        color: #fffaf4;
        border-radius: 20px;
        padding: 28px 30px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .raw-hero::after {
        content: "?";
        position: absolute;
        right: 100px;
        top: -45px;
        font-size: 160px;
        opacity: .08;
        pointer-events: none;
    }

    .raw-hero-inner {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .raw-hero h2 {
        color: #fffaf4;
        font-size: 1.9rem;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .raw-hero p {
        color: rgba(255,255,255,.8);
        margin: 0;
        font-size: .9rem;
    }

    .raw-panel {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 7px 20px rgba(74,38,22,.05);
    }

    .raw-panel-head {
        padding: 22px 24px;
        border-bottom: 1px solid var(--line);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .raw-panel-title h4 {
        color: var(--brown);
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0;
    }

    .raw-panel-title p {
        color: var(--muted);
        font-size: .85rem;
        margin: 5px 0 0;
    }

    .raw-search-form {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .raw-filter {
        min-width: 175px;
        padding: 10px 12px;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: #fff;
        color: var(--text);
        outline: none;
        font-size: .85rem;
    }

    .raw-filter:focus {
        border-color: var(--brown-light);
        box-shadow: 0 0 0 3px rgba(107,58,36,.1);
    }

    .raw-search-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border: none;
        border-radius: 9px;
        background: var(--brown);
        color: #fffaf4;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
    }

    .raw-search-btn:hover {
        background: var(--brown-light);
        color: #fffaf4;
    }

    .raw-reset {
        padding: 10px 13px;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: #fff;
        color: var(--brown);
        text-decoration: none;
        font-size: .85rem;
    }

    .raw-reset:hover {
        background: var(--cream);
        color: var(--brown);
    }

    .raw-table-wrap {
        overflow-x: auto;
    }

    .raw-table {
        width: 100%;
        min-width: 1000px;
        border-collapse: collapse;
    }

    .raw-table thead {
        background: #f3e4d3;
    }

    .raw-table th {
        padding: 16px 18px;
        color: var(--brown);
        font-size: .76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
        text-align: left;
    }

    .raw-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #f0e4d9;
        color: var(--text);
        font-size: .86rem;
        vertical-align: middle;
    }

    .raw-table tbody tr:hover {
        background: #fff5eb;
    }

    .raw-material-name {
        color: var(--brown);
        font-weight: 700;
        display: block;
    }

    .raw-sku {
        color: var(--muted);
        font-size: .75rem;
        display: block;
        margin-top: 4px;
    }

    .raw-unit {
        color: var(--muted);
        font-size: .8rem;
    }

    .raw-stock {
        font-weight: 700;
        color: var(--brown);
        white-space: nowrap;
    }

    .raw-stock.low {
        color: #b7791f;
    }

    .raw-stock.out {
        color: #a6382a;
    }

    .raw-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 30px;
        font-size: .75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .raw-status.active {
        background: #e3f2e7;
        color: #27643a;
    }

    .raw-status.inactive {
        background: #fbe3df;
        color: #a6382a;
    }

    .raw-stock-status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .raw-stock-status.normal {
        background: #e3f2e7;
        color: #27643a;
    }

    .raw-stock-status.low {
        background: #fff1d6;
        color: #956000;
    }

    .raw-stock-status.out {
        background: #fbe3df;
        color: #a6382a;
    }

    .raw-empty {
        padding: 55px 20px;
        text-align: center;
        color: var(--muted);
    }

    .raw-empty i {
        display: block;
        font-size: 2.8rem;
        color: #c7a58c;
        margin-bottom: 12px;
    }

    .raw-empty h5 {
        color: var(--brown);
        font-size: 1.2rem;
        font-weight: 700;
    }

    .raw-empty p {
        margin-bottom: 0;
        font-size: .85rem;
    }

    .raw-pagination {
        padding: 18px 24px;
        border-top: 1px solid var(--line);
    }

    .raw-pagination-info {
        color: var(--muted);
        font-size: .8rem;
    }

    @media(max-width: 767px) {
        .raw-hero {
            padding: 23px 20px;
        }

        .raw-hero h2 {
            font-size: 1.55rem;
        }

        .raw-top-actions,
        .raw-add-btn {
            width: 100%;
        }

        .raw-panel-head {
            padding: 18px;
            align-items: stretch;
        }

        .raw-search-form {
            width: 100%;
        }

        .raw-filter {
            width: 100%;
        }

        .raw-search-btn,
        .raw-reset {
            flex: 1;
            text-align: center;
        }
    }
</style>

<div class="raw-page">

    <div class="raw-top-actions">
        <a href="{{ route('admin.stock.raw-materials.create') }}"
           class="raw-add-btn">
            <i class="bi bi-plus-lg"></i>
            Add Raw Material
        </a>
    </div>

    <section class="raw-hero">
        <div class="raw-hero-inner">
            <div>
                <h2>Raw Material Management</h2>
                <p>Manage ingredients, branch stock and measurement units.</p>
            </div>
        </div>
    </section>

    <section class="raw-panel">

        <div class="raw-panel-head">

            <div class="raw-panel-title">
                <h4>Raw Materials</h4>
                <p>
                    {{ $totalMaterials }} registered stock records
                    · {{ $activeMaterials }} active records
                </p>
            </div>

            <form method="GET"
                  action="{{ route('admin.stock.raw-materials') }}"
                  class="raw-search-form">

                <input
                    type="search"
                    name="search"
                    class="raw-filter"
                    placeholder="Search material name or SKU..."
                    value="{{ request('search') }}"
                    aria-label="Search raw material"
                >

                <select name="status" class="raw-filter" aria-label="Filter status">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>

                <button type="submit" class="raw-search-btn">
                    <i class="bi bi-search"></i>
                    Search
                </button>

                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.stock.raw-materials') }}" class="raw-reset">
                        Reset
                    </a>
                @endif

            </form>

        </div>

        <div class="raw-table-wrap">

            <table class="raw-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Material Name</th>
                        <th>Available Stock</th>
                        <th>Minimum Stock</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Stock Status</th>
                        <th>Product Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($materials as $material)

                        @php
                            $product = $material->productVariant?->product;
                            $unit = $material->productVariant?->unit;
                            $stock = (float) $material->stock_quantity;
                            $minimum = (float) $material->min_stock;

                            $isOut = $stock <= 0;
                            $isLow = !$isOut && $stock <= $minimum;
                        @endphp

                        <tr>

                            <td>{{ $materials->firstItem() + $loop->index }}</td>

                            <td>
                                <span class="raw-material-name">
                                    {{ $product?->name ?? 'Unknown Material' }}
                                </span>
                                <span class="raw-sku">
                                    SKU: {{ $product?->sku ?? '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="raw-stock {{ $isOut ? 'out' : ($isLow ? 'low' : '') }}">
                                    {{ number_format($stock, 3) }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($minimum, 3) }}
                            </td>

                            <td>
                                @if($unit)
                                    {{ $unit->name }}
                                    <span class="raw-unit">
                                        ({{ $unit->abbreviation }})
                                    </span>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                {{ $product?->category?->name ?? 'Uncategorized' }}
                            </td>

                            <td>
                                {{ $material->branch?->branch_name ?? '—' }}
                            </td>

                            <td>
                                @if($isOut)
                                    <span class="raw-stock-status out">Out of Stock</span>
                                @elseif($isLow)
                                    <span class="raw-stock-status low">Low Stock</span>
                                @else
                                    <span class="raw-stock-status normal">In Stock</span>
                                @endif
                            </td>

                            <td>
                                @if($product?->status === 'active')
                                    <span class="raw-status active">
                                        <i class="bi bi-circle-fill" style="font-size:6px"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="raw-status inactive">
                                        <i class="bi bi-circle-fill" style="font-size:6px"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">
                                <div class="raw-empty">
                                    <i class="bi bi-box-seam"></i>
                                    <h5>No Raw Materials Found</h5>

                                    <p>
                                        @if(request()->filled('search') || request()->filled('status'))
                                            No materials match your search or filter.
                                        @else
                                            No raw materials are registered for this branch yet.
                                            Start by adding your first raw material.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="raw-pagination">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <span class="raw-pagination-info">
                    Showing {{ $materials->firstItem() ?? 0 }}
                    to {{ $materials->lastItem() ?? 0 }}
                    of {{ $materials->total() }} stock records
                </span>

                {{ $materials->links() }}

            </div>
        </div>

    </section>

</div>

@endsection
