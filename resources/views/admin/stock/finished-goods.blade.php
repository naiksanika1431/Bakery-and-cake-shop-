@extends('layouts.admin.app')

@section('title', 'Finished Goods Stock')
@section('page-heading', 'Finished Goods Stock')

@section('content')

<style>
    .fg-page {
        --brown: #4A2616;
        --brown-light: #75452f;
        --cream: #f9efe3;
        --paper: #fffaf4;
        --text: #4a2f26;
        --muted: #8d7468;
        --line: #ead9ca;
        color: var(--text);
    }

    .fg-page .fg-hero {
        background: linear-gradient(120deg, #75452f, #3b2015);
        color: #fffaf4;
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 8px 20px rgba(74, 38, 22, .12);
    }

    .fg-page .fg-hero h2 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .fg-page .fg-hero p {
        margin: 0;
        color: #f0ded0;
        font-size: 14px;
    }

    .fg-page .fg-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 18px 20px;
        height: 100%;
        box-shadow: 0 4px 12px rgba(74, 38, 22, .04);
    }

    .fg-page .fg-card-label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .fg-page .fg-card-value {
        color: var(--brown);
        font-size: 26px;
        font-weight: 800;
        margin-top: 6px;
    }

    .fg-page .fg-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 11px;
        background: var(--cream);
        color: var(--brown);
        font-size: 18px;
    }

    .fg-page .fg-panel {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .fg-page .fg-panel-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--line);
        background: #fdf8f3;
    }

    .fg-page .fg-panel-header h5 {
        color: var(--brown);
        font-weight: 700;
        margin: 0 0 3px;
        font-size: 17px;
    }

    .fg-page .fg-panel-header small {
        color: var(--muted);
        font-size: 13px;
    }

    .fg-page .form-control,
    .fg-page .form-select {
        border: 1px solid var(--line);
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13.5px;
        background-color: #fff;
    }

    .fg-page .form-control:focus,
    .fg-page .form-select:focus {
        border-color: var(--brown-light);
        box-shadow: 0 0 0 3px rgba(117, 69, 47, .12);
    }

    .fg-page .btn-filter {
        background: var(--brown);
        color: white;
        border: 0;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13.5px;
        font-weight: 600;
    }

    .fg-page .btn-filter:hover {
        background: var(--brown-light);
        color: white;
    }

    .fg-page .btn-reset {
        background: var(--cream);
        color: var(--brown);
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .fg-page .table {
        margin: 0;
        vertical-align: middle;
    }

    .fg-page .table thead th {
        background: #f6eadf;
        color: #6b4a3a;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 13px 15px;
        border-bottom: 1px solid var(--line);
        white-space: nowrap;
    }

    .fg-page .table tbody td {
        padding: 14px 15px;
        border-color: #f0e5dc;
        font-size: 13.5px;
        color: var(--text);
    }

    .fg-page .product-name {
        font-weight: 700;
        color: var(--brown);
        font-size: 14px;
    }

    .fg-page .product-sku {
        color: var(--muted);
        font-size: 11.5px;
        margin-top: 2px;
    }

    .fg-page .stock-number {
        font-weight: 700;
        font-size: 14.5px;
    }

    .fg-page .stock-badge {
        display: inline-block;
        border-radius: 20px;
        padding: 4px 11px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .fg-page .badge-in {
        background: #e5f3e8;
        color: #26713b;
    }

    .fg-page .badge-low {
        background: #fff0d7;
        color: #98610c;
    }

    .fg-page .badge-out {
        background: #fbe5e2;
        color: #b23b30;
    }

    .fg-page .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: var(--muted);
    }

    .fg-page .empty-state i {
        font-size: 38px;
        color: #b99882;
        display: block;
        margin-bottom: 14px;
    }

    .fg-page .request-form {
        min-width: 240px;
        max-width: 280px;
    }

    .fg-page .request-form summary {
        list-style: none;
        cursor: pointer;
        display: inline-block;
    }

    .fg-page .request-form summary::-webkit-details-marker {
        display: none;
    }

    .fg-page .request-form[open] summary {
        margin-bottom: 12px;
    }

    .fg-page .request-label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--brown);
    }

    .fg-page .request-form textarea {
        resize: vertical;
    }

    .fg-page .section-note {
        color: var(--muted);
        font-size: 13px;
    }

    .fg-page .help-text {
        font-size: 12.5px;
        color: var(--muted);
        margin-top: 4px;
    }

    @media (max-width: 767px) {
        .fg-page .fg-hero {
            padding: 20px;
        }
        .fg-page .fg-hero h2 {
            font-size: 20px;
        }
    }
</style>

<div class="fg-page">

    {{-- ==================== HERO ==================== --}}
    <div class="fg-hero">
        <h2><i class="bi bi-box-seam me-2"></i>Finished Goods Stock</h2>
        <p>View and manage ready-to-sell products stock across all branches</p>
    </div>

    {{-- ==================== FLASH MESSAGES ==================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ==================== SUMMARY CARDS ==================== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="fg-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fg-card-label">Total Products</div>
                        <div class="fg-card-value">{{ number_format($totalProducts) }}</div>
                    </div>
                    <div class="fg-icon"><i class="bi bi-boxes"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="fg-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fg-card-label">Total Available Quantity</div>
                        <div class="fg-card-value">{{ number_format($totalStock, 3) }}</div>
                    </div>
                    <div class="fg-icon"><i class="bi bi-clipboard-data"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== INVENTORY TABLE ==================== --}}
    <div class="fg-panel">

        <div class="fg-panel-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h5>Product Stock List</h5>
                    <small>Current stock of finished goods by branch</small>
                </div>
            </div>

            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.stock.finished-goods') }}" class="row g-2 mt-3">
                <div class="col-md-5">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by product name or SKU..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="in_stock" @selected(request('status') === 'in_stock')>In Stock</option>
                        <option value="low_stock" @selected(request('status') === 'low_stock')>Low Stock</option>
                        <option value="out_of_stock" @selected(request('status') === 'out_of_stock')>Out of Stock</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn-filter flex-grow-1">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    <a href="{{ route('admin.stock.finished-goods') }}" class="btn-reset">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Available</th>
                        <th>Min. Stock</th>
                        <th>Unit</th>
                        <th>Status</th>
                        @if($canRequestStock)
                            <th style="min-width: 160px;">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($stockItems as $index => $item)
                        @php
                            $quantity = (float) $item->stock_quantity;
                            $minimum  = (float) $item->min_stock;

                            if ($quantity <= 0) {
                                $status = 'Out of Stock';
                                $badge  = 'badge-out';
                            } elseif ($quantity <= $minimum) {
                                $status = 'Low Stock';
                                $badge  = 'badge-low';
                            } else {
                                $status = 'In Stock';
                                $badge  = 'badge-in';
                            }

                            $product = $item->productVariant?->product;
                        @endphp

                        <tr>
                            <td>{{ $stockItems->firstItem() + $index }}</td>

                            <td>
                                <div class="product-name">{{ $product?->name ?? 'Unknown Product' }}</div>
                                <div class="product-sku">SKU: {{ $item->productVariant?->sku ?? 'N/A' }}</div>
                            </td>

                            <td>{{ $product?->category?->name ?? '—' }}</td>
                            <td>{{ $item->branch?->branch_name ?? '—' }}</td>

                            <td>
                                <span class="stock-number">{{ number_format($quantity, 3) }}</span>
                            </td>

                            <td>{{ number_format($minimum, 3) }}</td>

                            <td>{{ $item->productVariant?->unit?->abbreviation ?? '—' }}</td>

                            <td>
                                <span class="stock-badge {{ $badge }}">{{ $status }}</span>
                            </td>

                            @if($canRequestStock)
                                <td>
                                    <details class="request-form">
                                        <summary class="btn-filter">
                                            <i class="bi bi-pencil-square me-1"></i> Adjust
                                        </summary>

                                        <form method="POST"
                                              action="{{ route('admin.stock.finished-goods.adjustments.store') }}"
                                              class="mt-2">
                                            @csrf
                                            <input type="hidden" name="branch_product_id" value="{{ $item->id }}">

                                            <label class="request-label">What do you want to do?</label>
                                            <select name="adjustment_type" class="form-select mb-2" required>
                                                <option value="add">Add Stock (+)</option>
                                                <option value="remove">Remove Stock (−)</option>
                                                <option value="set">Set Exact Quantity</option>
                                            </select>

                                            <label class="request-label">Quantity</label>
                                            <input type="number"
                                                   name="quantity"
                                                   class="form-control mb-2"
                                                   min="0.001"
                                                   step="0.001"
                                                   placeholder="e.g. 10.500"
                                                   required>

                                            <label class="request-label">Reason</label>
                                            <textarea name="reason"
                                                      class="form-control mb-2"
                                                      rows="2"
                                                      maxlength="2000"
                                                      placeholder="Why are you adjusting the stock?"
                                                      required></textarea>

                                            <button type="submit" class="btn-filter w-100">
                                                Submit Request
                                            </button>
                                            <div class="help-text mt-1">
                                                This request needs approval before stock changes.
                                            </div>
                                        </form>
                                    </details>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canRequestStock ? 9 : 8 }}">
                                <div class="empty-state">
                                    <i class="bi bi-inboxes"></i>
                                    <h6 class="fw-bold mb-1">No products found</h6>
                                    <p class="mb-0">Try changing the search or filter options.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stockItems->hasPages())
            <div class="p-3 border-top">
                {{ $stockItems->links() }}
            </div>
        @endif
    </div>

    {{-- ==================== ADJUSTMENT REQUESTS ==================== --}}
    <div class="fg-panel">

        <div class="fg-panel-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5><i class="bi bi-clipboard-check me-2"></i>Stock Adjustment Requests</h5>
                    <small class="section-note">All pending and completed stock change requests</small>
                </div>

                <span class="stock-badge badge-low">
                    {{ $adjustmentRequests->where('status', 'pending')->count() }} Pending
                </span>
            </div>
        </div>

        @if($adjustmentRequests->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h6 class="fw-bold mb-1">No requests yet</h6>
                <p class="mb-0">When someone requests a stock change, it will appear here.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Branch</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Reviewed By</th>
                            @if($canApproveStock)
                                <th style="min-width: 180px;">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adjustmentRequests as $adjustment)
                            @php
                                $adjProduct = $adjustment->branchProduct?->productVariant?->product;
                                $typeLabel = match($adjustment->adjustment_type ?? 'add') {
                                    'add'    => 'Add (+)',
                                    'remove' => 'Remove (−)',
                                    'set'    => 'Set Exact',
                                    default  => 'Add (+)',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <div class="product-name">{{ $adjProduct?->name ?? 'Unknown' }}</div>
                                    <div class="product-sku">
                                        SKU: {{ $adjustment->branchProduct?->productVariant?->sku ?? 'N/A' }}
                                    </div>
                                </td>

                                <td>{{ $adjustment->branchProduct?->branch?->branch_name ?? '—' }}</td>

                                <td>
                                    <span class="stock-badge 
                                        {{ ($adjustment->adjustment_type ?? 'add') === 'remove' ? 'badge-out' : 
                                           (($adjustment->adjustment_type ?? 'add') === 'set' ? 'badge-low' : 'badge-in') }}">
                                        {{ $typeLabel }}
                                    </span>
                                </td>

                                <td class="fw-bold">{{ number_format((float) $adjustment->quantity, 3) }}</td>

                                <td style="max-width: 180px; white-space: normal;">
                                    {{ $adjustment->reason }}
                                </td>

                                <td>{{ $adjustment->requester?->name ?? 'Unknown' }}</td>

                                <td>{{ $adjustment->created_at?->format('d M Y, h:i A') ?? '—' }}</td>

                                <td>
                                    @if($adjustment->status === 'pending')
                                        <span class="stock-badge badge-low">Pending</span>
                                    @elseif($adjustment->status === 'approved')
                                        <span class="stock-badge badge-in">Approved</span>
                                    @else
                                        <span class="stock-badge badge-out">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    <div>{{ $adjustment->approver?->name ?? '—' }}</div>
                                    @if($adjustment->approval_remarks)
                                        <small class="text-muted d-block">{{ $adjustment->approval_remarks }}</small>
                                    @endif
                                    @if($adjustment->reviewed_at)
                                        <small class="text-muted d-block">
                                            {{ $adjustment->reviewed_at->format('d M Y, h:i A') }}
                                        </small>
                                    @endif
                                </td>

                                @if($canApproveStock)
                                    <td>
                                        @if($adjustment->status === 'pending' && (int) $adjustment->requested_by !== (int) auth()->id())
                                            {{-- Approve --}}
                                            <form method="POST"
                                                  action="{{ route('admin.stock.finished-goods.adjustments.approve', $adjustment) }}"
                                                  class="mb-2">
                                                @csrf
                                                <input type="text"
                                                       name="approval_remarks"
                                                       class="form-control form-control-sm mb-1"
                                                       maxlength="1000"
                                                       placeholder="Optional note">
                                                <button type="submit"
                                                        class="btn btn-sm btn-success w-100"
                                                        onclick="return confirm('Approve this request and update stock?')">
                                                    <i class="bi bi-check-circle me-1"></i> Approve
                                                </button>
                                            </form>

                                            {{-- Reject --}}
                                            <form method="POST"
                                                  action="{{ route('admin.stock.finished-goods.adjustments.reject', $adjustment) }}">
                                                @csrf
                                                <textarea name="approval_remarks"
                                                          class="form-control form-control-sm mb-1"
                                                          rows="2"
                                                          maxlength="1000"
                                                          placeholder="Reason for rejection"
                                                          required></textarea>
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger w-100"
                                                        onclick="return confirm('Reject this request?')">
                                                    <i class="bi bi-x-circle me-1"></i> Reject
                                                </button>
                                            </form>
                                        @elseif($adjustment->status === 'pending')
                                            <small class="text-muted">You cannot review your own request</small>
                                        @else
                                            <small class="text-muted">Already reviewed</small>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection