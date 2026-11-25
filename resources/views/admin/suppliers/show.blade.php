@extends('layouts.admin.app')

@section('title', 'Supplier Details')
@section('page-heading', 'Supplier Details')

@section('content')

<style>
.supplier-show-page {
    --brown: #4A2616;
    --paper: #fffaf4;
    --text: #4a2f26;
    --muted: #8d7468;
    --line: #ead8c8;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
}
.supplier-show-page * { box-sizing: border-box; }

.supplier-show-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: white;
    padding: 25px 28px;
    border-radius: 16px;
    margin-bottom: 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}
.supplier-show-hero h2 {
    color: white;
    margin: 0 0 6px;
    font-size: 1.7rem;
}
.supplier-show-hero p {
    color: #f3dfd0;
    margin: 0;
}
.supplier-show-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}
.supplier-show-btn {
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: .85rem;
}
.supplier-edit-btn {
    background: #fffaf4;
    color: #4A2616;
}
.supplier-back-btn {
    background: rgba(255,255,255,.15);
    color: white;
    border: 1px solid rgba(255,255,255,.3);
}
.supplier-show-btn:hover { opacity: .88; }

.supplier-profile-card {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
}
.supplier-profile-top {
    display: flex;
    align-items: center;
    gap: 17px;
    padding-bottom: 22px;
    border-bottom: 1px solid var(--line);
    margin-bottom: 22px;
}
.supplier-avatar {
    width: 65px;
    height: 65px;
    border-radius: 15px;
    background: #f0e1d2;
    color: var(--brown);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 800;
    flex-shrink: 0;
}
.supplier-profile-top h3 {
    color: var(--brown);
    margin: 0 0 5px;
    font-size: 1.35rem;
}
.supplier-profile-top p {
    color: var(--muted);
    margin: 0;
    font-size: .88rem;
}
.supplier-status {
    display: inline-block;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
    margin-top: 8px;
}
.supplier-status.active {
    color: #287348;
    background: #e3f3e8;
}
.supplier-status.inactive {
    color: #995044;
    background: #f8e5e1;
}
.supplier-section { margin-bottom: 25px; }
.supplier-section:last-child { margin-bottom: 0; }
.supplier-section h4 {
    color: var(--brown);
    font-size: 1.05rem;
    margin: 0 0 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--line);
}
.supplier-details-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px 18px;
}
.supplier-detail { min-width: 0; }
.supplier-detail.full { grid-column: 1 / -1; }
.supplier-detail span {
    display: block;
    color: var(--muted);
    font-size: .78rem;
    font-weight: 600;
    margin-bottom: 6px;
}
.supplier-detail strong {
    display: block;
    color: var(--text);
    font-size: .92rem;
    font-weight: 700;
    overflow-wrap: anywhere;
}
@media(max-width: 768px) {
    .supplier-details-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .supplier-show-hero { align-items: flex-start; flex-direction: column; }
}
@media(max-width: 480px) {
    .supplier-details-grid { grid-template-columns: 1fr; }
    .supplier-detail.full { grid-column: auto; }
    .supplier-profile-card { padding: 17px; }
}
</style>

<div class="supplier-show-page">

    <div class="supplier-show-hero">
        <div>
            <h2>Supplier Profile</h2>
            <p>View complete supplier business information.</p>
        </div>

        <div class="supplier-show-actions">
            <a href="{{ route('admin.suppliers.index') }}"
               class="supplier-show-btn supplier-back-btn">
                Back to List
            </a>

            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
               class="supplier-show-btn supplier-edit-btn">
                Edit Supplier
            </a>
        </div>
    </div>

    <div class="supplier-profile-card">

        <div class="supplier-profile-top">

            <div class="supplier-avatar">
                {{ strtoupper(substr($supplier->supplier_name, 0, 1)) }}
            </div>

            <div>
                <h3>{{ $supplier->supplier_name }}</h3>
                <p>Supplier Code: {{ $supplier->supplier_code }}</p>

                <span class="supplier-status {{ $supplier->status }}">
                    {{ ucfirst($supplier->status) }}
                </span>
            </div>

        </div>

        <!-- BASIC INFORMATION -->
        <div class="supplier-section">
            <h4>Basic Information</h4>

            <div class="supplier-details-grid">

                <div class="supplier-detail">
                    <span>Supplier Code</span>
                    <strong>{{ $supplier->supplier_code }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Supplier Name</span>
                    <strong>{{ $supplier->supplier_name }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Supplier Type</span>
                    <strong>{{ $supplier->supplier_type ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Status</span>
                    <strong>{{ ucfirst($supplier->status) }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Registered On</span>
                    <strong>{{ $supplier->created_at?->format('d M Y') ?? '—' }}</strong>
                </div>

            </div>
        </div>

        <!-- CONTACT INFORMATION -->
        <div class="supplier-section">
            <h4>Contact Information</h4>

            <div class="supplier-details-grid">

                <div class="supplier-detail">
                    <span>Contact Person</span>
                    <strong>{{ $supplier->contact_person ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Mobile Number</span>
                    <strong>{{ $supplier->phone }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Alternate Mobile</span>
                    <strong>{{ $supplier->alternate_phone ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Email Address</span>
                    <strong>{{ $supplier->email ?: '—' }}</strong>
                </div>

            </div>
        </div>

        <!-- TAX INFORMATION -->
        <div class="supplier-section">
            <h4>Business & Tax Information</h4>

            <div class="supplier-details-grid">

                <div class="supplier-detail">
                    <span>GST Number</span>
                    <strong>{{ $supplier->gst_number ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>PAN Number</span>
                    <strong>{{ $supplier->pan_number ?: '—' }}</strong>
                </div>

            </div>
        </div>

        <!-- ADDRESS -->
        <div class="supplier-section">
            <h4>Address Details</h4>

            <div class="supplier-details-grid">

                <div class="supplier-detail full">
                    <span>Business Address</span>
                    <strong>{{ $supplier->address ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>City</span>
                    <strong>{{ $supplier->city ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>State</span>
                    <strong>{{ $supplier->state ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Pincode</span>
                    <strong>{{ $supplier->pincode ?: '—' }}</strong>
                </div>

            </div>
        </div>

        <!-- PAYMENT -->
        <div class="supplier-section">
            <h4>Payment Information</h4>

            <div class="supplier-details-grid">

                <div class="supplier-detail">
                    <span>Payment Terms</span>
                    <strong>{{ $supplier->payment_terms ?: '—' }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Credit Limit</span>
                    <strong>₹{{ number_format((float) $supplier->credit_limit, 2) }}</strong>
                </div>

                <div class="supplier-detail">
                    <span>Last Updated</span>
                    <strong>{{ $supplier->updated_at?->format('d M Y') ?? '—' }}</strong>
                </div>

                <div class="supplier-detail full">
                    <span>Additional Notes</span>
                    <strong>{{ $supplier->notes ?: '—' }}</strong>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
