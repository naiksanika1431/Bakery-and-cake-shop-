@extends('layouts.admin.app')

@section('title', $order ? 'Edit Custom Cake Order' : 'New Custom Cake Order')

@section('content')
@php
    $cake = $order?->customCakeDetail;
    $accountId = old('customer_account_id', $order?->customer_account_id);
    $customerName = old('customer_name', $order?->customer_name);
    $customerPhone = old('customer_phone', $order?->customer_phone);
    $customerEmail = old('customer_email', $order?->customer_email);
    $requiredDate = old('required_date', $cake?->required_date?->format('Y-m-d'));
    $requiredTime = old('required_time', $cake?->required_time ? \Illuminate\Support\Carbon::parse($cake->required_time)->format('H:i') : '');
    $fulfilment = old('fulfilment_type', $cake?->fulfilment_type ?? 'pickup');
    $isEdit = (bool) $order;
@endphp

<div class="cco-page">
    <div class="cco-header">
        <div>
            <a href="{{ route('admin.custom-cake-orders.index') }}" class="cco-back">
                <i class="bi bi-arrow-left"></i> Back to Custom Cake Orders
            </a>
            <h2>{{ $isEdit ? 'Edit Custom Cake Order' : 'Create Custom Cake Order' }}</h2>
            <p>{{ $isEdit ? 'Update customer requirements while the quotation is still a draft.' : 'Enter the customer information and cake requirements to create a draft order.' }}</p>
        </div>

        @if($isEdit)
            <span class="cco-order-number">{{ $order->order_number }}</span>
        @endif
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ $isEdit ? route('admin.custom-cake-orders.update', $order->id) : route('admin.custom-cake-orders.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <section class="cco-card">
            <div class="cco-section-heading">
                <span class="cco-section-icon"><i class="bi bi-person"></i></span>
                <div>
                    <h5>Customer Information</h5>
                    <p>Select an existing customer account or enter customer details manually.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Existing Customer Account <span class="cco-muted">(Optional)</span></label>
                    <select name="customer_account_id" id="customer_account_id" class="form-select">
                        <option value="">Manual customer entry / No login account</option>
                        @foreach($customerAccounts as $account)
                            <option
                                value="{{ $account->id }}"
                                data-name="{{ $account->name }}"
                                data-phone="{{ $account->phone }}"
                                data-email="{{ $account->email }}"
                                @selected((string) $accountId === (string) $account->id)
                            >
                                {{ $account->name }} — {{ $account->phone }} ({{ $account->email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="cco-help">Selecting an account links this order to the customer's login.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control"
                        value="{{ $customerName }}" maxlength="255" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                        value="{{ $customerPhone }}" maxlength="20" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="customer_email" id="customer_email" class="form-control"
                        value="{{ $customerEmail }}" maxlength="255">
                </div>
            </div>
        </section>

        <section class="cco-card">
            <div class="cco-section-heading">
                <span class="cco-section-icon"><i class="bi bi-cake2"></i></span>
                <div>
                    <h5>Cake Requirements</h5>
                    <p>Capture the customer's requested cake specifications.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Occasion <span class="text-danger">*</span></label>
                    <input type="text" name="occasion" class="form-control"
                        value="{{ old('occasion', $cake?->occasion) }}" placeholder="Birthday, Wedding, Anniversary..." required maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Required Date <span class="text-danger">*</span></label>
                    <input type="date" name="required_date" class="form-control"
                        value="{{ $requiredDate }}" min="{{ now()->toDateString() }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Required Time <span class="text-danger">*</span></label>
                    <input type="time" name="required_time" class="form-control"
                        value="{{ $requiredTime }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Number of Servings <span class="text-danger">*</span></label>
                    <input type="number" name="servings" class="form-control"
                        value="{{ old('servings', $cake?->servings) }}" min="1" max="10000" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Flavour <span class="text-danger">*</span></label>
                    <input type="text" name="flavour" class="form-control"
                        value="{{ old('flavour', $cake?->flavour) }}" placeholder="Chocolate, Vanilla, Butterscotch..." required maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Weight (kg) <span class="text-danger">*</span></label>
                    <input type="number" name="weight" class="form-control"
                        value="{{ old('weight', $cake?->weight_kg) }}" min="0.5" max="100" step="0.01" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cake Size</label>
                    <input type="text" name="cake_size" class="form-control"
                        value="{{ old('cake_size', $cake?->cake_size) }}" placeholder="Small, Medium, Large or custom size" maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cake Shape <span class="text-danger">*</span></label>
                    <input type="text" name="shape" class="form-control"
                        value="{{ old('shape', $cake?->shape) }}" placeholder="Round, Heart, Number, Custom..." required maxlength="255">
                    <small class="cco-help">Any custom shape is allowed.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Dimensions</label>
                    <input type="text" name="dimensions" class="form-control"
                        value="{{ old('dimensions', $cake?->dimensions) }}" placeholder="Example: 12 × 10 × 8 inches" maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Shape Complexity</label>
                    <select name="shape_complexity" class="form-select">
                        <option value="">Not specified</option>
                        @foreach(['simple' => 'Simple', 'medium' => 'Medium', 'complex' => 'Complex', 'very_complex' => 'Very Complex'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('shape_complexity', $cake?->shape_complexity) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="cco-card">
            <div class="cco-section-heading">
                <span class="cco-section-icon"><i class="bi bi-palette"></i></span>
                <div>
                    <h5>Design & Decoration</h5>
                    <p>Record the requested theme, colours, cream and decoration details.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Theme</label>
                    <input type="text" name="theme" class="form-control"
                        value="{{ old('theme', $cake?->theme) }}" placeholder="Floral, Cartoon, Minimal..." maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Colour</label>
                    <input type="text" name="colour" class="form-control"
                        value="{{ old('colour', $cake?->colour) }}" placeholder="Pink, Blue, White..." maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cream Type</label>
                    <input type="text" name="cream_type" class="form-control"
                        value="{{ old('cream_type', $cake?->cream_type) }}" placeholder="Whipped cream, Buttercream..." maxlength="255">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Decorations</label>
                    <textarea name="decorations" class="form-control" rows="3" maxlength="2000"
                        placeholder="Describe decoration requirements...">{{ old('decorations', $cake?->decorations) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cake Message</label>
                    <textarea name="cake_message" class="form-control" rows="3" maxlength="500"
                        placeholder="Message to be written on the cake...">{{ old('cake_message', $cake?->cake_message) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Reference Image</label>
                    <input type="file" name="reference_image" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                    <small class="cco-help">JPG, JPEG, PNG or WEBP. Maximum file size: 5 MB.</small>

                    @if($cake?->reference_image_path)
                        <div class="cco-existing-image mt-2">
                            <span>Current reference image:</span>
                            <a href="{{ asset('storage/' . $cake->reference_image_path) }}" target="_blank" rel="noopener">
                                View uploaded image <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="cco-card">
            <div class="cco-section-heading">
                <span class="cco-section-icon"><i class="bi bi-truck"></i></span>
                <div>
                    <h5>Fulfilment Details</h5>
                    <p>Choose customer pickup or delivery.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Fulfilment Type <span class="text-danger">*</span></label>
                    <select name="fulfilment_type" id="fulfilment_type" class="form-select" required>
                        <option value="pickup" @selected($fulfilment === 'pickup')>Store Pickup</option>
                        <option value="delivery" @selected($fulfilment === 'delivery')>Home Delivery</option>
                    </select>
                </div>
            </div>

            <div id="delivery_fields" class="row g-3 mt-1" style="{{ $fulfilment === 'delivery' ? '' : 'display:none;' }}">
                <div class="col-12">
                    <label class="form-label">Delivery Address <span class="text-danger">*</span></label>
                    <textarea name="delivery_address" id="delivery_address" class="form-control" rows="2" maxlength="1000">{{ old('delivery_address', $order?->delivery_address) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" name="delivery_city" id="delivery_city" class="form-control"
                        value="{{ old('delivery_city', $order?->delivery_city) }}" maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">State <span class="text-danger">*</span></label>
                    <input type="text" name="delivery_state" id="delivery_state" class="form-control"
                        value="{{ old('delivery_state', $order?->delivery_state) }}" maxlength="255">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                    <input type="text" name="delivery_pincode" id="delivery_pincode" class="form-control"
                        value="{{ old('delivery_pincode', $order?->delivery_pincode) }}" maxlength="20">
                </div>
            </div>
        </section>

        <section class="cco-card">
            <div class="cco-section-heading">
                <span class="cco-section-icon"><i class="bi bi-chat-left-text"></i></span>
                <div>
                    <h5>Special Instructions</h5>
                    <p>Any additional customer requirements or instructions.</p>
                </div>
            </div>

            <textarea name="special_instructions" class="form-control" rows="4" maxlength="5000"
                placeholder="Enter special instructions...">{{ old('special_instructions', $cake?->special_instructions) }}</textarea>
        </section>

        <div class="cco-note">
            <i class="bi bi-info-circle"></i>
            <div>
                <strong>Quotation and pricing</strong>
                <p class="mb-0">This order will be saved as a draft with zero pricing. You can enter the final price and requested advance separately from the order details page. No quotation will be sent automatically.</p>
            </div>
        </div>

        <div class="cco-actions">
            <a href="{{ route('admin.custom-cake-orders.index') }}" class="btn cco-cancel">Cancel</a>
            <button type="submit" class="btn cco-submit">
                <i class="bi bi-check-lg me-1"></i>
                {{ $isEdit ? 'Save Changes' : 'Create Draft Order' }}
            </button>
        </div>
    </form>
</div>

<style>
    .cco-page {
        --cco-ink: #263b35;
        --cco-sage: #527f70;
        --cco-paper: #fffefa;
        --cco-bg: #f7f6f0;
        color: var(--cco-ink);
        padding-bottom: 35px;
    }

    .cco-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .cco-back {
        display: inline-block;
        color: var(--cco-sage);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .cco-back:hover { color: var(--cco-ink); }

    .cco-header h2 {
        font-weight: 750;
        font-size: 27px;
        margin: 0 0 6px;
        color: var(--cco-ink);
    }

    .cco-header p { color: #718078; margin: 0; }

    .cco-order-number {
        background: #e9f0eb;
        color: var(--cco-sage);
        padding: 9px 14px;
        border-radius: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .cco-card {
        background: var(--cco-paper);
        border: 1px solid #e9e9df;
        border-radius: 15px;
        padding: 23px;
        margin-bottom: 18px;
        box-shadow: 0 3px 14px rgba(38,59,53,.035);
    }

    .cco-section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 22px;
    }

    .cco-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #eaf0eb;
        color: var(--cco-sage);
        font-size: 19px;
        flex-shrink: 0;
    }

    .cco-section-heading h5 {
        margin: 0 0 3px;
        font-weight: 750;
        color: var(--cco-ink);
    }

    .cco-section-heading p {
        margin: 0;
        color: #87928b;
        font-size: 13px;
    }

    .cco-page .form-label {
        color: #40554c;
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 7px;
    }

    .cco-page .form-control,
    .cco-page .form-select {
        border: 1px solid #dfe5df;
        border-radius: 9px;
        min-height: 43px;
        color: #30453c;
        background-color: #fff;
        box-shadow: none;
    }

    .cco-page textarea.form-control { min-height: 90px; }

    .cco-page .form-control:focus,
    .cco-page .form-select:focus {
        border-color: #82a797;
        box-shadow: 0 0 0 3px rgba(82,127,112,.11);
    }

    .cco-help, .cco-muted {
        color: #89948d;
        font-size: 12px;
    }

    .cco-existing-image {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        font-size: 13px;
    }

    .cco-existing-image a {
        color: var(--cco-sage);
        font-weight: 650;
        text-decoration: none;
    }

    .cco-note {
        display: flex;
        gap: 13px;
        padding: 17px 19px;
        border-radius: 12px;
        background: #edf3ee;
        color: #40594d;
        margin-bottom: 20px;
    }

    .cco-note > i { font-size: 20px; color: var(--cco-sage); }
    .cco-note p { font-size: 13px; margin-top: 4px; }

    .cco-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .cco-cancel, .cco-submit {
        border-radius: 9px;
        padding: 11px 20px;
        font-weight: 650;
    }

    .cco-cancel {
        border: 1px solid #dfe5df;
        color: #58685f;
        background: #fff;
    }

    .cco-submit {
        color: #fff;
        background: #527f70;
        border: 1px solid #527f70;
    }

    .cco-submit:hover {
        color: #fff;
        background: #3e6658;
        border-color: #3e6658;
    }

    @media(max-width: 767px) {
        .cco-header { align-items: flex-start; flex-direction: column; }
        .cco-card { padding: 17px; }
        .cco-actions { flex-direction: column-reverse; }
        .cco-actions .btn { width: 100%; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const accountSelect = document.getElementById('customer_account_id');
    const nameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('customer_phone');
    const emailInput = document.getElementById('customer_email');

    accountSelect.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];

        if (!this.value) {
            return;
        }

        nameInput.value = option.dataset.name || '';
        phoneInput.value = option.dataset.phone || '';
        emailInput.value = option.dataset.email || '';
    });

    const fulfilmentSelect = document.getElementById('fulfilment_type');
    const deliveryFields = document.getElementById('delivery_fields');

    const deliveryInputs = [
        document.getElementById('delivery_address'),
        document.getElementById('delivery_city'),
        document.getElementById('delivery_state'),
        document.getElementById('delivery_pincode')
    ];

    function updateDeliveryFields() {
        const isDelivery = fulfilmentSelect.value === 'delivery';

        deliveryFields.style.display = isDelivery ? '' : 'none';

        deliveryInputs.forEach(function (input) {
            input.required = isDelivery;
        });
    }

    fulfilmentSelect.addEventListener('change', updateDeliveryFields);
    updateDeliveryFields();
});
</script>
@endsection