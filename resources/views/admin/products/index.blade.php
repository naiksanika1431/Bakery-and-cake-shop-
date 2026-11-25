
@extends('layouts.admin.app')

@section('title', 'Product Management')
@section('page-heading', 'Product Management')

@section('content')

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Manage Products</h4>
            <p class="text-muted mb-0">Manage your bakery products, barcodes, pricing and details.</p>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="btn text-white px-4 py-2"
           style="background:#4A2616;border-radius:10px;">
            <i class="bi bi-plus-lg me-2"></i> Add Product
        </a>
    </div>

    {{-- Success / Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Product Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Total Products</div>
                    <h3 class="fw-bold mb-0">{{ $totalProducts ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Active Products</div>
                    <h3 class="fw-bold mb-0 text-success">{{ $activeProducts ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Inactive Products</div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $inactiveProducts ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Products Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="mb-3">All Products</h5>

            {{-- Filters --}}
            <form action="{{ route('admin.products.index') }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-4">
                    <input type="search"
                           name="search"
                           class="form-control"
                           placeholder="Search product name or SKU..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit"
                            class="btn text-white flex-grow-1"
                            style="background:#4A2616;">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>

                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead style="background:#f9efe3;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Product</th>
                            <th class="py-3">SKU</th>
                            <th class="py-3">Barcode</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Product Type</th>
                            <th class="py-3">GST</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                {{-- Serial Number --}}
                                <td class="px-4">
                                    {{ $products->firstItem() + $loop->index }}
                                </td>

                                {{-- Product Image and Name --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">

                                        @if($product->image)

                                            <img src="{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 width="48"
                                                 height="48"
                                                 class="rounded-3"
                                                 style="object-fit:cover;">

                                        @else

                                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                                 style="width:48px;height:48px;background:#f9efe3;">
                                                <i class="bi bi-cake2 fs-4" style="color:#8d7468;"></i>
                                            </div>

                                        @endif

                                        <div>
                                            <span class="fw-semibold d-block">
                                                {{ $product->name }}
                                            </span>

                                            <small class="text-muted">
                                                {{ $product->baseUnit?->name ?? 'Unit not set' }}
                                            </small>
                                        </div>

                                    </div>
                                </td>

                                {{-- SKU --}}
                                <td>
                                    {{ $product->sku }}
                                </td>

                                {{-- Product Barcode --}}
                                <td>
                                    @if($product->barcode)
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-upc me-1"></i>
                                            {{ $product->barcode }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Not generated</span>
                                    @endif
                                </td>

                                {{-- Category --}}
                                <td>
                                    {{ $product->category?->name ?? 'Uncategorized' }}
                                </td>

                                {{-- Product Type --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ ucwords(str_replace('_', ' ', $product->product_type ?? 'finished_good')) }}
                                    </span>
                                </td>

                                {{-- GST Rate --}}
                                <td>
                                    {{ number_format((float) $product->gst_rate, 2) }}%
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($product->status === 'active')
                                        <span class="badge bg-success-subtle text-success px-3 py-2">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end px-4">
                                    <div class="d-inline-flex gap-2">

                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="View Product">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Product">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this product?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Product">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center py-5">

                                    <i class="bi bi-box-seam fs-1 text-muted"></i>

                                    <h5 class="mt-3">No Products Found</h5>

                                    <p class="text-muted mb-3">
                                        Start by adding your first bakery product.
                                    </p>

                                    <a href="{{ route('admin.products.create') }}"
                                       class="btn text-white"
                                       style="background:#4A2616;">
                                        Add First Product
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
