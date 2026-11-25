@extends('layouts.admin.app')

@section('title', 'Edit Category')
@section('page-heading', 'Edit Category')

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
            <h4 class="mb-1">Edit Category</h4>
            <p class="text-muted mb-0">
                Update the details of {{ $category->name }}.
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

            <form action="{{ route('admin.categories.update', $category->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Category Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}"
                           placeholder="Enter category name"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Category Image --}}
                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold">
                        Category Image
                    </label>

                    <div class="mb-3">
                        @if($category->image)
                            <p class="small text-muted mb-2">Current Image</p>
                            <img src="{{ $category->image }}"
                                 id="currentImagePreview"
                                 alt="{{ $category->name }}"
                                 style="width:160px;height:120px;object-fit:cover;border-radius:12px;border:1px solid #ddd;">
                        @else
                            <p class="text-muted small mb-0">
                                No image uploaded for this category yet.
                            </p>
                        @endif
                    </div>

                    <input type="file"
                           name="image"
                           id="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/webp">

                    <small class="text-muted">
                        Select a new image only if you want to replace the current one.
                        Supported formats: JPG, JPEG, PNG, WEBP. Maximum size: 2 MB.
                    </small>

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="mt-3" id="newImageContainer" style="display:none;">
                        <p class="small text-muted mb-2">New Image Preview</p>
                        <img id="newImagePreview"
                             src=""
                             alt="New category preview"
                             style="width:160px;height:120px;object-fit:cover;border-radius:12px;border:1px solid #ddd;">
                    </div>
                </div>

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
                                {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
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

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">
                        Category Status <span class="text-danger">*</span>
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                        <option value="active"
                            {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}"
                       class="btn btn-light border px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn text-white px-4"
                            style="background:#4A2616;">
                        <i class="bi bi-check-lg me-2"></i> Update Category
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('newImagePreview');
    const previewContainer = document.getElementById('newImageContainer');

    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (!file) {
            previewContainer.style.display = 'none';
            preview.src = '';
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG, JPEG, PNG or WEBP image.');
            imageInput.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        preview.src = URL.createObjectURL(file);
        previewContainer.style.display = 'block';
    });
});
</script>

@endsection
