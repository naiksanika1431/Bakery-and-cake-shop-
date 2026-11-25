@extends('layouts.admin.app')

@section('title', 'Order Details')
@section('page-heading', 'Order Details')

@section('content')

@php
    $statusColors = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'processing' => 'primary',
        'ready' => 'success',
        'completed' => 'success',
        'cancelled' => 'danger',
        'paid' => 'success',
        'partial' => 'warning',
        'failed' => 'danger',
        'refunded' => 'secondary',
    ];
@endphp

<div class="container-fluid px-0">

    {{-- Header Actions --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <a href="{{ route('admin.orders.index') }}"
               class="text-decoration-none"
               style="color:#4A2616;">
                <i class="bi bi-arrow-left me-2"></i> Back to Orders
            </a>

            <h4 class="mt-3 mb-1">{{ $order->order_number }}</h4>
            <p class="text-muted mb-0">
                Order placed on
                {{ $order->placed_at ? $order->placed_at->format('d M Y, h:i A') : $order->created_at?->format('d M Y, h:i A') }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.edit', $order->id) }}"
               class="btn text-white px-4"
               style="background:#4A2616;">
                <i class="bi bi-pencil me-2"></i> Edit Order
            </a>

            <button type="button" onclick="window.print()"
                    class="btn btn-light border px-4">
                <i class="bi bi-printer me-2"></i> Print
            </button>
        </div>

    </div>

    {{-- Status Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">Order Status</p>
                    <span class="badge bg-{{ $statusColors[$order->order_status] ?? 'secondary' }} px-3 py-2">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">Payment Status</p>
                    <span class="badge bg-{{ $statusColors[$order->payment_status] ?? 'secondary' }} px-3 py-2">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">Grand Total</p>
                    <h4 class="mb-0" style="color:#4A2616;">
                        ₹{{ number_format($order->grand_total, 2) }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    {{-- Customer Details --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-0">Customer Information</h5>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">

                <div class="col-md-4">
                    <small class="text-muted">Customer Name</small>
                    <h6 class="mt-1">{{ $order->customer_name }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Phone Number</small>
                    <h6 class="mt-1">{{ $order->customer_phone }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Email Address</small>
                    <h6 class="mt-1">{{ $order->customer_email ?: 'Not provided' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Order Type</small>
                    <h6 class="mt-1">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Branch</small>
                    <h6 class="mt-1">{{ $order->branch?->branch_name ?? 'Not assigned' }}</h6>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Scheduled Date</small>
                    <h6 class="mt-1">
                        {{ $order->scheduled_at?->format('d M Y, h:i A') ?? 'Not scheduled' }}
                    </h6>
                </div>

                <div class="col-12">
                    <small class="text-muted">Delivery Address</small>
                    <h6 class="mt-1">
                        {{ $order->delivery_address ?: 'No address provided' }}
                    </h6>
                    <p class="text-muted mb-0">
                        {{ collect([$order->delivery_city, $order->delivery_state, $order->delivery_pincode])->filter()->implode(', ') ?: '—' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-0">Ordered Products</h5>
        </div>

        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead style="background:#f9efe3;">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th class="text-end">Line Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($item->product_image)
                                            <img src="{{ $item->product_image }}"
                                                 alt="{{ $item->product_name }}"
                                                 width="48" height="48"
                                                 class="rounded-3"
                                                 style="object-fit:cover;">
                                        @else
                                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                                 style="width:48px;height:48px;background:#f9efe3;">
                                                <i class="bi bi-cake2 fs-4" style="color:#4A2616;"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <strong>{{ $item->product_name }}</strong>

                                            @if($item->is_customized)
                                                <div>
                                                    <span class="badge bg-info">Customized</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $item->product_sku ?: '—' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                <td>₹{{ number_format($item->discount, 2) }}</td>
                                <td>
                                    ₹{{ number_format($item->tax_amount, 2) }}
                                    <small class="text-muted d-block">{{ $item->tax_rate }}%</small>
                                </td>
                                <td class="text-end fw-semibold">
                                    ₹{{ number_format($item->line_total, 2) }}
                                </td>
                            </tr>

                            @if($item->customization_details || $item->special_instructions)
                                <tr>
                                    <td colspan="7" class="bg-light">
                                        @if($item->customization_details)
                                            <strong>Customization:</strong>
                                            {{ $item->customization_details }}
                                        @endif

                                        @if($item->special_instructions)
                                            <div class="mt-1">
                                                <strong>Special Instructions:</strong>
                                                {{ $item->special_instructions }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No products found for this order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Order Summary --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0">Order Notes</h5>
                </div>

                <div class="card-body p-4">
                    {{ $order->notes ?: 'No additional notes.' }}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0">Payment Summary</h5>
                </div>

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <strong>₹{{ number_format($order->subtotal, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Discount</span>
                        <strong>₹{{ number_format($order->discount, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax</span>
                        <strong>₹{{ number_format($order->tax_amount, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Delivery Charge</span>
                        <strong>₹{{ number_format($order->delivery_charge, 2) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <h5>Grand Total</h5>
                        <h5 style="color:#4A2616;">
                            ₹{{ number_format($order->grand_total, 2) }}
                        </h5>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Payment Method</span>
                        <strong>{{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : '—' }}</strong>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Payment Transactions --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-0">Payment Transactions</h5>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead style="background:#f9efe3;">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Reference</th>
                            <th>Method</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($order->payments as $payment)
                            <tr>
                                <td>{{ $payment->transaction_id ?: '—' }}</td>
                                <td>{{ $payment->payment_reference ?: '—' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td>{{ ucfirst($payment->payment_type) }}</td>
                                <td>₹{{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusColors[$payment->payment_status] ?? 'secondary' }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->paid_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            </tr>

                            @if($payment->refund_amount > 0)
                                <tr>
                                    <td colspan="7" class="bg-light">
                                        <strong>Refund:</strong>
                                        ₹{{ number_format($payment->refund_amount, 2) }}
                                        @if($payment->refund_reason)
                                            <span class="ms-2">
                                                {{ $payment->refund_reason }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No payment transactions recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Order Status History --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-0">Order Status History</h5>
        </div>

        <div class="card-body p-4">

            @forelse($order->statusHistories as $history)
                <div class="d-flex gap-3 pb-4">

                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:38px;height:38px;background:#f9efe3;color:#4A2616;">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="flex-grow-1">
                        <strong>
                            {{ $history->old_status ? ucfirst($history->old_status) . ' → ' : '' }}
                            {{ ucfirst($history->new_status) }}
                        </strong>

                        <div class="small text-muted">
                            {{ $history->created_at?->format('d M Y, h:i A') }}
                            @if($history->updatedBy)
                                · {{ $history->updatedBy->name }}
                            @endif
                        </div>

                        @if($history->notes)
                            <p class="mb-0 mt-1">{{ $history->notes }}</p>
                        @endif
                    </div>

                </div>
            @empty
                <p class="text-muted mb-0">No status history available.</p>
            @endforelse

        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    @media print {
        .sidebar,
        .navbar,
        .btn,
        a[href] {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
    }
</style>
@endpush
