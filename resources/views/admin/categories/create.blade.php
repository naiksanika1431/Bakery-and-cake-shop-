
@extends('layouts.admin.app')

@section('title', 'Add Category')
@section('page-heading', 'Add Category')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}"
           class="text-decoration-none"
           style="color:#4A2616;">
            <i class="bi bi-arrow-left me-2"></i> Back to Categories
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4" style="max-width:850px;">

        <div class="card-header bg-white border-0 p-4">
            <h4 class="mb-1">Create New Category</h4>
            <p class="text-muted mb-0">
                Add a category to organize your bakery products.
            </p>
        </div>

        <div class="card-body p-4">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <!-- CATEGORY NAME -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Category Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Example: Cakes, Pastries, Breads"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- CATEGORY IMAGE -->
                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold">
                        Category Picture
                    </label>

                    <input type="file"
                           name="image"
                           id="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           onchange="previewCategoryImage(event)">

                    <small class="text-muted">
                        Upload JPG, PNG or WEBP image. Maximum size: 2 MB.
                    </small>

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <!-- IMAGE PREVIEW -->
                    <div class="mt-3" id="imagePreviewContainer" style="display:none;">
                        <p class="fw-semibold mb-2">Image Preview</p>

                        <img id="imagePreview"
                             src=""
                             alt="Category Preview"
                             style="width:180px;
                                    height:140px;
                                    object-fit:cover;
                                    border-radius:12px;
                                    border:1px solid #eee;">
                    </div>
                </div>

                <!-- PARENT CATEGORY -->
                <div class="mb-4">
                    <label for="parent_id" class="form-label fw-semibold">
                        Parent Category
                    </label>

                    <select name="parent_id"
                            id="parent_id"
                            class="form-select @error('parent_id') is-invalid @enderror">

                        <option value="">Main Category (No Parent)</option>

                        @foreach($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}"
                                {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endforeach

                    </select>

                    <small class="text-muted">
                        Select a parent only if this is a subcategory.
                    </small>

                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- CATEGORY STATUS -->
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">
                        Category Status <span class="text-danger">*</span>
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                        <option value="active"
                            {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <!-- BUTTONS -->
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.categories.index') }}"
                       class="btn btn-light border px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn text-white px-4"
                            style="background:#4A2616;">
                        <i class="bi bi-check-lg me-2"></i>
                        Save Category
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script>
    function previewCategoryImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        if (file) {
            preview.src = URL.createObjectURL(file);
            container.style.display = 'block';
        } else {
            preview.src = '';
            container.style.display = 'none';
        }
    }
</script>

@endsection
