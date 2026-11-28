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

    @if($isCustomCakeOnlyOrder)
        @php
            $cake = $order->customCakeDetail;
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Customer & Order Information</h5>
                <p class="text-muted mb-0">Custom cake booking details (read-only).</p>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label text-muted">Order Number</label><input class="form-control" value="{{ $order->order_number }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted">Customer Name</label><input class="form-control" value="{{ $order->customer_name }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted">Phone</label><input class="form-control" value="{{ $order->customer_phone }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted">Email</label><input class="form-control" value="{{ $order->customer_email ?: 'Not provided' }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted">Order Type</label><input class="form-control" value="{{ ucfirst($order->order_type ?? '-') }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted">Scheduled Date & Time</label><input class="form-control" value="{{ $order->scheduled_at ? $order->scheduled_at->format('d M Y, h:i A') : '-' }}" readonly></div>
                    <div class="col-12"><label class="form-label text-muted">Delivery Address</label><textarea class="form-control" rows="2" readonly>{{ $order->delivery_address ?: 'Not provided' }}</textarea></div>
                    <div class="col-md-4"><label class="form-label text-muted">City</label><input class="form-control" value="{{ $order->delivery_city ?: '-' }}" readonly></div>
                    <div class="col-md-4"><label class="form-label text-muted">State</label><input class="form-control" value="{{ $order->delivery_state ?: '-' }}" readonly></div>
                    <div class="col-md-4"><label class="form-label text-muted">Pincode</label><input class="form-control" value="{{ $order->delivery_pincode ?: '-' }}" readonly></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Custom Cake Requirements</h5>
                <p class="text-muted mb-0">Customer-submitted cake specifications (read-only).</p>
            </div>
            <div class="card-body p-4">
                @if($cake)
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label text-muted">Occasion</label><input class="form-control" value="{{ $cake->occasion ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Required Date</label><input class="form-control" value="{{ $cake->required_date?->format('d M Y') ?? '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Required Time</label><input class="form-control" value="{{ $cake->required_time?->format('h:i A') ?? '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Fulfilment</label><input class="form-control" value="{{ ucfirst($cake->fulfilment_type ?? '-') }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Servings</label><input class="form-control" value="{{ $cake->servings ?? '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Flavour</label><input class="form-control" value="{{ $cake->flavour ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Weight (kg)</label><input class="form-control" value="{{ $cake->weight_kg ?? '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Cake Size</label><input class="form-control" value="{{ $cake->cake_size ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Shape</label><input class="form-control" value="{{ $cake->shape ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Dimensions</label><input class="form-control" value="{{ $cake->dimensions ?: ($cake->dimension_cm ? $cake->dimension_cm . ' cm' : '-') }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Shape Complexity</label><input class="form-control" value="{{ $cake->shape_complexity ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Theme</label><input class="form-control" value="{{ $cake->theme ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Colour</label><input class="form-control" value="{{ $cake->colour ?: '-' }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Cream Type</label><input class="form-control" value="{{ $cake->cream_type ?: '-' }}" readonly></div>
                        <div class="col-12"><label class="form-label text-muted">Decorations</label><textarea class="form-control" rows="2" readonly>{{ $cake->decorations ?: '-' }}</textarea></div>
                        <div class="col-12"><label class="form-label text-muted">Cake Message</label><textarea class="form-control" rows="2" readonly>{{ $cake->cake_message ?: '-' }}</textarea></div>
                        <div class="col-12"><label class="form-label text-muted">Special Instructions</label><textarea class="form-control" rows="3" readonly>{{ $cake->special_instructions ?: '-' }}</textarea></div>
                    </div>

                    @if($cake->reference_image_path)
                        <div class="mt-4">
                            <label class="form-label fw-semibold">Customer Reference Image</label>
                            <div>
                                <a href="{{ asset('storage/' . ltrim($cake->reference_image_path, '/')) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . ltrim($cake->reference_image_path, '/')) }}" alt="Cake reference" class="img-thumbnail" style="max-width:280px;max-height:280px;object-fit:contain;">
                                </a>
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">
                    <h6 class="mb-3">Cake Pricing Breakdown</h6>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label text-muted">Flavour Charge</label><input class="form-control" value="{{ number_format((float)$cake->flavour_charge, 2) }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Shape Charge</label><input class="form-control" value="{{ number_format((float)$cake->shape_charge, 2) }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Theme / Decoration</label><input class="form-control" value="{{ number_format((float)$cake->theme_decoration_charge, 2) }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Cream Charge</label><input class="form-control" value="{{ number_format((float)$cake->cream_charge, 2) }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Delivery Charge</label><input class="form-control" value="{{ number_format((float)$cake->delivery_charge, 2) }}" readonly></div>
                        <div class="col-md-4"><label class="form-label text-muted">Calculated Estimate</label><input class="form-control" value="{{ number_format((float)$cake->calculated_total, 2) }}" readonly></div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">Custom cake detail record was not found for this order.</div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Quotation Summary</h5>
                <p class="text-muted mb-0">Admin quotation values are not editable on this page.</p>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label text-muted">Final Quotation</label><input class="form-control fw-bold" value="{{ number_format((float)$order->grand_total, 2) }}" readonly></div>
                    <div class="col-md-4"><label class="form-label text-muted">Advance Requested</label><input class="form-control" value="{{ number_format((float)$order->advance_requested, 2) }}" readonly></div>
                    <div class="col-md-4"><label class="form-label text-muted">Quotation Status</label><input class="form-control" value="{{ ucfirst($order->quotation_status ?? 'draft') }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted">Expected Completion</label><input class="form-control" value="{{ $order->expected_completion_at ? $order->expected_completion_at->format('d M Y, h:i A') : '-' }}" readonly></div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1">Update Payment & Order Status</h5>
                    <p class="text-muted mb-0">Only these two fields can be changed here.</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Status *</label>
                            <select name="payment_status" class="form-select" required>
                                @foreach(['pending', 'paid', 'partial', 'failed', 'refunded'] as $status)
                                    <option value="{{ $status }}" @selected(old('payment_status', $order->payment_status) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Order Status *</label>
                            <select name="order_status" class="form-select" required>
                                @foreach(['pending', 'confirmed', 'processing', 'ready', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected(old('order_status', $order->order_status) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light border px-4">Cancel</a>
                <button type="submit" class="btn text-white px-4" style="background:#4A2616;">
                    <i class="bi bi-check-lg me-2"></i> Update Status
                </button>
            </div>
        </form>
    @else
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
                            @foreach([
                                'walk_in' => 'Walk-in',
                                'online' => 'Online',
                                'phone' => 'Phone',
                                'delivery' => 'Delivery',
                                'pickup' => 'Pickup'
                            ] as $value => $label)
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

        {{-- Order Products --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-1">Order Products</h5>
                <p class="text-muted mb-0">
                    Select products and update their quantities and details.
                </p>
            </div>

            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background:#f9efe3;">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Discount</th>
                                <th>Tax %</th>
                                <th>Customization</th>
                                <th>Special Instructions</th>
                            </tr>
                        </thead>

                        <tbody id="order-items">

                            @forelse($order->items as $index => $item)
                                <tr>
                                    <td style="min-width:200px;">
                                        @if($item->is_customized)
                                            <div class="p-3 rounded-3 border bg-light">
                                                <strong>{{ $item->product_name }}</strong>
                                                <div class="small text-muted mt-1">
                                                    <i class="bi bi-cake2 me-1"></i>
                                                    Custom Cake Item
                                                </div>
                                            </div>
                                        @else
                                            <select
                                                name="items[{{ $index }}][product_id]"
                                                class="form-select"
                                                required>

                                                <option value="">Select Product</option>

                                                @foreach($products as $product)
                                                    <option
                                                        value="{{ $product->id }}"
                                                        @selected(
                                                            old("items.$index.product_id", $item->product_id) == $product->id
                                                        )>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        @endif
                                    </td>

                                    <td style="min-width:100px;">
                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control"
                                               min="1"
                                               step="1"
                                               value="{{ old("items.$index.quantity", (int) $item->quantity) }}"
                                               required>
                                    </td>

                                    <td style="min-width:110px;">
                                        <input type="number"
                                               name="items[{{ $index }}][discount]"
                                               class="form-control"
                                               min="0"
                                               step="0.01"
                                               value="{{ old("items.$index.discount", $item->discount) }}">
                                    </td>

                                    <td style="min-width:90px;">
                                        <input type="number"
                                               name="items[{{ $index }}][tax_rate]"
                                               class="form-control"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               value="{{ old("items.$index.tax_rate", $item->tax_rate) }}">
                                    </td>

                                    <td style="min-width:160px;">
                                        <textarea
                                            name="items[{{ $index }}][customization_details]"
                                            class="form-control"
                                            rows="2">{{ old("items.$index.customization_details", $item->customization_details) }}</textarea>
                                    </td>

                                    <td style="min-width:160px;">
                                        <textarea
                                            name="items[{{ $index }}][special_instructions]"
                                            class="form-control"
                                            rows="2">{{ old("items.$index.special_instructions", $item->special_instructions) }}</textarea>
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
                        Product prices are automatically taken from the active branch product pricing.
                        The server recalculates item totals and the final order total.
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

                            @foreach([
                                'cash' => 'Cash',
                                'upi' => 'UPI',
                                'card' => 'Card',
                                'online' => 'Online',
                                'bank_transfer' => 'Bank Transfer'
                            ] as $value => $label)
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
                            @foreach([
                                'pending',
                                'confirmed',
                                'processing',
                                'ready',
                                'completed',
                                'cancelled'
                            ] as $status)
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
                            @foreach([
                                'pending',
                                'paid',
                                'partial',
                                'failed',
                                'refunded'
                            ] as $status)
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

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-light border px-4">
                Cancel
            </a>

            <button type="submit"
                    class="btn text-white px-4"
                    style="background:#4A2616;">
                <i class="bi bi-check-lg me-2"></i> Update Order
            </button>

        </div>

    </form>
</div>

    @endif
@endsection