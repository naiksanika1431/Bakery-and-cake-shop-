@extends('layouts.admin.app')

@section('content')
@php
    $formatMoney = fn ($amount) => '₹' . number_format((float) $amount, 2);

    $statusStyles = [
        'pending' => 'status-pending',
        'confirmed' => 'status-confirmed',
        'processing' => 'status-processing',
        'ready' => 'status-ready',
        'completed' => 'status-completed',
        'cancelled' => 'status-cancelled',
        'rejected' => 'status-rejected',
    ];
@endphp

<style>
    .custom-cake-page {
        --cake-ink: #263b35;
        --cake-muted: #7b8981;
        --cake-sage: #527f70;
        --cake-sage-dark: #344f43;
        --cake-paper: #fffefa;
        --cake-border: #e9ebe3;
        color: var(--cake-ink);
        padding-bottom: 30px;
    }

    .custom-cake-page .page-heading {
        background: linear-gradient(115deg, #344f43, #20352c);
        border-radius: 18px;
        padding: 27px 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(38,59,53,.12);
    }

    .custom-cake-page .page-heading::after {
        content: "✿";
        position: absolute;
        right: 35px;
        top: -35px;
        font-size: 150px;
        line-height: 1;
        color: rgba(255,255,255,.07);
        pointer-events: none;
    }

    .custom-cake-page .page-heading h2 {
        font-size: 25px;
        font-weight: 750;
        letter-spacing: -.5px;
    }

    .custom-cake-page .page-heading p {
        color: rgba(255,255,255,.72);
        font-size: 13px;
    }

    .custom-cake-page .add-order-btn {
        background: #fffefa;
        color: #344f43;
        border: 0;
        border-radius: 10px;
        font-weight: 700;
        padding: 11px 17px;
        position: relative;
        z-index: 1;
        transition: .2s ease;
    }

    .custom-cake-page .add-order-btn:hover {
        background: #e9f0e9;
        color: #20352c;
        transform: translateY(-2px);
    }

    .custom-cake-page .stat-card {
        background: var(--cake-paper);
        border: 1px solid var(--cake-border);
        border-radius: 15px;
        padding: 19px;
        height: 100%;
        transition: .2s ease;
    }

    .custom-cake-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 9px 22px rgba(38,59,53,.07);
    }

    .custom-cake-page .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .custom-cake-page .stat-label {
        color: var(--cake-muted);
        font-size: 12px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .custom-cake-page .stat-value {
        color: var(--cake-ink);
        font-size: 29px;
        font-weight: 750;
        margin-top: 9px;
        line-height: 1.2;
    }

    .custom-cake-page .stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-size: 19px;
    }

    .custom-cake-page .icon-total { background: #edf2ec; color: #527f70; }
    .custom-cake-page .icon-pending { background: #fff3dc; color: #b78128; }
    .custom-cake-page .icon-production { background: #eaf0fa; color: #5475a5; }
    .custom-cake-page .icon-completed { background: #e6f3e9; color: #41805a; }

    .custom-cake-page .panel-card {
        background: var(--cake-paper);
        border: 1px solid var(--cake-border);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(38,59,53,.035);
    }

    .custom-cake-page .filter-card {
        padding: 20px;
    }

    .custom-cake-page .form-control,
    .custom-cake-page .form-select {
        border: 1px solid #e3e7df;
        border-radius: 9px;
        min-height: 43px;
        font-size: 13px;
        color: var(--cake-ink);
        background-color: #fff;
    }

    .custom-cake-page .form-control:focus,
    .custom-cake-page .form-select:focus {
        border-color: #83a596;
        box-shadow: 0 0 0 3px rgba(82,127,112,.12);
    }

    .custom-cake-page .filter-label {
        display: block;
        color: #66766d;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .custom-cake-page .btn-filter {
        background: var(--cake-sage-dark);
        color: #fff;
        border: 1px solid var(--cake-sage-dark);
        border-radius: 9px;
        font-size: 13px;
        font-weight: 650;
        min-height: 43px;
    }

    .custom-cake-page .btn-filter:hover {
        background: #263b35;
        color: #fff;
    }

    .custom-cake-page .btn-reset {
        border: 1px solid #dfe5dd;
        color: #607168;
        border-radius: 9px;
        min-height: 43px;
        font-size: 13px;
        background: #fff;
    }

    .custom-cake-page .btn-reset:hover {
        background: #f1f4ef;
    }

    .custom-cake-page .table-heading {
        padding: 19px 22px;
        border-bottom: 1px solid var(--cake-border);
    }

    .custom-cake-page .table-heading h5 {
        font-size: 16px;
        font-weight: 750;
        color: var(--cake-ink);
    }

    .custom-cake-page .table-heading p {
        color: var(--cake-muted);
        font-size: 12px;
        margin: 4px 0 0;
    }

    .custom-cake-page .table {
        margin: 0;
        min-width: 950px;
    }

    .custom-cake-page .table thead th {
        background: #f5f6f0;
        color: #6c7b71;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .65px;
        padding: 14px 15px;
        border-bottom: 1px solid var(--cake-border);
        white-space: nowrap;
    }

    .custom-cake-page .table tbody td {
        padding: 16px 15px;
        border-color: #eff0e9;
        color: #394d43;
        font-size: 12px;
        vertical-align: middle;
    }

    .custom-cake-page .table tbody tr:hover {
        background: #fbfcf8;
    }

    .custom-cake-page .order-number {
        color: #344f43;
        font-weight: 750;
        font-size: 12px;
        white-space: nowrap;
    }

    .custom-cake-page .customer-name {
        color: #2d4037;
        font-weight: 700;
        font-size: 12px;
    }

    .custom-cake-page .customer-phone,
    .custom-cake-page .muted-text {
        color: #8a958d;
        font-size: 11px;
        margin-top: 4px;
    }

    .custom-cake-page .amount {
        color: #344f43;
        font-weight: 750;
        white-space: nowrap;
    }

    .custom-cake-page .balance-amount {
        color: #a66f2d;
        font-weight: 750;
        white-space: nowrap;
    }

    .custom-cake-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
        text-transform: capitalize;
    }

    .custom-cake-page .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .custom-cake-page .status-pending { background: #fff3d9; color: #a87922; }
    .custom-cake-page .status-confirmed { background: #e8f1ff; color: #4774ad; }
    .custom-cake-page .status-processing { background: #eeeaff; color: #7660b3; }
    .custom-cake-page .status-ready { background: #e4f4ef; color: #38816a; }
    .custom-cake-page .status-completed { background: #e5f3e8; color: #428052; }
    .custom-cake-page .status-cancelled,
    .custom-cake-page .status-rejected { background: #fbe9e8; color: #b6534d; }

    .custom-cake-page .view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #edf3ee;
        border: 1px solid #dce8de;
        color: #3d6654;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: .2s ease;
    }

    .custom-cake-page .view-btn:hover {
        background: #344f43;
        border-color: #344f43;
        color: #fff;
    }

    .custom-cake-page .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .custom-cake-page .empty-icon {
        width: 65px;
        height: 65px;
        border-radius: 20px;
        background: #edf2ec;
        color: #527f70;
        display: grid;
        place-items: center;
        font-size: 28px;
        margin: 0 auto 15px;
    }

    .custom-cake-page .empty-state h6 {
        color: var(--cake-ink);
        font-weight: 750;
    }

    .custom-cake-page .empty-state p {
        color: var(--cake-muted);
        font-size: 12px;
    }

    .custom-cake-page .pagination {
        margin: 0;
    }

    .custom-cake-page .pagination .page-link {
        color: #527f70;
        border-color: #e7ebe4;
        font-size: 12px;
    }

    .custom-cake-page .pagination .active .page-link {
        background: #344f43;
        border-color: #344f43;
        color: #fff;
    }

    @media (max-width: 767px) {
        .custom-cake-page .page-heading {
            padding: 22px 20px;
        }

        .custom-cake-page .page-heading h2 {
            font-size: 21px;
        }

        .custom-cake-page .page-heading::after {
            right: 10px;
            font-size: 100px;
        }

        .custom-cake-page .add-order-btn {
            margin-top: 15px;
        }

        .custom-cake-page .stat-value {
            font-size: 25px;
        }

        .custom-cake-page .filter-card {
            padding: 15px;
        }
    }
</style>

<div class="container-fluid custom-cake-page">

    {{-- Page Header --}}
    <div class="page-heading mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <div class="small mb-2" style="color:rgba(255,255,255,.65); letter-spacing:1.2px; text-transform:uppercase;">
                    Bakery Management / Orders
                </div>
                <h2 class="mb-1">Custom Cake Orders</h2>
                <p class="mb-0">Manage customer bookings, quotations, payments and cake production.</p>
            </div>

            <a href="{{ route('admin.custom-cake-orders.create') }}" class="btn add-order-btn">
                <i class="bi bi-plus-lg me-2"></i>New Custom Order
            </a>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['Total Orders', $totalOrders ?? 0, 'bi-receipt-cutoff', 'icon-total'],
            ['Pending Orders', $pendingOrders ?? 0, 'bi-clock-history', 'icon-pending'],
            ['In Production', $processingOrders ?? 0, 'bi-gear-wide-connected', 'icon-production'],
            ['Completed Orders', $completedOrders ?? 0, 'bi-check2-circle', 'icon-completed'],
        ] as $stat)
            <div class="col-xl-3 col-md-6 col-6">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">{{ $stat[0] }}</div>
                        <div class="stat-icon {{ $stat[3] }}">
                            <i class="bi {{ $stat[2] }}"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format((int) $stat[1]) }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Search and Filters --}}
    <div class="panel-card filter-card mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-funnel" style="color:#527f70;"></i>
            <h6 class="fw-bold mb-0">Search & Filter Orders</h6>
        </div>

        <form method="GET" action="{{ route('admin.custom-cake-orders.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-xl-4 col-md-6">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Order number, customer name or phone">
                </div>

                <div class="col-xl-3 col-md-6">
                    <label class="filter-label">Order Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Order Statuses</option>
                        @foreach(['pending','confirmed','processing','ready','completed','cancelled','rejected'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label class="filter-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All Payment Statuses</option>
                        @foreach(['pending','partial','paid','failed','refunded'] as $status)
                            <option value="{{ $status }}" @selected(request('payment_status') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter flex-grow-1">
                            <i class="bi bi-search me-1"></i>Apply
                        </button>
                        <a href="{{ route('admin.custom-cake-orders.index') }}"
                           class="btn btn-reset" title="Reset filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="panel-card overflow-hidden">
        <div class="table-heading d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Custom Cake Booking List</h5>
                <p>Review customer bookings, quotations and order progress.</p>
            </div>

            <span class="small px-3 py-2 rounded-pill"
                  style="background:#edf2ec; color:#527f70; font-weight:700;">
                <i class="bi bi-list-check me-1"></i>
                {{ $orders->total() }} Orders
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order Details</th>
                        <th>Customer</th>
                        <th>Required Date</th>
                        <th>Total Amount</th>
                        <th>Advance Paid</th>
                        <th>Balance</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        @php
                            $paidAmount = (float) $order->payments
                                ->where('payment_status', 'paid')
                                ->where('payment_type', 'payment')
                                ->sum(fn ($payment) => (float) $payment->amount - (float) ($payment->refund_amount ?? 0));

                            $balance = max(0, (float) $order->grand_total - $paidAmount);
                            $orderStatus = strtolower($order->order_status ?? 'pending');
                            $statusClass = $statusStyles[$orderStatus] ?? 'status-pending';
                        @endphp

                        <tr>
                            <td>
                                <div class="order-number">{{ $order->order_number }}</div>
                                <div class="muted-text">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $order->created_at?->format('d M Y') ?? '—' }}
                                </div>
                            </td>

                            <td>
                                <div class="customer-name">{{ $order->customer_name ?: 'Customer' }}</div>
                                <div class="customer-phone">
                                    <i class="bi bi-telephone me-1"></i>{{ $order->customer_phone ?: 'No phone' }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $order->scheduled_at?->format('d M Y') ?? '—' }}
                                </div>
                                <div class="muted-text">
                                    {{ $order->scheduled_at?->format('h:i A') ?? 'Not scheduled' }}
                                </div>
                            </td>

                            <td>
                                <span class="amount">{{ $formatMoney($order->grand_total) }}</span>
                            </td>

                            <td>
                                <span class="amount">{{ $formatMoney($paidAmount) }}</span>
                            </td>

                            <td>
                                <span class="{{ $balance > 0 ? 'balance-amount' : 'amount' }}">
                                    {{ $formatMoney($balance) }}
                                </span>
                            </td>

                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($orderStatus) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.custom-cake-orders.show', $order->id) }}"
                                   class="view-btn">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-cake2"></i>
                                    </div>
                                    <h6>No Custom Cake Orders Found</h6>
                                    <p class="mb-0">
                                        Try changing your filters or add a new custom cake order.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 border-top"
                 style="border-color:#e9ebe3 !important;">
                <div class="small text-muted">
                    Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }}
                    of {{ $orders->total() }} orders
                </div>
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
