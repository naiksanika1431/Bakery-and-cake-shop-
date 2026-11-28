@extends('layouts.admin.app')

@section('title', 'Custom Cake Order')
@section('page-heading', 'Custom Cake Order')

@section('content')
@php
    $cake = $order->customCakeDetail;

    $paidAmount = $order->payments
        ->where('payment_status', 'paid')
        ->where('payment_type', 'payment')
        ->sum(function ($payment) {
            return max(
                0,
                (float) $payment->amount - (float) ($payment->refund_amount ?? 0)
            );
        });

    $balance = max(0, (float) $order->grand_total - (float) $paidAmount);

    $statusLabels = [
        'pending' => 'Pending',
        'new' => 'New',
        'confirmed' => 'Confirmed',
        'processing' => 'In Production',
        'preparation' => 'In Production',
        'ready' => 'Ready',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'rejected' => 'Rejected',
    ];

    $statusClasses = [
        'pending' => 'warning',
        'new' => 'warning',
        'confirmed' => 'info',
        'processing' => 'primary',
        'preparation' => 'primary',
        'ready' => 'success',
        'completed' => 'success',
        'cancelled' => 'danger',
        'rejected' => 'danger',
    ];

    $currentStatus = $order->order_status ?? 'pending';
    $quotationStatus = $order->quotation_status ?? 'draft';

    $nextStatus = match ($currentStatus) {
        'confirmed' => 'processing',
        'processing' => 'ready',
        'ready' => 'completed',
        default => null,
    };

    $nextStatusLabels = [
        'processing' => 'Move to In Production',
        'ready' => 'Mark as Ready',
        'completed' => 'Mark as Completed',
    ];

    $adminPhone = auth('web')->user()?->phone;
    $customerWhatsapp = preg_replace('/\D+/', '', (string) $order->customer_phone);

    if (strlen($customerWhatsapp) === 10) {
        $customerWhatsapp = '91' . $customerWhatsapp;
    }

    $validCustomerWhatsapp = preg_match('/^91[6-9]\d{9}$/', $customerWhatsapp) === 1;
    $validAdminPhone = !empty($adminPhone);

    $fulfilment = ucfirst($cake?->fulfilment_type ?? $order->order_type ?? 'Pickup');
@endphp

<div class="custom-cake-page">

    {{-- PAGE HEADER --}}
    <div class="page-top">
        <div>
            <a href="{{ route('admin.custom-cake-orders.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Back to Custom Cake Orders
            </a>

            <div class="page-title-row">
                <div>
                    <span class="eyebrow">CUSTOM CAKE MANAGEMENT</span>
                    <h3>Order #{{ $order->order_number }}</h3>
                    <p>
                        <i class="bi bi-calendar3"></i>
                        Booked on {{ $order->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </p>
                </div>

                <div class="header-badges">
                    <span class="status-pill status-{{ $statusClasses[$currentStatus] ?? 'secondary' }}">
                        {{ $statusLabels[$currentStatus] ?? ucfirst(str_replace('_', ' ', $currentStatus)) }}
                    </span>

                    <span class="quotation-pill">
                        <i class="bi bi-file-earmark-text"></i>
                        Quotation: {{ ucfirst($quotationStatus) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ORDER OVERVIEW --}}
    <div class="overview-grid">
        <div class="overview-card">
            <div class="overview-icon icon-brown">
                <i class="bi bi-person"></i>
            </div>
            <div>
                <span>Customer</span>
                <strong>{{ $order->customer_name ?: 'N/A' }}</strong>
                <small>{{ $order->customer_phone ?: 'No mobile number' }}</small>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon icon-sage">
                <i class="bi bi-cake2"></i>
            </div>
            <div>
                <span>Cake Flavour</span>
                <strong>{{ $cake?->flavour ?: 'Not specified' }}</strong>
                <small>{{ $cake?->weight_kg ? $cake->weight_kg . ' kg' : 'Weight not specified' }}</small>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon icon-gold">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <div>
                <span>Quotation Amount</span>
                <strong>₹{{ number_format((float) $order->grand_total, 2) }}</strong>
                <small>{{ ucfirst($quotationStatus) }} quotation</small>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon icon-purple">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <span>Required Date</span>
                <strong>{{ $cake?->required_date?->format('d M Y') ?? $order->scheduled_at?->format('d M Y') ?? 'N/A' }}</strong>
                <small>{{ $cake?->required_time?->format('h:i A') ?? $order->scheduled_at?->format('h:i A') ?? 'Time not set' }}</small>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- CUSTOMER DETAILS --}}
        <div class="col-lg-6">
            <div class="pro-card h-100">
                <div class="section-heading">
                    <div class="section-icon"><i class="bi bi-person-vcard"></i></div>
                    <div>
                        <h5>Customer & Booking</h5>
                        <p>Customer contact and fulfilment information</p>
                    </div>
                </div>

                <div class="pro-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span>Customer Name</span>
                            <strong>{{ $order->customer_name ?: 'N/A' }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Mobile Number</span>
                            <strong>{{ $order->customer_phone ?: 'N/A' }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Email Address</span>
                            <strong>{{ $order->customer_email ?: 'N/A' }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Occasion</span>
                            <strong>{{ $cake?->occasion ?: 'N/A' }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Required Date</span>
                            <strong>{{ $cake?->required_date?->format('d M Y') ?? $order->scheduled_at?->format('d M Y') ?? 'N/A' }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Required Time</span>
                            <strong>{{ $cake?->required_time?->format('h:i A') ?? $order->scheduled_at?->format('h:i A') ?? 'N/A' }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Fulfilment Method</span>
                            <strong>{{ $fulfilment }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Booking Date</span>
                            <strong>{{ $order->created_at?->format('d M Y') ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    @if($order->order_type === 'delivery')
                        <div class="subsection">
                            <h6><i class="bi bi-geo-alt me-2"></i>Delivery Address</h6>
                            <p>{{ $order->delivery_address ?: 'N/A' }}</p>
                            <small>
                                {{ collect([$order->delivery_city, $order->delivery_state, $order->delivery_pincode])->filter()->implode(', ') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- CAKE SPECIFICATIONS --}}
        <div class="col-lg-6">
            <div class="pro-card h-100">
                <div class="section-heading">
                    <div class="section-icon"><i class="bi bi-cake2"></i></div>
                    <div>
                        <h5>Cake Specifications</h5>
                        <p>Custom requirements provided by the customer</p>
                    </div>
                </div>

                <div class="pro-card-body">
                    @if($cake)
                        <div class="info-grid">
                            <div class="info-item">
                                <span>Servings</span>
                                <strong>{{ $cake->servings ?? 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Flavour</span>
                                <strong>{{ $cake->flavour ?: 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Weight</span>
                                <strong>{{ $cake->weight_kg ? $cake->weight_kg . ' kg' : 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Cake Size</span>
                                <strong>{{ $cake->cake_size ?: 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Shape</span>
                                <strong>{{ $cake->shape ?: 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Dimensions</span>
                                <strong>{{ $cake->dimensions ?: ($cake->dimension_cm ? $cake->dimension_cm . ' cm' : 'N/A') }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Shape Complexity</span>
                                <strong>{{ $cake->shape_complexity ?: 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Theme</span>
                                <strong>{{ $cake->theme ?: 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Colour</span>
                                <strong>{{ $cake->colour ?: 'N/A' }}</strong>
                            </div>
                            <div class="info-item">
                                <span>Cream Type</span>
                                <strong>{{ $cake->cream_type ?: 'N/A' }}</strong>
                            </div>
                        </div>

                        <div class="subsection">
                            <h6>Additional Requirements</h6>

                            <div class="requirement-block">
                                <span>Decorations</span>
                                <p>{{ $cake->decorations ?: 'No decoration details provided.' }}</p>
                            </div>

                            <div class="requirement-block">
                                <span>Cake Message</span>
                                <p>{{ $cake->cake_message ?: 'No cake message provided.' }}</p>
                            </div>

                            <div class="requirement-block">
                                <span>Special Instructions</span>
                                <p>{{ $cake->special_instructions ?: 'No special instructions.' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="empty-state">Cake specification details are not available.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- REFERENCE IMAGE --}}
        @if($cake?->reference_image_path)
            <div class="col-lg-5">
                <div class="pro-card h-100">
                    <div class="section-heading">
                        <div class="section-icon"><i class="bi bi-image"></i></div>
                        <div>
                            <h5>Reference Cake Image</h5>
                            <p>Customer's inspiration image</p>
                        </div>
                    </div>

                    <div class="pro-card-body">
                        <a href="{{ asset('storage/' . $cake->reference_image_path) }}" target="_blank" rel="noopener">
                            <img
                                src="{{ asset('storage/' . $cake->reference_image_path) }}"
                                alt="Customer cake reference"
                                class="reference-image">
                        </a>
                        <small class="image-hint">Click the image to view full size.</small>
                    </div>
                </div>
            </div>
        @endif

        {{-- PAYMENT SUMMARY --}}
        <div class="{{ $cake?->reference_image_path ? 'col-lg-7' : 'col-lg-12' }}">
            <div class="pro-card h-100">
                <div class="section-heading">
                    <div class="section-icon"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <h5>Payment Summary</h5>
                        <p>Quotation, received payments and outstanding balance</p>
                    </div>
                </div>

                <div class="pro-card-body">
                    <div class="money-grid">
                        <div class="money-card">
                            <span>Quoted Total</span>
                            <strong>₹{{ number_format((float) $order->grand_total, 2) }}</strong>
                        </div>
                        <div class="money-card received">
                            <span>Amount Received</span>
                            <strong>₹{{ number_format((float) $paidAmount, 2) }}</strong>
                        </div>
                        <div class="money-card balance">
                            <span>Balance Due</span>
                            <strong>₹{{ number_format((float) $balance, 2) }}</strong>
                        </div>
                    </div>

                    <div class="info-grid mt-4">
                        <div class="info-item">
                            <span>Advance Requested</span>
                            <strong>₹{{ number_format((float) $order->advance_requested, 2) }}</strong>
                        </div>
                        <div class="info-item">
                            <span>Payment Status</span>
                            <strong>{{ ucfirst($order->payment_status ?? 'pending') }}</strong>
                        </div>
                    </div>

                    @if($order->payments->isNotEmpty())
                        <div class="subsection">
                            <h6>Payment Transactions</h6>

                            <div class="table-responsive">
                                <table class="table payment-table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                                <td>₹{{ number_format((float) $payment->amount, 2) }}</td>
                                                <td>
                                                    <span class="mini-status">
                                                        {{ ucfirst($payment->payment_status ?? 'N/A') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- QUOTATION --}}
        <div class="col-12">
            <div class="pro-card">
                <div class="section-heading">
                    <div class="section-icon"><i class="bi bi-file-earmark-text"></i></div>
                    <div>
                        <h5>Customer Quotation</h5>
                        <p>Prepare and review the final cake quotation</p>
                    </div>
                    <span class="ms-auto quotation-pill">{{ ucfirst($quotationStatus) }}</span>
                </div>

                <div class="pro-card-body">
                    @if($quotationStatus === 'draft')
                        <div class="info-banner">
                            <i class="bi bi-info-circle"></i>
                            <span>Enter the final quotation details. Saving will keep the quotation as a draft.</span>
                        </div>

                        <form method="POST" action="{{ route('admin.custom-cake-orders.quotation.save', $order->id) }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="final_price" class="form-label">Final Cake Price (₹) <span class="text-danger">*</span></label>
                                    <input
                                        type="number"
                                        class="form-control @error('final_price') is-invalid @enderror"
                                        id="final_price"
                                        name="final_price"
                                        min="0.01"
                                        max="9999999999.99"
                                        step="0.01"
                                        value="{{ old('final_price', $order->grand_total > 0 ? $order->grand_total : '') }}"
                                        required>
                                    @error('final_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="advance_requested" class="form-label">Advance Amount Requested (₹) <span class="text-danger">*</span></label>
                                    <input
                                        type="number"
                                        class="form-control @error('advance_requested') is-invalid @enderror"
                                        id="advance_requested"
                                        name="advance_requested"
                                        min="0"
                                        step="0.01"
                                        value="{{ old('advance_requested', $order->advance_requested ?? '0.00') }}"
                                        required>
                                    @error('advance_requested')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="field-hint">The advance cannot exceed the final cake price.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="expected_completion_at" class="form-label">Expected Completion Date & Time <span class="text-danger">*</span></label>
                                    <input
                                        type="datetime-local"
                                        class="form-control @error('expected_completion_at') is-invalid @enderror"
                                        id="expected_completion_at"
                                        name="expected_completion_at"
                                        value="{{ old('expected_completion_at', $order->expected_completion_at?->format('Y-m-d\TH:i')) }}"
                                        required>
                                    @error('expected_completion_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="admin_notes" class="form-label">Internal Admin Notes</label>
                                    <textarea
                                        class="form-control @error('admin_notes') is-invalid @enderror"
                                        id="admin_notes"
                                        name="admin_notes"
                                        rows="3"
                                        maxlength="5000"
                                        placeholder="Private notes for the bakery team...">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                                    @error('admin_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="field-hint">Private notes are not included in customer messages.</small>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-bakery">
                                        <i class="bi bi-save me-2"></i>Save Quotation as Draft
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if((float) $order->grand_total > 0 && $order->expected_completion_at)
                            <div class="info-banner mt-3">
                                <i class="bi bi-whatsapp"></i>
                                <span>After sending the quotation to the customer through WhatsApp or another channel, click below to update its status.</span>
                            </div>

                            <form method="POST"
                                  action="{{ route('admin.custom-cake-orders.quotation.send', $order->id) }}"
                                  class="text-end mt-3"
                                  onsubmit="return confirm('Have you actually sent this quotation to the customer? Confirm only after sending it.');">
                                @csrf
                                <button type="submit" class="btn btn-bakery">
                                    <i class="bi bi-send me-2"></i>Mark Quotation as Sent
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="quotation-summary">
                            <div>
                                <span>Final Cake Price</span>
                                <strong>₹{{ number_format((float) $order->grand_total, 2) }}</strong>
                            </div>
                            <div>
                                <span>Advance Requested</span>
                                <strong>₹{{ number_format((float) $order->advance_requested, 2) }}</strong>
                            </div>
                            <div>
                                <span>Expected Completion</span>
                                <strong>{{ $order->expected_completion_at?->format('d M Y, h:i A') ?? 'Not set' }}</strong>
                            </div>
                        </div>

                        @if($order->admin_notes)
                            <div class="internal-note">
                                <strong><i class="bi bi-lock me-1"></i>Internal Admin Notes</strong>
                                <p>{{ $order->admin_notes }}</p>
                            </div>
                        @endif

                        <div class="info-banner mt-3">
                            <i class="bi bi-info-circle"></i>
                            <span>This quotation is no longer a draft. Editing is currently disabled.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- WHATSAPP SHARING --}}
        <div class="col-12">
            <div class="pro-card whatsapp-card">
                <div class="section-heading">
                    <div class="section-icon whatsapp-icon"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <h5>WhatsApp Quotation Sharing</h5>
                        <p>Prepare an editable message for the customer</p>
                    </div>
                </div>

                <div class="pro-card-body">
                    @if((float) $order->grand_total > 0 && $order->expected_completion_at)
                        @if($validAdminPhone && $validCustomerWhatsapp)
                            <div class="whatsapp-account">
                                <div>
                                    <span>Admin profile mobile</span>
                                    <strong>{{ $adminPhone }}</strong>
                                </div>
                                <small>
                                    WhatsApp opens using the account currently logged in on your device.
                                    Make sure it matches your admin profile number.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="whatsapp_quotation_message" class="form-label">Customer Message</label>

                                <textarea id="whatsapp_quotation_message" class="form-control" rows="12">{{ "Hello " . ($order->customer_name ?: 'Customer') . ",

Thank you for choosing us for your special occasion!

Your custom cake quotation is ready.

Order Number: " . $order->order_number . "
Cake Flavour: " . ($cake?->flavour ?: 'As discussed') . "
Weight: " . ($cake?->weight_kg ? $cake->weight_kg . ' kg' : 'As discussed') . "
Shape: " . ($cake?->shape ?: 'As discussed') . "
Theme: " . ($cake?->theme ?: 'As discussed') . "
Final Cake Price: Rs. " . number_format((float) $order->grand_total, 2) . "
Advance Amount: Rs. " . number_format((float) $order->advance_requested, 2) . "
Expected Completion: " . $order->expected_completion_at->format('d M Y, h:i A') . "
Fulfilment: " . $fulfilment . "

Please log in to your account to review and accept or reject the quotation.

Thank you!" }}</textarea>

                                <small class="field-hint">Edit the message before opening WhatsApp. Private admin notes are never included.</small>
                            </div>

                            <button
                                type="button"
                                class="btn btn-whatsapp"
                                id="open_custom_cake_whatsapp"
                                data-phone="{{ $customerWhatsapp }}">
                                <i class="bi bi-whatsapp me-2"></i>Open WhatsApp
                            </button>

                            <small class="field-hint d-block mt-2">WhatsApp will open with a prefilled message. You must press Send in WhatsApp yourself.</small>
                        @else
                            <div class="alert alert-warning mb-0">
                                @if(!$validAdminPhone)
                                    Please save your mobile number in Admin Profile first.
                                @endif

                                @if(!$validCustomerWhatsapp)
                                    The customer's mobile number is missing or invalid. Please verify the customer contact number.
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="info-banner">
                            <i class="bi bi-info-circle"></i>
                            <span>Save a quotation with a final price and expected completion date before preparing the WhatsApp message.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ORDER STATUS MANAGEMENT --}}
        <div class="col-12">
            <div class="pro-card status-management-card">
                <div class="section-heading">
                    <div class="section-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <h5>Order Status Management</h5>
                        <p>Update the production progress of this custom cake</p>
                    </div>
                    <span class="status-pill status-{{ $statusClasses[$currentStatus] ?? 'secondary' }}">
                        {{ $statusLabels[$currentStatus] ?? ucfirst(str_replace('_', ' ', $currentStatus)) }}
                    </span>
                </div>

                <div class="pro-card-body">
                    @if($currentStatus === 'pending' || $currentStatus === 'new')
                        <div class="info-banner">
                            <i class="bi bi-hourglass-split"></i>
                            <span>This order is waiting for the customer's quotation response. Production cannot begin until the customer accepts the quotation.</span>
                        </div>
                    @elseif($currentStatus === 'rejected')
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-x-circle me-2"></i>
                            The customer rejected this quotation. Production status cannot be changed.
                            @if($order->customer_rejection_reason)
                                <div class="mt-2"><strong>Reason:</strong> {{ $order->customer_rejection_reason }}</div>
                            @endif
                        </div>
                    @elseif($currentStatus === 'cancelled')
                        <div class="alert alert-secondary mb-0">
                            This order has been cancelled. No further status changes are available.
                        </div>
                    @elseif($currentStatus === 'completed')
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            This custom cake order has been completed.
                        </div>
                    @elseif($nextStatus)
                        <div class="status-progress">
                            <div class="progress-step {{ in_array($currentStatus, ['confirmed', 'processing', 'ready', 'completed']) ? 'done' : '' }}">
                                <span><i class="bi bi-check"></i></span>
                                <small>Confirmed</small>
                            </div>
                            <div class="progress-line {{ in_array($currentStatus, ['processing', 'ready', 'completed']) ? 'done' : '' }}"></div>
                            <div class="progress-step {{ in_array($currentStatus, ['processing', 'ready', 'completed']) ? 'done' : '' }}">
                                <span><i class="bi bi-gear"></i></span>
                                <small>In Production</small>
                            </div>
                            <div class="progress-line {{ in_array($currentStatus, ['ready', 'completed']) ? 'done' : '' }}"></div>
                            <div class="progress-step {{ in_array($currentStatus, ['ready', 'completed']) ? 'done' : '' }}">
                                <span><i class="bi bi-box-seam"></i></span>
                                <small>Ready</small>
                            </div>
                            <div class="progress-line {{ $currentStatus === 'completed' ? 'done' : '' }}"></div>
                            <div class="progress-step {{ $currentStatus === 'completed' ? 'done' : '' }}">
                                <span><i class="bi bi-flag"></i></span>
                                <small>Completed</small>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.custom-cake-orders.status.update', $order->id) }}" class="status-update-form">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="order_status" value="{{ $nextStatus }}">

                            <div class="row g-3 align-items-end">
                                <div class="col-md-7">
                                    <label for="status_note" class="form-label">Status Update Note (Optional)</label>
                                    <input
                                        type="text"
                                        name="status_note"
                                        id="status_note"
                                        class="form-control"
                                        maxlength="1000"
                                        placeholder="Example: Cake preparation started...">
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-bakery w-100">
                                        <i class="bi bi-arrow-right-circle me-2"></i>
                                        {{ $nextStatusLabels[$nextStatus] ?? 'Update Status' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="info-banner">
                            <i class="bi bi-info-circle"></i>
                            <span>This order does not currently have an available production status action.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ORDER HISTORY --}}
        <div class="col-12">
            <div class="pro-card">
                <div class="section-heading">
                    <div class="section-icon"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h5>Order Activity History</h5>
                        <p>Track recorded status changes and responsible users</p>
                    </div>
                </div>

                <div class="pro-card-body">
                    @forelse($order->statusHistories->sortByDesc('created_at') as $history)
                        <div class="history-item">
                            <div class="history-marker">
                                <i class="bi bi-check-lg"></i>
                            </div>

                            <div class="history-content">
                                <strong>
                                    {{ $statusLabels[$history->old_status] ?? ucfirst(str_replace('_', ' ', $history->old_status ?? 'New')) }}
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    {{ $statusLabels[$history->new_status] ?? ucfirst(str_replace('_', ' ', $history->new_status)) }}
                                </strong>

                                <div class="history-meta">
                                    <span><i class="bi bi-person me-1"></i>{{ $history->updatedBy?->name ?? 'System / Customer' }}</span>
                                    <span><i class="bi bi-clock me-1"></i>{{ $history->created_at?->format('d M Y, h:i A') }}</span>
                                </div>

                                @if($history->notes)
                                    <p>{{ $history->notes }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-clock-history"></i>
                            <p>No status history has been recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .custom-cake-page {
        --cake-ink: #34483e;
        --cake-muted: #829087;
        --cake-sage: #527f70;
        --cake-sage-dark: #3f6759;
        --cake-paper: #fffefa;
        --cake-bg: #f7f6f0;
        --cake-border: #e7e8dc;
        color: var(--cake-ink);
        padding-bottom: 24px;
    }

    .custom-cake-page .page-top {
        margin-bottom: 25px;
    }

    .custom-cake-page .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--cake-sage-dark);
        text-decoration: none;
        font-size: .88rem;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .custom-cake-page .back-link:hover {
        color: var(--cake-ink);
    }

    .custom-cake-page .page-title-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .custom-cake-page .eyebrow {
        color: var(--cake-sage);
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
    }

    .custom-cake-page .page-title-row h3 {
        font-size: clamp(1.35rem, 3vw, 1.9rem);
        font-weight: 750;
        margin: 5px 0;
        color: #263b35;
        overflow-wrap: anywhere;
    }

    .custom-cake-page .page-title-row p {
        color: var(--cake-muted);
        margin: 0;
        font-size: .88rem;
    }

    .custom-cake-page .header-badges {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .custom-cake-page .status-pill,
    .custom-cake-page .quotation-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 30px;
        padding: 8px 13px;
        font-size: .78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .custom-cake-page .status-warning { background: #fff2d6; color: #956817; }
    .custom-cake-page .status-info { background: #e2f0f6; color: #28647d; }
    .custom-cake-page .status-primary { background: #e8e9f8; color: #53559b; }
    .custom-cake-page .status-success { background: #e2f2e8; color: #32724d; }
    .custom-cake-page .status-danger { background: #fbe7e5; color: #a4443d; }
    .custom-cake-page .status-secondary { background: #ecefed; color: #59645e; }

    .custom-cake-page .quotation-pill {
        background: #f1f1e9;
        color: #596c5f;
        border: 1px solid #e3e5d9;
    }

    .custom-cake-page .overview-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 24px;
    }

    .custom-cake-page .overview-card {
        background: var(--cake-paper);
        border: 1px solid var(--cake-border);
        border-radius: 15px;
        padding: 17px;
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        box-shadow: 0 4px 15px rgba(38, 59, 53, .035);
    }

    .custom-cake-page .overview-icon {
        width: 43px;
        height: 43px;
        border-radius: 13px;
        display: grid;
        place-items: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .custom-cake-page .icon-brown { background: #f3e9df; color: #90664b; }
    .custom-cake-page .icon-sage { background: #e4efe8; color: #527f70; }
    .custom-cake-page .icon-gold { background: #f8f0d9; color: #a18339; }
    .custom-cake-page .icon-purple { background: #eeeaf5; color: #78649a; }

    .custom-cake-page .overview-card span,
    .custom-cake-page .overview-card small {
        display: block;
        color: var(--cake-muted);
        font-size: .75rem;
    }

    .custom-cake-page .overview-card strong {
        display: block;
        font-size: .96rem;
        margin: 2px 0;
        overflow-wrap: anywhere;
    }

    .custom-cake-page .pro-card {
        background: var(--cake-paper);
        border: 1px solid var(--cake-border);
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(38, 59, 53, .045);
        height: 100%;
        overflow: hidden;
    }

    .custom-cake-page .section-heading {
        padding: 18px 21px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--cake-border);
        background: linear-gradient(110deg, #fffefa, #f8f8f1);
    }

    .custom-cake-page .section-icon {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 12px;
        color: var(--cake-sage-dark);
        background: #eaf0e8;
        font-size: 1.1rem;
    }

    .custom-cake-page .section-heading h5 {
        color: #30463c;
        font-size: 1rem;
        font-weight: 750;
        margin: 0 0 3px;
    }

    .custom-cake-page .section-heading p {
        color: var(--cake-muted);
        font-size: .77rem;
        margin: 0;
    }

    .custom-cake-page .pro-card-body {
        padding: 21px;
    }

    .custom-cake-page .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px 22px;
    }

    .custom-cake-page .info-item span,
    .custom-cake-page .requirement-block span {
        display: block;
        color: var(--cake-muted);
        font-size: .75rem;
        margin-bottom: 5px;
    }

    .custom-cake-page .info-item strong {
        display: block;
        color: #34483e;
        font-size: .9rem;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .custom-cake-page .subsection {
        border-top: 1px solid var(--cake-border);
        margin-top: 21px;
        padding-top: 18px;
    }

    .custom-cake-page .subsection h6 {
        color: #40574a;
        font-size: .88rem;
        font-weight: 750;
        margin-bottom: 12px;
    }

    .custom-cake-page .subsection p {
        color: #58675e;
        font-size: .86rem;
        margin-bottom: 4px;
        overflow-wrap: anywhere;
    }

    .custom-cake-page .subsection small {
        color: var(--cake-muted);
    }

    .custom-cake-page .requirement-block {
        padding: 10px 12px;
        background: #fafaf5;
        border-radius: 9px;
        margin-bottom: 9px;
    }

    .custom-cake-page .requirement-block p {
        margin: 0;
    }

    .custom-cake-page .reference-image {
        width: 100%;
        max-height: 390px;
        object-fit: contain;
        border-radius: 12px;
        background: #f7f6f0;
        border: 1px solid var(--cake-border);
        padding: 7px;
    }

    .custom-cake-page .image-hint,
    .custom-cake-page .field-hint {
        color: var(--cake-muted);
        font-size: .76rem;
        display: block;
        margin-top: 8px;
    }

    .custom-cake-page .money-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 13px;
    }

    .custom-cake-page .money-card {
        background: #f8f7ef;
        border: 1px solid #e9e7d9;
        border-radius: 12px;
        padding: 16px;
    }

    .custom-cake-page .money-card span {
        display: block;
        font-size: .76rem;
        color: var(--cake-muted);
        margin-bottom: 7px;
    }

    .custom-cake-page .money-card strong {
        display: block;
        font-size: 1.2rem;
        color: #34483e;
        overflow-wrap: anywhere;
    }

    .custom-cake-page .money-card.received strong { color: #36805a; }
    .custom-cake-page .money-card.balance strong { color: #a65c4d; }

    .custom-cake-page .payment-table {
        font-size: .82rem;
        margin-bottom: 0;
    }

    .custom-cake-page .payment-table thead th {
        color: var(--cake-muted);
        font-weight: 700;
        border-bottom-color: var(--cake-border);
        white-space: nowrap;
    }

    .custom-cake-page .payment-table td {
        color: #46594d;
        border-bottom-color: #f0f0e8;
    }

    .custom-cake-page .mini-status {
        background: #edf1e9;
        border-radius: 20px;
        padding: 4px 9px;
        font-size: .72rem;
        font-weight: 650;
    }

    .custom-cake-page .form-label {
        color: #40574a;
        font-size: .83rem;
        font-weight: 700;
    }

    .custom-cake-page .form-control {
        border: 1px solid #dfe4da;
        border-radius: 9px;
        padding: 10px 12px;
        color: #34483e;
        font-size: .88rem;
    }

    .custom-cake-page .form-control:focus {
        border-color: #7d9d89;
        box-shadow: 0 0 0 .2rem rgba(82, 127, 112, .12);
    }

    .custom-cake-page .btn-bakery {
        background: var(--cake-sage-dark);
        border: 1px solid var(--cake-sage-dark);
        color: white;
        border-radius: 9px;
        padding: 10px 19px;
        font-weight: 700;
        font-size: .87rem;
    }

    .custom-cake-page .btn-bakery:hover {
        background: #304f43;
        border-color: #304f43;
        color: white;
    }

    .custom-cake-page .info-banner {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        background: #f1f5ee;
        color: #526d5b;
        border: 1px solid #e0e9dc;
        border-radius: 10px;
        font-size: .84rem;
        margin-bottom: 18px;
    }

    .custom-cake-page .info-banner i {
        font-size: 1rem;
        margin-top: 1px;
    }

    .custom-cake-page .internal-note {
        margin-top: 18px;
        padding: 14px;
        border-radius: 10px;
        background: #faf7ee;
        border: 1px solid #eee5d2;
    }

    .custom-cake-page .internal-note strong {
        color: #806d43;
        font-size: .82rem;
    }

    .custom-cake-page .internal-note p {
        color: #655e4e;
        font-size: .85rem;
        margin: 7px 0 0;
        white-space: pre-line;
    }

    .custom-cake-page .whatsapp-icon {
        background: #e4f3e9;
        color: #25834b;
    }

    .custom-cake-page .whatsapp-account {
        background: #f5f8f3;
        border: 1px solid #e2eade;
        padding: 13px 15px;
        border-radius: 10px;
        margin-bottom: 18px;
    }

    .custom-cake-page .whatsapp-account span,
    .custom-cake-page .whatsapp-account strong {
        display: block;
    }

    .custom-cake-page .whatsapp-account span {
        color: var(--cake-muted);
        font-size: .75rem;
    }

    .custom-cake-page .whatsapp-account strong {
        color: #34483e;
        margin: 3px 0 7px;
    }

    .custom-cake-page .whatsapp-account small {
        color: #6e7d71;
        font-size: .77rem;
    }

    .custom-cake-page .btn-whatsapp {
        background: #25834b;
        border: 1px solid #25834b;
        color: white;
        border-radius: 9px;
        padding: 10px 20px;
        font-weight: 700;
    }

    .custom-cake-page .btn-whatsapp:hover {
        background: #1d6d3e;
        color: white;
    }

    .custom-cake-page .status-progress {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        margin: 4px 0 26px;
    }

    .custom-cake-page .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 7px;
        min-width: 62px;
        text-align: center;
    }

    .custom-cake-page .progress-step span {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #eff1eb;
        color: #98a198;
        border: 1px solid #e0e5dc;
    }

    .custom-cake-page .progress-step.done span {
        background: #e2f0e6;
        color: #3e8158;
        border-color: #c9dfcf;
    }

    .custom-cake-page .progress-step small {
        color: #78867b;
        font-size: .69rem;
        font-weight: 650;
    }

    .custom-cake-page .progress-line {
        flex: 1;
        height: 3px;
        background: #e7eae2;
        margin-top: -22px;
        border-radius: 5px;
    }

    .custom-cake-page .progress-line.done {
        background: #8caf95;
    }

    .custom-cake-page .status-update-form {
        padding-top: 17px;
        border-top: 1px solid var(--cake-border);
    }

    .custom-cake-page .history-item {
        display: flex;
        gap: 13px;
        position: relative;
        padding-bottom: 20px;
    }

    .custom-cake-page .history-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 15px;
        top: 32px;
        bottom: 0;
        width: 2px;
        background: #e7eae1;
    }

    .custom-cake-page .history-marker {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #e9f1e9;
        color: #527f70;
        z-index: 1;
    }

    .custom-cake-page .history-content {
        min-width: 0;
        padding-top: 3px;
    }

    .custom-cake-page .history-content > strong {
        font-size: .88rem;
        color: #34483e;
    }

    .custom-cake-page .history-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 13px;
        color: var(--cake-muted);
        font-size: .75rem;
        margin-top: 5px;
    }

    .custom-cake-page .history-content p {
        font-size: .82rem;
        color: #657268;
        margin: 7px 0 0;
    }

    .custom-cake-page .empty-state {
        text-align: center;
        color: var(--cake-muted);
        padding: 25px 10px;
    }

    .custom-cake-page .empty-state i {
        font-size: 1.7rem;
        display: block;
        margin-bottom: 8px;
    }

    @media (max-width: 991px) {
        .custom-cake-page .overview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .custom-cake-page .overview-grid {
            grid-template-columns: 1fr;
        }

        .custom-cake-page .info-grid {
            grid-template-columns: 1fr;
        }

        .custom-cake-page .money-grid {
            grid-template-columns: 1fr;
        }

        .custom-cake-page .section-heading,
        .custom-cake-page .pro-card-body {
            padding: 15px;
        }

        .custom-cake-page .status-progress {
            gap: 2px;
        }

        .custom-cake-page .progress-step {
            min-width: 48px;
        }

        .custom-cake-page .progress-step small {
            font-size: .61rem;
        }

        .custom-cake-page .progress-step span {
            width: 29px;
            height: 29px;
        }

        .custom-cake-page .progress-line {
            margin-top: -20px;
        }

        .custom-cake-page .header-badges {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('open_custom_cake_whatsapp');
    const messageField = document.getElementById('whatsapp_quotation_message');

    if (!button || !messageField) {
        return;
    }

    button.addEventListener('click', function () {
        const phone = button.dataset.phone;
        const message = messageField.value.trim();

        if (!phone || !/^91[6-9]\d{9}$/.test(phone)) {
            alert('Please verify the customer mobile number.');
            return;
        }

        if (!message) {
            alert('Please enter a WhatsApp message.');
            messageField.focus();
            return;
        }

        const whatsappUrl = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
        window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
    });
});
</script>
@endpush