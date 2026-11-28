@extends('layouts.admin.app')

@section('title', 'Customer Payment History')
@section('page-heading', 'Customer Payment History')

@section('content')
<style>
.customer-payment-history {
    --brown: #4A2616;
    --cream: #f9efe3;
    --paper: #fffaf4;
    --text: #4a2f26;
    --muted: #8d7468;
    --line: #ead8c8;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
}
.customer-payment-history * { box-sizing: border-box; }
.history-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.history-hero h2 { color: #fffaf4; font-size: 1.8rem; margin: 0 0 7px; }
.history-hero p { margin: 0; color: #f3dfd0; }
.history-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
}
.history-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.history-panel-header h3 { color: var(--brown); margin: 0 0 4px; font-size: 1.3rem; }
.history-panel-header p { color: var(--muted); margin: 0; font-size: .84rem; }
.history-back-link {
    display: inline-block;
    padding: 9px 14px;
    border-radius: 8px;
    background: var(--brown);
    color: white;
    text-decoration: none;
    font-size: .85rem;
    font-weight: 700;
}
.history-back-link:hover { color: white; background: #75452f; }
.history-filters { display: flex; gap: 10px; margin-bottom: 18px; }
.history-filters input {
    flex: 1;
    min-width: 0;
    background: white;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
}
.history-search-btn {
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: var(--brown);
    color: white;
    font-weight: 600;
    cursor: pointer;
}
.history-reset-btn {
    padding: 10px 14px;
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--brown);
    text-decoration: none;
    background: white;
    font-size: .88rem;
}
.history-table-wrap { overflow-x: auto; }
.history-table { width: 100%; border-collapse: collapse; min-width: 950px; }
.history-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .8rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.history-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
}
.history-table tbody tr:hover { background: #fff5eb; }
.history-invoice { color: var(--brown); font-weight: 800; }
.history-muted { color: var(--muted); font-size: .8rem; margin-top: 4px; }
.history-amount { color: #287348; font-weight: 800; white-space: nowrap; }
.history-method {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    background: #f0e5d9;
    color: #65412d;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
}
.history-empty { text-align: center; padding: 38px; color: var(--muted); }
.history-empty strong { display: block; color: var(--brown); font-size: 1.05rem; margin-bottom: 6px; }
.history-pagination { margin-top: 18px; }
@media(max-width: 768px) {
    .history-panel { padding: 15px; }
    .history-filters { flex-wrap: wrap; }
    .history-filters input { flex-basis: 100%; }
    .history-hero { padding: 22px; }
    .history-hero h2 { font-size: 1.45rem; }
}
</style>

<div class="customer-payment-history">
    <div class="history-hero">
        <h2>Customer Payment History</h2>
        <p>Review all recorded collections against customer invoices.</p>
    </div>

    <div class="history-panel">
        <div class="history-panel-header">
            <div>
                <h3>Recorded Payments</h3>
                <p>Payments recorded for customer-linked invoices.</p>
            </div>
            <a href="{{ route('admin.customer-payments.index') }}" class="history-back-link">
                Outstanding Invoices
            </a>
        </div>

        <form method="GET"
              action="{{ route('admin.customer-payments.history') }}"
              class="history-filters">
            <input type="text"
                   name="search"
                   placeholder="Search reference, invoice number, customer name or phone..."
                   value="{{ $search }}">
            <button type="submit" class="history-search-btn">Search</button>
            <a href="{{ route('admin.customer-payments.history') }}" class="history-reset-btn">Reset</a>
        </form>

        <div class="history-table-wrap">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Invoice Number</th>
                        <th>Customer</th>
                        <th>Branch</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : $payment->created_at?->format('d M Y, h:i A') ?? '—' }}
                            </td>
                            <td class="history-invoice">
                                {{ $payment->order?->order_number ?? '—' }}
                            </td>
                            <td>
                                <strong>{{ $payment->order?->customer?->name ?? $payment->order?->customer_name ?? '—' }}</strong>
                                <div class="history-muted">
                                    {{ $payment->order?->customer?->phone ?? $payment->order?->customer_phone ?? '—' }}
                                </div>
                            </td>
                            <td>{{ $payment->order?->branch?->name ?? '—' }}</td>
                            <td>
                                <span class="history-method">{{ $payment->payment_method }}</span>
                            </td>
                            <td>{{ $payment->payment_reference ?? $payment->transaction_id ?? '—' }}</td>
                            <td class="history-amount">
                                INR {{ number_format((float) $payment->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="history-empty">
                                <strong>No customer payments recorded yet</strong>
                                Recorded customer invoice collections will appear here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="history-pagination">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
