@extends('layouts.admin.app')

@section('title', 'Create Order')
@section('page-heading', 'Create Order')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}"
           class="text-decoration-none"
           style="color:#4A2616;">
            <i class="bi bi-arrow-left me-2"></i> Back to Orders
        </a>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Customer and Order Details --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Customer & Order Information</h5>
                <p class="text-muted mb-0">Enter customer and delivery details.</p>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="customer_name" class="form-label fw-semibold">
                            Customer Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="customer_name"
                               id="customer_name"
                               class="form-control @error('customer_name') is-invalid @enderror"
                               value="{{ old('customer_name') }}"
                               placeholder="Enter customer name"
                               required>

                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="customer_phone" class="form-label fw-semibold">
                            Customer Phone <span class="text-danger">*</span>
                        </label>

                        <input type="tel"
                               name="customer_phone"
                               id="customer_phone"
                               class="form-control @error('customer_phone') is-invalid @enderror"
                               value="{{ old('customer_phone') }}"
                               placeholder="Enter phone number"
                               required>

                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="customer_email" class="form-label fw-semibold">
                            Customer Email
                        </label>

                        <input type="email"
                               name="customer_email"
                               id="customer_email"
                               class="form-control @error('customer_email') is-invalid @enderror"
                               value="{{ old('customer_email') }}"
                               placeholder="Enter email address">

                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="order_type" class="form-label fw-semibold">
                            Order Type <span class="text-danger">*</span>
                        </label>

                        <select name="order_type"
                                id="order_type"
                                class="form-select @error('order_type') is-invalid @enderror"
                                required>
                            <option value="walk_in" {{ old('order_type', 'walk_in') === 'walk_in' ? 'selected' : '' }}>
                                Walk-in
                            </option>
                            <option value="online" {{ old('order_type') === 'online' ? 'selected' : '' }}>
                                Online
                            </option>
                            <option value="phone" {{ old('order_type') === 'phone' ? 'selected' : '' }}>
                                Phone
                            </option>
                        </select>

                        @error('order_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="order_date" class="form-label fw-semibold">
                            Order Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="order_date"
                               id="order_date"
                               class="form-control @error('order_date') is-invalid @enderror"
                               value="{{ old('order_date', now()->format('Y-m-d')) }}"
                               required>

                        @error('order_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="delivery_date" class="form-label fw-semibold">
                            Delivery Date
                        </label>

                        <input type="date"
                               name="delivery_date"
                               id="delivery_date"
                               class="form-control @error('delivery_date') is-invalid @enderror"
                               value="{{ old('delivery_date') }}">

                        @error('delivery_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="delivery_address" class="form-label fw-semibold">
                            Delivery Address
                        </label>

                        <textarea name="delivery_address"
                                  id="delivery_address"
                                  rows="2"
                                  class="form-control @error('delivery_address') is-invalid @enderror"
                                  placeholder="Enter delivery address">{{ old('delivery_address') }}</textarea>

                        @error('delivery_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Order Products</h5>
                <p class="text-muted mb-0">Select the products and quantities ordered by the customer.</p>
            </div>

            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background:#f9efe3;">
                            <tr>
                                <th style="min-width:220px;">Product</th>
                                <th style="min-width:120px;">Quantity</th>
                                <th style="min-width:140px;">Unit Price (?)</th>
                            </tr>
                        </thead>

                        <tbody id="order-products">
                            <tr>
                                <td>
                                    <select name="items[0][product_id]"
                                            class="form-select"
                                            required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                           name="items[0][quantity]"
                                           class="form-control"
                                           min="1"
                                           value="{{ old('items.0.quantity', 1) }}"
                                           required>
                                </td>

                                <td>
                                    <input type="number"
                                           name="items[0][unit_price]"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           value="{{ old('items.0.unit_price', 0) }}"
                                           required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <small class="text-muted">
                    Product prices and order totals should be validated and calculated by the server.
                </small>

            </div>
        </div>

        {{-- Payment and Status --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Payment & Order Status</h5>
                <p class="text-muted mb-0">Set the payment method and initial order status.</p>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="payment_method" class="form-label fw-semibold">
                            Payment Method <span class="text-danger">*</span>
                        </label>

                        <select name="payment_method"
                                id="payment_method"
                                class="form-select"
                                required>
                            <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                            <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="payment_status" class="form-label fw-semibold">
                            Payment Status <span class="text-danger">*</span>
                        </label>

                        <select name="payment_status"
                                id="payment_status"
                                class="form-select"
                                required>
                            <option value="pending" {{ old('payment_status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ old('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">
                            Order Status <span class="text-danger">*</span>
                        </label>

                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ old('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="ready" {{ old('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold">
                            Order Notes
                        </label>

                        <textarea name="notes"
                                  id="notes"
                                  rows="3"
                                  class="form-control"
                                  placeholder="Special instructions, cake message, flavour, design, etc.">{{ old('notes') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-light border px-4">
                Cancel
            </a>

            <button type="submit"
                    class="btn text-white px-4"
                    style="background:#4A2616;">
                <i class="bi bi-check-lg me-2"></i> Create Order
            </button>
        </div>

    </form>

</div>

@endsection
