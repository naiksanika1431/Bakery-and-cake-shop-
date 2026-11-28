@extends('layouts.customer.app')

@section('title', 'My Account')

@section('content')
<div class="customer-account-page">
    <div class="container py-5">
        <div class="account-heading mb-4">
            <span class="account-eyebrow">PERSONAL SPACE</span>
            <h1>My Account</h1>
            <p>Welcome back, {{ $customer->name }}! Manage your account information and cake orders here.</p>
        </div>

        @if(session('success'))
            <div class="account-alert account-alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="account-alert account-alert-error">
                <strong>Please check the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Customer Profile --}}
        <div class="account-card">
            <div class="account-card-header">
                <div class="account-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h4>{{ $customer->name }}</h4>
                    <span>Customer Account</span>
                </div>
            </div>

            <div class="account-details">
                <div class="account-detail">
                    <span>Full Name</span>
                    <strong>{{ $customer->name }}</strong>
                </div>

                <div class="account-detail">
                    <span>Email Address</span>
                    <strong>{{ $customer->email }}</strong>
                </div>

                <div class="account-detail">
                    <span>Mobile Number</span>
                    <strong>{{ $customer->phone }}</strong>
                </div>

                <div class="account-detail">
                    <span>Account Created</span>
                    <strong>{{ $customer->created_at?->format('d M Y') ?? 'N/A' }}</strong>
                </div>
            </div>

            <div class="account-actions">
                <a href="{{ route('customer.home') }}" class="account-home-btn">
                    <i class="bi bi-house"></i> Back to Shop
                </a>

                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="account-logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Custom Cake Orders --}}
        <section class="orders-section">
            <div class="orders-heading">
                <div>
                    <span class="account-eyebrow">YOUR BAKERY BOOKINGS</span>
                    <h2>My Custom Cake Orders</h2>
                    <p>Track your cake requests, quotations and payments.</p>
                </div>
                <a href="{{ route('customer.custom-cake-orders.create') }}" class="new-order-btn">
                    <i class="bi bi-plus-circle"></i> Book a Cake
                </a>
            </div>

            @forelse($orders as $order)
                @php
                    $cake = $order->customCakeDetail;

                    $paidAmount = $order->payments
                        ->where('payment_status', 'paid')
                        ->where('payment_type', 'payment')
                        ->sum(function ($payment) {
                            return (float) $payment->amount - (float) ($payment->refund_amount ?? 0);
                        });

                    $hasQuotation = in_array($order->quotation_status, ['sent', 'accepted', 'rejected'], true);

                    $orderStatusLabels = [
                        'pending' => 'Request Submitted',
                        'under_review' => 'Under Review',
                        'quotation_sent' => 'Quotation Sent',
                        'confirmed' => 'Confirmed',
                        'processing' => 'In Production',
                        'ready' => 'Ready',
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

                <article class="customer-order-card">
                    <div class="customer-order-header">
                        <div>
                            <span class="order-number-label">ORDER NUMBER</span>
                            <h3>{{ $order->order_number }}</h3>
                            <span class="order-date">
                                Booked {{ $order->placed_at?->format('d M Y, h:i A') ?? $order->created_at?->format('d M Y') }}
                            </span>
                        </div>

                        <div class="order-status-group">
                            <span class="order-status status-{{ $order->order_status }}">
                                {{ $orderStatusLabels[$order->order_status] ?? ucfirst(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                            <span class="quotation-status">
                                {{ $quotationLabels[$order->quotation_status] ?? ucfirst(str_replace('_', ' ', $order->quotation_status ?? 'pending')) }}
                            </span>
                        </div>
                    </div>

                    @if($cake)
                        <div class="cake-specifications">
                            <h4><i class="bi bi-cake2"></i> Cake Requirements</h4>

                            <div class="cake-spec-grid">
                                <div><span>Occasion</span><strong>{{ $cake->occasion }}</strong></div>
                                <div><span>Flavour</span><strong>{{ $cake->flavour }}</strong></div>
                                <div><span>Weight</span><strong>{{ $cake->weight_kg }} kg</strong></div>
                                <div><span>Shape</span><strong>{{ $cake->shape }}</strong></div>
                                <div><span>Servings</span><strong>{{ $cake->servings }}</strong></div>
                                <div><span>Theme</span><strong>{{ $cake->theme ?: 'Not specified' }}</strong></div>
                                <div><span>Cream</span><strong>{{ $cake->cream_type ?: 'Not specified' }}</strong></div>
                                <div><span>Fulfilment</span><strong>{{ ucfirst($cake->fulfilment_type) }}</strong></div>
                                <div><span>Required Date</span><strong>{{ $cake->required_date?->format('d M Y') ?? 'N/A' }}</strong></div>
                                <div><span>Required Time</span><strong>{{ $cake->required_time ? \Illuminate\Support\Carbon::parse($cake->required_time)->format('h:i A') : 'N/A' }}</strong></div>
                            </div>

                            @if($cake->cake_message)
                                <div class="cake-message">
                                    <span>Cake Message</span>
                                    <strong>{{ $cake->cake_message }}</strong>
                                </div>
                            @endif

                            @if($cake->special_instructions)
                                <div class="cake-message">
                                    <span>Your Instructions</span>
                                    <strong>{{ $cake->special_instructions }}</strong>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($hasQuotation)
                        <div class="quotation-panel">
                            <h4><i class="bi bi-receipt"></i> Bakery Quotation</h4>

                            <div class="quotation-grid">
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
                                    <strong>{{ $order->expected_completion_at?->format('d M Y, h:i A') ?? 'To be confirmed' }}</strong>
                                </div>
                            </div>

                            @if($order->quotation_status === 'sent')
                                <div class="quotation-response">
                                    <p>Please review the quotation. Accept it to confirm your cake order, or reject it if you do not wish to proceed.</p>

                                    <div class="quotation-buttons">
                                        <form action="{{ route('customer.custom-cake-orders.accept', $order->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to accept this quotation and confirm your cake order?');">
                                            @csrf
                                            <button type="submit" class="accept-quotation-btn">
                                                <i class="bi bi-check-circle"></i> Accept Quotation
                                            </button>
                                        </form>

                                        <form action="{{ route('customer.custom-cake-orders.reject', $order->id) }}" method="POST"
                                              class="reject-quotation-form"
                                              onsubmit="return confirm('Are you sure you want to reject this quotation?');">
                                            @csrf
                                            <label for="rejection_reason_{{ $order->id }}">Reason for rejection</label>
                                            <textarea id="rejection_reason_{{ $order->id }}"
                                                      name="rejection_reason"
                                                      rows="2"
                                                      maxlength="1000"
                                                      required
                                                      placeholder="Please tell us why you are rejecting this quotation."></textarea>
                                            <button type="submit" class="reject-quotation-btn">
                                                <i class="bi bi-x-circle"></i> Reject Quotation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($order->quotation_status === 'accepted')
                                <div class="response-message response-accepted">
                                    <i class="bi bi-check-circle-fill"></i>
                                    You accepted this quotation on {{ $order->customer_responded_at?->format('d M Y, h:i A') ?? 'N/A' }}.
                                </div>
                            @elseif($order->quotation_status === 'rejected')
                                <div class="response-message response-rejected">
                                    <i class="bi bi-x-circle-fill"></i>
                                    You rejected this quotation on {{ $order->customer_responded_at?->format('d M Y, h:i A') ?? 'N/A' }}.
                                    @if($order->customer_rejection_reason)
                                        <p><strong>Your reason:</strong> {{ $order->customer_rejection_reason }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Payment Summary --}}
                        <div class="payment-panel">
                            <h4><i class="bi bi-wallet2"></i> Payment Summary</h4>

                            <div class="payment-summary-grid">
                                <div>
                                    <span>Order Total</span>
                                    <strong>₹{{ number_format((float) $order->grand_total, 2) }}</strong>
                                </div>
                                <div>
                                    <span>Amount Paid</span>
                                    <strong class="paid-amount">₹{{ number_format($paidAmount, 2) }}</strong>
                                </div>
                                <div>
                                    <span>Balance Due</span>
                                    <strong class="balance-amount">₹{{ number_format(max(0, (float) $order->grand_total - $paidAmount), 2) }}</strong>
                                </div>
                            </div>

                            @if($order->payments->where('payment_status', 'paid')->where('payment_type', 'payment')->isNotEmpty())
                                <div class="payment-history">
                                    <h5>Payment History</h5>
                                    @foreach($order->payments->where('payment_status', 'paid')->where('payment_type', 'payment')->sortByDesc('paid_at') as $payment)
                                        <div class="payment-history-row">
                                            <div>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Payment')) }}</strong>
                                                <span>{{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Date not available' }}</span>
                                            </div>
                                            <strong>₹{{ number_format((float) $payment->amount, 2) }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-payment-message">No payments have been recorded for this order yet.</p>
                            @endif
                        </div>
                    @else
                        <div class="quotation-pending">
                            <i class="bi bi-hourglass-split"></i>
                            <div>
                                <strong>Your quotation is being prepared.</strong>
                                <p>The bakery will share the final price and advance details after reviewing your request.</p>
                            </div>
                        </div>
                    @endif
                </article>
            @empty
                <div class="no-orders-card">
                    <i class="bi bi-cake2"></i>
                    <h3>No Custom Cake Orders Yet</h3>
                    <p>Your custom cake bookings and quotations will appear here.</p>
                    <a href="{{ route('customer.custom-cake-orders.create') }}" class="new-order-btn">
                        Book Your First Cake
                    </a>
                </div>
            @endforelse
        </section>
    </div>
</div>

<style>
.customer-account-page {
    background: #FCEEEB;
    min-height: 70vh;
    padding: 30px 0;
    color: #421719;
}
.customer-account-page .account-heading h1 {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(32px, 5vw, 46px);
    margin: 8px 0;
}
.customer-account-page .account-eyebrow {
    color: #793638;
    font-size: 11px;
    letter-spacing: 3px;
    font-weight: 700;
}
.customer-account-page .account-heading p {
    color: #795b59;
}
.customer-account-page .account-card {
    background: #fffefa;
    max-width: 760px;
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 12px 35px rgba(66, 23, 25, .08);
}
.customer-account-page .account-card-header {
    display: flex;
    align-items: center;
    gap: 18px;
    padding-bottom: 25px;
    border-bottom: 1px solid #f0dfda;
}
.customer-account-page .account-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: #FCEEEB;
    color: #793638;
    font-size: 28px;
}
.customer-account-page .account-card-header h4 {
    margin: 0 0 5px;
    color: #421719;
    font-weight: 700;
}
.customer-account-page .account-card-header span,
.customer-account-page .account-detail span {
    color: #947876;
    font-size: 13px;
}
.customer-account-page .account-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    padding: 28px 0;
}
.customer-account-page .account-detail {
    display: flex;
    flex-direction: column;
    gap: 7px;
    overflow-wrap: anywhere;
}
.customer-account-page .account-detail strong {
    color: #421719;
    font-size: 15px;
}
.customer-account-page .account-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    border-top: 1px solid #f0dfda;
    padding-top: 24px;
}
.customer-account-page .account-actions a,
.customer-account-page .account-actions button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
}
.customer-account-page .account-home-btn {
    background: #FCEEEB;
    color: #592122;
}
.customer-account-page .account-logout-btn {
    background: #592122;
    color: white;
    border: 1px solid #592122;
}
.customer-account-page .orders-section {
    margin-top: 55px;
}
.customer-account-page .orders-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}
.customer-account-page .orders-heading h2 {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(26px, 4vw, 34px);
    margin: 7px 0;
}
.customer-account-page .orders-heading p {
    color: #795b59;
    margin: 0;
}
.customer-account-page .new-order-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #592122;
    color: #fff;
    padding: 12px 19px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
}
.customer-account-page .customer-order-card,
.customer-account-page .no-orders-card {
    background: #fffefa;
    border: 1px solid #f0dfda;
    border-radius: 16px;
    padding: 26px;
    margin-bottom: 22px;
    box-shadow: 0 10px 28px rgba(66, 23, 25, .06);
}
.customer-account-page .customer-order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    flex-wrap: wrap;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0dfda;
}
.customer-account-page .order-number-label,
.customer-account-page .customer-order-card .cake-spec-grid span,
.customer-account-page .quotation-grid span,
.customer-account-page .payment-summary-grid span {
    display: block;
    color: #947876;
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
}
.customer-account-page .customer-order-header h3 {
    font-size: 19px;
    margin: 5px 0;
    color: #421719;
    font-weight: 700;
}
.customer-account-page .order-date {
    color: #947876;
    font-size: 12px;
}
.customer-account-page .order-status-group {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 7px;
}
.customer-account-page .order-status,
.customer-account-page .quotation-status {
    display: inline-block;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: #f5e8e5;
    color: #793638;
}
.customer-account-page .status-confirmed,
.customer-account-page .status-completed,
.customer-account-page .status-ready {
    background: #e5f3e9;
    color: #28643c;
}
.customer-account-page .status-rejected,
.customer-account-page .status-cancelled {
    background: #fbe4e2;
    color: #a12c25;
}
.customer-account-page .status-processing {
    background: #e8edf9;
    color: #344d86;
}
.customer-account-page .cake-specifications,
.customer-account-page .quotation-panel,
.customer-account-page .payment-panel {
    padding-top: 22px;
}
.customer-account-page .cake-specifications h4,
.customer-account-page .quotation-panel h4,
.customer-account-page .payment-panel h4 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 17px;
    color: #592122;
}
.customer-account-page .cake-spec-grid,
.customer-account-page .quotation-grid,
.customer-account-page .payment-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}
.customer-account-page .cake-spec-grid strong,
.customer-account-page .quotation-grid strong,
.customer-account-page .payment-summary-grid strong {
    display: block;
    color: #421719;
    font-size: 14px;
    margin-top: 5px;
    overflow-wrap: anywhere;
}
.customer-account-page .cake-message {
    background: #fcf3f0;
    border-radius: 8px;
    padding: 12px 14px;
    margin-top: 14px;
}
.customer-account-page .cake-message span,
.customer-account-page .cake-message strong {
    display: block;
}
.customer-account-page .cake-message span {
    color: #947876;
    font-size: 12px;
    margin-bottom: 5px;
}
.customer-account-page .cake-message strong {
    font-size: 14px;
    overflow-wrap: anywhere;
}
.customer-account-page .quotation-panel,
.customer-account-page .payment-panel {
    border-top: 1px solid #f0dfda;
    margin-top: 22px;
}
.customer-account-page .quotation-response {
    background: #fcf3f0;
    border-radius: 10px;
    padding: 18px;
    margin-top: 20px;
}
.customer-account-page .quotation-response > p {
    color: #795b59;
    font-size: 13px;
}
.customer-account-page .quotation-buttons {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    flex-wrap: wrap;
}
.customer-account-page .quotation-buttons form {
    margin: 0;
}
.customer-account-page .accept-quotation-btn,
.customer-account-page .reject-quotation-btn {
    border: 0;
    border-radius: 7px;
    padding: 11px 16px;
    font-weight: 700;
    cursor: pointer;
    font-size: 13px;
}
.customer-account-page .accept-quotation-btn {
    background: #28643c;
    color: #fff;
}
.customer-account-page .reject-quotation-form {
    flex: 1 1 260px;
}
.customer-account-page .reject-quotation-form label {
    display: block;
    color: #795b59;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 6px;
}
.customer-account-page .reject-quotation-form textarea {
    width: 100%;
    border: 1px solid #e4cfca;
    border-radius: 7px;
    padding: 10px;
    resize: vertical;
    margin-bottom: 8px;
    font-size: 13px;
}
.customer-account-page .reject-quotation-btn {
    background: #a12c25;
    color: #fff;
}
.customer-account-page .response-message {
    padding: 13px 15px;
    border-radius: 8px;
    margin-top: 18px;
    font-size: 13px;
}
.customer-account-page .response-accepted {
    background: #e5f3e9;
    color: #28643c;
}
.customer-account-page .response-rejected {
    background: #fbe9e7;
    color: #a12c25;
}
.customer-account-page .response-message p {
    margin: 8px 0 0;
}
.customer-account-page .paid-amount {
    color: #28643c !important;
}
.customer-account-page .balance-amount {
    color: #a12c25 !important;
}
.customer-account-page .payment-history {
    margin-top: 20px;
    border-top: 1px dashed #e4cfca;
    padding-top: 15px;
}
.customer-account-page .payment-history h5 {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 12px;
}
.customer-account-page .payment-history-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 10px 0;
    border-bottom: 1px solid #f5e8e5;
}
.customer-account-page .payment-history-row div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.customer-account-page .payment-history-row div strong,
.customer-account-page .payment-history-row > strong {
    font-size: 13px;
}
.customer-account-page .payment-history-row span,
.customer-account-page .no-payment-message {
    color: #947876;
    font-size: 12px;
}
.customer-account-page .quotation-pending {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    background: #fcf3f0;
    border-radius: 10px;
    padding: 18px;
    margin-top: 20px;
    color: #793638;
}
.customer-account-page .quotation-pending > i {
    font-size: 22px;
}
.customer-account-page .quotation-pending p {
    color: #795b59;
    margin: 5px 0 0;
    font-size: 13px;
}
.customer-account-page .no-orders-card {
    text-align: center;
    padding: 45px 20px;
}
.customer-account-page .no-orders-card > i {
    font-size: 42px;
    color: #793638;
}
.customer-account-page .no-orders-card h3 {
    font-family: 'DM Serif Display', serif;
    margin: 12px 0 7px;
}
.customer-account-page .no-orders-card p {
    color: #795b59;
    margin-bottom: 18px;
}
.customer-account-page .account-alert {
    border-radius: 9px;
    padding: 14px 18px;
    margin-bottom: 20px;
    font-size: 14px;
}
.customer-account-page .account-alert-success {
    background: #e5f3e9;
    color: #28643c;
}
.customer-account-page .account-alert-error {
    background: #fbe4e2;
    color: #a12c25;
}
@media (max-width: 768px) {
    .customer-account-page .cake-spec-grid,
    .customer-account-page .quotation-grid,
    .customer-account-page .payment-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 576px) {
    .customer-account-page .account-card {
        padding: 22px 18px;
    }
    .customer-account-page .account-details,
    .customer-account-page .cake-spec-grid,
    .customer-account-page .quotation-grid,
    .customer-account-page .payment-summary-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .customer-account-page .customer-order-card {
        padding: 19px 16px;
    }
    .customer-account-page .order-status-group {
        align-items: flex-start;
    }
}
</style>
@endsection