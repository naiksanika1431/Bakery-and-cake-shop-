
@extends('layouts.admin.app')

@section('title', 'Edit Product')
@section('page-heading', 'Edit Product')

@section('content')

@php
    $oldVariants = old('variants', $product->productVariants->map(function ($variant) use ($branches) {
        $branchSettings = [];

        foreach ($branches as $branchIndex => $branch) {
            $branchProduct = $variant->branchProducts->firstWhere('branch_id', $branch->id);

            $branchSettings[$branchIndex] = [
                'branch_id' => $branch->id,
                'selling_price' => $branchProduct?->selling_price ?? $variant->default_selling_price ?? '',
                'min_stock' => $branchProduct?->min_stock ?? '0',
                'is_enabled' => $branchProduct ? (int) $branchProduct->is_enabled : 1,
                'is_available' => $branchProduct ? (int) $branchProduct->is_available : 1,
                'online_available' => $branchProduct ? (int) $branchProduct->online_available : 1,
            ];
        }

        return [
            'id' => $variant->id,
            'variant_name' => $variant->variant_name,
            'sku' => $variant->sku,
            'barcode' => $variant->barcode,
            'unit_id' => $variant->unit_id,
            'default_selling_price' => $variant->default_selling_price ?? '',
            'cost_price' => $variant->cost_price ?? '0',
            'branches' => $branchSettings,
        ];
    })->toArray());
@endphp

<div class="container-fluid px-0 bakery-product-edit">

    <style>
        .bakery-product-edit {
            color: #38251c;
        }

        .bakery-product-edit .product-card {
            max-width: 1200px;
            border: 0;
            border-radius: 18px;
            box-shadow: 0 5px 25px rgba(74, 38, 22, .07);
        }

        .bakery-product-edit .section-title {
            color: #4A2616;
            font-weight: 700;
        }

        .bakery-product-edit .form-label {
            font-weight: 600;
            color: #49362d;
        }

        .bakery-product-edit .form-control:focus,
        .bakery-product-edit .form-select:focus {
            border-color: #9b725d;
            box-shadow: 0 0 0 .2rem rgba(74, 38, 22, .10);
        }

        .bakery-product-edit .variant-card {
            background: #fffefa;
            border: 1px solid #e8ddd5 !important;
            border-radius: 14px;
        }

        .bakery-product-edit .branch-card {
            background: #f9f5f0;
            border: 1px solid #eadfd5;
            border-radius: 12px;
        }

        .bakery-product-edit .btn-bakery {
            background: #4A2616;
            color: white;
        }

        .bakery-product-edit .btn-bakery:hover {
            background: #633923;
            color: white;
        }

        .bakery-product-edit .current-image {
            width: 150px;
            height: 130px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e8ddd5;
        }

        .bakery-product-edit .info-note {
            background: #f9f5f0;
            border: 1px solid #eadfd5;
            border-radius: 10px;
            padding: 12px 15px;
            color: #725847;
            font-size: 13px;
        }

        .bakery-product-edit .form-text {
            font-size: 12px;
        }

        .bakery-product-edit .barcode-value {
            font-family: monospace;
            letter-spacing: .5px;
        }
    </style>

    {{-- Back Navigation --}}
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}"
           class="text-decoration-none"
           style="color:#4A2616;">
            <i class="bi bi-arrow-left me-2"></i> Back to Products
        </a>
    </div>

    <div class="card product-card">

        {{-- Header --}}
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4 class="fw-bold mb-1">Edit Product</h4>
                    <p class="text-muted mb-0">
                        Update product information, variants, pricing and branch configurations.
                    </p>
                </div>

                <span class="badge bg-light text-dark border px-3 py-2">
                    Product ID: #{{ $product->id }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Please correct the following errors:
                    </strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- PRODUCT INFORMATION --}}
                <div class="mb-4">
                    <h5 class="section-title mb-1">Product Information</h5>
                    <p class="text-muted small mb-3">
                        Basic information and tax configuration for this product.
                    </p>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Product Name *</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $product->name) }}"
                                   placeholder="Enter product name"
                                   required>

                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Product SKU *</label>
                            <input type="text"
                                   name="sku"
                                   class="form-control"
                                   value="{{ old('sku', $product->sku) }}"
                                   placeholder="Enter product SKU"
                                   required>

                            @error('sku')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Product Barcode</label>
                            <input type="text"
                                   name="barcode"
                                   class="form-control barcode-value"
                                   value="{{ old('barcode', $product->barcode) }}"
                                   placeholder="Leave blank to auto-generate">

                            <small class="form-text text-muted">
                                Existing barcode is preserved if left blank.
                            </small>

                            @error('barcode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('category_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Base Unit *</label>
                            <select name="base_unit_id" class="form-select" required>
                                <option value="">Select Base Unit</option>

                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('base_unit_id', $product->base_unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->abbreviation }})
                                    </option>
                                @endforeach

                            </select>

                            @error('base_unit_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Product Type *</label>

                            @php
                                $productTypes = [
                                    'finished_good' => 'Finished Good',
                                    'raw_material' => 'Raw Material',
                                    'semi_finished' => 'Semi Finished',
                                    'packaging_material' => 'Packaging Material',
                                ];
                            @endphp

                            <select name="product_type" class="form-select" required>
                                <option value="">Select Product Type</option>

                                @foreach($productTypes as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('product_type', $product->product_type) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach

                            </select>

                            @error('product_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">HSN Code</label>
                            <input type="text"
                                   name="hsn_code"
                                   class="form-control"
                                   value="{{ old('hsn_code', $product->hsn_code) }}"
                                   placeholder="Enter HSN code"
                                   maxlength="20">

                            @error('hsn_code')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">GST Rate (%) *</label>
                            <input type="number"
                                   name="gst_rate"
                                   class="form-control"
                                   value="{{ old('gst_rate', $product->gst_rate) }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   required>

                            @error('gst_rate')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Product Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="active"
                                    {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>

                            @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Product Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Describe the product...">{{ old('description', $product->description) }}</textarea>

                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <hr class="my-4">

                {{-- PRODUCT IMAGE --}}
                <div class="mb-4">

                    <h5 class="section-title mb-1">Product Image</h5>
                    <p class="text-muted small mb-3">
                        View the current image or upload a replacement.
                    </p>

                    <div class="mb-3">
                        @if($product->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="current-image">
                        @else
                            <div class="info-note">
                                <i class="bi bi-image me-1"></i>
                                No product image uploaded.
                            </div>
                        @endif
                    </div>

                    <label class="form-label">Replace Product Image</label>

                    <input type="file"
                           name="image"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">

                    <small class="text-muted">
                        JPG, PNG or WEBP. Maximum 2 MB. Leave empty to keep the current image.
                    </small>

                    @error('image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror

                </div>

                <hr class="my-4">

                {{-- PRODUCT VARIANTS --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                    <div>
                        <h5 class="section-title mb-1">Product Variants</h5>
                        <p class="text-muted small mb-0">
                            Configure sizes, barcodes, prices and branch availability.
                        </p>
                    </div>

                    <button type="button"
                            class="btn btn-bakery"
                            id="addVariant">
                        <i class="bi bi-plus-lg me-1"></i> Add Variant
                    </button>

                </div>

                <div class="info-note mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Each variant can have its own SKU, barcode, selling price and cost price.
                    Leave a new variant's barcode blank to generate it automatically when saving.
                </div>

                @if($branches->isEmpty())
                    <div class="alert alert-warning">
                        No active branches found. Please configure an active branch before editing variants.
                    </div>
                @endif

                <div id="variantsContainer">

                    @foreach($oldVariants as $index => $variant)

                        <div class="variant-card border rounded-3 p-3 mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h6 class="fw-bold mb-0" style="color:#4A2616;">
                                    <i class="bi bi-box-seam me-1"></i>
                                    Variant <span class="variant-number">{{ $index + 1 }}</span>
                                </h6>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger remove-variant">
                                    <i class="bi bi-trash"></i> Remove
                                </button>

                            </div>

                            @if(!empty($variant['id']))
                                <input type="hidden"
                                       name="variants[{{ $index }}][id]"
                                       value="{{ $variant['id'] }}">
                            @endif

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Variant Name *</label>
                                    <input type="text"
                                           name="variants[{{ $index }}][variant_name]"
                                           class="form-control"
                                           value="{{ $variant['variant_name'] ?? '' }}"
                                           placeholder="Example: 500 Gram"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Variant SKU *</label>
                                    <input type="text"
                                           name="variants[{{ $index }}][sku]"
                                           class="form-control"
                                           value="{{ $variant['sku'] ?? '' }}"
                                           placeholder="Enter variant SKU"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Variant Barcode</label>
                                    <input type="text"
                                           name="variants[{{ $index }}][barcode]"
                                           class="form-control barcode-value"
                                           value="{{ $variant['barcode'] ?? '' }}"
                                           placeholder="Leave blank to auto-generate">

                                    <small class="form-text text-muted">
                                        Existing barcode is preserved if left blank.
                                    </small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Unit *</label>

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

                                <div class="col-md-4">
                                    <label class="form-label">Default Selling Price *</label>
                                    <input type="number"
                                           name="variants[{{ $index }}][default_selling_price]"
                                           class="form-control"
                                           value="{{ $variant['default_selling_price'] ?? '' }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cost Price *</label>
                                    <input type="number"
                                           name="variants[{{ $index }}][cost_price]"
                                           class="form-control"
                                           value="{{ $variant['cost_price'] ?? '0' }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00"
                                           required>
                                </div>

                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3" style="color:#4A2616;">
                                <i class="bi bi-shop me-1"></i>
                                Branch Configurations
                            </h6>

                            @foreach($branches as $branchIndex => $branch)

                                @php
                                    $branchSettings = $variant['branches'][$branchIndex] ?? [];
                                @endphp

                                <div class="branch-card p-3 mb-3">

                                    <h6 class="fw-bold mb-3">
                                        {{ $branch->branch_name }}
                                    </h6>

                                    <input type="hidden"
                                           name="variants[{{ $index }}][branches][{{ $branchIndex }}][branch_id]"
                                           value="{{ $branch->id }}">

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label">Branch Selling Price *</label>
                                            <input type="number"
                                                   name="variants[{{ $index }}][branches][{{ $branchIndex }}][selling_price]"
                                                   class="form-control"
                                                   value="{{ $branchSettings['selling_price'] ?? $variant['default_selling_price'] ?? '' }}"
                                                   min="0"
                                                   step="0.01"
                                                   required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Minimum Stock</label>
                                            <input type="number"
                                                   name="variants[{{ $index }}][branches][{{ $branchIndex }}][min_stock]"
                                                   class="form-control"
                                                   value="{{ $branchSettings['min_stock'] ?? '0' }}"
                                                   min="0"
                                                   step="0.001">
                                        </div>

                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="form-check mb-2">

                                                <input type="hidden"
                                                       name="variants[{{ $index }}][branches][{{ $branchIndex }}][is_enabled]"
                                                       value="0">

                                                <input type="checkbox"
                                                       name="variants[{{ $index }}][branches][{{ $branchIndex }}][is_enabled]"
                                                       value="1"
                                                       class="form-check-input"
                                                       id="enabled_{{ $index }}_{{ $branchIndex }}"
                                                       {{ !empty($branchSettings['is_enabled']) ? 'checked' : '' }}>

                                                <label class="form-check-label"
                                                       for="enabled_{{ $index }}_{{ $branchIndex }}">
                                                    Enabled at this branch
                                                </label>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">

                                                <input type="hidden"
                                                       name="variants[{{ $index }}][branches][{{ $branchIndex }}][is_available]"
                                                       value="0">

                                                <input type="checkbox"
                                                       name="variants[{{ $index }}][branches][{{ $branchIndex }}][is_available]"
                                                       value="1"
                                                       class="form-check-input"
                                                       id="available_{{ $index }}_{{ $branchIndex }}"
                                                       {{ !empty($branchSettings['is_available']) ? 'checked' : '' }}>

                                                <label class="form-check-label"
                                                       for="available_{{ $index }}_{{ $branchIndex }}">
                                                    Available for sale
                                                </label>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">

                                                <input type="hidden"
                                                       name="variants[{{ $index }}][branches][{{ $branchIndex }}][online_available]"
                                                       value="0">

                                                <input type="checkbox"
                                                       name="variants[{{ $index }}][branches][{{ $branchIndex }}][online_available]"
                                                       value="1"
                                                       class="form-check-input"
                                                       id="online_{{ $index }}_{{ $branchIndex }}"
                                                       {{ !empty($branchSettings['online_available']) ? 'checked' : '' }}>

                                                <label class="form-check-label"
                                                       for="online_{{ $index }}_{{ $branchIndex }}">
                                                    Available for online orders
                                                </label>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </div>

                    @endforeach

                </div>

                @error('variants')
                    <div class="text-danger small mb-3">{{ $message }}</div>
                @enderror

                <hr class="my-4">

                {{-- FORM ACTIONS --}}
                <div class="d-flex justify-content-end gap-2 flex-wrap">

                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-light border px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-bakery px-4"
                            {{ $units->isEmpty() || $branches->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-check-lg me-2"></i>
                        Update Product
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

{{-- NEW VARIANT TEMPLATE --}}
<template id="variantTemplate">

    <div class="variant-card border rounded-3 p-3 mb-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h6 class="fw-bold mb-0" style="color:#4A2616;">
                <i class="bi bi-box-seam me-1"></i>
                Variant <span class="variant-number"></span>
            </h6>

            <button type="button"
                    class="btn btn-sm btn-outline-danger remove-variant">
                <i class="bi bi-trash"></i> Remove
            </button>

        </div>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Variant Name *</label>
                <input type="text"
                       name="variants[__INDEX__][variant_name]"
                       class="form-control"
                       placeholder="Example: 1 Kg"
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Variant SKU *</label>
                <input type="text"
                       name="variants[__INDEX__][sku]"
                       class="form-control"
                       placeholder="Enter variant SKU"
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Variant Barcode</label>
                <input type="text"
                       name="variants[__INDEX__][barcode]"
                       class="form-control barcode-value"
                       placeholder="Leave blank to auto-generate">
            </div>

            <div class="col-md-4">
                <label class="form-label">Unit *</label>
                <select name="variants[__INDEX__][unit_id]"
                        class="form-select"
                        required>
                    <option value="">Select Unit</option>

                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->name }} ({{ $unit->abbreviation }})
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Default Selling Price *</label>
                <input type="number"
                       name="variants[__INDEX__][default_selling_price]"
                       class="form-control"
                       min="0"
                       step="0.01"
                       placeholder="0.00"
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Cost Price *</label>
                <input type="number"
                       name="variants[__INDEX__][cost_price]"
                       class="form-control"
                       min="0"
                       step="0.01"
                       value="0"
                       required>
            </div>

        </div>

        <hr class="my-4">

        <h6 class="fw-bold mb-3" style="color:#4A2616;">
            <i class="bi bi-shop me-1"></i>
            Branch Configurations
        </h6>

        @foreach($branches as $branchIndex => $branch)

            <div class="branch-card p-3 mb-3">

                <h6 class="fw-bold mb-3">
                    {{ $branch->branch_name }}
                </h6>

                <input type="hidden"
                       name="variants[__INDEX__][branches][{{ $branchIndex }}][branch_id]"
                       value="{{ $branch->id }}">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Branch Selling Price *</label>
                        <input type="number"
                               name="variants[__INDEX__][branches][{{ $branchIndex }}][selling_price]"
                               class="form-control"
                               min="0"
                               step="0.01"
                               placeholder="0.00"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Minimum Stock</label>
                        <input type="number"
                               name="variants[__INDEX__][branches][{{ $branchIndex }}][min_stock]"
                               class="form-control"
                               min="0"
                               step="0.001"
                               value="0">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">

                            <input type="hidden"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][is_enabled]"
                                   value="0">

                            <input type="checkbox"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][is_enabled]"
                                   value="1"
                                   class="form-check-input"
                                   id="enabled___INDEX___{{ $branchIndex }}"
                                   checked>

                            <label class="form-check-label"
                                   for="enabled___INDEX___{{ $branchIndex }}">
                                Enabled at this branch
                            </label>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">

                            <input type="hidden"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][is_available]"
                                   value="0">

                            <input type="checkbox"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][is_available]"
                                   value="1"
                                   class="form-check-input"
                                   id="available___INDEX___{{ $branchIndex }}"
                                   checked>

                            <label class="form-check-label"
                                   for="available___INDEX___{{ $branchIndex }}">
                                Available for sale
                            </label>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">

                            <input type="hidden"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][online_available]"
                                   value="0">

                            <input type="checkbox"
                                   name="variants[__INDEX__][branches][{{ $branchIndex }}][online_available]"
                                   value="1"
                                   class="form-check-input"
                                   id="online___INDEX___{{ $branchIndex }}"
                                   checked>

                            <label class="form-check-label"
                                   for="online___INDEX___{{ $branchIndex }}">
                                Available for online orders
                            </label>

                        </div>
                    </div>

                </div>
            </div>

        @endforeach

    </div>

</template>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('variantsContainer');
    const addButton = document.getElementById('addVariant');
    const template = document.getElementById('variantTemplate');

    let nextIndex = {{ count($oldVariants) }};

    function updateVariantNumbers() {
        container.querySelectorAll('.variant-card').forEach((card, index) => {
            card.querySelector('.variant-number').textContent = index + 1;
        });
    }

    addButton.addEventListener('click', function () {

        const html = template.innerHTML.replace(/__INDEX__/g, nextIndex);

        container.insertAdjacentHTML('beforeend', html);

        nextIndex++;

        updateVariantNumbers();
    });

    container.addEventListener('click', function (event) {

        const removeButton = event.target.closest('.remove-variant');

        if (!removeButton) {
            return;
        }

        const cards = container.querySelectorAll('.variant-card');

        if (cards.length <= 1) {
            alert('At least one product variant is required.');
            return;
        }

        removeButton.closest('.variant-card').remove();

        updateVariantNumbers();
    });

    updateVariantNumbers();

});
</script>

@endsection
