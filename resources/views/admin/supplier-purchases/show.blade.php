@extends('layouts.admin.app')

@section('title', 'Purchase Details')
@section('page-heading', 'Purchase Details')

@section('content')

<style>
    .purchase-show {
        --brown: #4A2616;
        --cream: #f9efe3;
        --paper: #fffaf4;
        color: #38271f;
    }

    .purchase-show .panel {
        background: var(--paper);
        border: 1px solid #ead9c9;
        border-radius: 13px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .purchase-show .page-title {
        color: var(--brown);
        font-weight: 700;
        margin: 0;
    }

    .purchase-show .muted {
        color: #806b5e;
        font-size: 13px;
    }

    .purchase-show .section-title {
        color: var(--brown);
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 18px;
    }

    .purchase-show .info-label {
        color: #806b5e;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .purchase-show .info-value {
        font-weight: 600;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .purchase-show .status {
        display: inline-block;
        padding: 6px 13px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .purchase-show .status-draft {
        background: #fff0cf;
        color: #8a5b0b;
    }

    .purchase-show .status-received {
        background: #dcefe0;
        color: #28613b;
    }

    .purchase-show .status-cancelled {
        background: #f7dddd;
        color: #9b3030;
    }

    .purchase-show .item-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .purchase-show .item-table th {
        background: var(--cream);
        color: var(--brown);
        padding: 13px 10px;
        font-size: 12px;
        text-align: left;
        white-space: nowrap;
    }

    .purchase-show .item-table td {
        padding: 13px 10px;
        border-bottom: 1px solid #eee2d7;
        font-size: 13px;
        vertical-align: middle;
    }

    .purchase-show .totals {
        max-width: 370px;
        margin-left: auto;
        background: var(--cream);
        border-radius: 10px;
        padding: 18px;
    }

    .purchase-show .total-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 7px 0;
        font-size: 14px;
    }

    .purchase-show .grand-total {
        border-top: 1px solid #d9c2ad;
        margin-top: 8px;
        padding-top: 13px;
        color: var(--brown);
        font-size: 19px;
        font-weight: 700;
    }

    .purchase-show .back-btn {
        border: 1px solid #cdb5a2;
        color: var(--brown);
        padding: 10px 17px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .purchase-show .receive-btn {
        background: var(--brown);
        color: white;
        border: 0;
        padding: 11px 19px;
        border-radius: 8px;
        font-weight: 600;
    }

    .purchase-show .receive-btn:hover {
        background: #75452f;
    }

    @media(max-width: 768px) {
        .purchase-show .panel {
            padding: 15px;
        }
    }
</style>

<div class="purchase-show">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h3 class="page-title">Purchase Details</h3>
            <p class="muted mb-0">
                Purchase No: {{ $purchase->purchase_number }}
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.supplier-purchases.index') }}" class="back-btn">
                Back to Purchases
            </a>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- PURCHASE OVERVIEW --}}
    <div class="panel">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h5 class="section-title mb-0">Purchase Information</h5>

            <span class="status status-{{ $purchase->status }}">
                {{ $purchase->status }}
            </span>
        </div>

        <div class="row g-4">

            <div class="col-md-3 col-6">
                <div class="info-label">Purchase Number</div>
                <div class="info-value">{{ $purchase->purchase_number }}</div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Supplier Invoice</div>
                <div class="info-value">
                    {{ $purchase->supplier_invoice_number ?: 'Not provided' }}
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Purchase Date</div>
                <div class="info-value">
                    {{ $purchase->purchase_date?->format('d M Y') }}
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Expected Delivery</div>
                <div class="info-value">
                    {{ $purchase->expected_date?->format('d M Y') ?? 'Not specified' }}
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Supplier</div>
                <div class="info-value">
                    {{ $purchase->supplier->supplier_name ?? 'N/A' }}
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Supplier Code</div>
                <div class="info-value">
                    {{ $purchase->supplier->supplier_code ?? 'N/A' }}
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Branch</div>
                <div class="info-value">
                    {{ $purchase->branch->branch_name ?? 'N/A' }}
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="info-label">Created By</div>
                <div class="info-value">
                    {{ $purchase->creator->name ?? 'N/A' }}
                </div>
            </div>

        </div>

        @if($purchase->notes)
            <div class="mt-4">
                <div class="info-label">Purchase Notes</div>
                <div class="info-value">{{ $purchase->notes }}</div>
            </div>
        @endif

        @if($purchase->received_at)
            <div class="mt-4">
                <div class="info-label">Stock Received At</div>
                <div class="info-value">
                    {{ $purchase->received_at->format('d M Y, h:i A') }}
                </div>
            </div>
        @endif

    </div>

    {{-- PURCHASE ITEMS --}}
    <div class="panel">

        <h5 class="section-title">Purchased Raw Materials</h5>

        <div class="table-responsive">
            <table class="item-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Raw Material</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>GST %</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Line Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchase->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>
                                    {{ $item->productVariant->product->name ?? 'N/A' }}
                                </strong>
                                <br>
                                <small class="muted">
                                    {{ $item->productVariant->variant_name ?? '' }}
                                </small>
                            </td>

                            <td>
                                {{ $item->productVariant->sku ?? 'N/A' }}
                            </td>

                            <td>
                                {{ number_format((float) $item->quantity, 3) }}
                                {{ $item->productVariant->unit->name ?? '' }}
                            </td>

                            <td>
                                ?{{ number_format((float) $item->unit_cost, 2) }}
                            </td>

                            <td>{{ number_format((float) $item->gst_rate, 2) }}%</td>

                            <td>
                                ?{{ number_format((float) $item->discount_amount, 2) }}
                            </td>

                            <td>
                                ?{{ number_format((float) $item->tax_amount, 2) }}
                            </td>

                            <td>
                                <strong>
                                    ?{{ number_format((float) $item->line_total, 2) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                No purchase items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TOTALS --}}
        <div class="totals mt-4">

            <div class="total-row">
                <span>Subtotal</span>
                <strong>?{{ number_format((float) $purchase->subtotal, 2) }}</strong>
            </div>

            <div class="total-row">
                <span>Total Discount</span>
                <strong>?{{ number_format((float) $purchase->discount_amount, 2) }}</strong>
            </div>

            <div class="total-row">
                <span>Total GST</span>
                <strong>?{{ number_format((float) $purchase->tax_amount, 2) }}</strong>
            </div>

            <div class="total-row grand-total">
                <span>Grand Total</span>
                <strong>?{{ number_format((float) $purchase->grand_total, 2) }}</strong>
            </div>

        </div>

    </div>

    {{-- RECEIVE STOCK --}}
    @if($purchase->status === 'draft')
        <div class="panel">

            <h5 class="section-title">Receive Stock</h5>

            <p class="muted">
                Receiving this purchase will increase the selected branch's raw material
                stock and create stock ledger entries.
            </p>

            <form method="POST"
                  action="{{ route('admin.supplier-purchases.receive', $purchase) }}"
                  onsubmit="return confirm('Are you sure you want to receive this purchase? Branch stock will be updated.');">

                @csrf

                <button type="submit" class="receive-btn">
                    Receive Purchase & Update Stock
                </button>

            </form>

        </div>
    @elseif($purchase->status === 'received')
        <div class="alert alert-success">
            <strong>Purchase Received!</strong>
            Branch stock has been updated successfully.
        </div>
    @elseif($purchase->status === 'cancelled')
        <div class="alert alert-danger">
            This purchase has been cancelled.
        </div>
    @endif

</div>

@endsection
