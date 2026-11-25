@extends('layouts.admin.app')

@section('title', 'Add Supplier Purchase')
@section('page-heading', 'Add Supplier Purchase')

@section('content')

<style>
    .purchase-create {
        --brown: #4A2616;
        --cream: #f9efe3;
        --paper: #fffaf4;
        color: #38271f;
    }

    .purchase-create .panel {
        background: var(--paper);
        border: 1px solid #ead9c9;
        border-radius: 13px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .purchase-create .section-title {
        color: var(--brown);
        font-weight: 700;
        font-size: 17px;
        margin-bottom: 18px;
    }

    .purchase-create label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
        color: #604332;
    }

    .purchase-create .form-control,
    .purchase-create .form-select {
        border: 1px solid #dfcbb9;
        border-radius: 8px;
        padding: 10px 12px;
        background: #fffefa;
    }

    .purchase-create .form-control:focus,
    .purchase-create .form-select:focus {
        border-color: #75452f;
        box-shadow: 0 0 0 3px rgba(117,69,47,.10);
    }

    .purchase-create .item-table {
        min-width: 950px;
        width: 100%;
        border-collapse: collapse;
    }

    .purchase-create .item-table th {
        background: var(--cream);
        color: var(--brown);
        font-size: 12px;
        padding: 12px 9px;
        white-space: nowrap;
    }

    .purchase-create .item-table td {
        padding: 9px;
        border-bottom: 1px solid #eee2d7;
        vertical-align: middle;
    }

    .purchase-create .item-table input,
    .purchase-create .item-table select {
        min-width: 85px;
        font-size: 13px;
    }

    .purchase-create .item-table .product-select {
        min-width: 220px;
    }

    .purchase-create .add-item-btn {
        background: #75452f;
        color: white;
        border: 0;
        border-radius: 8px;
        padding: 9px 15px;
    }

    .purchase-create .remove-item-btn {
        background: #f7dddd;
        color: #9b3030;
        border: 0;
        border-radius: 7px;
        padding: 7px 11px;
    }

    .purchase-create .totals {
        max-width: 380px;
        margin-left: auto;
        background: #f9efe3;
        padding: 18px;
        border-radius: 10px;
    }

    .purchase-create .total-row {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        font-size: 14px;
    }

    .purchase-create .grand-total {
        border-top: 1px solid #d9c2ad;
        margin-top: 8px;
        padding-top: 13px;
        font-weight: 700;
        font-size: 19px;
        color: var(--brown);
    }

    .purchase-create .save-btn {
        background: var(--brown);
        color: white;
        border: 0;
        padding: 11px 22px;
        border-radius: 8px;
        font-weight: 600;
    }

    .purchase-create .cancel-btn {
        border: 1px solid #cdb5a2;
        color: var(--brown);
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
    }

    @media(max-width: 768px) {
        .purchase-create .panel {
            padding: 15px;
        }
    }
</style>

<div class="purchase-create">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 style="color:#4A2616;font-weight:700;margin:0;">Create Supplier Purchase</h3>
            <p style="color:#806b5e;margin:5px 0 0;">Record a new raw material purchase.</p>
        </div>

        <a href="{{ route('admin.supplier-purchases.index') }}" class="cancel-btn">
            Back to Purchases
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.supplier-purchases.store') }}">
        @csrf

        <div class="panel">
            <h5 class="section-title">Purchase Information</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ (string) old('branch_id') === (string) $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ (string) old('supplier_id') === (string) $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }} ({{ $supplier->supplier_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Supplier Invoice Number</label>
                    <input type="text" name="supplier_invoice_number"
                           class="form-control"
                           value="{{ old('supplier_invoice_number') }}"
                           placeholder="Enter invoice number">
                </div>

                <div class="col-md-4">
                    <label>Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" name="purchase_date" class="form-control"
                           value="{{ old('purchase_date', now()->toDateString()) }}" required>
                </div>

                <div class="col-md-4">
                    <label>Expected Delivery Date</label>
                    <input type="date" name="expected_date" class="form-control"
                           value="{{ old('expected_date') }}">
                </div>

                <div class="col-12">
                    <label>Purchase Notes</label>
                    <textarea name="notes" class="form-control" rows="2"
                              placeholder="Additional purchase details">{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="section-title mb-0">Purchase Items</h5>
                <button type="button" class="add-item-btn" id="addItemBtn">
                    + Add Item
                </button>
            </div>

            <div class="table-responsive">
                <table class="item-table">
                    <thead>
                        <tr>
                            <th style="width:25%">Raw Material / Variant</th>
                            <th>Quantity</th>
                            <th>Unit Cost (?)</th>
                            <th>GST %</th>
                            <th>Discount (?)</th>
                            <th>Line Total (?)</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="purchaseItems">

                        <tr class="purchase-item">
                            <td>
                                <select name="items[0][product_variant_id]"
                                        class="form-select product-select" required>
                                    <option value="">Select Raw Material</option>
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            data-cost="{{ $variant->cost_price ?? 0 }}">
                                            {{ $variant->product->name ?? 'Product' }}
                                            - {{ $variant->variant_name }}
                                            @if($variant->sku)
                                                ({{ $variant->sku }})
                                            @endif
                                            @if($variant->unit)
                                                - {{ $variant->unit->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="items[0][quantity]"
                                       class="form-control quantity" min="0.001"
                                       step="0.001" value="1" required>
                            </td>

                            <td>
                                <input type="number" name="items[0][unit_cost]"
                                       class="form-control unit-cost" min="0"
                                       step="0.01" value="0" required>
                            </td>

                            <td>
                                <input type="number" name="items[0][gst_rate]"
                                       class="form-control gst-rate" min="0"
                                       max="100" step="0.01" value="0">
                            </td>

                            <td>
                                <input type="number" name="items[0][discount_amount]"
                                       class="form-control discount" min="0"
                                       step="0.01" value="0">
                            </td>

                            <td>
                                <strong class="line-total">?0.00</strong>
                            </td>

                            <td>
                                <button type="button" class="remove-item-btn removeItemBtn">
                                    Remove
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="totals mt-4">
                <div class="total-row">
                    <span>Subtotal</span>
                    <strong id="subtotal">?0.00</strong>
                </div>

                <div class="total-row">
                    <span>Total Discount</span>
                    <strong id="totalDiscount">?0.00</strong>
                </div>

                <div class="total-row">
                    <span>Total GST</span>
                    <strong id="totalTax">?0.00</strong>
                </div>

                <div class="total-row grand-total">
                    <span>Grand Total</span>
                    <strong id="grandTotal">?0.00</strong>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end flex-wrap gap-2 mb-4">
            <a href="{{ route('admin.supplier-purchases.index') }}" class="cancel-btn">
                Cancel
            </a>

            <button type="submit" class="save-btn">
                Save Purchase as Draft
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tbody = document.getElementById('purchaseItems');
    const addButton = document.getElementById('addItemBtn');

    let itemIndex = 1;

    const productOptions = @json($variants->map(function ($variant) {
        return [
            'id' => $variant->id,
            'label' => ($variant->product->name ?? 'Product')
                . ' - ' . $variant->variant_name
                . ($variant->sku ? ' (' . $variant->sku . ')' : '')
                . ($variant->unit ? ' - ' . $variant->unit->name : ''),
            'cost' => (float) ($variant->cost_price ?? 0),
        ];
    })->values());

    function createRow(index) {
        const row = document.createElement('tr');
        row.className = 'purchase-item';

        const options = productOptions.map(function (product) {
            return `<option value="${product.id}" data-cost="${product.cost}">${escapeHtml(product.label)}</option>`;
        }).join('');

        row.innerHTML = `
            <td>
                <select name="items[${index}][product_variant_id]"
                        class="form-select product-select" required>
                    <option value="">Select Raw Material</option>
                    ${options}
                </select>
            </td>

            <td>
                <input type="number" name="items[${index}][quantity]"
                       class="form-control quantity" min="0.001"
                       step="0.001" value="1" required>
            </td>

            <td>
                <input type="number" name="items[${index}][unit_cost]"
                       class="form-control unit-cost" min="0"
                       step="0.01" value="0" required>
            </td>

            <td>
                <input type="number" name="items[${index}][gst_rate]"
                       class="form-control gst-rate" min="0"
                       max="100" step="0.01" value="0">
            </td>

            <td>
                <input type="number" name="items[${index}][discount_amount]"
                       class="form-control discount" min="0"
                       step="0.01" value="0">
            </td>

            <td><strong class="line-total">?0.00</strong></td>

            <td>
                <button type="button" class="remove-item-btn removeItemBtn">
                    Remove
                </button>
            </td>
        `;

        return row;
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    addButton.addEventListener('click', function () {
        tbody.appendChild(createRow(itemIndex));
        itemIndex++;
        calculateTotals();
    });

    tbody.addEventListener('click', function (event) {
        if (event.target.classList.contains('removeItemBtn')) {
            const rows = tbody.querySelectorAll('.purchase-item');

            if (rows.length <= 1) {
                alert('At least one purchase item is required.');
                return;
            }

            event.target.closest('tr').remove();
            calculateTotals();
        }
    });

    tbody.addEventListener('change', function (event) {
        if (event.target.classList.contains('product-select')) {
            const selected = event.target.selectedOptions[0];
            const row = event.target.closest('tr');

            if (selected && selected.value) {
                row.querySelector('.unit-cost').value =
                    selected.dataset.cost || 0;
            }

            calculateTotals();
        }
    });

    tbody.addEventListener('input', calculateTotals);

    function calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;
        let grandTotal = 0;

        tbody.querySelectorAll('.purchase-item').forEach(function (row) {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitCost = parseFloat(row.querySelector('.unit-cost').value) || 0;
            const gstRate = parseFloat(row.querySelector('.gst-rate').value) || 0;
            const discount = parseFloat(row.querySelector('.discount').value) || 0;

            const baseAmount = Math.round(quantity * unitCost * 100) / 100;
            const validDiscount = Math.min(discount, baseAmount);
            const taxableAmount = baseAmount - validDiscount;
            const tax = Math.round(taxableAmount * gstRate) / 100;
            const lineTotal = Math.round((taxableAmount + tax) * 100) / 100;

            subtotal += baseAmount;
            totalDiscount += validDiscount;
            totalTax += tax;
            grandTotal += lineTotal;

            row.querySelector('.line-total').textContent =
                '?' + lineTotal.toFixed(2);
        });

        document.getElementById('subtotal').textContent =
            '?' + subtotal.toFixed(2);

        document.getElementById('totalDiscount').textContent =
            '?' + totalDiscount.toFixed(2);

        document.getElementById('totalTax').textContent =
            '?' + totalTax.toFixed(2);

        document.getElementById('grandTotal').textContent =
            '?' + grandTotal.toFixed(2);
    }

    calculateTotals();
});
</script>

@endsection
