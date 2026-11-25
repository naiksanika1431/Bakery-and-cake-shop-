@extends('layouts.admin.app')

@section('title', 'Order Management')
@section('page-heading', 'Order Management')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Manage Orders</h4>
            <p class="text-muted mb-0">Track and manage your bakery customer orders.</p>
        </div>

        <a href="{{ route('admin.orders.create') }}"
           class="btn text-white px-4 py-2"
           style="background:#4A2616;border-radius:10px;">
            <i class="bi bi-plus-lg me-2"></i> Create Order
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Order Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Total Orders</div>
                    <h3 class="fw-bold mb-0">{{ $totalOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Pending Orders</div>
                    <h3 class="fw-bold mb-0 text-warning">{{ $pendingOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Completed Orders</div>
                    <h3 class="fw-bold mb-0 text-success">{{ $completedOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Cancelled Orders</div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $cancelledOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-3">All Orders</h5>

            <form action="{{ route('admin.orders.index') }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-4">
                    <input type="search"
                           name="search"
                           class="form-control"
                           placeholder="Search order number or customer..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Order Status</option>
                        @foreach(['pending', 'confirmed', 'processing', 'ready', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}"
                                {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="payment_status" class="form-select">
                        <option value="">All Payment Status</option>
                        @foreach(['pending', 'paid', 'partial', 'failed', 'refunded'] as $paymentStatus)
                            <option value="{{ $paymentStatus }}"
                                {{ request('payment_status') === $paymentStatus ? 'selected' : '' }}>
                                {{ ucfirst($paymentStatus) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit"
                            class="btn text-white flex-grow-1"
                            style="background:#4A2616;">
                        <i class="bi bi-search"></i>
                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead style="background:#f9efe3;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Order Number</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Order Date</th>
                            <th class="py-3">Total Amount</th>
                            <th class="py-3">Order Status</th>
                            <th class="py-3">Payment</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-4">
                                    {{ $orders->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $order->order_number }}
                                </td>

                                <td>
                                    {{ $order->customer?->name ?? $order->customer_name ?? 'Walk-in Customer' }}
                                </td>

                                <td>
                                    {{ $order->created_at?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td class="fw-semibold">
                                    ?{{ number_format((float) $order->total_amount, 2) }}
                                </td>

                                <td>
                                    @php
                                        $orderStatusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'processing' => 'primary',
                                            'ready' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $orderStatusColor = $orderStatusColors[$order->status] ?? 'secondary';
                                    @endphp

                                    <span class="badge bg-{{ $orderStatusColor }}-subtle text-{{ $orderStatusColor }} px-3 py-2">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $paymentStatusColors = [
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'partial' => 'info',
                                            'failed' => 'danger',
                                            'refunded' => 'secondary',
                                        ];
                                        $paymentStatusColor = $paymentStatusColors[$order->payment_status] ?? 'secondary';
                                    @endphp

                                    <span class="badge bg-{{ $paymentStatusColor }}-subtle text-{{ $paymentStatusColor }} px-3 py-2">
                                        {{ ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </td>

                                <td class="text-end px-4">
                                    <div class="d-inline-flex gap-2">

                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Order">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Order">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this order?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Order">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-receipt fs-1 text-muted"></i>
                                    <h5 class="mt-3">No Orders Found</h5>
                                    <p class="text-muted mb-3">
                                        No orders match your current filters.
                                    </p>
                                    <a href="{{ route('admin.orders.create') }}"
                                       class="btn text-white"
                                       style="background:#4A2616;">
                                        Create First Order
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        @if($orders->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
