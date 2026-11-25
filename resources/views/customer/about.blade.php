@extends('layouts.customer.app')

@section('title', 'About Us')

@section('content')

<!-- About Hero -->
<section class="py-5 text-center"
         style="background:linear-gradient(120deg,#f9e9df,#f4d4c5);">
    <div class="container py-5">
        <span class="text-uppercase fw-semibold" style="color:#9b5144;">
            Welcome to ZAPCODEX Bakery
        </span>
        <h1 class="display-4 fw-bold mt-2" style="color:#63382e;">
            Our Story, Your Happiness
        </h1>
        <p class="fs-5 text-secondary mx-auto" style="max-width:650px;">
            Baking happiness into every moment with fresh ingredients,
            delicious flavours and a little extra love.
        </p>
    </div>
</section>

<!-- Our Story -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=900"
                     alt="Fresh bakery products"
                     class="img-fluid rounded-4 shadow"
                     style="width:100%;height:420px;object-fit:cover;">
            </div>

            <div class="col-lg-6">
                <span class="fw-semibold text-uppercase" style="color:#9b5144;">
                    About Our Bakery
                </span>

                <h2 class="display-6 fw-bold mt-2 mb-4" style="color:#63382e;">
                    Baking With Love Since Day One
                </h2>

                <p class="text-secondary lh-lg">
                    At ZAPCODEX Bakery, we believe that every celebration
                    deserves something sweet. Our bakery brings together
                    delicious flavours, quality ingredients and creativity
                    to make every bite special.
                </p>

                <p class="text-secondary lh-lg">
                    From beautifully designed cakes to fresh breads,
                    pastries and cookies, we aim to make your everyday
                    moments and special occasions more memorable.
                </p>

                <p class="text-secondary lh-lg">
                    Our goal is simple: serve delicious bakery products
                    and make every customer feel special.
                </p>

                <a href="{{ route('customer.products') }}"
                   class="btn btn-bakery px-4 py-2 mt-2">
                    Explore Our Products
                </a>
            </div>

        </div>
    </div>
</section>

<!-- Mission and Vision -->
<section class="py-5" style="background:#fff5ef;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="fw-semibold text-uppercase" style="color:#9b5144;">
                What Drives Us
            </span>
            <h2 class="fw-bold mt-2" style="color:#63382e;">
                Our Mission & Vision
            </h2>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="bg-white rounded-4 shadow-sm p-5 h-100 text-center">
                    <div class="mb-3">
                        <i class="bi bi-heart-fill fs-1" style="color:#b85c50;"></i>
                    </div>
                    <h3 class="fw-bold" style="color:#63382e;">Our Mission</h3>
                    <p class="text-secondary lh-lg mb-0">
                        To provide fresh, delicious and high-quality bakery
                        products while creating a wonderful experience
                        for every customer.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="bg-white rounded-4 shadow-sm p-5 h-100 text-center">
                    <div class="mb-3">
                        <i class="bi bi-eye-fill fs-1" style="color:#b85c50;"></i>
                    </div>
                    <h3 class="fw-bold" style="color:#63382e;">Our Vision</h3>
                    <p class="text-secondary lh-lg mb-0">
                        To become a trusted bakery brand known for
                        creativity, quality, customer satisfaction
                        and memorable flavours.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <span class="fw-semibold text-uppercase" style="color:#9b5144;">
                Why Choose Us
            </span>
            <h2 class="fw-bold mt-2" style="color:#63382e;">
                What Makes Us Special?
            </h2>
        </div>

        <div class="row g-4">

            <div class="col-6 col-lg-3">
                <div class="text-center p-3">
                    <i class="bi bi-stars fs-1" style="color:#b85c50;"></i>
                    <h5 class="fw-bold mt-3">Fresh Products</h5>
                    <p class="text-secondary small">
                        Delicious bakery products made fresh.
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="text-center p-3">
                    <i class="bi bi-award fs-1" style="color:#b85c50;"></i>
                    <h5 class="fw-bold mt-3">Quality Ingredients</h5>
                    <p class="text-secondary small">
                        Carefully selected quality ingredients.
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="text-center p-3">
                    <i class="bi bi-palette fs-1" style="color:#b85c50;"></i>
                    <h5 class="fw-bold mt-3">Creative Designs</h5>
                    <p class="text-secondary small">
                        Beautiful cakes for every celebration.
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="text-center p-3">
                    <i class="bi bi-emoji-smile fs-1" style="color:#b85c50;"></i>
                    <h5 class="fw-bold mt-3">Customer Happiness</h5>
                    <p class="text-secondary small">
                        Your happiness is our priority.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Call To Action -->
<section class="py-5 text-center" style="background:#63382e;">
    <div class="container py-4">
        <h2 class="text-white fw-bold">Let's Make Every Moment Sweeter!</h2>
        <p class="text-white-50 mt-3">
            Explore our delicious collection and find your favourite treat.
        </p>
        <a href="{{ route('customer.products') }}"
           class="btn btn-light px-4 py-2 mt-2 fw-semibold"
           style="color:#63382e;">
            Shop Now
        </a>
    </div>
</section>

@endsection
