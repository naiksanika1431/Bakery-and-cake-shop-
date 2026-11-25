@extends('layouts.admin.app')

@section('title', 'Category Details')
@section('page-heading', 'Category Details')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <a href="{{ route('admin.categories.index') }}"
               class="text-decoration-none"
               style="color:#4A2616;">
                <i class="bi bi-arrow-left me-2"></i> Back to Categories
            </a>
            <h4 class="mt-3 mb-1">{{ $category->name }}</h4>
            <p class="text-muted mb-0">View category information and related products.</p>
        </div>

        <a href="{{ route('admin.categories.edit', $category->id) }}"
           class="btn text-white px-4"
           style="background:#4A2616;">
            <i class="bi bi-pencil me-2"></i> Edit Category
        </a>
    </div>

    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0">Category Information</h5>
                </div>

                <div class="card-body p-4">
                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Category Name</small>
                        <h5 class="mb-0">{{ $category->name }}</h5>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Parent Category</small>
                        <span>{{ $category->parent?->name ?? 'Main Category' }}</span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Status</small>

                        @if($category->status === 'active')
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                Active
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Total Products</small>
                        <h5 class="mb-0">{{ $category->products_count ?? $category->products->count() }}</h5>
                    </div>

                    <div>
                        <small class="text-muted d-block mb-1">Created On</small>
                        {{ $category->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0">Products in this Category</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead style="background:#f9efe3;">
                                <tr>
                                    <th class="px-4 py-3">Product Name</th>
                                    <th class="py-3">SKU</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($category->products as $product)
                                    <tr>
                                        <td class="px-4 fw-semibold">{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            <span class="badge {{ $product->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-2">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
                                            No products assigned to this category yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
