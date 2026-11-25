
@extends('layouts.admin.app')

@section('title', 'POS Billing')
@section('page-heading', 'POS Billing')

@section('content')

<div class="pos-screen">

    <div class="pos-topbar">
        <div>
            <h3><i class="bi bi-shop"></i> Bakery POS</h3>
            <p>Point of Sale &middot; New Transaction</p>
        </div>

        <div class="pos-top-actions">
            <a href="{{ route('admin.pos.sales') }}" class="pos-top-btn">
                <i class="bi bi-clock-history"></i> Sales History
            </a>
            <a href="{{ route('admin.pos.held-bills') }}" class="pos-top-btn">
                <i class="bi bi-pause-circle"></i> Held Bills
            </a>
            <a href="{{ route('admin.pos.settings') }}" class="pos-top-btn">
                <i class="bi bi-gear"></i>
            </a>
        </div>
    </div>

    @if($catalogPreview)
        <div class="pos-preview-notice">
            <i class="bi bi-info-circle"></i>
            Catalog Preview Mode &middot; Sign in to view branch-specific prices and availability.
        </div>
    @else
        <div class="pos-branch-info">
            <i class="bi bi-shop"></i>
            {{ $branch->branch_name }}
        </div>
    @endif

    <div class="pos-billing-layout">

        {{-- LEFT: PRODUCT CATALOG --}}
        <section class="pos-products-panel">

            <div class="pos-panel-heading">
                <div>
                    <h4>Product Catalog</h4>
                    <p>Search and add products to your bill</p>
                </div>
                <span class="pos-product-count">{{ $products->count() }} Products</span>
            </div>

            <div class="pos-search-row">
                <div class="pos-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="productSearch"
                           placeholder="Search product name, SKU or barcode...">
                </div>

                <button type="button" class="pos-scan-btn">
                    <i class="bi bi-upc-scan"></i>
                    Scan Barcode
                </button>
            </div>

            {{-- DYNAMIC CATEGORIES --}}
            <div class="pos-category-section">
                <button type="button" class="pos-category active" data-category="all">
                    All Products
                </button>

                @foreach($categories as $category)
                    <button type="button"
                            class="pos-category"
                            data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <div class="pos-catalog-heading">
                <h5>Available Products</h5>
                <span>{{ $company->company_name }}</span>
            </div>

            {{-- DYNAMIC PRODUCT GRID --}}
            <div class="pos-product-grid" id="posProductGrid">

                @forelse($products as $product)

                    @foreach($product->productVariants as $variant)

                        @php
                            $branchProduct = $variant->branchProducts->first();

                            $sellingPrice = $branchProduct
                                ? $branchProduct->selling_price
                                : $variant->default_selling_price;
                        @endphp

                        <button type="button"
                                class="pos-product-card"
                                data-category="{{ $product->category_id }}"
                                data-search="{{ strtolower($product->name.' '.$product->sku.' '.$product->barcode.' '.$variant->sku.' '.$variant->barcode.' '.$variant->variant_name) }}"
                                data-variant-id="{{ $variant->id }}"
                                data-product-name="{{ $product->name }}"
                                data-variant-name="{{ $variant->variant_name }}"
                                data-sku="{{ $variant->sku }}"
                                data-price="{{ $sellingPrice !== null ? (float) $sellingPrice : '' }}"
                                data-gst-rate="{{ (float) $product->gst_rate }}"
                                {{ $sellingPrice === null ? 'disabled' : '' }}>

                            <div class="pos-product-image">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}">
                                @else
                                    <i class="bi bi-cake2"></i>
                                @endif
                            </div>

                            <div class="pos-product-info">
                                <h6>{{ $product->name }}</h6>

                                <span>{{ $variant->variant_name }}</span>
                                <span>SKU: {{ $variant->sku }}</span>

                                @if($sellingPrice !== null)
                                    <strong>&#8377;{{ number_format((float) $sellingPrice, 2) }}</strong>
                                @else
                                    <strong class="pos-price-missing">Price not set</strong>
                                @endif
                            </div>

                        </button>

                    @endforeach

                @empty
                    <div class="pos-empty-products">
                        <div class="pos-empty-icon">
                            <i class="bi bi-basket3"></i>
                        </div>
                        <h5>No Products Found</h5>
                        <p>Add active products and variants to your product catalog.</p>
                    </div>
                @endforelse

                @if($products->isNotEmpty() && $products->every(fn($product) => $product->productVariants->isEmpty()))
                    <div class="pos-empty-products">
                        <div class="pos-empty-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5>No Product Variants</h5>
                        <p>Add at least one variant to your products to display them here.</p>
                    </div>
                @endif

            </div>

        </section>

        {{-- RIGHT: BILLING CART --}}
        <section class="pos-cart-panel">

            <div class="pos-cart-heading">
                <div>
                    <h4>Current Bill</h4>
                    <p>New transaction</p>
                </div>
                <span class="pos-cart-icon">
                    <i class="bi bi-receipt"></i>
                </span>
            </div>
        {{-- CUSTOMER SELECTION --}}
        <div class="pos-customer-section">

            <label for="posCustomerSelect">Customer</label>

            <div class="pos-customer-select">
                <i class="bi bi-person"></i>

                <select id="posCustomerSelect" name="customer_id">
                    <option value="">Walk-in Customer</option>

                    @foreach($customers as $customer)
                        <option
                            value="{{ $customer->id }}"
                            data-name="{{ $customer->name }}"
                            data-phone="{{ $customer->phone }}"
                            data-email="{{ $customer->email }}"
                        >
                            {{ $customer->name }} - {{ $customer->phone }}
                        </option>
                    @endforeach
                </select>

                <button type="button" title="Add Customer" id="addCustomerButton">
                    <i class="bi bi-person-plus"></i>
                </button>
            </div>

            <div class="pos-walkin-field" id="walkInCustomerField">
                <label for="walkInCustomerName">Walk-in Customer Name</label>
                <input
                    type="text"
                    id="walkInCustomerName"
                    name="walk_in_customer_name"
                    placeholder="Enter customer name"
                    value="Walk-in Customer"
                    maxlength="255"
                >
            </div>

            <div class="pos-selected-customer" id="selectedCustomerDetails" hidden>
                <div>
                    <i class="bi bi-person-check"></i>
                    <span id="selectedCustomerName"></span>
                </div>
                <small id="selectedCustomerContact"></small>
            </div>

        </div>

        {{-- ADD NEW CUSTOMER MODAL --}}
        <div class="pos-customer-modal" id="addCustomerModal" aria-hidden="true">

            <div class="pos-customer-modal-overlay" id="closeCustomerModalOverlay"></div>

            <div class="pos-customer-modal-content">

                <div class="pos-customer-modal-header">
                    <div>
                        <h4><i class="bi bi-person-plus"></i> Add New Customer</h4>
                        <p>Register a new walk-in customer</p>
                    </div>

                    <button type="button" id="closeCustomerModal" class="pos-modal-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="addCustomerForm"
                      data-url="{{ route('admin.pos.customers.store') }}">

                    @csrf

                    <div class="pos-modal-field">
                        <label for="newCustomerName">Customer Name <span>*</span></label>
                        <input
                            type="text"
                            id="newCustomerName"
                            name="name"
                            placeholder="Enter customer name"
                            maxlength="255"
                            required
                        >
                        <small class="pos-field-error" id="nameError"></small>
                    </div>

                    <div class="pos-modal-field">
                        <label for="newCustomerPhone">Mobile Number <span>*</span></label>
                        <input
                            type="tel"
                            id="newCustomerPhone"
                            name="phone"
                            placeholder="Enter 10-digit mobile number"
                            maxlength="10"
                            inputmode="numeric"
                            pattern="[6-9][0-9]{9}"
                            required
                        >
                        <small class="pos-field-error" id="phoneError"></small>
                    </div>

                    <div class="pos-modal-field">
                        <label for="newCustomerEmail">
                            Email Address <small>(Optional)</small>
                        </label>
                        <input
                            type="email"
                            id="newCustomerEmail"
                            name="email"
                            placeholder="Enter email address"
                            maxlength="255"
                        >
                        <small class="pos-field-error" id="emailError"></small>
                    </div>

                    <div class="pos-customer-modal-actions">
                        <button type="button" id="cancelCustomerButton" class="pos-modal-cancel">
                            Cancel
                        </button>

                        <button type="submit" id="saveCustomerButton" class="pos-modal-save">
                            <i class="bi bi-check-circle"></i>
                            Save Customer
                        </button>
                    </div>

                    <div id="customerFormMessage"
                         class="pos-customer-form-message"
                         role="alert"></div>

                </form>

            </div>
        </div>
        {{-- BILL DISCOUNT MODAL --}}
        <div class="pos-customer-modal" id="addBillDiscountModal" aria-hidden="true">

            <div class="pos-customer-modal-overlay" id="closeBillDiscountOverlay"></div>

            <div class="pos-customer-modal-content">

                <div class="pos-customer-modal-header">
                    <div>
                        <h4><i class="bi bi-percent"></i> Bill Discount</h4>
                        <p>Apply a discount to the current bill</p>
                    </div>

                    <button type="button" id="closeBillDiscountModal" class="pos-modal-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="pos-modal-field">
                    <label for="billDiscountType">Discount Type</label>
                    <select id="billDiscountType">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (&#8377;)</option>
                    </select>
                </div>

                <div class="pos-modal-field">
                    <label for="billDiscountValue">Discount Value</label>
                    <input
                        type="number"
                        id="billDiscountValue"
                        min="0"
                        step="0.01"
                        placeholder="Enter discount value"
                    >
                    <small class="pos-field-error" id="billDiscountError"></small>
                </div>

                <div class="pos-customer-modal-actions">
                    <button type="button" id="removeBillDiscountButton" class="pos-modal-cancel">
                        Remove Discount
                    </button>

                    <button type="button" id="applyBillDiscountButton" class="pos-modal-save">
                        <i class="bi bi-check-circle"></i>
                        Apply Discount
                    </button>
                </div>

                <div id="billDiscountMessage"
                     class="pos-customer-form-message"
                     role="alert"></div>

            </div>
        </div>
                {{-- SIMPLE PAYMENT MODAL --}}
        <div class="pos-customer-modal" id="posCheckoutModal" aria-hidden="true">
            <div class="pos-customer-modal-overlay" id="closeCheckoutOverlay"></div>

            <div class="pos-customer-modal-content">
                <div class="pos-customer-modal-header">
                    <div>
                        <h4>Collect Payment</h4>
                    </div>
                    <button type="button" id="closeCheckoutModal" class="pos-modal-close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="pos-modal-field">
                    <label>CURRENT BILL AMOUNT</label>
                    <div class="pos-checkout-summary">
                        <strong id="checkoutGrandTotal">&#8377;0.00</strong>
                    </div>
                </div>

                <div class="pos-modal-field">
                    <label for="checkoutPaymentMethod">Payment Mode</label>
                    <select id="checkoutPaymentMethod">
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                        <option value="split">Split Payment</option>
                        <option value="due">Due / Credit</option>
                    </select>
                </div>

                <div id="checkoutSinglePaymentFields">
                    <div class="pos-modal-field">
                        <label for="checkoutAmountReceived" id="checkoutAmountLabel">Cash Received</label>
                        <input type="number" id="checkoutAmountReceived"
                               min="0" step="0.01" placeholder="Enter amount">
                    </div>

                    <div class="pos-modal-field" id="checkoutReferenceField" style="display:none;">
                        <label for="checkoutPaymentReference">Transaction Reference</label>
                        <input type="text" id="checkoutPaymentReference"
                               maxlength="255" placeholder="Enter transaction reference">
                    </div>
                </div>

                <div id="checkoutSplitPaymentFields" style="display:none;">
                    <div class="pos-modal-field">
                        <label for="splitCashAmount">Cash Amount</label>
                        <input type="number" id="splitCashAmount" min="0" step="0.01" value="0">
                    </div>

                    <div class="pos-modal-field">
                        <label for="splitUpiAmount">UPI Amount</label>
                        <input type="number" id="splitUpiAmount" min="0" step="0.01" value="0">
                    </div>

                    <div class="pos-modal-field">
                        <label for="splitUpiReference">UPI Transaction Reference</label>
                        <input type="text" id="splitUpiReference"
                               maxlength="255" placeholder="Enter UPI reference">
                    </div>

                    <div class="pos-modal-field">
                        <label for="splitCardAmount">Card Amount</label>
                        <input type="number" id="splitCardAmount" min="0" step="0.01" value="0">
                    </div>

                    <div class="pos-modal-field">
                        <label for="splitCardReference">Card Transaction Reference</label>
                        <input type="text" id="splitCardReference"
                               maxlength="255" placeholder="Enter card reference">
                    </div>
                </div>

                <div class="pos-checkout-balance">
                    <div>
                        <span>Change Return</span>
                        <strong id="checkoutChange">&#8377;0.00</strong>
                    </div>
                    <div>
                        <span>Remaining Balance</span>
                        <strong id="checkoutRemaining">&#8377;0.00</strong>
                    </div>
                </div>

                <small class="pos-field-error" id="checkoutPaymentError"></small>

                <div class="pos-customer-modal-actions">
                    <button type="button" id="cancelCheckoutButton" class="pos-modal-cancel">
                        Cancel
                    </button>
                    <button type="button" id="confirmCheckoutButton" class="pos-modal-save" disabled>
                        Collect Payment
                    </button>
                </div>

                <div class="pos-simple-checkout-footer">
                    <span>v1.0</span>
                    <span>&#169; 2026 <a href="https://zapcodex.com/" target="_blank" rel="noopener">Zapcodex</a>. All rights reserved.</span>
                </div>
            </div>
        </div>
<div class="pos-tax-section">
                <label>Invoice Type</label>
                <div class="pos-tax-options">
                    <label>
                        <input type="radio" name="invoice_type" value="gst" checked>
                        GST Invoice
                    </label>
                    <label>
                        <input type="radio" name="invoice_type" value="non_gst">
                        Non-GST
                    </label>
                </div>
            </div>

            <div class="pos-cart-items-heading">
                <h5>Cart Items</h5>
                <span id="cartItemCount">0 Items</span>
            </div>

            <div id="posCartItems">
                <div class="pos-empty-cart">
                    <div>
                        <i class="bi bi-cart3"></i>
                        <h5>Your cart is empty</h5>
                        <p>Select products from the catalog to start billing.</p>
                    </div>
                </div>
            </div>

            <div class="pos-bill-summary">

                <div class="pos-discount-row">
                    <span>Item Discount</span>
                    <span id="posItemDiscount">&#8377;0.00</span>
                </div>

                <div class="pos-discount-row">
                    <span>Bill Discount</span>
                    <div>
                        <strong id="posBillDiscountAmount">&#8377;0.00</strong>
                        <button type="button" id="addBillDiscountButton">+ Add Discount</button>
                    </div>
                </div>

                <div class="pos-summary-line">
                    <span>Subtotal</span>
                    <strong id="posSubtotal">&#8377;0.00</strong>
                </div>

                <div class="pos-summary-line">
                    <span>GST</span>
                    <strong id="posGstTotal">&#8377;0.00</strong>
                </div>

                <div class="pos-grand-total">
                    <span>Grand Total</span>
                    <strong id="posGrandTotal">&#8377;0.00</strong>
                </div>

            </div>

            <div class="pos-cart-actions">
                <button type="button" class="pos-hold-btn" disabled>
                    <i class="bi bi-pause-circle"></i>
                    Hold Bill
                </button>

                <button type="button" class="pos-pay-btn" disabled>
                    <i class="bi bi-credit-card"></i>
                    Pay & Print
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>

        </section>

    </div>

</div>

@endsection

@push('styles')
<style>
    .pos-screen { color:#4A2616; width:100%; }
    .pos-topbar { display:flex; justify-content:space-between; align-items:center; gap:15px; margin-bottom:20px; }
    .pos-topbar h3 { font-size:22px; font-weight:750; margin:0 0 5px; }
    .pos-topbar h3 i { color:#A2765B; margin-right:7px; }
    .pos-topbar p { color:#927F74; font-size:12px; margin:0; }
    .pos-top-actions { display:flex; gap:8px; }
    .pos-top-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 13px; border:1px solid #EAD9CB; border-radius:8px; background:#fff; color:#67432F; text-decoration:none; font-size:12px; font-weight:650; }
    .pos-top-btn:hover { background:#FFF5EC; color:#4A2616; }

    .pos-preview-notice, .pos-branch-info { padding:11px 14px; margin-bottom:15px; border-radius:8px; font-size:12px; }
    .pos-preview-notice { background:#FFF6E8; color:#805B27; border:1px solid #F0DFBD; }
    .pos-branch-info { background:#EDF6EF; color:#42684A; border:1px solid #D5E8D8; }

    .pos-billing-layout { display:grid; grid-template-columns:minmax(0,1.5fr) minmax(340px,1fr); gap:18px; align-items:stretch; }
    .pos-products-panel, .pos-cart-panel { background:#FFFEFC; border:1px solid #EEDFD3; border-radius:13px; padding:20px; min-width:0; }
    .pos-panel-heading, .pos-cart-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
    .pos-panel-heading h4, .pos-cart-heading h4 { font-size:17px; font-weight:750; margin:0 0 5px; }
    .pos-panel-heading p, .pos-cart-heading p { color:#998579; font-size:12px; margin:0; }
    .pos-product-count { background:#F9EDE3; color:#75452E; padding:7px 11px; border-radius:7px; font-size:11px; font-weight:700; }

    .pos-search-row { display:flex; gap:9px; margin-bottom:17px; }
    .pos-search-box { flex:1; display:flex; align-items:center; gap:10px; padding:0 13px; border:1px solid #E9DCD1; border-radius:9px; background:#fff; min-width:0; }
    .pos-search-box i { color:#A78D7C; }
    .pos-search-box input { width:100%; min-width:0; height:43px; border:0; outline:0; font-size:12px; background:transparent; color:#4A2616; }
    .pos-scan-btn { border:0; background:#4A2616; color:#fff; border-radius:9px; padding:0 14px; font-size:12px; font-weight:650; white-space:nowrap; }

    .pos-category-section { display:flex; gap:8px; overflow-x:auto; padding-bottom:12px; border-bottom:1px solid #F1E7DF; }
    .pos-category { flex-shrink:0; padding:8px 13px; border:1px solid #EDE0D6; border-radius:20px; color:#806D60; background:#fff; font-size:11px; font-weight:650; cursor:pointer; }
    .pos-category.active { background:#4A2616; color:#fff; border-color:#4A2616; }

    .pos-catalog-heading { display:flex; justify-content:space-between; align-items:center; gap:10px; margin:18px 0 13px; }
    .pos-catalog-heading h5, .pos-cart-items-heading h5 { font-size:13px; font-weight:750; margin:0; }
    .pos-catalog-heading span { color:#A18D82; font-size:10px; }

    .pos-product-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .pos-product-card { border:1px solid #F0E4DA; border-radius:10px; background:#fff; padding:9px; text-align:left; min-width:0; cursor:pointer; transition:.2s; }
    .pos-product-card:hover { border-color:#B58E73; box-shadow:0 4px 12px rgba(74,38,22,.08); transform:translateY(-2px); }
    .pos-product-card:disabled { opacity:.7; cursor:not-allowed; }
    .pos-product-card:disabled:hover { transform:none; box-shadow:none; border-color:#F0E4DA; }
    .pos-product-image { height:105px; background:#FAF1E9; border-radius:8px; display:flex; align-items:center; justify-content:center; overflow:hidden; color:#A2765B; font-size:30px; }
    .pos-product-image img { width:100%; height:100%; object-fit:cover; }
    .pos-product-info h6 { font-size:12px; font-weight:700; margin:9px 0 4px; color:#4A2616; }
    .pos-product-info span { display:block; color:#A18D82; font-size:10px; margin-top:3px; }
    .pos-product-info strong { display:block; color:#865638; font-size:13px; margin-top:7px; }
    .pos-product-info .pos-price-missing { color:#B45309; font-size:11px; }

    .pos-empty-products { grid-column:1/-1; text-align:center; padding:65px 15px; color:#998579; }
    .pos-empty-icon { font-size:35px; color:#B58E73; margin-bottom:10px; }
    .pos-empty-products h5 { color:#68432F; font-size:15px; font-weight:700; }
    .pos-empty-products p { font-size:12px; margin:0; }

    .pos-cart-heading { border-bottom:1px solid #F0E4DA; padding-bottom:15px; }
    .pos-cart-icon { width:40px; height:40px; background:#F9EDE3; color:#75452E; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:19px; }

    .pos-customer-section { margin-bottom:17px; }
    .pos-customer-section > label { display:block; font-size:11px; font-weight:700; color:#725443; margin-bottom:8px; }
    .pos-customer-select { display:flex; align-items:center; gap:9px; height:43px; border:1px solid #E9DCD1; border-radius:8px; padding:0 10px; background:#fff; color:#9A806F; }
    .pos-customer-select select { flex:1; min-width:0; border:0; outline:0; background:transparent; color:#60432F; font-size:12px; }
    .pos-customer-select button { border:0; background:#F9EDE3; color:#75452E; border-radius:6px; width:29px; height:29px; cursor:pointer; }

    .pos-walkin-field { margin-top:12px; }
    .pos-walkin-field label { display:block; font-size:11px; font-weight:700; color:#725443; margin-bottom:7px; }
    .pos-walkin-field input { width:100%; height:40px; border:1px solid #E9DCD1; border-radius:8px; padding:0 11px; outline:0; color:#60432F; font-size:12px; background:#fff; }
    .pos-walkin-field input:focus { border-color:#A2765B; box-shadow:0 0 0 2px rgba(162,118,91,.10); }

    .pos-selected-customer { margin-top:11px; padding:10px 12px; background:#EDF6EF; border:1px solid #D5E8D8; border-radius:8px; color:#42684A; }
    .pos-selected-customer[hidden] { display:none; }
    .pos-selected-customer div { display:flex; align-items:center; gap:7px; font-size:12px; font-weight:700; }
    .pos-selected-customer small { display:block; margin:5px 0 0 22px; font-size:10px; color:#63806A; }

    /* CUSTOMER REGISTRATION MODAL */
    .pos-customer-modal {
        display:none;
        position:fixed;
        inset:0;
        z-index:9999;
        align-items:center;
        justify-content:center;
        padding:20px;
    }

    .pos-customer-modal.active {
        display:flex;
    }

    .pos-customer-modal-overlay {
        position:absolute;
        inset:0;
        background:rgba(35,20,12,.58);
        backdrop-filter:blur(3px);
    }

    .pos-customer-modal-content {
        position:relative;
        z-index:1;
        width:100%;
        max-width:440px;
        padding:24px;
        background:#FFFEFC;
        border:1px solid #EEDFD3;
        border-radius:15px;
        box-shadow:0 20px 60px rgba(40,20,10,.22);
        animation:posModalIn .2s ease;
    }

    @keyframes posModalIn {
        from { opacity:0; transform:translateY(12px); }
        to { opacity:1; transform:translateY(0); }
    }

    .pos-customer-modal-header {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        margin-bottom:23px;
    }

    .pos-customer-modal-header h4 {
        font-size:17px;
        font-weight:750;
        color:#4A2616;
        margin:0 0 5px;
    }

    .pos-customer-modal-header h4 i {
        color:#A2765B;
        margin-right:5px;
    }

    .pos-customer-modal-header p {
        font-size:11px;
        color:#998579;
        margin:0;
    }

    .pos-modal-close {
        width:32px;
        height:32px;
        border:0;
        border-radius:7px;
        background:#F9EDE3;
        color:#75452E;
        cursor:pointer;
    }

    .pos-modal-field {
        margin-bottom:17px;
    }

    .pos-modal-field label {
        display:block;
        font-size:11px;
        font-weight:700;
        color:#725443;
        margin-bottom:7px;
    }

    .pos-modal-field label span {
        color:#DC2626;
    }

    .pos-modal-field label small {
        font-weight:400;
        color:#998579;
    }

    .pos-modal-field input {
        width:100%;
        height:43px;
        padding:0 12px;
        border:1px solid #E9DCD1;
        border-radius:8px;
        outline:none;
        background:#fff;
        color:#60432F;
        font-size:12px;
    }

    .pos-modal-field input:focus {
        border-color:#A2765B;
        box-shadow:0 0 0 3px rgba(162,118,91,.10);
    }

    .pos-modal-field input.is-invalid {
        border-color:#DC2626;
    }

    .pos-field-error {
        display:block;
        min-height:0;
        color:#DC2626;
        font-size:10px;
        margin-top:5px;
    }

    .pos-customer-modal-actions {
        display:flex;
        gap:10px;
        margin-top:22px;
    }

    .pos-modal-cancel,
    .pos-modal-save {
        flex:1;
        height:43px;
        border-radius:8px;
        font-size:12px;
        font-weight:700;
        cursor:pointer;
    }

    .pos-modal-cancel {
        background:#fff;
        color:#79563F;
        border:1px solid #DCC8B8;
    }

    .pos-modal-save {
        background:#4A2616;
        color:#fff;
        border:0;
    }

    .pos-modal-save:disabled {
        opacity:.6;
        cursor:wait;
    }

    .pos-customer-form-message {
        display:none;
        padding:10px;
        margin-top:14px;
        border-radius:7px;
        font-size:11px;
    }

    .pos-customer-form-message.success {
        display:block;
        color:#28613A;
        background:#EDF6EF;
        border:1px solid #D5E8D8;
    }

    .pos-customer-form-message.error {
        display:block;
        color:#B42318;
        background:#FFF0EE;
        border:1px solid #F5D0CC;
    }

    body.pos-modal-open {
        overflow:hidden;
    }
    .pos-tax-section { margin-bottom:20px; }
    .pos-tax-section>label { display:block; font-size:11px; font-weight:700; color:#725443; margin-bottom:8px; }
    .pos-tax-options { display:flex; gap:10px; }
    .pos-tax-options label { flex:1; padding:10px; border:1px solid #E9DCD1; border-radius:8px; font-size:11px; color:#765B49; background:#fff; }
    .pos-tax-options input { accent-color:#75452E; margin-right:5px; }
    .pos-cart-items-heading { display:flex; justify-content:space-between; margin-bottom:10px; }
    .pos-cart-items-heading span { font-size:11px; color:#A18D82; }
    .pos-empty-cart { min-height:155px; display:flex; align-items:center; justify-content:center; text-align:center; border:1px dashed #E8D9CD; border-radius:10px; background:#FFFCF9; padding:20px; }
    .pos-empty-cart i { font-size:31px; color:#B99A84; }
    .pos-empty-cart h5 { font-size:13px; color:#67432F; font-weight:700; margin:9px 0 5px; }
    .pos-empty-cart p { font-size:11px; color:#A18D82; margin:0; }
    .pos-cart-item { display:flex; gap:10px; align-items:center; padding:12px 0; border-bottom:1px solid #F0E4DA; }
    .pos-cart-item-info { flex:1; min-width:0; }
    .pos-cart-item-info h6 { font-size:12px; font-weight:700; color:#4A2616; margin:0 0 4px; }
    .pos-cart-item-info small { display:block; color:#A18D82; font-size:10px; }
    .pos-cart-item-price { color:#75452E; font-size:12px; font-weight:700; margin-top:5px; }
    .pos-cart-gst { color:#94735D; font-size:11px; margin-top:4px; }
    .pos-cart-qty { display:flex; align-items:center; gap:7px; margin-top:7px; }
    .pos-cart-qty button { width:24px; height:24px; border:1px solid #E5D5C9; border-radius:5px; background:#fff; color:#75452E; font-weight:700; }
    .pos-cart-qty span { min-width:16px; text-align:center; font-size:11px; font-weight:700; }
    .pos-cart-remove { border:0; background:transparent; color:#B45309; font-size:15px; padding:5px; }
    .pos-bill-summary { padding-top:17px; }
    .pos-discount-row, .pos-summary-line { display:flex; justify-content:space-between; align-items:center; margin-bottom:13px; font-size:12px; color:#806D60; }
    .pos-discount-row button { border:0; background:transparent; color:#9A6040; font-size:11px; font-weight:700; }
    .pos-summary-line strong { color:#60432F; }
    .pos-grand-total { border-top:1px solid #EBDDD2; padding-top:15px; margin-top:14px; display:flex; justify-content:space-between; align-items:center; }
    .pos-grand-total span { font-size:14px; font-weight:750; }
    .pos-grand-total strong { color:#4A2616; font-size:23px; font-weight:800; }
    .pos-cart-actions { display:grid; grid-template-columns:.85fr 1.5fr; gap:9px; margin-top:20px; }
    .pos-hold-btn, .pos-pay-btn { height:46px; border-radius:8px; font-size:12px; font-weight:700; }
    .pos-hold-btn { background:#fff; color:#79563F; border:1px solid #DCC8B8; }
    .pos-pay-btn { background:#4A2616; color:#fff; border:0; }
    .pos-hold-btn:disabled, .pos-pay-btn:disabled { opacity:.55; cursor:not-allowed; }

    @media(max-width:1100px) {
        .pos-billing-layout { grid-template-columns:1fr; }
        .pos-cart-panel { min-height:500px; }
    }

    @media(max-width:650px) {
        .pos-topbar { align-items:flex-start; flex-direction:column; }
        .pos-top-actions { flex-wrap:wrap; }
        .pos-products-panel, .pos-cart-panel { padding:14px; }
        .pos-product-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .pos-search-row { flex-direction:column; }
        .pos-scan-btn { height:40px; }
    }

    /* PAYMENT & CHECKOUT MODAL */
    #posCheckoutModal .pos-customer-modal-content {
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .pos-checkout-summary {
        background: #F8F1EB;
        border: 1px solid #EEDFD3;
        border-radius: 9px;
        padding: 15px;
        margin-top: 7px;
    }

    .pos-checkout-summary strong {
        display: block;
        color: #4A2616;
        font-size: 25px;
        font-weight: 800;
    }

    .pos-checkout-balance {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 17px;
    }

    .pos-checkout-balance > div {
        background: #F8F5F1;
        border: 1px solid #EEDFD3;
        border-radius: 9px;
        padding: 12px;
    }

    .pos-checkout-balance span {
        display: block;
        color: #89796E;
        font-size: 10px;
        font-weight: 650;
        margin-bottom: 6px;
    }

    .pos-checkout-balance strong {
        color: #4A2616;
        font-size: 17px;
        font-weight: 800;
    }

    #checkoutSplitPaymentFields {
        padding: 12px;
        background: #FCF9F6;
        border: 1px solid #EEDFD3;
        border-radius: 10px;
        margin-top: 12px;
    }

    #posCheckoutModal .pos-field-error {
        display: block;
        margin-top: 10px;
    }

    @media(max-width: 480px) {
        #posCheckoutModal {
            padding: 10px;
        }

        #posCheckoutModal .pos-customer-modal-content {
            padding: 18px;
            max-height: 94vh;
        }

        .pos-checkout-balance {
            gap: 7px;
        }
    }


    /* SIMPLE COLLECT PAYMENT DESIGN */
    #posCheckoutModal .pos-customer-modal-content {
        max-width: 420px;
        padding: 24px;
        gap: 14px;
    }

    #posCheckoutModal .pos-customer-modal-header h4 {
        font-size: 21px;
        font-weight: 800;
        color: #4A2616;
    }

    #posCheckoutModal .pos-checkout-summary {
        background: transparent;
        border: none;
        padding: 4px 0;
        margin-top: 4px;
    }

    #posCheckoutModal .pos-checkout-summary strong {
        font-size: 34px;
        font-weight: 850;
    }

    #posCheckoutModal .pos-modal-field {
        margin-bottom: 12px;
    }

    #posCheckoutModal .pos-checkout-balance {
        margin-top: 4px;
    }

    #posCheckoutModal .pos-checkout-balance > div {
        padding: 10px;
    }

    .pos-simple-checkout-footer {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        border-top: 1px solid #EEDFD3;
        padding-top: 12px;
        color: #89796E;
        font-size: 10px;
    }

    .pos-simple-checkout-footer a {
        color: #79563F;
        text-decoration: none;
        font-weight: 700;
    }

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('productSearch');
    const categoryButtons = document.querySelectorAll('.pos-category');
    const productCards = document.querySelectorAll('.pos-product-card');

    const cartContainer = document.getElementById('posCartItems');
    const cartItemCount = document.getElementById('cartItemCount');

    // CUSTOMER ELEMENTS
    const customerSelect = document.getElementById('posCustomerSelect');
    const walkInCustomerField = document.getElementById('walkInCustomerField');
    const walkInCustomerName = document.getElementById('walkInCustomerName');
    const selectedCustomerDetails = document.getElementById('selectedCustomerDetails');
    const selectedCustomerName = document.getElementById('selectedCustomerName');
    const selectedCustomerContact = document.getElementById('selectedCustomerContact');
    const addCustomerButton = document.getElementById('addCustomerButton');

    let activeCategory = 'all';
    let cart = [];
    let billDiscountType = 'percentage';
    let billDiscountValue = 0;

    // CUSTOMER SELECTION
    function updateCustomerSelection() {

        const selectedOption = customerSelect.selectedOptions[0];

        if (!customerSelect.value) {

            // WALK-IN CUSTOMER
            walkInCustomerField.style.display = 'block';
            selectedCustomerDetails.hidden = true;

            walkInCustomerName.disabled = false;

        } else {

            // REGISTERED CUSTOMER
            walkInCustomerField.style.display = 'none';
            selectedCustomerDetails.hidden = false;

            walkInCustomerName.disabled = true;

            const name = selectedOption.dataset.name || '';
            const phone = selectedOption.dataset.phone || '';
            const email = selectedOption.dataset.email || '';

            selectedCustomerName.textContent = name;

            selectedCustomerContact.textContent =
            [phone, email].filter(Boolean).join(" | ");
        }
    }

    customerSelect.addEventListener('change', updateCustomerSelection);

    // INITIAL CUSTOMER STATE
    updateCustomerSelection();

    // ADD CUSTOMER MODAL AND AJAX REGISTRATION
    const customerModal = document.getElementById('addCustomerModal');
    const customerForm = document.getElementById('addCustomerForm');
    const saveCustomerButton = document.getElementById('saveCustomerButton');
    const customerFormMessage = document.getElementById('customerFormMessage');

    function openCustomerModal() {
        customerForm.reset();
        clearCustomerErrors();

        customerModal.classList.add('active');
        customerModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('pos-modal-open');

        document.getElementById('newCustomerName').focus();
    }

    function closeCustomerModal() {
        customerModal.classList.remove('active');
        customerModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('pos-modal-open');
    }

    function clearCustomerErrors() {
        ['name', 'phone', 'email'].forEach(function (field) {
            const error = document.getElementById(field + 'Error');
            const input = document.querySelector('[name="' + field + '"]');

            error.textContent = '';
            input.classList.remove('is-invalid');
        });

        customerFormMessage.textContent = '';
        customerFormMessage.className = 'pos-customer-form-message';
    }

    function showCustomerError(field, message) {
        const error = document.getElementById(field + 'Error');
        const input = document.querySelector('[name="' + field + '"]');

        if (error && input) {
            error.textContent = message;
            input.classList.add('is-invalid');
        }
    }

    addCustomerButton.addEventListener('click', openCustomerModal);

    document.getElementById('closeCustomerModal')
        .addEventListener('click', closeCustomerModal);

    document.getElementById('cancelCustomerButton')
        .addEventListener('click', closeCustomerModal);

    document.getElementById('closeCustomerModalOverlay')
        .addEventListener('click', closeCustomerModal);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && customerModal.classList.contains('active')) {
            closeCustomerModal();
        }
    });

    document.getElementById('newCustomerPhone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    customerForm.querySelectorAll('input').forEach(function (input) {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');

            const error = document.getElementById(this.name + 'Error');
            if (error) error.textContent = '';
        });
    });

    customerForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        clearCustomerErrors();

        if (!customerForm.reportValidity()) {
            return;
        }

        const phone = document.getElementById('newCustomerPhone').value.trim();
        const email = document.getElementById('newCustomerEmail').value.trim();

        if (!/^[6-9][0-9]{9}$/.test(phone)) {
            showCustomerError('phone', 'Enter a valid 10-digit Indian mobile number.');
            return;
        }

        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showCustomerError('email', 'Enter a valid email address.');
            return;
        }

        saveCustomerButton.disabled = true;
        saveCustomerButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Saving...';

        try {
            const response = await fetch(customerForm.dataset.url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(customerForm)
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    Object.keys(result.errors).forEach(function (field) {
                        showCustomerError(field, result.errors[field][0]);
                    });
                } else {
                    throw new Error(result.message || 'Unable to save customer.');
                }

                return;
            }

            const customer = result.customer;

            const option = document.createElement('option');
            option.value = customer.id;
            option.dataset.name = customer.name;
            option.dataset.phone = customer.phone;
            option.dataset.email = customer.email || '';
            option.textContent = customer.name + ' - ' + customer.phone;

            customerSelect.appendChild(option);
            customerSelect.value = customer.id;

            updateCustomerSelection();

            walkInCustomerName.value = 'Walk-in Customer';

            customerFormMessage.textContent = 'Customer added successfully.';
            customerFormMessage.className = 'pos-customer-form-message success';

            saveCustomerButton.innerHTML = '<i class="bi bi-check-circle"></i> Saved';

            setTimeout(function () {
                closeCustomerModal();
                customerForm.reset();
                clearCustomerErrors();
                saveCustomerButton.innerHTML = '<i class="bi bi-check-circle"></i> Save Customer';
            }, 900);

        } catch (error) {
            customerFormMessage.textContent = error.message || 'Something went wrong. Please try again.';
            customerFormMessage.className = 'pos-customer-form-message error';

        } finally {
            saveCustomerButton.disabled = false;
        }
    });

    // GET CURRENT CUSTOMER DETAILS FOR FUTURE BILLING
    function getCustomerDetails() {

        if (!customerSelect.value) {

            return {
                customer_id: null,
                customer_name: walkInCustomerName.value.trim() || 'Walk-in Customer',
                customer_phone: null,
                customer_email: null
            };

        }

        const option = customerSelect.selectedOptions[0];

        return {
            customer_id: customerSelect.value,
            customer_name: option.dataset.name || '',
            customer_phone: option.dataset.phone || '',
            customer_email: option.dataset.email || ''
        };
    }

    // SEARCH AND CATEGORY FILTER
    function filterProducts() {

        const search = searchInput.value.toLowerCase().trim();

        productCards.forEach(function (card) {

            const matchesSearch = card.dataset.search.includes(search);

            const matchesCategory = activeCategory === 'all' ||
                card.dataset.category === activeCategory;

            card.style.display = matchesSearch && matchesCategory ? '' : 'none';

        });
    }

    searchInput.addEventListener('input', filterProducts);

    categoryButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            categoryButtons.forEach(btn => btn.classList.remove('active'));

            button.classList.add('active');

            activeCategory = button.dataset.category;

            filterProducts();

        });
    });

    // BILL DISCOUNT MODAL
    const billDiscountModal = document.getElementById('addBillDiscountModal');
    const billDiscountButton = document.getElementById('addBillDiscountButton');
    const billDiscountTypeInput = document.getElementById('billDiscountType');
    const billDiscountValueInput = document.getElementById('billDiscountValue');
    const billDiscountError = document.getElementById('billDiscountError');
    const billDiscountMessage = document.getElementById('billDiscountMessage');

    function openBillDiscountModal() {
        billDiscountTypeInput.value = billDiscountType;
        billDiscountValueInput.value = billDiscountValue || '';
        billDiscountError.textContent = '';
        billDiscountMessage.textContent = '';

        billDiscountModal.classList.add('active');
        billDiscountModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('pos-modal-open');

        billDiscountValueInput.focus();
    }

    function closeBillDiscountModal() {
        billDiscountModal.classList.remove('active');
        billDiscountModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('pos-modal-open');
    }

    billDiscountButton.addEventListener('click', openBillDiscountModal);

    document.getElementById('closeBillDiscountModal')
        .addEventListener('click', closeBillDiscountModal);

    document.getElementById('closeBillDiscountOverlay')
        .addEventListener('click', closeBillDiscountModal);

    billDiscountValueInput.addEventListener('input', function () {
        billDiscountError.textContent = '';
        billDiscountMessage.textContent = '';
    });

    document.getElementById('applyBillDiscountButton')
        .addEventListener('click', function () {

            const value = Number(billDiscountValueInput.value);
            const type = billDiscountTypeInput.value;

            const subtotal = cart.reduce(function (sum, item) {
                return sum + (item.price * item.quantity);
            }, 0);

            if (cart.length === 0 || subtotal <= 0) {
                billDiscountError.textContent = 'Add products to the cart first.';
                return;
            }

            if (!Number.isFinite(value) || value <= 0) {
                billDiscountError.textContent = 'Enter a discount greater than zero.';
                return;
            }

            if (type === 'percentage' && value > 100) {
                billDiscountError.textContent = 'Percentage cannot exceed 100%.';
                return;
            }

            const discountAmount = type === 'percentage'
                ? subtotal * value / 100
                : value;

            if (discountAmount > subtotal) {
                billDiscountError.textContent = 'Discount cannot exceed the subtotal.';
                return;
            }

            billDiscountType = type;
            billDiscountValue = value;

            updateTotals();
            closeBillDiscountModal();
        });

    document.getElementById('removeBillDiscountButton')
        .addEventListener('click', function () {
            billDiscountType = 'percentage';
            billDiscountValue = 0;

            updateTotals();
            closeBillDiscountModal();
        });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && billDiscountModal.classList.contains('active')) {
            closeBillDiscountModal();
        }
    });
    // ADD PRODUCTS TO CART
    productCards.forEach(function (card) {

        card.addEventListener('click', function () {

            const variantId = card.dataset.variantId;
            const price = Number(card.dataset.price);

            if (!variantId || card.dataset.price === '' || !Number.isFinite(price)) {
                return;
            }

            const existingItem = cart.find(item => item.variantId === variantId);

            if (existingItem) {

                existingItem.quantity++;

            } else {

                cart.push({
                    variantId: variantId,
                    name: card.dataset.productName,
                    variant: card.dataset.variantName,
                    sku: card.dataset.sku,
                    price: price,
                    gstRate: Number(card.dataset.gstRate) || 0,
                    quantity: 1
                });

            }

            renderCart();

        });
    });

    // DISPLAY CART
    function renderCart() {

        const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);

        cartItemCount.textContent = totalQuantity +
            (totalQuantity === 1 ? ' Item' : ' Items');

        if (cart.length === 0) {

            cartContainer.innerHTML = `
                <div class="pos-empty-cart">
                    <div>
                        <i class="bi bi-cart3"></i>
                        <h5>Your cart is empty</h5>
                        <p>Select products from the catalog to start billing.</p>
                    </div>
                </div>
            `;

            updateTotals();

            return;
        }

        const isGstInvoice =
            document.querySelector('input[name="invoice_type"]:checked')?.value === 'gst';

        cartContainer.innerHTML = cart.map(item => {

            const originalPrice = item.price * item.quantity;

            const gstAmount = isGstInvoice
                ? originalPrice * item.gstRate / 100
                : 0;

            const finalPrice = originalPrice + gstAmount;

            return `
                <div class="pos-cart-item">

                    <div class="pos-cart-item-info">

                        <h6>${escapeHtml(item.name)}</h6>

                        <small>
                            ${escapeHtml(item.variant)} | SKU: ${escapeHtml(item.sku || '-')}
                        </small>

                        <small class="pos-cart-gst">
                            GST: ${Number(item.gstRate).toFixed(2)}%
                        </small>

                        <div class="pos-cart-item-price">
                            Original Price: ${formatMoney(originalPrice)}
                        </div>

                        <div class="pos-cart-gst">
                            GST Amount: ${formatMoney(gstAmount)}
                        </div>

                        <div class="pos-cart-item-price">
                            Final Price: ${formatMoney(finalPrice)}
                        </div>

                        <div class="pos-cart-qty">

                            <button type="button"
                                    data-action="decrease"
                                    data-id="${item.variantId}">-</button>

                            <span>${item.quantity}</span>

                            <button type="button"
                                    data-action="increase"
                                    data-id="${item.variantId}">+</button>

                        </div>

                    </div>

                    <button type="button"
                            class="pos-cart-remove"
                            data-action="remove"
                            data-id="${item.variantId}"
                            title="Remove item">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>
            `;

        }).join('');

        updateTotals();
    }

    // INCREASE, DECREASE AND REMOVE CART ITEMS
    cartContainer.addEventListener('click', function (event) {

        const button = event.target.closest('button[data-action]');

        if (!button) return;

        const item = cart.find(item => item.variantId === button.dataset.id);

        if (!item) return;

        if (button.dataset.action === 'increase') {

            item.quantity++;

        } else if (button.dataset.action === 'decrease') {

            item.quantity--;

            if (item.quantity <= 0) {
                cart = cart.filter(cartItem => cartItem.variantId !== item.variantId);
            }

        } else if (button.dataset.action === 'remove') {

            cart = cart.filter(cartItem => cartItem.variantId !== item.variantId);

        }

        renderCart();

    });

    // CALCULATE SUBTOTAL, DISCOUNT, GST AND GRAND TOTAL
    function updateTotals() {

        if (cart.length === 0) {
            billDiscountType = 'percentage';
            billDiscountValue = 0;
        }

        const isGstInvoice =
            document.querySelector('input[name="invoice_type"]:checked')?.value === 'gst';

        let taxableSubtotal = 0;
        let gstTotal = 0;

        cart.forEach(function (item) {
            taxableSubtotal += item.price * item.quantity;
        });

        let discountAmount = 0;

        if (billDiscountValue > 0 && taxableSubtotal > 0) {
            discountAmount = billDiscountType === 'percentage'
                ? taxableSubtotal * billDiscountValue / 100
                : billDiscountValue;
        }

        discountAmount = Math.min(discountAmount, taxableSubtotal);

        const discountedSubtotal = taxableSubtotal - discountAmount;

        cart.forEach(function (item) {

            const itemTotal = item.price * item.quantity;

            // Allocate bill discount proportionally to each cart item.
            const itemDiscount = taxableSubtotal > 0
                ? discountAmount * (itemTotal / taxableSubtotal)
                : 0;

            const discountedItemTotal = itemTotal - itemDiscount;

            const itemGst = isGstInvoice
                ? discountedItemTotal * (item.gstRate / 100)
                : 0;

            gstTotal += itemGst;

        });

        const grandTotal = discountedSubtotal + gstTotal;

        document.getElementById('posItemDiscount').textContent =
            formatMoney(0);

        document.getElementById('posBillDiscountAmount').textContent =
            formatMoney(discountAmount);

        document.getElementById('posSubtotal').textContent =
            formatMoney(taxableSubtotal);

        document.getElementById('posGstTotal').textContent =
            formatMoney(gstTotal);

        document.getElementById('posGrandTotal').textContent =
            formatMoney(grandTotal);

        billDiscountButton.disabled = cart.length === 0;

        document.querySelector('.pos-hold-btn').disabled = cart.length === 0;

        document.querySelector('.pos-pay-btn').disabled = cart.length === 0;

    }
    // REFRESH CART WHEN INVOICE TYPE CHANGES
    document.querySelectorAll('input[name="invoice_type"]').forEach(function (input) {

        input.addEventListener('change', function () {
            renderCart();
        });

    });

    // CURRENCY FORMAT
    function formatMoney(amount) {

        return '\u20B9' + Number(amount).toFixed(2);

    }

    // PREVENT HTML INJECTION IN PRODUCT DETAILS
    function escapeHtml(value) {

        return String(value ?? '').replace(/[&<>"']/g, function (char) {

            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];

        });

    }    // PAYMENT & CHECKOUT MODAL CONTROLS

    const checkoutModal = document.getElementById('posCheckoutModal');
    const checkoutButton = document.querySelector('.pos-pay-btn');
    const checkoutMethod = document.getElementById('checkoutPaymentMethod');
    const checkoutSingleFields = document.getElementById('checkoutSinglePaymentFields');
    const checkoutSplitFields = document.getElementById('checkoutSplitPaymentFields');
    const checkoutReferenceField = document.getElementById('checkoutReferenceField');
    const checkoutAmountInput = document.getElementById('checkoutAmountReceived');
    const checkoutAmountLabel = document.getElementById('checkoutAmountLabel');
    const checkoutError = document.getElementById('checkoutPaymentError');

    function openCheckoutModal() {
        if (cart.length === 0) return;

        const total = Number(document.getElementById('posGrandTotal').textContent.replace(/[^\d.]/g, '')) || 0;

        document.getElementById('checkoutGrandTotal').textContent = formatMoney(total);
        checkoutError.textContent = '';
        checkoutAmountInput.value = total.toFixed(2);

        document.getElementById('checkoutPaymentReference').value = '';

        document.getElementById('splitCashAmount').value = '0';
        document.getElementById('splitUpiAmount').value = '0';
        document.getElementById('splitCardAmount').value = '0';
        document.getElementById('splitUpiReference').value = '';
        document.getElementById('splitCardReference').value = '';

        checkoutMethod.value = 'cash';
        updateCheckoutPaymentFields();
        updateCheckoutBalance();

        checkoutModal.classList.add('active');
        checkoutModal.setAttribute('aria-hidden', 'false');
    }

    function closeCheckoutModal() {
        checkoutModal.classList.remove('active');
        checkoutModal.setAttribute('aria-hidden', 'true');
        checkoutError.textContent = '';
    }

    function updateCheckoutPaymentFields() {
        const method = checkoutMethod.value;

        checkoutSingleFields.style.display = ['split', 'due'].includes(method) ? 'none' : 'block';
        checkoutSplitFields.style.display = method === 'split' ? 'block' : 'none';

        checkoutReferenceField.style.display =
            ['upi', 'card'].includes(method) ? 'block' : 'none';

        const confirmButton = document.getElementById('confirmCheckoutButton');

        checkoutAmountLabel.textContent =
            method === 'cash' ? 'Cash Received' : 'Amount Paid for This Bill';

        checkoutError.textContent = '';
        confirmButton.disabled = true;

        updateCheckoutBalance();
    }

    function updateCheckoutBalance() {
        const total = Number(
            document.getElementById('checkoutGrandTotal').textContent.replace(/[^\d.]/g, '')
        ) || 0;

        const method = checkoutMethod.value;
        const reference = document.getElementById('checkoutPaymentReference').value.trim();

        const cash = Number(document.getElementById('splitCashAmount').value || 0);
        const upi = Number(document.getElementById('splitUpiAmount').value || 0);
        const card = Number(document.getElementById('splitCardAmount').value || 0);
        const upiReference = document.getElementById('splitUpiReference').value.trim();
        const cardReference = document.getElementById('splitCardReference').value.trim();
        const singleAmount = Number(checkoutAmountInput.value || 0);

        let received = 0;
        let change = 0;
        let message = '';

        const validAmount = function (value) {
            return Number.isFinite(value) &&
                value >= 0 &&
                Math.abs(value * 100 - Math.round(value * 100)) < 0.000001;
        };

        if (method === 'cash') {
            received = singleAmount;

            if (!validAmount(singleAmount) || singleAmount <= 0) {
                message = 'Enter a valid cash amount with up to two decimal places.';
            } else if (singleAmount < total) {
                message = 'Cash received must be at least the bill total.';
            } else {
                change = singleAmount - total;
            }

        } else if (method === 'upi' || method === 'card') {
            received = singleAmount;

            if (!validAmount(singleAmount) || singleAmount <= 0) {
                message = 'Enter a valid payment amount with up to two decimal places.';
            } else if (Math.abs(singleAmount - total) >= 0.005) {
                message = 'Payment amount must exactly match the bill total.';
            } else if (!reference) {
                message = 'Enter the transaction reference.';
            }

        } else if (method === 'due') {
            received = 0;

        } else if (method === 'split') {
            const amounts = [cash, upi, card];

            received = amounts.reduce(function (sum, amount) {
                return sum + amount;
            }, 0);

            if (!amounts.every(validAmount)) {
                message = 'All split amounts must be non-negative with up to two decimal places.';
            } else if (Math.abs(received - total) >= 0.005) {
                message = 'Cash + UPI + Card must exactly equal the bill total.';
            } else if (upi > 0 && !upiReference) {
                message = 'Enter the UPI transaction reference.';
            } else if (card > 0 && !cardReference) {
                message = 'Enter the card transaction reference.';
            }

        }
        const remaining = Math.max(0, total - received);

        document.getElementById('checkoutChange').textContent = formatMoney(change);
        document.getElementById('checkoutRemaining').textContent = formatMoney(remaining);

        const confirmButton = document.getElementById('confirmCheckoutButton');

        confirmButton.disabled = Boolean(message) || total <= 0;

        checkoutError.textContent = message ||
            (method === 'due' ? 'This bill will remain unpaid as Due / Credit.' : 'Payment details are valid. Ready to complete sale.');
    }

    // SUBMIT POS CHECKOUT TO LARAVEL
    const checkoutUrl = @json(route('admin.pos.checkout'));
    const checkoutCsrfToken = customerForm.querySelector('input[name="_token"]')?.value || '';
    const confirmCheckoutButton = document.getElementById('confirmCheckoutButton');
    let checkoutSubmitting = false;

    confirmCheckoutButton.addEventListener('click', async function () {
        if (checkoutSubmitting || confirmCheckoutButton.disabled || cart.length === 0) {
            return;
        }

        checkoutSubmitting = true;
        confirmCheckoutButton.disabled = true;
        confirmCheckoutButton.textContent = 'Saving...';
        checkoutError.textContent = '';

        const method = checkoutMethod.value;
        const customer = getCustomerDetails();

        const payload = {
            items: cart.map(function (item) {
                return {
                    variant_id: Number(item.variantId),
                    quantity: item.quantity
                };
            }),
            customer_id: customer.customer_id || null,
            customer_name: customer.customer_name,
            invoice_type: document.querySelector('input[name="invoice_type"]:checked')?.value || 'gst',
            discount_type: billDiscountType,
            discount_value: billDiscountValue,
            payment_method: method,
            amount_received: method === 'due' ? 0 : Number(checkoutAmountInput.value || 0),
            payment_reference: document.getElementById('checkoutPaymentReference').value.trim(),
            split_cash_amount: Number(document.getElementById('splitCashAmount').value || 0),
            split_upi_amount: Number(document.getElementById('splitUpiAmount').value || 0),
            split_card_amount: Number(document.getElementById('splitCardAmount').value || 0),
            split_upi_reference: document.getElementById('splitUpiReference').value.trim(),
            split_card_reference: document.getElementById('splitCardReference').value.trim()
        };

        let checkoutSucceeded = false;
        let serverError = '';

        try {
            const response = await fetch(checkoutUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': checkoutCsrfToken
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                if (response.status === 422 && result.errors) {
                    serverError = Object.values(result.errors)
                        .flat()
                        .join(' ');
                } else {
                    serverError = result.message || 'Unable to complete the sale. Please try again.';
                }

                return;
            }

            if (!result.success || !result.order) {
                serverError = result.message || 'The server returned an unexpected checkout response.';
                return;
            }

            checkoutSucceeded = true;

            const orderNumber = result.order.order_number;

            cart = [];
            billDiscountType = 'percentage';
            billDiscountValue = 0;

            renderCart();
            updateTotals();
            closeCheckoutModal();

            if (!result.invoice_url) {
                serverError = 'Sale saved, but the invoice URL was not returned.';
                return;
            }

            window.location.href = result.invoice_url;

        } catch (error) {
            serverError = error.message || 'A network error occurred. Please try again.';
        } finally {
            checkoutSubmitting = false;
            confirmCheckoutButton.textContent = 'Collect Payment';

            if (!checkoutSucceeded) {
                updateCheckoutBalance();
                checkoutError.textContent = serverError || checkoutError.textContent;
            }
        }
    });
    checkoutButton.addEventListener('click', openCheckoutModal);

    document.getElementById('closeCheckoutModal').addEventListener('click', closeCheckoutModal);
    document.getElementById('cancelCheckoutButton').addEventListener('click', closeCheckoutModal);
    document.getElementById('closeCheckoutOverlay').addEventListener('click', closeCheckoutModal);

    checkoutMethod.addEventListener('change', updateCheckoutPaymentFields);
    checkoutAmountInput.addEventListener('input', updateCheckoutBalance);
    document.getElementById('splitUpiReference').addEventListener('input', updateCheckoutBalance);
    document.getElementById('splitCardReference').addEventListener('input', updateCheckoutBalance);
    document.getElementById('checkoutPaymentReference').addEventListener('input', updateCheckoutBalance);

    checkoutSplitFields.querySelectorAll('input').forEach(function (input) {
        input.addEventListener('input', updateCheckoutBalance);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && checkoutModal.classList.contains('active')) {
            closeCheckoutModal();
        }
    });


});
</script>
@endpush




