@extends('layouts.admin.app')

@section('title', 'Add Raw Material')
@section('page-heading', 'Add Raw Material')

@section('content')
<style>
    .raw-form-page {
        --brown: #4A2616;
        --brown-light: #75452f;
        --cream: #f9efe3;
        --paper: #fffaf4;
        --text: #4a2f26;
        --muted: #927c70;
        color: var(--text);
    }

    .raw-form-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        gap: 12px;
    }

    .raw-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid #e7d5c5;
        border-radius: 10px;
        background: #fffaf4;
        color: var(--brown);
        text-decoration: none;
        font-weight: 600;
    }

    .raw-form-hero {
        background: linear-gradient(120deg, #75452f, #4A2616);
        color: #fffaf4;
        padding: 25px 28px;
        border-radius: 17px;
        margin-bottom: 22px;
    }

    .raw-form-hero h3 {
        font-weight: 700;
        margin: 0 0 7px;
    }

    .raw-form-hero p {
        margin: 0;
        color: #f0dfd1;
    }

    .raw-form-card {
        background: var(--paper);
        border: 1px solid #eadccc;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .raw-form-card-header {
        padding: 17px 22px;
        border-bottom: 1px solid #eee1d4;
        background: #fcf4eb;
    }

    .raw-form-card-header h5 {
        margin: 0;
        color: var(--brown);
        font-size: 16px;
        font-weight: 700;
    }

    .raw-form-card-body {
        padding: 23px;
    }

    .raw-form-page .form-label {
        color: #5a392a;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .raw-form-page .form-control,
    .raw-form-page .form-select {
        min-height: 44px;
        border: 1px solid #e5d5c6;
        border-radius: 9px;
        background-color: #fff;
        color: #4a2f26;
        box-shadow: none;
    }

    .raw-form-page textarea.form-control {
        min-height: 105px;
    }

    .raw-form-page .form-control:focus,
    .raw-form-page .form-select:focus {
        border-color: #9b6b4e;
        box-shadow: 0 0 0 3px rgba(117, 69, 47, .10);
    }

    .raw-form-page .form-text {
        color: var(--muted);
        font-size: 12px;
    }

    .raw-save-btn {
        border: 0;
        border-radius: 10px;
        padding: 11px 22px;
        background: linear-gradient(120deg, #75452f, #4A2616);
        color: white;
        font-weight: 700;
    }

    .raw-save-btn:hover {
        color: white;
        opacity: .92;
    }

    .raw-cancel-btn {
        border: 1px solid #e3d1c1;
        border-radius: 10px;
        padding: 11px 22px;
        color: #684532;
        background: #fffaf4;
        text-decoration: none;
        font-weight: 600;
    }

    @media(max-width: 576px) {
        .raw-form-hero { padding: 20px; }
        .raw-form-card-body { padding: 16px; }
        .raw-form-top { align-items: flex-start; }
    }
</style>

<div class="raw-form-page">

    <div class="raw-form-top">
        <div>
            <h4 class="mb-1 fw-bold" style="color:#4A2616;">New Raw Material</h4>
            <small class="text-muted">Enter the raw material details below.</small>
        </div>

        <a href="{{ route('admin.stock.raw-materials') }}" class="raw-back-btn">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <section class="raw-form-hero">
        <h3><i class="bi bi-box-seam me-2"></i>Add Raw Material</h3>
        <p>Create a raw material record and assign its initial stock to a branch.</p>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.stock.raw-materials.store') }}" method="POST">
        @csrf

        <div class="raw-form-card">
            <div class="raw-form-card-header">
                <h5><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
            </div>

            <div class="raw-form-card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Material Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Maida, Sugar, Butter" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Base Unit <span class="text-danger">*</span></label>
                        <select name="base_unit_id"
                                class="form-select @error('base_unit_id') is-invalid @enderror" required>
                            <option value="">Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    @selected(old('base_unit_id') == $unit->id)>
                                    {{ $unit->name }} ({{ $unit->abbreviation }})
                                </option>
                            @endforeach
                        </select>
                        @error('base_unit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" @selected(old('status', 'active') == 'active')>Active</option>
                            <option value="inactive" @selected(old('status') == 'inactive')>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                                  placeholder="Optional material details...">{{ old('description') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="raw-form-card">
            <div class="raw-form-card-header">
                <h5><i class="bi bi-boxes me-2"></i>Branch Stock Details</h5>
            </div>

            <div class="raw-form-card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Branch <span class="text-danger">*</span></label>
                        <select name="branch_id"
                                class="form-select @error('branch_id') is-invalid @enderror" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    @selected(old('branch_id') == $branch->id)>
                                    {{ $branch->branch_name }} ({{ $branch->branch_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Opening Stock <span class="text-danger">*</span></label>
                        <input type="number" name="opening_stock" min="0" step="0.001"
                               class="form-control @error('opening_stock') is-invalid @enderror"
                               value="{{ old('opening_stock', 0) }}" required>
                        @error('opening_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Initial quantity available at the selected branch.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Minimum Stock Level</label>
                        <input type="number" name="min_stock" min="0" step="0.001"
                               class="form-control @error('min_stock') is-invalid @enderror"
                               value="{{ old('min_stock', 0) }}">
                        @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Used to identify low-stock materials.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cost Price <span class="text-danger">*</span></label>
                        <input type="number" name="cost_price" min="0" step="0.01"
                               class="form-control @error('cost_price') is-invalid @enderror"
                               value="{{ old('cost_price', 0) }}" required>
                        @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Default cost price per selected unit.</div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 flex-wrap mb-4">
            <a href="{{ route('admin.stock.raw-materials') }}" class="raw-cancel-btn">
                Cancel
            </a>
            <button type="submit" class="raw-save-btn">
                <i class="bi bi-check-circle me-1"></i> Save Raw Material
            </button>
        </div>

    </form>
</div>
@endsection
