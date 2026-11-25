@extends('layouts.admin.app')

@section('title', 'Edit Supplier')
@section('page-heading', 'Edit Supplier')

@section('content')

<style>
.supplier-form-page {
    --brown: #4A2616;
    --cream: #f9efe3;
    --paper: #fffaf4;
    --text: #4a2f26;
    --line: #ead8c8;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
}
.supplier-form-page * { box-sizing: border-box; }

.supplier-form-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: white;
    padding: 24px 28px;
    border-radius: 16px;
    margin-bottom: 22px;
}
.supplier-form-hero h2 {
    color: white;
    margin: 0 0 6px;
    font-size: 1.7rem;
}
.supplier-form-hero p { margin: 0; color: #f3dfd0; }

.supplier-form-card {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 25px;
}
.supplier-section { margin-bottom: 27px; }
.supplier-section h3 {
    color: var(--brown);
    font-size: 1.2rem;
    padding-bottom: 11px;
    border-bottom: 1px solid var(--line);
    margin-bottom: 18px;
}
.supplier-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px;
}
.supplier-field label {
    display: block;
    font-size: .84rem;
    font-weight: 700;
    margin-bottom: 7px;
}
.supplier-field input,
.supplier-field select,
.supplier-field textarea {
    width: 100%;
    border: 1px solid var(--line);
    border-radius: 8px;
    background: white;
    padding: 11px 12px;
    color: var(--text);
    font-family: inherit;
    outline: none;
}
.supplier-field input:focus,
.supplier-field select:focus,
.supplier-field textarea:focus {
    border-color: #9b7055;
    box-shadow: 0 0 0 3px rgba(117,69,47,.09);
}
.supplier-field textarea {
    min-height: 85px;
    resize: vertical;
}
.supplier-field.full { grid-column: 1 / -1; }
.supplier-required { color: #b64236; }
.supplier-error {
    color: #b64236;
    font-size: .78rem;
    margin-top: 5px;
}
.supplier-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-top: 1px solid var(--line);
    padding-top: 20px;
}
.supplier-btn {
    border: 0;
    border-radius: 9px;
    padding: 11px 20px;
    text-decoration: none;
    font-weight: 700;
    font-size: .88rem;
    cursor: pointer;
}
.supplier-btn-save {
    background: linear-gradient(135deg, #75452f, #4A2616);
    color: white;
}
.supplier-btn-cancel {
    background: #eee2d6;
    color: var(--brown);
}
@media(max-width: 650px) {
    .supplier-form-grid { grid-template-columns: 1fr; }
    .supplier-field.full { grid-column: auto; }
    .supplier-form-card { padding: 16px; }
}
</style>

<div class="supplier-form-page">

    <div class="supplier-form-hero">
        <h2>Edit Supplier</h2>
        <p>Update supplier details and business information.</p>
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

    <div class="supplier-form-card">

        <form action="{{ route('admin.suppliers.update', $supplier->id) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="supplier-section">
                <h3>Basic Information</h3>

                <div class="supplier-form-grid">

                    <div class="supplier-field">
                        <label>Supplier Code <span class="supplier-required">*</span></label>
                        <input type="text" name="supplier_code"
                               value="{{ old('supplier_code', $supplier->supplier_code) }}" required>
                        @error('supplier_code')
                            <div class="supplier-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="supplier-field">
                        <label>Supplier Name <span class="supplier-required">*</span></label>
                        <input type="text" name="supplier_name"
                               value="{{ old('supplier_name', $supplier->supplier_name) }}" required>
                        @error('supplier_name')
                            <div class="supplier-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="supplier-field">
                        <label>Supplier Type</label>
                        <select name="supplier_type">
                            <option value="">Select Supplier Type</option>
                            @foreach(['Raw Material', 'Dairy Products', 'Packaging Material', 'Bakery Ingredients', 'Equipment', 'Other'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('supplier_type', $supplier->supplier_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="supplier-field">
                        <label>Status <span class="supplier-required">*</span></label>
                        <select name="status" required>
                            <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="supplier-section">
                <h3>Contact Information</h3>

                <div class="supplier-form-grid">

                    <div class="supplier-field">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person"
                               value="{{ old('contact_person', $supplier->contact_person) }}">
                    </div>

                    <div class="supplier-field">
                        <label>Mobile Number <span class="supplier-required">*</span></label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $supplier->phone) }}" required>
                        @error('phone')
                            <div class="supplier-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="supplier-field">
                        <label>Alternate Mobile Number</label>
                        <input type="text" name="alternate_phone"
                               value="{{ old('alternate_phone', $supplier->alternate_phone) }}">
                    </div>

                    <div class="supplier-field">
                        <label>Email Address</label>
                        <input type="email" name="email"
                               value="{{ old('email', $supplier->email) }}">
                        @error('email')
                            <div class="supplier-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="supplier-section">
                <h3>Business & Tax Information</h3>

                <div class="supplier-form-grid">

                    <div class="supplier-field">
                        <label>GST Number</label>
                        <input type="text" name="gst_number"
                               value="{{ old('gst_number', $supplier->gst_number) }}">
                    </div>

                    <div class="supplier-field">
                        <label>PAN Number</label>
                        <input type="text" name="pan_number"
                               value="{{ old('pan_number', $supplier->pan_number) }}">
                    </div>

                </div>
            </div>

            <div class="supplier-section">
                <h3>Address Details</h3>

                <div class="supplier-form-grid">

                    <div class="supplier-field full">
                        <label>Business Address</label>
                        <textarea name="address">{{ old('address', $supplier->address) }}</textarea>
                    </div>

                    <div class="supplier-field">
                        <label>City</label>
                        <input type="text" name="city"
                               value="{{ old('city', $supplier->city) }}">
                    </div>

                    <div class="supplier-field">
                        <label>State</label>
                        <input type="text" name="state"
                               value="{{ old('state', $supplier->state) }}">
                    </div>

                    <div class="supplier-field">
                        <label>Pincode</label>
                        <input type="text" name="pincode"
                               value="{{ old('pincode', $supplier->pincode) }}">
                    </div>

                </div>
            </div>

            <div class="supplier-section">
                <h3>Payment Information</h3>

                <div class="supplier-form-grid">

                    <div class="supplier-field">
                        <label>Payment Terms</label>
                        <select name="payment_terms">
                            <option value="">Select Payment Terms</option>
                            @foreach(['Immediate', '7 Days', '15 Days', '30 Days', '45 Days', '60 Days'] as $term)
                                <option value="{{ $term }}"
                                    {{ old('payment_terms', $supplier->payment_terms) == $term ? 'selected' : '' }}>
                                    {{ $term }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="supplier-field">
                        <label>Credit Limit (₹)</label>
                        <input type="number" name="credit_limit"
                               value="{{ old('credit_limit', $supplier->credit_limit) }}"
                               min="0" step="0.01">
                    </div>

                    <div class="supplier-field full">
                        <label>Notes</label>
                        <textarea name="notes">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>

                </div>
            </div>

            <div class="supplier-form-actions">
                <a href="{{ route('admin.suppliers.index') }}"
                   class="supplier-btn supplier-btn-cancel">
                    Cancel
                </a>

                <button type="submit" class="supplier-btn supplier-btn-save">
                    Update Supplier
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
