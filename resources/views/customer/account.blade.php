@extends('layouts.customer.app')

@section('title', 'My Account')

@section('content')
<div class="delight-account">

    {{-- PAGE INTRO --}}
    <section class="account-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-label">
                    <span class="hero-label-line"></span>
                    YOUR PERSONAL BAKERY SPACE
                </div>

                <h1>Welcome back, <span>{{ $customer->name }}</span></h1>

                <p>
                    Your sweetest moments start here. Manage your profile,
                    custom cake orders and quotations in one place.
                </p>

                <div class="hero-decoration">✦</div>
            </div>
        </div>
    </section>

    <div class="container account-container">

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="delight-alert success-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="delight-alert error-alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    <strong>Please check the following:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- PROFILE CARD --}}
        <section class="profile-card">
            <div class="profile-top">
                <div class="profile-identity">
                    <div class="profile-avatar">
                        <i class="bi bi-person-fill"></i>
                        <span class="avatar-online"></span>
                    </div>

                    <div class="profile-name">
                        <span class="profile-kicker">MY PROFILE</span>
                        <h2>{{ $customer->name }}</h2>
                        <p>
                            <i class="bi bi-patch-check-fill"></i>
                            Delight Bakery Customer
                        </p>
                    </div>
                </div>

                <div class="member-badge">
                    <i class="bi bi-stars"></i>
                    VALUED CUSTOMER
                </div>
            </div>

            <div class="profile-divider"></div>

            <div class="profile-details">
                <div class="profile-detail">
                    <div class="detail-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <span>Email Address</span>
                        <strong>{{ $customer->email }}</strong>
                    </div>
                </div>

                <div class="profile-detail">
                    <div class="detail-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <span>Mobile Number</span>
                        <strong>{{ $customer->phone ?: 'Not provided' }}</strong>
                    </div>
                </div>

                <div class="profile-detail">
                    <div class="detail-icon">
                        <i class="bi bi-calendar-heart"></i>
                    </div>
                    <div>
                        <span>Member Since</span>
                        <strong>{{ $customer->created_at?->format('d M Y') ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <div class="profile-footer">
                <a href="{{ route('customer.home') }}" class="profile-home-btn">
                    <i class="bi bi-house-door"></i>
                    Back to Shop
                </a>

                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="profile-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </div>
        </section>

        {{-- ORDERS SECTION --}}
        <section class="orders-section">

            <div class="section-heading">
                <div>
                    <div class="section-eyebrow">
                        <span></span> YOUR BAKERY BOOKINGS
                    </div>
                    <h2>My Custom Cake Orders</h2>
                    <p>Every celebration deserves a cake made specially for you.</p>
                </div>

                <a href="{{ route('customer.custom-cake-orders.create') }}"
                   class="new-order-btn">
                    <i class="bi bi-plus-circle"></i>
                    Book a Custom Cake
                </a>
            </div>

            @forelse($orders as $order)

                @php
                    $cake = $order->customCakeDetail;

                    $paidAmount = $order->payments
                        ->where('payment_status', 'paid')
                        ->where('payment_type', 'payment')
                        ->sum(function ($payment) {
                            return (float) $payment->amount
                                - (float) ($payment->refund_amount ?? 0);
                        });

                    $hasQuotation = in_array(
                        $order->quotation_status,
                        ['sent', 'accepted', 'rejected'],
                        true
                    );

                    $orderStatusLabels = [
                        'pending' => 'Request Submitted',
                        'under_review' => 'Under Review',
                        'quotation_sent' => 'Quotation Sent',
                        'confirmed' => 'Confirmed',
                        'processing' => 'In Production',
                        'ready' => 'Ready for Collection',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ];

                    $quotationLabels = [
                        'draft' => 'Preparing Quotation',
                        'sent' => 'Awaiting Your Response',
                        'accepted' => 'Quotation Accepted',
                        'rejected' => 'Quotation Rejected',
                    ];
                @endphp

                <article class="order-card">

                    {{-- ORDER HEADER --}}
                    <div class="order-card-header">
                        <div class="order-reference">
                            <span class="order-reference-label">
                                <i class="bi bi-receipt"></i>
                                ORDER REFERENCE
                            </span>

                            <h3>{{ $order->order_number }}</h3>

                            <span class="order-booked-date">
                                <i class="bi bi-calendar3"></i>
                                Booked
                                {{ $order->placed_at?->format('d M Y, h:i A') ?? $order->created_at?->format('d M Y') }}
                            </span>
                        </div>

                        <div class="order-badges">
                            <span class="order-status status-{{ $order->order_status }}">
                                {{ $orderStatusLabels[$order->order_status] ?? ucfirst(str_replace('_', ' ', $order->order_status)) }}
                            </span>

                            <span class="quotation-status quotation-{{ $order->quotation_status ?? 'draft' }}">
                                <i class="bi bi-file-earmark-check"></i>
                                {{ $quotationLabels[$order->quotation_status] ?? ucfirst(str_replace('_', ' ', $order->quotation_status ?? 'pending')) }}
                            </span>
                        </div>
                    </div>

                    {{-- CAKE SPECIFICATIONS --}}
                    @if($cake)
                        <div class="order-content-section">
                            <div class="content-section-title">
                                <div class="content-title-icon">
                                    <i class="bi bi-cake2"></i>
                                </div>
                                <div>
                                    <h4>Your Custom Cake</h4>
                                    <p>Made according to your requirements</p>
                                </div>
                            </div>

                            <div class="cake-spec-grid">

                                <div class="cake-spec">
                                    <span>Occasion</span>
                                    <strong>{{ $cake->occasion ?: 'Not specified' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Flavour</span>
                                    <strong>{{ $cake->flavour ?: 'Not specified' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Weight</span>
                                    <strong>{{ $cake->weight_kg }} kg</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Shape</span>
                                    <strong>{{ $cake->shape ?: 'Not specified' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Servings</span>
                                    <strong>{{ $cake->servings ?: 'Not specified' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Theme</span>
                                    <strong>{{ $cake->theme ?: 'Not specified' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Cream Type</span>
                                    <strong>{{ $cake->cream_type ?: 'Not specified' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Fulfilment</span>
                                    <strong>{{ ucfirst($cake->fulfilment_type ?? 'N/A') }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Required Date</span>
                                    <strong>{{ $cake->required_date?->format('d M Y') ?? 'N/A' }}</strong>
                                </div>

                                <div class="cake-spec">
                                    <span>Required Time</span>
                                    <strong>
                                        {{ $cake->required_time ? \Illuminate\Support\Carbon::parse($cake->required_time)->format('h:i A') : 'N/A' }}
                                    </strong>
                                </div>

                            </div>

                            @if($cake->cake_message)
                                <div class="special-message">
                                    <div class="special-message-icon">
                                        <i class="bi bi-chat-heart"></i>
                                    </div>
                                    <div>
                                        <span>Message on Your Cake</span>
                                        <strong>{{ $cake->cake_message }}</strong>
                                    </div>
                                </div>
                            @endif

                            @if($cake->special_instructions)
                                <div class="special-message">
                                    <div class="special-message-icon">
                                        <i class="bi bi-card-text"></i>
                                    </div>
                                    <div>
                                        <span>Special Instructions</span>
                                        <strong>{{ $cake->special_instructions }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- QUOTATION AND PAYMENT --}}
                    @if($hasQuotation)

                        <div class="quotation-section">

                            <div class="content-section-title">
                                <div class="content-title-icon quotation-icon">
                                    <i class="bi bi-file-earmark-ruled"></i>
                                </div>
                                <div>
                                    <h4>Bakery Quotation</h4>
                                    <p>Your final custom cake pricing</p>
                                </div>
                            </div>

                            <div class="quotation-grid">
                                <div class="quotation-item">
                                    <span>Final Cake Price</span>
                                    <strong>
                                        ₹{{ number_format((float) $order->grand_total, 2) }}
                                    </strong>
                                </div>

                                <div class="quotation-item">
                                    <span>Advance Requested</span>
                                    <strong>
                                        ₹{{ number_format((float) $order->advance_requested, 2) }}
                                    </strong>
                                </div>

                                <div class="quotation-item">
                                    <span>Expected Completion</span>
                                    <strong>
                                        {{ $order->expected_completion_at?->format('d M Y, h:i A') ?? 'To be confirmed' }}
                                    </strong>
                                </div>
                            </div>

                            {{-- CUSTOMER ACCEPT / REJECT --}}
                            @if($order->quotation_status === 'sent')

                                <div class="response-box">
                                    <div class="response-box-heading">
                                        <i class="bi bi-bell-fill"></i>
                                        Your response is required
                                    </div>

                                    <p>
                                        Please review the quotation carefully.
                                        Accept it to confirm your cake order, or
                                        reject it if you do not wish to proceed.
                                    </p>

                                    <div class="quotation-actions">

                                        <form
                                            action="{{ route('customer.custom-cake-orders.accept', $order->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to accept this quotation and confirm your cake order?');">

                                            @csrf

                                            <button type="submit" class="accept-btn">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Accept Quotation
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('customer.custom-cake-orders.reject', $order->id) }}"
                                            method="POST"
                                            class="reject-form"
                                            onsubmit="return confirm('Are you sure you want to reject this quotation?');">

                                            @csrf

                                            <label for="rejection_reason_{{ $order->id }}">
                                                Reason for rejection
                                            </label>

                                            <textarea
                                                id="rejection_reason_{{ $order->id }}"
                                                name="rejection_reason"
                                                rows="2"
                                                maxlength="1000"
                                                required
                                                placeholder="Please tell us why you are rejecting this quotation."></textarea>

                                            <button type="submit" class="reject-btn">
                                                <i class="bi bi-x-circle-fill"></i>
                                                Reject Quotation
                                            </button>
                                        </form>

                                    </div>
                                </div>

                            @elseif($order->quotation_status === 'accepted')

                                <div class="response-result accepted-result">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <div>
                                        <strong>Quotation Accepted!</strong>
                                        <p>
                                            You accepted this quotation on
                                            {{ $order->customer_responded_at?->format('d M Y, h:i A') ?? 'N/A' }}.
                                        </p>
                                    </div>
                                </div>

                            @elseif($order->quotation_status === 'rejected')

                                <div class="response-result rejected-result">
                                    <i class="bi bi-x-circle-fill"></i>
                                    <div>
                                        <strong>Quotation Rejected</strong>
                                        <p>
                                            You rejected this quotation on
                                            {{ $order->customer_responded_at?->format('d M Y, h:i A') ?? 'N/A' }}.
                                        </p>

                                        @if($order->customer_rejection_reason)
                                            <p class="rejection-reason">
                                                <strong>Your reason:</strong>
                                                {{ $order->customer_rejection_reason }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                            @endif
                        </div>

                        {{-- PAYMENT SUMMARY --}}
                        <div class="payment-section">

                            <div class="content-section-title">
                                <div class="content-title-icon payment-icon">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <div>
                                    <h4>Payment Overview</h4>
                                    <p>Your cake order payment details</p>
                                </div>
                            </div>

                            <div class="payment-grid">

                                <div class="payment-item">
                                    <span>Order Total</span>
                                    <strong>
                                        ₹{{ number_format((float) $order->grand_total, 2) }}
                                    </strong>
                                </div>

                                <div class="payment-item paid-item">
                                    <span>Amount Paid</span>
                                    <strong>
                                        ₹{{ number_format($paidAmount, 2) }}
                                    </strong>
                                </div>

                                <div class="payment-item balance-item">
                                    <span>Balance Due</span>
                                    <strong>
                                        ₹{{ number_format(max(0, (float) $order->grand_total - $paidAmount), 2) }}
                                    </strong>
                                </div>

                            </div>

                            @if($order->payments->where('payment_status', 'paid')->where('payment_type', 'payment')->isNotEmpty())

                                <div class="payment-history">
                                    <h5>
                                        <i class="bi bi-clock-history"></i>
                                        Payment History
                                    </h5>

                                    @foreach($order->payments->where('payment_status', 'paid')->where('payment_type', 'payment')->sortByDesc('paid_at') as $payment)

                                        <div class="payment-history-row">
                                            <div class="payment-method">
                                                <span class="payment-history-icon">
                                                    <i class="bi bi-credit-card"></i>
                                                </span>
                                                <div>
                                                    <strong>
                                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Payment')) }}
                                                    </strong>
                                                    <span>
                                                        {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Date not available' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <strong class="history-amount">
                                                ₹{{ number_format((float) $payment->amount, 2) }}
                                            </strong>
                                        </div>

                                    @endforeach
                                </div>

                            @else

                                <div class="no-payment">
                                    <i class="bi bi-info-circle"></i>
                                    No payments have been recorded for this order yet.
                                </div>

                            @endif

                        </div>

                    @else

                        {{-- QUOTATION PENDING --}}
                        <div class="pending-quotation">
                            <div class="pending-icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                            <div>
                                <strong>Your quotation is being prepared</strong>
                                <p>
                                    Our bakery team is reviewing your cake requirements.
                                    We will share the final price and advance details
                                    once your quotation is ready.
                                </p>
                                <span>
                                    <i class="bi bi-heart-fill"></i>
                                    Thank you for choosing Delight Bakery!
                                </span>
                            </div>
                        </div>

                    @endif

                </article>

            @empty

                {{-- EMPTY STATE --}}
                <div class="empty-orders">
                    <div class="empty-cake-icon">
                        <i class="bi bi-cake2"></i>
                    </div>

                    <span class="empty-eyebrow">YOUR SWEET JOURNEY STARTS HERE</span>

                    <h3>No Custom Cake Orders Yet</h3>

                    <p>
                        Have a celebration coming up? Create a cake that is
                        uniquely yours. We would love to bake something special!
                    </p>

                    <a href="{{ route('customer.custom-cake-orders.create') }}"
                       class="new-order-btn">
                        <i class="bi bi-plus-circle"></i>
                        Book Your First Cake
                    </a>
                </div>

            @endforelse

        </section>

        {{-- BOTTOM BRAND MESSAGE --}}
        <div class="account-brand-footer">
            <span>BAKED WITH LOVE</span>
            <div class="footer-decoration">✦ ✦ ✦</div>
            <p>Thank you for making Delight Bakery part of your celebrations.</p>
        </div>

    </div>
</div>

<style>
/* =========================================
   DELIGHT BAKERY - CUSTOMER ACCOUNT
========================================= */

.delight-account {
    --cream: #f9efe3;
    --paper: #fffaf4;
    --paper-white: #ffffff;
    --brown: #4A2616;
    --brown-dark: #35190f;
    --chocolate: #5a3427;
    --caramel: #d9a066;
    --peach: #f6c9b9;
    --blush: #f4cdd2;
    --line: #ead8c8;
    --text: #4a2f26;
    --muted: #8d7468;
    --green: #34764b;
    --red: #ad4942;

    background: #f8f2eb;
    min-height: 100vh;
    color: var(--text);
    padding-bottom: 45px;
    font-family: 'DM Sans', 'Inter', sans-serif;
}

.delight-account * {
    box-sizing: border-box;
}

.delight-account button,
.delight-account a {
    -webkit-tap-highlight-color: transparent;
}

/* HERO */

.delight-account .account-hero {
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(circle at 85% 20%, rgba(217,160,102,.22), transparent 28%),
        linear-gradient(120deg, #35190f 0%, #4A2616 55%, #68432e 100%);
    color: #fffaf4;
    padding: 65px 0 105px;
}

.delight-account .account-hero::before {
    content: "";
    position: absolute;
    width: 350px;
    height: 350px;
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 50%;
    right: -100px;
    top: -170px;
}

.delight-account .account-hero::after {
    content: "";
    position: absolute;
    width: 230px;
    height: 230px;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 50%;
    right: 30px;
    bottom: -180px;
}

.delight-account .hero-content {
    position: relative;
    z-index: 2;
    max-width: 760px;
}

.delight-account .hero-label {
    display: flex;
    align-items: center;
    gap: 11px;
    color: #e8bd91;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 3px;
}

.delight-account .hero-label-line {
    width: 32px;
    height: 2px;
    background: var(--caramel);
}

.delight-account .hero-content h1 {
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: clamp(36px, 5vw, 55px);
    line-height: 1.15;
    margin: 19px 0 13px;
    color: #fffaf4;
}

.delight-account .hero-content h1 span {
    color: #edbd88;
}

.delight-account .hero-content > p {
    color: rgba(255,250,244,.76);
    font-size: 15px;
    line-height: 1.8;
    max-width: 580px;
    margin: 0;
}

.delight-account .hero-decoration {
    position: absolute;
    right: 45px;
    top: 15px;
    color: rgba(237,189,136,.35);
    font-size: 100px;
}

/* MAIN CONTAINER */

.delight-account .account-container {
    position: relative;
    z-index: 3;
    margin-top: -55px;
}

/* ALERTS */

.delight-account .delight-alert {
    display: flex;
    gap: 13px;
    align-items: flex-start;
    padding: 16px 19px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
}

.delight-account .delight-alert > i {
    font-size: 19px;
}

.delight-account .success-alert {
    background: #e7f3e8;
    color: #28643c;
    border: 1px solid #cce6d0;
}

.delight-account .error-alert {
    background: #fff0ed;
    color: #a12c25;
    border: 1px solid #f1d1cb;
}

.delight-account .error-alert ul {
    padding-left: 19px;
    margin-bottom: 0;
    margin-top: 8px;
}

/* PROFILE CARD */

.delight-account .profile-card {
    background: var(--paper);
    border: 1px solid rgba(234,216,200,.9);
    border-radius: 19px;
    padding: 30px;
    box-shadow: 0 18px 55px rgba(74,38,22,.10);
}

.delight-account .profile-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.delight-account .profile-identity {
    display: flex;
    align-items: center;
    gap: 19px;
}

.delight-account .profile-avatar {
    width: 78px;
    height: 78px;
    flex-shrink: 0;
    border-radius: 50%;
    display: grid;
    place-items: center;
    position: relative;
    background: linear-gradient(140deg, #f6dfca, #f0c5a1);
    color: var(--brown);
    font-size: 36px;
    box-shadow: inset 0 0 0 5px rgba(255,255,255,.55);
}

.delight-account .avatar-online {
    position: absolute;
    width: 14px;
    height: 14px;
    background: #4b9c65;
    border: 3px solid var(--paper);
    border-radius: 50%;
    right: 3px;
    bottom: 5px;
}

.delight-account .profile-kicker {
    display: block;
    color: #a17a5c;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2.5px;
    margin-bottom: 6px;
}

.delight-account .profile-name h2 {
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 27px;
    color: var(--brown);
    margin: 0 0 5px;
}

.delight-account .profile-name p {
    color: var(--muted);
    font-size: 12px;
    margin: 0;
}

.delight-account .profile-name p i {
    color: #bc8c5b;
    margin-right: 4px;
}

.delight-account .member-badge {
    background: #f6eadb;
    color: #855b36;
    border: 1px solid #ead6be;
    padding: 9px 14px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
}

.delight-account .member-badge i {
    margin-right: 5px;
    color: #c58a4f;
}

.delight-account .profile-divider {
    height: 1px;
    background: var(--line);
    margin: 27px 0;
}

.delight-account .profile-details {
    display: grid;
    grid-template-columns: repeat(3, minmax(0,1fr));
    gap: 20px;
}

.delight-account .profile-detail {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.delight-account .detail-icon {
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border-radius: 12px;
    background: #f6eadb;
    color: #95623b;
    display: grid;
    place-items: center;
    font-size: 18px;
}

.delight-account .profile-detail > div:last-child {
    min-width: 0;
}

.delight-account .profile-detail span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-bottom: 5px;
}

.delight-account .profile-detail strong {
    display: block;
    color: var(--brown);
    font-size: 13px;
    overflow-wrap: anywhere;
}

.delight-account .profile-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    border-top: 1px solid var(--line);
    margin-top: 27px;
    padding-top: 22px;
}

.delight-account .profile-footer form {
    margin: 0;
}

.delight-account .profile-home-btn,
.delight-account .profile-logout-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 11px 18px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: .25s ease;
    cursor: pointer;
}

.delight-account .profile-home-btn {
    background: #f2e4d6;
    color: var(--brown);
    border: 1px solid #ead8c8;
}

.delight-account .profile-home-btn:hover {
    background: #ead5c0;
    color: var(--brown);
    transform: translateY(-2px);
}

.delight-account .profile-logout-btn {
    background: transparent;
    border: 1px solid #e3c9bd;
    color: #985149;
}

.delight-account .profile-logout-btn:hover {
    background: #fff0ed;
    border-color: #d9aaa0;
}

/* ORDERS HEADING */

.delight-account .orders-section {
    margin-top: 55px;
}

.delight-account .section-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.delight-account .section-eyebrow {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #a27650;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2.4px;
}

.delight-account .section-eyebrow span {
    display: inline-block;
    width: 24px;
    height: 2px;
    background: var(--caramel);
}

.delight-account .section-heading h2 {
    font-family: 'DM Serif Display', Georgia, serif;
    color: var(--brown);
    font-size: clamp(27px,4vw,36px);
    margin: 9px 0 5px;
}

.delight-account .section-heading p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}

.delight-account .new-order-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 13px 19px;
    border-radius: 9px;
    background: linear-gradient(135deg,#6a3e27,#402013);
    color: #fffaf4;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(74,38,22,.17);
    transition: .25s ease;
}

.delight-account .new-order-btn:hover {
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 10px 23px rgba(74,38,22,.23);
}

/* ORDER CARD */

.delight-account .order-card {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 17px;
    padding: 27px;
    margin-bottom: 23px;
    box-shadow: 0 9px 28px rgba(74,38,22,.045);
    transition: .25s ease;
}

.delight-account .order-card:hover {
    box-shadow: 0 15px 35px rgba(74,38,22,.085);
    transform: translateY(-2px);
}

.delight-account .order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: wrap;
    padding-bottom: 21px;
    border-bottom: 1px solid var(--line);
}

.delight-account .order-reference-label {
    display: block;
    color: #a17b5d;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.8px;
}

.delight-account .order-reference-label i {
    margin-right: 5px;
}

.delight-account .order-reference h3 {
    font-family: 'DM Serif Display', Georgia, serif;
    color: var(--brown);
    font-size: 21px;
    margin: 7px 0;
    overflow-wrap: anywhere;
}

.delight-account .order-booked-date {
    color: var(--muted);
    font-size: 11px;
}

.delight-account .order-booked-date i {
    margin-right: 5px;
}

.delight-account .order-badges {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.delight-account .order-status,
.delight-account .quotation-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 30px;
    padding: 8px 12px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .3px;
}

.delight-account .order-status {
    background: #f5eadb;
    color: #80552f;
}

.delight-account .quotation-status {
    background: #f0e8df;
    color: #775b47;
}

.delight-account .status-confirmed,
.delight-account .status-completed,
.delight-account .status-ready {
    background: #e3f2e6;
    color: #28643c;
}

.delight-account .status-processing {
    background: #e8edf9;
    color: #344d86;
}

.delight-account .status-rejected,
.delight-account .status-cancelled {
    background: #fbe6e2;
    color: #a12c25;
}

.delight-account .quotation-sent {
    background: #fff0d9;
    color: #98632a;
}

.delight-account .quotation-accepted {
    background: #e3f2e6;
    color: #28643c;
}

.delight-account .quotation-rejected {
    background: #fbe6e2;
    color: #a12c25;
}

/* SECTION TITLES */

.delight-account .order-content-section,
.delight-account .quotation-section,
.delight-account .payment-section {
    padding-top: 25px;
}

.delight-account .content-section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
}

.delight-account .content-title-icon {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: #f4e6d6;
    color: #8a5d39;
    font-size: 19px;
}

.delight-account .quotation-icon {
    background: #f7e9dc;
    color: #9a663c;
}

.delight-account .payment-icon {
    background: #e7f0e5;
    color: #47744b;
}

.delight-account .content-section-title h4 {
    color: var(--brown);
    font-size: 15px;
    font-weight: 800;
    margin: 0 0 4px;
}

.delight-account .content-section-title p {
    color: var(--muted);
    font-size: 11px;
    margin: 0;
}

/* CAKE DETAILS */

.delight-account .cake-spec-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 11px;
}

.delight-account .cake-spec {
    background: #fbf4ec;
    border: 1px solid #f0e4d7;
    border-radius: 10px;
    padding: 14px;
    min-width: 0;
    transition: .2s ease;
}

.delight-account .cake-spec:hover {
    background: #f7ebde;
    border-color: #e6cdb4;
}

.delight-account .cake-spec span {
    display: block;
    color: #967c6a;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    margin-bottom: 7px;
}

.delight-account .cake-spec strong {
    display: block;
    color: var(--brown);
    font-size: 13px;
    overflow-wrap: anywhere;
}

.delight-account .special-message {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #fff4ee;
    border: 1px solid #f1dfd2;
    padding: 13px 15px;
    border-radius: 10px;
    margin-top: 12px;
}

.delight-account .special-message-icon {
    color: #b27a55;
    font-size: 19px;
}

.delight-account .special-message span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-bottom: 4px;
}

.delight-account .special-message strong {
    display: block;
    color: var(--brown);
    font-size: 13px;
    overflow-wrap: anywhere;
}

/* QUOTATION */

.delight-account .quotation-section {
    border-top: 1px solid var(--line);
    margin-top: 25px;
}

.delight-account .quotation-grid {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 12px;
}

.delight-account .quotation-item {
    padding: 17px;
    border-radius: 11px;
    background: #f8eee4;
    border: 1px solid #edddcd;
}

.delight-account .quotation-item span {
    display: block;
    color: #967b68;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .7px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.delight-account .quotation-item strong {
    color: var(--brown);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 19px;
    overflow-wrap: anywhere;
}

/* ACCEPT / REJECT */

.delight-account .response-box {
    background: #fff5e9;
    border: 1px solid #ebd3b8;
    border-radius: 13px;
    padding: 20px;
    margin-top: 19px;
}

.delight-account .response-box-heading {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #80552f;
    font-weight: 800;
    font-size: 14px;
}

.delight-account .response-box-heading i {
    color: #c58b4d;
    font-size: 18px;
}

.delight-account .response-box > p {
    color: #866c58;
    font-size: 12px;
    line-height: 1.7;
    margin: 9px 0 16px;
}

.delight-account .quotation-actions {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    flex-wrap: wrap;
}

.delight-account .quotation-actions form {
    margin: 0;
}

.delight-account .accept-btn,
.delight-account .reject-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 17px;
    border: 0;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: .25s ease;
}

.delight-account .accept-btn {
    color: #fff;
    background: #34764b;
    box-shadow: 0 5px 12px rgba(52,118,75,.15);
}

.delight-account .accept-btn:hover {
    background: #245b37;
    transform: translateY(-2px);
}

.delight-account .reject-form {
    flex: 1 1 260px;
    min-width: 0;
}

.delight-account .reject-form label {
    display: block;
    color: #805c4c;
    font-size: 11px;
    font-weight: 800;
    margin-bottom: 7px;
}

.delight-account .reject-form textarea {
    width: 100%;
    border: 1px solid #e8cfc5;
    border-radius: 8px;
    background: #fffdfb;
    color: var(--brown);
    padding: 11px;
    font-size: 12px;
    resize: vertical;
    outline: none;
    margin-bottom: 9px;
    transition: .2s ease;
}

.delight-account .reject-form textarea:focus {
    border-color: #b9786d;
    box-shadow: 0 0 0 3px rgba(185,120,109,.1);
}

.delight-account .reject-btn {
    background: #a7473f;
    color: #fff;
}

.delight-account .reject-btn:hover {
    background: #87352e;
    transform: translateY(-2px);
}

/* RESPONSE RESULTS */

.delight-account .response-result {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 16px;
    border-radius: 10px;
    margin-top: 18px;
    font-size: 13px;
}

.delight-account .response-result > i {
    font-size: 21px;
}

.delight-account .response-result strong {
    font-size: 13px;
}

.delight-account .response-result p {
    margin: 5px 0 0;
    font-size: 12px;
    line-height: 1.6;
}

.delight-account .accepted-result {
    background: #e8f4e9;
    color: #28643c;
    border: 1px solid #cde6d1;
}

.delight-account .rejected-result {
    background: #fff0ed;
    color: #a12c25;
    border: 1px solid #f1d1cb;
}

.delight-account .rejection-reason {
    margin-top: 9px !important;
    overflow-wrap: anywhere;
}

/* PAYMENT */

.delight-account .payment-section {
    border-top: 1px solid var(--line);
    margin-top: 25px;
}

.delight-account .payment-grid {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 12px;
}

.delight-account .payment-item {
    background: #f8eee4;
    border: 1px solid #edddcd;
    border-radius: 11px;
    padding: 17px;
}

.delight-account .payment-item span {
    display: block;
    color: #967b68;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    margin-bottom: 8px;
}

.delight-account .payment-item strong {
    display: block;
    color: var(--brown);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 19px;
    overflow-wrap: anywhere;
}

.delight-account .paid-item {
    background: #edf5eb;
    border-color: #d8e8d4;
}

.delight-account .paid-item strong {
    color: #34764b;
}

.delight-account .balance-item {
    background: #fff0ec;
    border-color: #f0d8d0;
}

.delight-account .balance-item strong {
    color: #ad4942;
}

.delight-account .payment-history {
    border-top: 1px dashed #e6d5c6;
    margin-top: 20px;
    padding-top: 17px;
}

.delight-account .payment-history h5 {
    color: var(--brown);
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 12px;
}

.delight-account .payment-history h5 i {
    color: #a47754;
    margin-right: 6px;
}

.delight-account .payment-history-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #f1e7dd;
}

.delight-account .payment-method {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.delight-account .payment-history-icon {
    width: 37px;
    height: 37px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    border-radius: 10px;
    background: #f2e7db;
    color: #8b603f;
}

.delight-account .payment-method div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.delight-account .payment-method strong {
    color: var(--brown);
    font-size: 12px;
}

.delight-account .payment-method span:not(.payment-history-icon) {
    color: var(--muted);
    font-size: 10px;
}

.delight-account .history-amount {
    color: #34764b;
    font-size: 14px;
    white-space: nowrap;
}

.delight-account .no-payment {
    display: flex;
    align-items: center;
    gap: 9px;
    background: #f8f0e7;
    color: #8c735f;
    border-radius: 9px;
    padding: 13px;
    font-size: 12px;
    margin-top: 15px;
}

/* PENDING QUOTATION */

.delight-account .pending-quotation {
    display: flex;
    gap: 15px;
    align-items: flex-start;
    background: linear-gradient(120deg,#fff5e9,#f9eadb);
    border: 1px solid #ecd7c0;
    border-radius: 12px;
    padding: 19px;
    margin-top: 23px;
}

.delight-account .pending-icon {
    width: 43px;
    height: 43px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    background: #f0ddc5;
    color: #9a693d;
    border-radius: 12px;
    font-size: 21px;
}

.delight-account .pending-quotation strong {
    display: block;
    color: var(--brown);
    font-size: 14px;
}

.delight-account .pending-quotation p {
    color: #8b735f;
    font-size: 12px;
    line-height: 1.7;
    margin: 6px 0 9px;
}

.delight-account .pending-quotation > div:last-child > span {
    color: #a77b51;
    font-size: 10px;
    font-weight: 700;
}

/* EMPTY STATE */

.delight-account .empty-orders {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 18px;
    padding: 55px 25px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(74,38,22,.05);
}

.delight-account .empty-cake-icon {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    margin: 0 auto 20px;
    background: #f5e5d5;
    color: #9a693d;
    font-size: 39px;
}

.delight-account .empty-eyebrow {
    color: #a27650;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
}

.delight-account .empty-orders h3 {
    font-family: 'DM Serif Display', Georgia, serif;
    color: var(--brown);
    font-size: 29px;
    margin: 12px 0 8px;
}

.delight-account .empty-orders p {
    max-width: 500px;
    margin: 0 auto 23px;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

/* FOOTER */

.delight-account .account-brand-footer {
    text-align: center;
    padding: 45px 15px 5px;
}

.delight-account .account-brand-footer > span {
    color: #a27650;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 3px;
}

.delight-account .footer-decoration {
    color: #c99a6c;
    font-size: 13px;
    letter-spacing: 8px;
    margin: 10px 0;
}

.delight-account .account-brand-footer p {
    color: #967e6d;
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 15px;
    margin: 0;
}

/* RESPONSIVE */

@media (max-width: 991px) {
    .delight-account .profile-details {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .delight-account .cake-spec-grid {
        grid-template-columns: repeat(3,minmax(0,1fr));
    }
}

@media (max-width: 767px) {
    .delight-account .account-hero {
        padding: 45px 0 85px;
    }

    .delight-account .hero-decoration {
        right: 10px;
        top: 35px;
        font-size: 65px;
    }

    .delight-account .profile-card {
        padding: 22px 19px;
    }

    .delight-account .profile-details {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .delight-account .cake-spec-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .delight-account .quotation-grid,
    .delight-account .payment-grid {
        grid-template-columns: 1fr;
    }

    .delight-account .section-heading {
        align-items: flex-start;
    }

    .delight-account .order-card {
        padding: 21px 17px;
    }
}

@media (max-width: 480px) {
    .delight-account .hero-content h1 {
        font-size: 35px;
    }

    .delight-account .profile-identity {
        gap: 13px;
    }

    .delight-account .profile-avatar {
        width: 61px;
        height: 61px;
        font-size: 28px;
    }

    .delight-account .profile-name h2 {
        font-size: 22px;
    }

    .delight-account .member-badge {
        font-size: 9px;
    }

    .delight-account .profile-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .delight-account .profile-footer a,
    .delight-account .profile-footer button {
        width: 100%;
    }

    .delight-account .profile-footer form {
        width: 100%;
    }

    .delight-account .order-badges {
        align-items: flex-start;
    }

    .delight-account .cake-spec-grid {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .delight-account .cake-spec {
        padding: 11px;
    }

    .delight-account .quotation-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .delight-account .quotation-actions form,
    .delight-account .accept-btn,
    .delight-account .reject-btn {
        width: 100%;
    }

    .delight-account .reject-form {
        flex-basis: auto;
    }

    .delight-account .payment-history-row {
        align-items: flex-start;
    }
}
</style>
@endsection
