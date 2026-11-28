@extends('layouts.admin.app')

@section('title', 'Customer Ledger Details')
@section('page-heading', 'Customer Ledger Details')

@section('content')
<style>
.customer-ledger-detail {
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
.customer-ledger-detail * { box-sizing: border-box; }
.detail-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.detail-hero h2 { color: #fffaf4; font-size: 1.8rem; margin: 0 0 7px; }
.detail-hero p { margin: 0; color: #f3dfd0; }
.detail-back {
    display: inline-block;
    margin-bottom: 16px;
    color: var(--brown);
    text-decoration: none;
    font-weight: 700;
}
.detail-back:hover { color: var(--brown-light); }
.detail-customer {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 20px 22px;
    margin-bottom: 20px;
}
.detail-customer h3 { color: var(--brown); margin: 0 0 14px; font-size: 1.2rem; }
.customer-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
}
.customer-info-item span {
    display: block;
    color: var(--muted);
    font-size: .78rem;
    font-weight: 600;
    margin-bottom: 5px;
}
.customer-info-item strong { color: var(--text); font-size: .95rem; }
.detail-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}
.detail-stat {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 19px;
}
.detail-stat span { color: var(--muted); font-size: .82rem; font-weight: 600; }
.detail-stat h3 { color: var(--brown); font-size: 1.55rem; margin: 9px 0 0; }
.detail-stat.debit h3 { color: #995044; }
.detail-stat.credit h3 { color: #287348; }
.detail-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
}
.detail-panel-header { margin-bottom: 18px; }
.detail-panel-header h3 { color: var(--brown); margin: 0 0 4px; font-size: 1.3rem; }
.detail-panel-header p { color: var(--muted); margin: 0; font-size: .84rem; }
.detail-table-wrap { overflow-x: auto; }
.detail-table { width: 100%; border-collapse: collapse; min-width: 800px; }
.detail-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .8rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.detail-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
}
.detail-table tbody tr:hover { background: #fff5eb; }
.detail-debit { color: #995044; font-weight: 700; white-space: nowrap; }
.detail-credit { color: #287348; font-weight: 700; white-space: nowrap; }
.detail-empty { text-align: center; padding: 35px; color: var(--muted); }
.detail-pagination { margin-top: 18px; }
.detail-note {
    background: #f8f0e7;
    border: 1px solid var(--line);
    border-radius: 9px;
    padding: 12px 15px;
    color: var(--muted);
    font-size: .84rem;
    margin-bottom: 18px;
}
@media(max-width: 768px) {
    .customer-info-grid, .detail-stats { grid-template-columns: 1fr 1fr; }
    .detail-panel { padding: 15px; }
    .detail-hero { padding: 22px; }
    .detail-hero h2 { font-size: 1.45rem; }
}
@media(max-width: 480px) {
    .customer-info-grid, .detail-stats { grid-template-columns: 1fr; }
}
</style>

<div class="customer-ledger-detail">
    <a href="{{ route('admin.customer-ledger.index') }}" class="detail-back">
        ? Back to Customer Ledger
    </a>

    <div class="detail-hero">
        <h2>Customer Ledger</h2>
        <p>Complete account statement and transaction history.</p>
    </div>

    <div class="detail-customer">
        <h3>Customer Information</h3>
        <div class="customer-info-grid">
            <div class="customer-info-item">
                <span>Customer Name</span>
                <strong>{{ $customer->name }}</strong>
            </div>
            <div class="customer-info-item">
                <span>Customer Code</span>
                <strong>{{ $customer->customer_code ?? '—' }}</strong>
            </div>
            <div class="customer-info-item">
                <span>Phone</span>
                <strong>{{ $customer->phone ?? '—' }}</strong>
            </div>
            <div class="customer-info-item">
                <span>Email</span>
                <strong>{{ $customer->email ?? '—' }}</strong>
            </div>
            <div class="customer-info-item">
                <span>Company</span>
                <strong>{{ $company->company_name }}</strong>
            </div>
        </div>
    </div>

    <div class="detail-stats">
        <div class="detail-stat debit">
            <span>Total Debit</span>
            <h3>INR {{ number_format((float) $totalDebit, 2) }}</h3>
        </div>
        <div class="detail-stat credit">
            <span>Total Credit</span>
            <h3>INR {{ number_format((float) $totalCredit, 2) }}</h3>
        </div>
        <div class="detail-stat">
            <span>Outstanding Balance</span>
            <h3>INR {{ number_format((float) $balance, 2) }}</h3>
        </div>
    </div>

    <div class="detail-panel">
        <div class="detail-panel-header">
            <h3>Transaction History</h3>
            <p>All ledger entries available within your authorized branch scope.</p>
        </div>

        <div class="detail-note">
            Debit represents amounts charged to the customer. Credit represents payments received.
        </div>

        <div class="detail-table-wrap">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference Type</th>
                        <th>Reference ID</th>
                        <th>Branch</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $runningBalance = 0; @endphp

                    @forelse($entries as $entry)
                        @php
                            $runningBalance += (float) $entry->debit - (float) $entry->credit;
                        @endphp
                        <tr>
                            <td>{{ $entry->date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $entry->reference_type)) }}</td>
                            <td>#{{ $entry->reference_id }}</td>
                            <td>{{ $entry->branch?->branch_name ?? '—' }}</td>
                            <td class="detail-debit">
                                INR {{ number_format((float) $entry->debit, 2) }}
                            </td>
                            <td class="detail-credit">
                                INR {{ number_format((float) $entry->credit, 2) }}
                            </td>
                            <td><strong>INR {{ number_format($runningBalance, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="detail-empty">
                                No ledger transactions recorded for this customer yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="detail-pagination">
            {{ $entries->links() }}
        </div>
    </div>
</div>
@endsection

