@extends('layouts.admin.app')

@section('title', 'Supplier Purchases')
@section('page-heading', 'Supplier Purchases')

@section('content')

<style>
    .purchase-page {
        --brown: #4A2616;
        --brown-light: #75452f;
        --cream: #f9efe3;
        --paper: #fffaf4;
        --ink: #38271f;
        color: var(--ink);
    }

    .purchase-page .page-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .purchase-page .page-description {
        color: #806b5e;
        margin: 5px 0 0;
        font-size: 14px;
    }

    .purchase-page .add-btn {
        background: var(--brown);
        color: #fff;
        padding: 11px 18px;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 600;
    }

    .purchase-page .add-btn:hover {
        background: var(--brown-light);
        color: #fff;
    }

    .purchase-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .purchase-page .summary-card {
        background: var(--paper);
        border: 1px solid #ead9c9;
        border-radius: 13px;
        padding: 19px;
    }

    .purchase-page .summary-label {
        color: #806b5e;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .purchase-page .summary-value {
        color: var(--brown);
        font-size: 27px;
        font-weight: 700;
    }

    .purchase-page .panel {
        background: var(--paper);
        border: 1px solid #ead9c9;
        border-radius: 13px;
        padding: 20px;
    }

    .purchase-page .filter-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        margin-bottom: 20px;
    }

    .purchase-page .form-control,
    .purchase-page .form-select {
        border: 1px solid #dfcbb9;
        border-radius: 8px;
        padding: 10px 12px;
        background: #fffefa;
    }

    .purchase-page .filter-btn {
        background: var(--brown);
        color: white;
        border: 0;
        border-radius: 8px;
        padding: 9px 18px;
    }

    .purchase-page .table-responsive {
        overflow-x: auto;
    }

    .purchase-page table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .purchase-page th {
        background: var(--cream);
        color: var(--brown);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 13px;
        text-align: left;
        white-space: nowrap;
    }

    .purchase-page td {
        padding: 14px 13px;
        border-bottom: 1px solid #eee2d7;
        font-size: 13px;
        vertical-align: middle;
    }

    .purchase-page .purchase-number {
        color: var(--brown);
        font-weight: 700;
    }

    .purchase-page .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .purchase-page .status-draft {
        background: #fff0cf;
        color: #8a5b0b;
    }

    .purchase-page .status-received {
        background: #dcefe0;
        color: #28613b;
    }

    .purchase-page .status-cancelled {
        background: #f7dddd;
        color: #9b3030;
    }

    .purchase-page .view-btn {
        color: var(--brown);
        border: 1px solid #cdb5a2;
        padding: 6px 12px;
        border-radius: 7px;
        text-decoration: none;
        font-weight: 600;
        font-size: 12px;
    }

    .purchase-page .view-btn:hover {
        background: var(--cream);
    }

    .purchase-page .empty-state {
        text-align: center;
        padding: 45px 15px;
        color: #806b5e;
    }

    .purchase-page .pagination {
        margin-top: 18px;
    }

    @media(max-width: 768px) {
        .purchase-page .summary-grid {
            grid-template-columns: 1fr;
        }

        .purchase-page .filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="purchase-page">

    <div class="page-top">
        <div>
            <h3 style="color:#4A2616;font-weight:700;margin:0;">Supplier Purchases</h3>
            <p class="page-description">Manage supplier purchases and stock receipts.</p>
        </div>

        <a href="{{ route('admin.supplier-purchases.create') }}" class="add-btn">
            + Add Purchase
        </a>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Purchases</div>
            <div class="summary-value">{{ $totalPurchases }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Draft Purchases</div>
            <div class="summary-value">{{ $draftPurchases }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Received Purchases</div>
            <div class="summary-value">{{ $receivedPurchases }}</div>
        </div>
    </div>

    <div class="panel">

        <form method="GET" action="{{ route('admin.supplier-purchases.index') }}">
            <div class="filter-row">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search purchase number, invoice or supplier..."
                    value="{{ request('search') }}"
                >

                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ (string) request('branch_id') === (string) $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="filter-btn">Filter</button>

            </div>
        </form>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Purchase No.</th>
                        <th>Supplier</th>
                        <th>Branch</th>
                        <th>Purchase Date</th>
                        <th>Grand Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>
                                <span class="purchase-number">
                                    {{ $purchase->purchase_number }}
                                </span>
                                @if($purchase->supplier_invoice_number)
                                    <br>
                                    <small style="color:#806b5e;">
                                        Invoice: {{ $purchase->supplier_invoice_number }}
                                    </small>
                                @endif
                            </td>

                            <td>{{ $purchase->supplier->supplier_name ?? 'N/A' }}</td>

                            <td>{{ $purchase->branch->branch_name ?? 'N/A' }}</td>

                            <td>{{ $purchase->purchase_date?->format('d M Y') }}</td>

                            <td style="font-weight:700;">
                                ?{{ number_format((float) $purchase->grand_total, 2) }}
                            </td>

                            <td>
                                <span class="status status-{{ $purchase->status }}">
                                    {{ $purchase->status }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.supplier-purchases.show', $purchase) }}"
                                   class="view-btn">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <h5>No purchases found</h5>
                                    <p>Create your first supplier purchase to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $purchases->links() }}
        </div>

    </div>
</div>

@endsection
