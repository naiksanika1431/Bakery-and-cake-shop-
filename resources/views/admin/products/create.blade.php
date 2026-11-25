@extends('layouts.admin.app')

@section('title', 'Add Product')
@section('page-heading', 'Add Product')

@section('content')
<div class="container-fluid px-0 bakery-product-create">

    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}"
           class="text-decoration-none"
           style="color:#4A2616;">
            <i class="bi bi-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4" style="max-width:1200px;">

        <div class="card-header bg-white border-0 p-4">
            <h4 class="mb-1 fw-bold" style="color:#4A2616;">Create New Product</h4>
            <p class="text-muted mb-0">
                Configure product information, GST, variants and branch-wise pricing.
            </p>
        </div>

        <div class="card-body p-4">

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

            @if($categories->isEmpty())
                <div class="alert alert-warning">
                    No active categories available. Create a category first.
                </div>
            @endif

            @if($units->isEmpty())
                <div class="alert alert-warning">
                    No units available. Create units before adding products.
                </div>
            @endif

            @if($branches->isEmpty())
                <div class="alert alert-warning">
                    No active branches are available. Create an active branch first.
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- BASIC PRODUCT INFORMATION --}}
                <h5 class="fw-bold mb-3 section-heading">
                    <i class="bi bi-box-seam me-2"></i>Basic Product Information
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Product Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Example: Chocolate Cake"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Product SKU <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="sku"
                               class="form-control @error('sku') is-invalid @enderror"
                               value="{{ old('sku') }}"
                               placeholder="Example: CAKE-001"
                               required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Barcode</label>
                        <input type="text"
                               name="barcode"
                               class="form-control @error('barcode') is-invalid @enderror"
                               value="{{ old('barcode') }}"
                               placeholder="Leave blank to auto-generate">
                        @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Base Unit <span class="text-danger">*</span>
                        </label>
                        <select name="base_unit_id"
                                class="form-select @error('base_unit_id') is-invalid @enderror"
                                required>
                            <option value="">Select Base Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('base_unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->abbreviation }})
                                </option>
                            @endforeach
                        </select>
                        @error('base_unit_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Product Type <span class="text-danger">*</span>
                        </label>
                        <select name="product_type"
                                class="form-select @error('product_type') is-invalid @enderror"
                                required>
                            <option value="finished_good"
                                {{ old('product_type', 'finished_good') == 'finished_good' ? 'selected' : '' }}>
                                Finished Product
                            </option>
                            <option value="raw_material"
                                {{ old('product_type') == 'raw_material' ? 'selected' : '' }}>
                                Raw Material
                            </option>
                            <option value="semi_finished"
                                {{ old('product_type') == 'semi_finished' ? 'selected' : '' }}>
                                Semi-Finished Product
                            </option>
                            <option value="packaging_material"
                                {{ old('product_type') == 'packaging_material' ? 'selected' : '' }}>
                                Packaging Material
                            </option>
                        </select>
                        @error('product_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Product Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="active"
                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive"
                                {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                </div>

                <hr class="my-4">

                {{-- TAX INFORMATION --}}
                <h5 class="fw-bold mb-3 section-heading">
                    <i class="bi bi-receipt me-2"></i>Tax Information
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">HSN Code</label>
                        <input type="text"
                               name="hsn_code"
                               class="form-control @error('hsn_code') is-invalid @enderror"
                               value="{{ old('hsn_code') }}"
                               placeholder="Enter HSN code">
                        @error('hsn_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            GST Rate (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="gst_rate"
                               class="form-control @error('gst_rate') is-invalid @enderror"
                               value="{{ old('gst_rate', '0.00') }}"
                               min="0"
                               max="100"
                               step="0.01"
                               placeholder="Example: 5.00"
                               required>
                        @error('gst_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Enter the applicable GST rate. This is configurable per product.
                        </small>
                    </div>

                </div>

                <hr class="my-4">

                {{-- DESCRIPTION AND IMAGE --}}
                <h5 class="fw-bold mb-3 section-heading">
                    <i class="bi bi-card-text me-2"></i>Description & Image
                </h5>

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-semibold">Product Description</label>
                        <textarea name="description"
                                  rows="3"
                                  class="form-control"
                                  placeholder="Describe ingredients, flavour, storage information, etc.">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Product Image</label>
                        <input type="file"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">JPG, PNG or WEBP. Maximum size: 2 MB.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                {{-- PRODUCT VARIANTS --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1 section-heading">
                            <i class="bi bi-layers me-2"></i>Product Variants
                        </h5>
                        <small class="text-muted">
                            Add sizes, central pricing and cost for each variant.
                        </small>
                    </div>

                    <button type="button"
                            id="addVariant"
                            class="btn btn-sm text-white"
                            style="background:#4A2616;">
                        <i class="bi bi-plus-lg me-1"></i> Add Variant
                    </button>
                </div>

                <div id="variantsContainer">

                    @php
                        $oldVariants = old('variants', [
                            [
                                'variant_name' => '',
                                'sku' => '',
                                'barcode' => '',
                                'unit_id' => '',
                                'default_selling_price' => '',
                                'cost_price' => '',
                                'branches' => [],
                            ]
                        ]);
                    @endphp

                    @foreach($oldVariants as $index => $variant)

                        <div class="variant-card border rounded-3 p-3 mb-4"
                             data-variant-index="{{ $index }}"
                             style="background:#fffefa;">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 variant-heading">
                                    Variant {{ $loop->iteration }}
                                </h6>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger remove-variant">
                                    <i class="bi bi-trash me-1"></i> Remove
                                </button>
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Variant Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="variants[{{ $index }}][variant_name]"
                                           class="form-control"
                                           value="{{ $variant['variant_name'] ?? '' }}"
                                           placeholder="Example: 500 Gram / 1 Kg / 2 Kg"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Variant SKU <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="variants[{{ $index }}][sku]"
                                           class="form-control"
                                           value="{{ $variant['sku'] ?? '' }}"
                                           placeholder="Example: CAKE-001-500G"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Variant Barcode</label>
                                    <input type="text"
                                           name="variants[{{ $index }}][barcode]"
                                           class="form-control"
                                           value="{{ $variant['barcode'] ?? '' }}"
                                           placeholder="Leave blank to auto-generate">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Variant Unit <span class="text-danger">*</span>
                                    </label>
                                    <select name="variants[{{ $index }}][unit_id]"
                                            class="form-select"
                                            required>
                                        <option value="">Select Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ ($variant['unit_id'] ?? '') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->abbreviation }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Default Selling Price (₹) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           name="variants[{{ $index }}][default_selling_price]"
                                           class="form-control default-price"
                                           value="{{ $variant['default_selling_price'] ?? '' }}"
                                           min="0"
                                           max="9999999999.99"
                                           step="0.01"
                                           placeholder="Example: 650"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Cost Price (₹) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           name="variants[{{ $index }}][cost_price]"
                                           class="form-control"
                                           value="{{ $variant['cost_price'] ?? '' }}"
                                           min="0"
                                           max="9999999999.99"
                                           step="0.01"
                                           placeholder="Example: 400"
                                           required>
                                </div>

                            </div>

                            {{-- BRANCH CONFIGURATION --}}
                            <div class="mt-4 pt-3 border-top">

                                <h6 class="fw-bold mb-1" style="color:#4A2616;">
                                    <i class="bi bi-shop me-2"></i>Branch-wise Configuration
                                </h6>

                                <p class="text-muted small mb-3">
                                    Configure this variant separately for every active branch.
                                </p>

                                @foreach($branches as $branchIndex => $branch)
                                    @php
                                        $branchConfig = collect($variant['branches'] ?? [])
                                            ->firstWhere('branch_id', $branch->id) ?? [];
                                        $branchPrefix = "variants[$index][branches][$branchIndex]";
                                    @endphp

                                    <div class="branch-config border rounded-3 p-3 mb-3"
                                         style="background:#fff;">

                                        <input type="hidden"
                                               name="{{ $branchPrefix }}[branch_id]"
                                               value="{{ $branch->id }}">

                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <strong>
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ $branch->branch_name }}
                                            </strong>
                                            <span class="badge bg-light text-dark border">
                                                {{ $branch->branch_code }}
                                            </span>
                                        </div>

                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    Branch Selling Price (₹) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number"
                                                       name="{{ $branchPrefix }}[selling_price]"
                                                       class="form-control branch-price"
                                                       value="{{ $branchConfig['selling_price'] ?? ($variant['default_selling_price'] ?? '') }}"
                                                       min="0"
                                                       max="9999999999.99"
                                                       step="0.01"
                                                       placeholder="Enter branch price"
                                                       required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Minimum Stock</label>
                                                <input type="number"
                                                       name="{{ $branchPrefix }}[min_stock]"
                                                       class="form-control"
                                                       value="{{ $branchConfig['min_stock'] ?? '0' }}"
                                                       min="0"
                                                       max="999999999.999"
                                                       step="0.001">
                                            </div>

                                            @php
                                                $branchEnabled = (string) ($branchConfig['is_enabled'] ?? '1');
                                                $branchAvailable = (string) ($branchConfig['is_available'] ?? '1');
                                                $branchOnline = (string) ($branchConfig['online_available'] ?? '1');
                                            @endphp

                                            <div class="col-md-4">
                                                <input type="hidden"
                                                       name="{{ $branchPrefix }}[is_enabled]"
                                                       value="0">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           name="{{ $branchPrefix }}[is_enabled]"
                                                           value="1"
                                                           class="form-check-input"
                                                           {{ $branchEnabled === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Enabled for this branch</label>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="hidden"
                                                       name="{{ $branchPrefix }}[is_available]"
                                                       value="0">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           name="{{ $branchPrefix }}[is_available]"
                                                           value="1"
                                                           class="form-check-input"
                                                           {{ $branchAvailable === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Available for in-store sale</label>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="hidden"
                                                       name="{{ $branchPrefix }}[online_available]"
                                                       value="0">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           name="{{ $branchPrefix }}[online_available]"
                                                           value="1"
                                                           class="form-check-input"
                                                           {{ $branchOnline === '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Available online</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    @endforeach

                </div>

                <div class="alert alert-light border small">
                    <i class="bi bi-info-circle me-1"></i>
                    Default selling price is the central reference price. Each branch can have its own selling price and stock settings.
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-light border px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn text-white px-4"
                            style="background:#4A2616;"
                            {{ $units->isEmpty() || $categories->isEmpty() || $branches->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-check-lg me-2"></i> Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- DYNAMIC VARIANT TEMPLATE --}}
<template id="variantTemplate">
    <div class="variant-card border rounded-3 p-3 mb-4"
         data-variant-index="__INDEX__"
         style="background:#fffefa;">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 variant-heading">New Variant</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                <i class="bi bi-trash me-1"></i> Remove
            </button>
        </div>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Variant Name *</label>
                <input type="text"
                       name="variants[__INDEX__][variant_name]"
                       class="form-control"
                       placeholder="Example: 500 Gram / 1 Kg"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Variant SKU *</label>
                <input type="text"
                       name="variants[__INDEX__][sku]"
                       class="form-control"
                       placeholder="Example: CAKE-001-500G"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Variant Barcode</label>
                <input type="text"
                       name="variants[__INDEX__][barcode]"
                       class="form-control"
                       placeholder="Leave blank to auto-generate">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Variant Unit *</label>
                <select name="variants[__INDEX__][unit_id]" class="form-select" required>
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->name }} ({{ $unit->abbreviation }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Default Selling Price (₹) *</label>
                <input type="number"
                       name="variants[__INDEX__][default_selling_price]"
                       class="form-control default-price"
                       min="0"
                       max="9999999999.99"
                       step="0.01"
                       placeholder="Example: 650"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Cost Price (₹) *</label>
                <input type="number"
                       name="variants[__INDEX__][cost_price]"
                       class="form-control"
                       min="0"
                       max="9999999999.99"
                       step="0.01"
                       placeholder="Example: 400"
                       required>
            </div>

        </div>

        <div class="mt-4 pt-3 border-top">
            <h6 class="fw-bold mb-1" style="color:#4A2616;">
                <i class="bi bi-shop me-2"></i>Branch-wise Configuration
            </h6>
            <p class="text-muted small mb-3">
                Configure this variant for every active branch.
            </p>

            @foreach($branches as $branchIndex => $branch)
                <div class="branch-config border rounded-3 p-3 mb-3"
                     style="background:#fff;">

                    <input type="hidden"
                           name="variants[__INDEX__][branches][{{ $branchIndex }}][branch_id]"
                           value="{{ $branch->id }}">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>
                            <i class="bi bi-geo-alt me-1"></i>{{ $branch->branch_name }}
                        </strong>
                        <span class="badge bg-light text-dark border">{{ $branch->branch_code }}</span>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Selling Price (₹) *</label>
                            <input type="number"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][selling_price]"
                                   class="form-control branch-price"
                                   min="0"
                                   max="9999999999.99"
                                   step="0.01"
                                   placeholder="Enter branch price"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Minimum Stock</label>
                            <input type="number"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][min_stock]"
                                   class="form-control"
                                   value="0"
                                   min="0"
                                   max="999999999.999"
                                   step="0.001">
                        </div>

                        <div class="col-md-4">
                            <input type="hidden"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][is_enabled]"
                                   value="0">
                            <div class="form-check">
                                <input type="checkbox"
                                       name="variants[__INDEX__][branches][{{ $branchIndex }}][is_enabled]"
                                       value="1"
                                       class="form-check-input"
                                       checked>
                                <label class="form-check-label">Enabled for this branch</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <input type="hidden"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][is_available]"
                                   value="0">
                            <div class="form-check">
                                <input type="checkbox"
                                       name="variants[__INDEX__][branches][{{ $branchIndex }}][is_available]"
                                       value="1"
                                       class="form-check-input"
                                       checked>
                                <label class="form-check-label">Available for in-store sale</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <input type="hidden"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][online_available]"
                                   value="0">
                            <div class="form-check">
                                <input type="checkbox"
                                       name="variants[__INDEX__][branches][{{ $branchIndex }}][online_available]"
                                       value="1"
                                       class="form-check-input"
                                       checked>
                                <label class="form-check-label">Available online</label>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</template>

<style>
.bakery-product-create .section-heading {
    color: #4A2616;
}

.bakery-product-create .form-control:focus,
.bakery-product-create .form-select:focus {
    border-color: #9b725c;
    box-shadow: 0 0 0 .2rem rgba(74, 38, 22, .10);
}

.bakery-product-create .branch-config {
    border-color: #eadfd6 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('variantsContainer');
    const addButton = document.getElementById('addVariant');
    const template = document.getElementById('variantTemplate');

    if (!container || !addButton || !template) {
        return;
    }

    let nextIndex = Math.max(
        -1,
        ...Array.from(container.querySelectorAll('.variant-card'))
            .map(card => Number(card.dataset.variantIndex))
    ) + 1;

    function updateVariantHeadings() {
        const cards = container.querySelectorAll('.variant-card');

        cards.forEach((card, index) => {
            card.querySelector('.variant-heading').textContent =
                'Variant ' + (index + 1);

            card.querySelector('.remove-variant').disabled =
                cards.length === 1;
        });
    }

    addButton.addEventListener('click', function () {

        const html = template.innerHTML.replaceAll('__INDEX__', nextIndex);

        container.insertAdjacentHTML('beforeend', html);

        const newCard = container.querySelector(
            '.variant-card[data-variant-index="' + nextIndex + '"]'
        );

        const defaultPrice = newCard.querySelector('.default-price');

        defaultPrice.addEventListener('input', function () {
            newCard.querySelectorAll('.branch-price').forEach(function (price) {
                if (!price.dataset.manuallyChanged) {
                    price.value = defaultPrice.value;
                }
            });
        });

        newCard.querySelectorAll('.branch-price').forEach(function (price) {
            price.addEventListener('input', function () {
                price.dataset.manuallyChanged = '1';
            });
        });

        nextIndex++;
        updateVariantHeadings();
    });

    container.addEventListener('click', function (event) {

        const removeButton = event.target.closest('.remove-variant');

        if (!removeButton || container.querySelectorAll('.variant-card').length <= 1) {
            return;
        }

        removeButton.closest('.variant-card').remove();
        updateVariantHeadings();
    });

    container.querySelectorAll('.variant-card').forEach(function (card) {
        const defaultPrice = card.querySelector('.default-price');

        if (!defaultPrice) {
            return;
        }

        card.querySelectorAll('.branch-price').forEach(function (price) {
            price.addEventListener('input', function () {
                price.dataset.manuallyChanged = '1';
            });
        });
    });

    updateVariantHeadings();
});
</script>
@endsection
