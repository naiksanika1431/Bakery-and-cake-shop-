@extends('layouts.admin.app')

@section('title', 'Edit Order')
@section('page-heading', 'Edit Order')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}"
           class="text-decoration-none"
           style="color:#4A2616;">
            <i class="bi bi-arrow-left me-2"></i> Back to Orders
        </a>
    </div>

    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Customer Information --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Customer Information</h5>
                <p class="text-muted mb-0">Update customer and order details.</p>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Order Number</label>
                        <input type="text" class="form-control"
                               value="{{ $order->order_number }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Customer Name *</label>
                        <input type="text" name="customer_name"
                               class="form-control"
                               value="{{ old('customer_name', $order->customer_name) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone *</label>
                        <input type="tel" name="customer_phone"
                               class="form-control"
                               value="{{ old('customer_phone', $order->customer_phone) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="customer_email"
                               class="form-control"
                               value="{{ old('customer_email', $order->customer_email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Order Type *</label>
                        <select name="order_type" class="form-select" required>
                            @foreach(['walk_in' => 'Walk-in', 'online' => 'Online', 'phone' => 'Phone', 'delivery' => 'Delivery', 'pickup' => 'Pickup'] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(old('order_type', $order->order_type) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Scheduled Date & Time</label>
                        <input type="datetime-local" name="scheduled_at"
                               class="form-control"
                               value="{{ old('scheduled_at', $order->scheduled_at ? $order->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Delivery Address</label>
                        <textarea name="delivery_address" rows="2"
                                  class="form-control">{{ old('delivery_address', $order->delivery_address) }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="delivery_city"
                               class="form-control"
                               value="{{ old('delivery_city', $order->delivery_city) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="delivery_state"
                               class="form-control"
                               value="{{ old('delivery_state', $order->delivery_state) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pincode</label>
                        <input type="text" name="delivery_pincode"
                               class="form-control"
                               value="{{ old('delivery_pincode', $order->delivery_pincode) }}">
                    </div>

                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Order Products</h5>
                <p class="text-muted mb-0">Review and update ordered products.</p>
            </div>

            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background:#f9efe3;">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Tax %</th>
                                <th>Customization</th>
                            </tr>
                        </thead>

                        <tbody id="order-items">
                            @forelse($order->items as $index => $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $index }}][product_id]"
                                                class="form-select" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @selected(old("items.$index.product_id", $item->product_id) == $product->id)>
                                                    {{ $product->name }} ({{ $product->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control"
                                               min="1"
                                               value="{{ old("items.$index.quantity", $item->quantity) }}"
                                               required>
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="items[{{ $index }}][unit_price]"
                                               class="form-control"
                                               min="0" step="0.01"
                                               value="{{ old("items.$index.unit_price", $item->unit_price) }}"
                                               required>
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="items[{{ $index }}][discount]"
                                               class="form-control"
                                               min="0" step="0.01"
                                               value="{{ old("items.$index.discount", $item->discount) }}">
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="items[{{ $index }}][tax_rate]"
                                               class="form-control"
                                               min="0" max="100" step="0.01"
                                               value="{{ old("items.$index.tax_rate", $item->tax_rate) }}">
                                    </td>

                                    <td>
                                        <textarea name="items[{{ $index }}][customization_details]"
                                                  class="form-control"
                                                  rows="2">{{ old("items.$index.customization_details", $item->customization_details) }}</textarea>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No order items found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-light border mb-0">
                    <small class="text-muted">
                        Item totals and final order totals must be recalculated and validated on the server.
                    </small>
                </div>
            </div>
        </div>

        {{-- Payment and Status --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Payment & Order Status</h5>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="">Select Method</option>
                            @foreach(['cash' => 'Cash', 'upi' => 'UPI', 'card' => 'Card', 'online' => 'Online', 'bank_transfer' => 'Bank Transfer'] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(old('payment_method', $order->payment_method) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Order Status *</label>
                        <select name="order_status" class="form-select" required>
                            @foreach(['pending', 'confirmed', 'processing', 'ready', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('order_status', $order->order_status) === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payment Status *</label>
                        <select name="payment_status" class="form-select" required>
                            @foreach(['pending', 'paid', 'partial', 'failed', 'refunded'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('payment_status', $order->payment_status) === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Order Notes</label>
                        <textarea name="notes" rows="3"
                                  class="form-control">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- Totals --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3">Current Order Summary</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted">Subtotal</label>
                        <input type="text" class="form-control"
                               value="{{ number_format($order->subtotal, 2) }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted">Discount</label>
                        <input type="text" class="form-control"
                               value="{{ number_format($order->discount, 2) }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted">Tax</label>
                        <input type="text" class="form-control"
                               value="{{ number_format($order->tax_amount, 2) }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted">Delivery Charge</label>
                        <input type="text" class="form-control"
                               value="{{ number_format($order->delivery_charge, 2) }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted">Grand Total</label>
                        <input type="text" class="form-control fw-bold"
                               value="{{ number_format($order->grand_total, 2) }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-light border px-4">Cancel</a>

            <button type="submit" class="btn text-white px-4"
                    style="background:#4A2616;">
                <i class="bi bi-check-lg me-2"></i> Update Order
            </button>
        </div>

    </form>
</div>

@endsection
