@extends('layouts.customer.app')

@section('title', 'Our Products')

@section('content')

<!-- Products Banner -->
<section class="py-5 text-center"
         style="background:linear-gradient(120deg,#f9e9df,#f4d4c5);">
    <div class="container py-4">
        <span class="text-uppercase fw-semibold" style="color:#9b5144;">
            Fresh From Our Bakery
        </span>
        <h1 class="display-4 fw-bold mt-2" style="color:#63382e;">
            Our Delicious Products
        </h1>
        <p class="fs-5 text-secondary mb-0">
            Discover your favourite cakes, pastries and bakery treats.
        </p>
    </div>
</section>

<!-- Products Section -->
<section class="py-5">
    <div class="container">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">All Products</h2>
                <p class="text-secondary mb-0">
                    Freshly baked happiness in every bite.
                </p>
            </div>

            @if($selectedBranch)
                <form method="GET" action="{{ route('customer.products') }}"
                      class="mt-3 mt-md-0">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="featured" {{ request('sort', 'featured') == 'featured' ? 'selected' : '' }}>
                            Sort By: Featured
                        </option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                            Price: Low to High
                        </option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                            Price: High to Low
                        </option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                            Name: A to Z
                        </option>
                    </select>
                </form>
            @endif
        </div>

        <!-- Branch Selection Message -->
        @if(!$selectedBranch)
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-shop display-3" style="color:#9b5144;"></i>
                </div>
                <h3 class="fw-bold">Choose Your Branch</h3>
                <p class="text-secondary">
                    Please select your preferred bakery branch from the navbar
                    to explore available products and prices.
                </p>
            </div>
        @else

            <div class="mb-3">
                <span class="badge rounded-pill px-3 py-2"
                      style="background:#6b3a24;">
                    <i class="bi bi-geo-alt"></i>
                    {{ $selectedBranch->branch_name }}
                </span>
            </div>

            <!-- Category Filters -->
            <div class="d-flex flex-wrap gap-2 mb-5">

                <a href="{{ route('customer.products', ['sort' => request('sort', 'featured')]) }}"
                   class="btn {{ !request('category') ? 'btn-bakery' : 'btn-outline-secondary' }}">
                    All Products
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('customer.products', [
                        'category' => $category->id,
                        'sort' => request('sort', 'featured')
                    ]) }}"
                       class="btn {{ request('category') == $category->id ? 'btn-bakery' : 'btn-outline-secondary' }}">
                        {{ $category->name }}
                    </a>
                @endforeach

            </div>

            <!-- Dynamic Product Cards -->
            <div class="row g-4">

                @forelse($products as $product)

                    @php
                        $variant = $product->productVariants->first();
                        $branchProduct = $variant ? $variant->branchProducts->first() : null;
                    @endphp

                    @if($variant && $branchProduct)

                        <div class="col-6 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                                @if($product->image)
                                    <img src="{{ $product->image }}"
                                         class="card-img-top"
                                         alt="{{ $product->name }}"
                                         style="height:220px;object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center"
                                         style="height:220px;background:#f4e5dc;">
                                        <i class="bi bi-cake2 display-2"
                                           style="color:#9b5144;"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">

                                    <span class="small text-uppercase fw-semibold mb-2"
                                          style="color:#9b5144;">
                                        {{ $product->category->name ?? 'Bakery Product' }}
                                    </span>

                                    <h5 class="fw-bold">
                                        {{ $product->name }}
                                    </h5>

                                    <p class="text-secondary small">
                                        {{ $product->description ?: 'Freshly prepared with quality ingredients.' }}
                                    </p>

                                    <p class="small text-muted mb-2">
                                        {{ $variant->variant_name }}
                                        @if($variant->unit)
                                            · {{ $variant->unit->name }}
                                        @endif
                                    </p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-0" style="color:#9b5144;">
                                            ?{{ number_format((float)$branchProduct->selling_price, 2) }}
                                        </h5>

                                        <button type="button"
                                                class="btn btn-bakery btn-sm"
                                                disabled
                                                title="Shopping cart will be available soon">
                                            <i class="bi bi-cart-plus"></i> Add
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endif

                @empty

                    <div class="col-12 text-center py-5">
                        <i class="bi bi-basket display-3" style="color:#9b5144;"></i>
                        <h3 class="fw-bold mt-3">No Products Available</h3>
                        <p class="text-secondary">
                            There are currently no online products available
                            for this branch or category.
                        </p>
                        @if(request('category'))
                            <a href="{{ route('customer.products') }}"
                               class="btn btn-bakery mt-2">
                                View All Products
                            </a>
                        @endif
                    </div>

                @endforelse

            </div>

        @endif

    </div>
</section>

@endsection
