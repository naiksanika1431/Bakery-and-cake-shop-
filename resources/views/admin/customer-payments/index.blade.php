@extends('layouts.admin.app')

@section('title', 'Customer Payments')
@section('page-heading', 'Customer Payments')

@section('content')
<style>
.customer-payments-page {
    --brown: #4A2616;
    --brown-light: #75452f;
    --cream: #f9efe3;
    --paper: #fffaf4;
    --text: #4a2f26;
    --muted: #8d7468;
    --line: #ead8c8;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
}
.customer-payments-page * { box-sizing: border-box; }
.payments-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.payments-hero h2 { color: #fffaf4; font-size: 1.8rem; margin: 0 0 7px; }
.payments-hero p { margin: 0; color: #f3dfd0; }
.payments-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
}
.payments-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}
.payments-panel-header h3 { color: var(--brown); margin: 0 0 4px; font-size: 1.3rem; }
.payments-panel-header p { color: var(--muted); margin: 0; font-size: .84rem; }
.payments-history-link {
    display: inline-block;
    padding: 9px 14px;
    border-radius: 8px;
    background: var(--brown);
    color: white;
    text-decoration: none;
    font-size: .85rem;
    font-weight: 700;
}
.payments-history-link:hover { color: white; background: var(--brown-light); }
.payments-filters { display: flex; gap: 10px; margin-bottom: 18px; }
.payments-filters input {
    flex: 1;
    min-width: 0;
    background: white;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
}
.payments-search-btn {
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: var(--brown);
    color: white;
    font-weight: 600;
    cursor: pointer;
}
.payments-reset-btn {
    padding: 10px 14px;
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--brown);
    text-decoration: none;
    background: white;
    font-size: .88rem;
}
.payments-table-wrap { overflow-x: auto; }
.payments-table { width: 100%; border-collapse: collapse; min-width: 1050px; }
.payments-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .8rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.payments-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
    vertical-align: top;
}
.payments-table tbody tr:hover { background: #fff5eb; }
.payment-invoice { color: var(--brown); font-weight: 800; }
.payment-muted { color: var(--muted); font-size: .8rem; margin-top: 4px; }
.payment-outstanding { color: #995044; font-weight: 800; white-space: nowrap; }
.payment-form { min-width: 270px; display: grid; gap: 7px; }
.payment-form input, .payment-form select, .payment-form textarea {
    width: 100%;
    border: 1px solid var(--line);
    border-radius: 7px;
    padding: 8px 9px;
    color: var(--text);
    background: white;
    font: inherit;
    font-size: .82rem;
}
.payment-form textarea { resize: vertical; min-height: 52px; }
.payment-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 7px; }
.payment-submit {
    border: 0;
    border-radius: 7px;
    padding: 9px 12px;
    background: #287348;
    color: white;
    font-weight: 700;
    cursor: pointer;
}
.payment-submit:hover { background: #205c3b; }
.payment-empty { text-align: center; padding: 38px; color: var(--muted); }
.payment-empty strong { display: block; color: var(--brown); font-size: 1.05rem; margin-bottom: 6px; }
.payment-pagination { margin-top: 18px; }
.payment-alert {
    padding: 12px 15px;
    border-radius: 9px;
    margin-bottom: 16px;
    font-size: .9rem;
}
.payment-alert-success { background: #e8f5ec; color: #23613b; border: 1px solid #c6e5d0; }
.payment-alert-error { background: #fff0ed; color: #994536; border: 1px solid #f0c9c0; }
@media(max-width: 768px) {
    .payments-panel { padding: 15px; }
    .payments-filters { flex-wrap: wrap; }
    .payments-filters input { flex-basis: 100%; }
    .payments-hero { padding: 22px; }
    .payments-hero h2 { font-size: 1.45rem; }
}
</style>

<div class="customer-payments-page">
    <div class="payments-hero">
        <h2>Customer Payments</h2>
        <p>Collect outstanding amounts against customer invoices and maintain accurate payment records.</p>
    </div>

    @if(session('success'))
        <div class="payment-alert payment-alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="payment-alert payment-alert-error">
            <strong>Please correct the following:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="payments-panel">
        <div class="payments-panel-header">
            <div>
                <h3>Outstanding Customer Invoices</h3>
                <p>Record a collection against an invoice with a pending or partial payment.</p>
            </div>
            <a href="{{ route('admin.customer-payments.history') }}" class="payments-history-link">
                Payment History
            </a>
        </div>

        <form method="GET"
              action="{{ route('admin.customer-payments.index') }}"
              class="payments-filters">
            <input type="text"
                   name="search"
                   placeholder="Search invoice number, customer name or phone..."
                   value="{{ $search }}">
            <button type="submit" class="payments-search-btn">Search</button>
            <a href="{{ route('admin.customer-payments.index') }}" class="payments-reset-btn">Reset</a>
        </form>

        <div class="payments-table-wrap">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Branch</th>
                        <th>Invoice Amount</th>
                        <th>Paid Amount</th>
                        <th>Outstanding</th>
                        <th>Record Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <div class="payment-invoice">{{ $order->order_number }}</div>
                                <div class="payment-muted">
                                    {{ $order->placed_at ? $order->placed_at->format('d M Y') : '—' }}
                                </div>
                            </td>
                            <td>
                                <strong>{{ $order->customer?->name ?? $order->customer_name ?? '—' }}</strong>
                                <div class="payment-muted">{{ $order->customer?->phone ?? $order->customer_phone ?? '—' }}</div>
                            </td>
                            <td>{{ $order->branch?->name ?? '—' }}</td>
                            <td>INR {{ number_format((float) $order->grand_total, 2) }}</td>
                            <td>INR {{ number_format((float) $order->total_paid, 2) }}</td>
                            <td class="payment-outstanding">
                                INR {{ number_format((float) $order->outstanding, 2) }}
                            </td>
                            <td>
                                <form method="POST"
                                      action="{{ route('admin.customer-payments.store') }}"
                                      class="payment-form">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                                    <input type="number"
                                           name="amount"
                                           min="0.01"
                                           max="{{ number_format((float) $order->outstanding, 2, '.', '') }}"
                                           step="0.01"
                                           placeholder="Amount (INR)"
                                           value="{{ old('order_id') == $order->id ? old('amount') : '' }}"
                                           required>

                                    <div class="payment-form-row">
                                        <select name="payment_method" required>
                                            <option value="">Payment method</option>
                                            <option value="cash" @selected(old('order_id') == $order->id && old('payment_method') === 'cash')>Cash</option>
                                            <option value="upi" @selected(old('order_id') == $order->id && old('payment_method') === 'upi')>UPI</option>
                                            <option value="card" @selected(old('order_id') == $order->id && old('payment_method') === 'card')>Card</option>
                                        </select>
                                        <input type="date"
                                               name="date"
                                               value="{{ old('order_id') == $order->id ? old('date') : now()->format('Y-m-d') }}"
                                               required>
                                    </div>

                                    <input type="text"
                                           name="payment_reference"
                                           placeholder="Transaction/reference number (optional)"
                                           value="{{ old('order_id') == $order->id ? old('payment_reference') : '' }}">

                                    <textarea name="notes"
                                              placeholder="Notes (optional)">{{ old('order_id') == $order->id ? old('notes') : '' }}</textarea>

                                    <button type="submit" class="payment-submit">Record Payment</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="payment-empty">
                                <strong>No outstanding customer invoices</strong>
                                There are currently no customer invoices with pending or partial payments.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="payment-pagination">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
