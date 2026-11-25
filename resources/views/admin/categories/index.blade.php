@extends('layouts.admin.app')

@section('title', 'Category Management')
@section('page-heading', 'Category Management')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Manage Categories</h4>
            <p class="text-muted mb-0">Organize your bakery products into categories.</p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="btn text-white px-4 py-2"
           style="background:#4A2616;border-radius:10px;">
            <i class="bi bi-plus-lg me-2"></i> Add Category
        </a>
    </div>

    {{-- Flash Messages --}}
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

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Total Categories</div>
                    <h3 class="fw-bold mb-0">{{ $totalCategories ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Active Categories</div>
                    <h3 class="fw-bold mb-0 text-success">{{ $activeCategories ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-muted small">Inactive Categories</div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $inactiveCategories ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Categories Table --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="mb-0">All Categories</h5>

                <form action="{{ route('admin.categories.index') }}"
                      method="GET"
                      class="d-flex gap-2">

                    <input type="search"
                           name="search"
                           class="form-control"
                           placeholder="Search category..."
                           value="{{ request('search') }}"
                           style="max-width:240px;border-radius:9px;">

                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>

                    @if(request('search'))
                        <a href="{{ route('admin.categories.index') }}"
                           class="btn btn-outline-danger">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">

                    <thead style="background:#f9efe3;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Category Name</th>
                            <th class="py-3">Parent Category</th>
                            <th class="py-3">Products</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="px-4">{{ $categories->firstItem() + $loop->index }}</td>

                                <td>
                                    <span class="fw-semibold">{{ $category->name }}</span>
                                </td>

                                <td>
                                    {{ $category->parent?->name ?? 'Main Category' }}
                                </td>

                                <td>
                                    {{ $category->products_count ?? 0 }}
                                </td>

                                <td>
                                    @if($category->status === 'active')
                                        <span class="badge bg-success-subtle text-success px-3 py-2">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end px-4">
                                    <div class="d-inline-flex gap-2">

                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Category">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Category">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-tags fs-1 text-muted"></i>
                                    <h5 class="mt-3">No Categories Found</h5>
                                    <p class="text-muted mb-3">
                                        Start by creating your first bakery category.
                                    </p>
                                    <a href="{{ route('admin.categories.create') }}"
                                       class="btn text-white"
                                       style="background:#4A2616;">
                                        Add First Category
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        @if($categories->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $categories->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
