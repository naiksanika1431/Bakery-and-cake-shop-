@extends('layouts.admin.app')

@section('title', 'Customer Ledger')
@section('page-heading', 'Customer Ledger')

@section('content')
<style>
.customer-ledger-page {
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
.customer-ledger-page * { box-sizing: border-box; }
.ledger-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.ledger-hero h2 { color: #fffaf4; font-size: 1.8rem; margin: 0 0 7px; }
.ledger-hero p { margin: 0; color: #f3dfd0; }
.ledger-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
}
.ledger-panel-header { margin-bottom: 18px; }
.ledger-panel-header h3 { color: var(--brown); margin: 0 0 4px; font-size: 1.3rem; }
.ledger-panel-header p { color: var(--muted); margin: 0; font-size: .84rem; }
.ledger-filters { display: flex; gap: 10px; margin-bottom: 18px; }
.ledger-filters input {
    flex: 1;
    min-width: 0;
    background: white;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
}
.ledger-search-btn {
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: var(--brown);
    color: white;
    font-weight: 600;
    cursor: pointer;
}
.ledger-reset-btn {
    padding: 10px 14px;
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--brown);
    text-decoration: none;
    background: white;
    font-size: .88rem;
}
.ledger-table-wrap { overflow-x: auto; }
.ledger-table { width: 100%; border-collapse: collapse; min-width: 850px; }
.ledger-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .8rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.ledger-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
}
.ledger-table tbody tr:hover { background: #fff5eb; }
.ledger-amount { font-weight: 700; white-space: nowrap; }
.ledger-debit { color: #995044; }
.ledger-credit { color: #287348; }
.ledger-balance { color: var(--brown); font-weight: 800; }
.ledger-view {
    display: inline-block;
    padding: 7px 11px;
    border-radius: 7px;
    background: #f0e5d9;
    color: #65412d;
    text-decoration: none;
    font-size: .8rem;
    font-weight: 700;
}
.ledger-view:hover { color: var(--brown); background: #ead8c8; }
.ledger-empty { text-align: center; padding: 35px; color: var(--muted); }
.ledger-pagination { margin-top: 18px; }
@media(max-width: 768px) {
    .ledger-panel { padding: 15px; }
    .ledger-filters { flex-wrap: wrap; }
    .ledger-filters input { flex-basis: 100%; }
    .ledger-hero { padding: 22px; }
    .ledger-hero h2 { font-size: 1.45rem; }
}
</style>

<div class="customer-ledger-page">
    <div class="ledger-hero">
        <h2>Customer Ledger</h2>
        <p>Track customer sales, payments, and outstanding balances.</p>
    </div>

    <div class="ledger-panel">
        <div class="ledger-panel-header">
            <h3>Customer Account Summary</h3>
            <p>Business customers with recorded sales and their current ledger balances.</p>
        </div>

        <form method="GET"
              action="{{ route('admin.customer-ledger.index') }}"
              class="ledger-filters">
            <input type="text"
                   name="search"
                   placeholder="Search customer name, code or phone..."
                   value="{{ $search }}">
            <button type="submit" class="ledger-search-btn">Search</button>
            <a href="{{ route('admin.customer-ledger.index') }}" class="ledger-reset-btn">Reset</a>
        </form>

        <div class="ledger-table-wrap">
            <table class="ledger-table">
                <thead>
                    <tr>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Total Debit</th>
                        <th>Total Credit</th>
                        <th>Outstanding</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->customer_code ?? '—' }}</strong></td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone ?? '—' }}</td>
                            <td class="ledger-amount ledger-debit">
                                INR {{ number_format((float) $customer->ledger_debit, 2) }}
                            </td>
                            <td class="ledger-amount ledger-credit">
                                INR {{ number_format((float) $customer->ledger_credit, 2) }}
                            </td>
                            <td class="ledger-amount ledger-balance">
                                INR {{ number_format((float) $customer->outstanding_balance, 2) }}
                            </td>
                            <td>
                                <a href="{{ route('admin.customer-ledger.show', $customer->id) }}"
                                   class="ledger-view">View Ledger</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="ledger-empty">
                                No customers with sales found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ledger-pagination">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection

