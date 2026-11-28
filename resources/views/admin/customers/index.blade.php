@extends('layouts.admin.app')

@section('title', 'Customer Management')
@section('page-heading', 'Customer Management')

@section('content')

<style>
.customer-page {
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
.customer-page * { box-sizing: border-box; }

.customer-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.customer-hero h2 {
    color: #fffaf4;
    font-size: 1.8rem;
    margin: 0 0 7px;
}
.customer-hero p {
    margin: 0;
    color: #f3dfd0;
}

.customer-stats {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 13px;
    margin-bottom: 22px;
}
.customer-stat {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 18px;
}
.customer-stat span {
    color: var(--muted);
    font-size: .82rem;
    font-weight: 600;
}
.customer-stat h3 {
    color: var(--brown);
    font-size: 1.7rem;
    margin: 9px 0 0;
}

.customer-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 22px;
}
.customer-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}
.customer-panel-header h3 {
    color: var(--brown);
    margin: 0 0 4px;
    font-size: 1.3rem;
}
.customer-panel-header p {
    color: var(--muted);
    margin: 0;
    font-size: .84rem;
}
.customer-add-btn {
    background: linear-gradient(135deg, #75452f, #4A2616);
    color: white;
    padding: 10px 17px;
    border-radius: 9px;
    text-decoration: none;
    font-weight: 700;
    font-size: .88rem;
    white-space: nowrap;
}
.customer-add-btn:hover { color: white; opacity: .9; }

.customer-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
}
.customer-filters input,
.customer-filters select {
    background: white;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
    min-width: 0;
}
.customer-filters input { flex: 1; }
.customer-search-btn {
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: var(--brown);
    color: white;
    font-weight: 600;
    cursor: pointer;
}
.customer-reset-btn {
    padding: 10px 14px;
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--brown);
    text-decoration: none;
    background: white;
    font-size: .88rem;
}
.customer-table-wrap { overflow-x: auto; }
.customer-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
}
.customer-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .8rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.customer-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
}
.customer-table tbody tr:hover { background: #fff5eb; }

.customer-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
    white-space: nowrap;
}
.customer-status.active,
.customer-status.verified {
    color: #287348;
    background: #e3f3e8;
}
.customer-status.inactive,
.customer-status.unverified {
    color: #995044;
    background: #f8e5e1;
}
.customer-source {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 7px;
    background: #f0e5d9;
    color: #65412d;
    font-size: .74rem;
    font-weight: 700;
}
.customer-actions {
    display: flex;
    gap: 7px;
}
.customer-actions a {
    padding: 6px 9px;
    border-radius: 6px;
    text-decoration: none;
    font-size: .78rem;
    font-weight: 600;
}
.customer-view { background: #f0e5d9; color: #65412d; }
.customer-edit { background: #eee4f2; color: #684879; }
.customer-empty {
    text-align: center;
    padding: 35px;
    color: var(--muted);
}
.customer-pagination { margin-top: 18px; }
.customer-alert {
    border-radius: 9px;
    padding: 12px 16px;
    margin-bottom: 18px;
}
.customer-note {
    background: #f8f0e7;
    border: 1px solid var(--line);
    border-radius: 9px;
    padding: 12px 15px;
    color: var(--muted);
    font-size: .84rem;
    margin-bottom: 18px;
}

@media(max-width: 1100px) {
    .customer-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media(max-width: 768px) {
    .customer-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .customer-filters { flex-wrap: wrap; }
    .customer-filters input { flex-basis: 100%; }
    .customer-panel { padding: 15px; }
    .customer-panel-header { align-items: flex-start; }
}
@media(max-width: 480px) {
    .customer-stats { grid-template-columns: 1fr 1fr; }
    .customer-hero { padding: 22px; }
    .customer-hero h2 { font-size: 1.45rem; }
}
</style>

<div class="customer-page">

    <div class="customer-hero">
        <h2>Customer Management</h2>
        <p>Manage business customers and website registered customer accounts separately.</p>
    </div>

    <div class="customer-stats">
        <div class="customer-stat">
            <span>Business Customers</span>
            <h3>{{ $totalCustomers }}</h3>
        </div>

        <div class="customer-stat">
            <span>Active Business</span>
            <h3>{{ $activeCustomers }}</h3>
        </div>

        <div class="customer-stat">
            <span>Inactive Business</span>
            <h3>{{ $inactiveCustomers }}</h3>
        </div>

        <div class="customer-stat">
            <span>Website Accounts</span>
            <h3>{{ $totalWebsiteCustomers }}</h3>
        </div>

        <div class="customer-stat">
            <span>Verified Accounts</span>
            <h3>{{ $verifiedWebsiteCustomers }}</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success customer-alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger customer-alert">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- BUSINESS CUSTOMERS --}}
    <div class="customer-panel">

        <div class="customer-panel-header">
            <div>
                <h3>Business Customers</h3>
                <p>Customers registered through Admin and POS billing.</p>
            </div>

            @if(auth()->user()?->hasPermission('customers.create'))
                <a href="{{ route('admin.customers.create') }}" class="customer-add-btn">
                    + Add Customer
                </a>
            @endif
        </div>

        <form method="GET"
              action="{{ route('admin.customers.index') }}"
              class="customer-filters">

            <input type="text"
                   name="search"
                   placeholder="Search name, customer code, phone or email..."
                   value="{{ request('search') }}">

            <select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <input type="hidden" name="website_status" value="{{ request('website_status') }}">

            <button type="submit" class="customer-search-btn">Search</button>

            <a href="{{ route('admin.customers.index') }}" class="customer-reset-btn">Reset</a>
        </form>

        <div class="customer-table-wrap">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->customer_code ?? '—' }}</strong></td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email ?? '—' }}</td>
                            <td>{{ $customer->city ?? '—' }}</td>
                            <td><span class="customer-source">Admin / POS</span></td>
                            <td>
                                <span class="customer-status {{ $customer->is_active ? 'active' : 'inactive' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="customer-actions">
                                    @if(auth()->user()?->hasPermission('customers.view'))
                                        <a href="{{ route('admin.customers.show', $customer->id) }}"
                                           class="customer-view">View</a>
                                    @endif

                                    @if(auth()->user()?->hasPermission('customers.update'))
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                           class="customer-edit">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="customer-empty">
                                No business customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="customer-pagination">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- WEBSITE CUSTOMERS --}}
    <div class="customer-panel">

        <div class="customer-panel-header">
            <div>
                <h3>Website Registered Customers</h3>
                <p>Accounts created through the bakery customer website.</p>
            </div>
            <span class="customer-source">Website Accounts</span>
        </div>

        <form method="GET"
              action="{{ route('admin.customers.index') }}"
              class="customer-filters">

            <input type="text"
                   name="search"
                   placeholder="Search website customer name, phone or email..."
                   value="{{ request('search') }}">

            <select name="website_status">
                <option value="">All Verification Status</option>
                <option value="verified" {{ request('website_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="unverified" {{ request('website_status') == 'unverified' ? 'selected' : '' }}>Not Verified</option>
            </select>

            <input type="hidden" name="status" value="{{ request('status') }}">

            <button type="submit" class="customer-search-btn">Search</button>

            <a href="{{ route('admin.customers.index') }}" class="customer-reset-btn">Reset</a>
        </form>

        <div class="customer-table-wrap">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Email Verification</th>
                        <th>Registration Date</th>
                        <th>Source</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($websiteCustomers as $account)
                        <tr>
                            <td><strong>{{ $account->name }}</strong></td>
                            <td>{{ $account->email }}</td>
                            <td>{{ $account->phone }}</td>
                            <td>
                                <span class="customer-status {{ $account->email_verified_at ? 'verified' : 'unverified' }}">
                                    {{ $account->email_verified_at ? 'Verified' : 'Not Verified' }}
                                </span>
                            </td>
                            <td>{{ $account->created_at?->format('d M Y') ?? '—' }}</td>
                            <td><span class="customer-source">Website</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="customer-empty">
                                No website customer accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="customer-pagination">
            {{ $websiteCustomers->links() }}
        </div>
    </div>

    <div class="customer-note">
        Business customer records and website login accounts are displayed separately.
        They are not automatically merged based on matching phone numbers or email addresses.
    </div>

</div>

@endsection
