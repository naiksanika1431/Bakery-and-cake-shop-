@extends('layouts.admin.app')

@section('title', 'Supplier Management')
@section('page-heading', 'Supplier Management')

@section('content')

<style>
.supplier-page {
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
.supplier-page * { box-sizing: border-box; }

.supplier-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.supplier-hero h2 {
    color: #fffaf4;
    font-size: 1.8rem;
    margin: 0 0 7px;
}
.supplier-hero p {
    margin: 0;
    color: #f3dfd0;
}

.supplier-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}
.supplier-stat {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 20px;
}
.supplier-stat span {
    color: var(--muted);
    font-size: .85rem;
    font-weight: 600;
}
.supplier-stat h3 {
    color: var(--brown);
    font-size: 1.8rem;
    margin: 9px 0 0;
}

.supplier-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
}
.supplier-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}
.supplier-panel-header h3 {
    color: var(--brown);
    margin: 0;
    font-size: 1.35rem;
}
.supplier-add-btn {
    background: linear-gradient(135deg, #75452f, #4A2616);
    color: white;
    padding: 10px 17px;
    border-radius: 9px;
    text-decoration: none;
    font-weight: 700;
    font-size: .88rem;
}
.supplier-add-btn:hover { color: white; opacity: .9; }

.supplier-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
}
.supplier-filters input,
.supplier-filters select {
    background: white;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
    min-width: 0;
}
.supplier-filters input { flex: 1; }
.supplier-search-btn {
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: var(--brown);
    color: white;
    font-weight: 600;
}
.supplier-table-wrap { overflow-x: auto; }
.supplier-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
}
.supplier-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .8rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.supplier-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .88rem;
}
.supplier-table tbody tr:hover { background: #fff5eb; }

.supplier-status {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
}
.supplier-status.active {
    color: #287348;
    background: #e3f3e8;
}
.supplier-status.inactive {
    color: #995044;
    background: #f8e5e1;
}
.supplier-actions {
    display: flex;
    gap: 7px;
}
.supplier-actions a {
    padding: 6px 9px;
    border-radius: 6px;
    text-decoration: none;
    font-size: .78rem;
    font-weight: 600;
}
.supplier-view { background: #f0e5d9; color: #65412d; }
.supplier-edit { background: #eee4f2; color: #684879; }
.supplier-empty {
    text-align: center;
    padding: 35px;
    color: var(--muted);
}
.supplier-pagination { margin-top: 18px; }

@media(max-width: 768px) {
    .supplier-stats { grid-template-columns: 1fr; }
    .supplier-filters { flex-wrap: wrap; }
    .supplier-filters input { flex-basis: 100%; }
    .supplier-panel { padding: 15px; }
}
</style>

<div class="supplier-page">

    <div class="supplier-hero">
        <h2>Supplier Management</h2>
        <p>Manage your bakery suppliers, contact details and supplier records.</p>
    </div>

    <div class="supplier-stats">
        <div class="supplier-stat">
            <span>Total Suppliers</span>
            <h3>{{ $totalSuppliers }}</h3>
        </div>

        <div class="supplier-stat">
            <span>Active Suppliers</span>
            <h3>{{ $activeSuppliers }}</h3>
        </div>

        <div class="supplier-stat">
            <span>Inactive Suppliers</span>
            <h3>{{ $totalSuppliers - $activeSuppliers }}</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="supplier-panel">

        <div class="supplier-panel-header">
            <h3>Supplier List</h3>

            <a href="{{ route('admin.suppliers.create') }}"
               class="supplier-add-btn">
                + Add Supplier
            </a>
        </div>

        <form method="GET"
              action="{{ route('admin.suppliers.index') }}"
              class="supplier-filters">

            <input type="text"
                   name="search"
                   placeholder="Search supplier name, code or phone..."
                   value="{{ request('search') }}">

            <select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            <button type="submit" class="supplier-search-btn">
                Search
            </button>
        </form>

        <div class="supplier-table-wrap">
            <table class="supplier-table">
                <thead>
                    <tr>
                        <th>Supplier Code</th>
                        <th>Supplier Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Supplier Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->supplier_code }}</td>

                            <td>
                                <strong>{{ $supplier->supplier_name }}</strong>
                            </td>

                            <td>{{ $supplier->contact_person ?? '—' }}</td>

                            <td>{{ $supplier->phone }}</td>

                            <td>{{ $supplier->supplier_type ?? '—' }}</td>

                            <td>
                                <span class="supplier-status {{ $supplier->status }}">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="supplier-actions">
                                    <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                       class="supplier-view">
                                        View
                                    </a>

                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                       class="supplier-edit">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="supplier-empty">
                                No suppliers found. Click Add Supplier to register your first supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="supplier-pagination">
            {{ $suppliers->links() }}
        </div>

    </div>
</div>

@endsection
