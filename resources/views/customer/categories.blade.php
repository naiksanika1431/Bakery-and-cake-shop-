@extends('layouts.customer.app')

@section('title', 'Categories')

@section('content')

<style>
    /* CATEGORIES PAGE */

    .categories-page {
        --cat-pink: #FCEEEB;
        --cat-card: #FFF7F4;
        --cat-soft: #FAE7E3;
        --cat-border: #EBD5D1;
        --cat-wine: #592122;
        --cat-wine-dark: #421719;
        --cat-ink: #211414;
        --cat-muted: #806C68;

        background: var(--cat-pink);
        color: var(--cat-ink);
        font-family: 'DM Sans', sans-serif;
    }

    /* HERO */

    .categories-hero {
        position: relative;
        overflow: hidden;
        padding: 85px 20px 95px;
        background:
            radial-gradient(circle at 12% 20%, rgba(255,255,255,.65), transparent 25%),
            radial-gradient(circle at 88% 75%, rgba(255,255,255,.45), transparent 24%),
            linear-gradient(125deg, #FCEEEB 0%, #F8DDD6 55%, #F2D0C8 100%);
        text-align: center;
    }

    .categories-hero::before,
    .categories-hero::after {
        position: absolute;
        width: 220px;
        height: 220px;
        border: 1px solid rgba(89,33,34,.12);
        border-radius: 50%;
        content: "";
        pointer-events: none;
    }

    .categories-hero::before {
        top: -105px;
        left: -65px;
        box-shadow: 0 0 0 22px rgba(89,33,34,.025),
                    0 0 0 45px rgba(89,33,34,.02);
    }

    .categories-hero::after {
        right: -80px;
        bottom: -120px;
        box-shadow: 0 0 0 22px rgba(89,33,34,.025),
                    0 0 0 45px rgba(89,33,34,.02);
    }

    .categories-hero-content {
        position: relative;
        z-index: 1;
        max-width: 780px;
        margin: auto;
    }

    .categories-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 17px;
        padding: 9px 18px;
        border: 1px solid rgba(89,33,34,.15);
        border-radius: 50px;
        background: rgba(255,247,244,.65);
        color: var(--cat-wine);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .2em;
        text-transform: uppercase;
    }

    .categories-hero h1 {
        margin: 0;
        color: var(--cat-wine);
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: clamp(2.8rem, 6vw, 4.7rem);
        line-height: 1.08;
    }

    .categories-hero h1 em {
        color: #87494A;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
    }

    .categories-hero p {
        max-width: 570px;
        margin: 19px auto 0;
        color: var(--cat-muted);
        font-size: 1rem;
        line-height: 1.8;
    }

    .hero-decoration {
        margin-top: 25px;
        color: var(--cat-wine);
        font-size: 1.15rem;
    }

    /* CATEGORY COLLECTION */

    .categories-section {
        padding: 75px 0 90px;
    }

    .categories-section-heading {
        max-width: 680px;
        margin: 0 auto 48px;
        text-align: center;
    }

    .categories-section-heading .small-label {
        display: block;
        margin-bottom: 12px;
        color: var(--cat-wine);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .25em;
        text-transform: uppercase;
    }

    .categories-section-heading h2 {
        margin: 0;
        color: var(--cat-ink);
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: clamp(2rem, 4vw, 3rem);
    }

    .categories-section-heading h2 em {
        color: var(--cat-wine);
        font-family: 'Cormorant Garamond', Georgia, serif;
    }

    .categories-section-heading p {
        margin: 13px 0 0;
        color: var(--cat-muted);
        font-size: .95rem;
        line-height: 1.8;
    }

    .category-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid rgba(89,33,34,.09);
        border-radius: 21px;
        background: var(--cat-card);
        box-shadow: 0 8px 25px rgba(89,33,34,.055);
        transition: transform .35s ease, box-shadow .35s ease;
    }

    .category-card:hover {
        transform: translateY(-9px);
        box-shadow: 0 18px 38px rgba(89,33,34,.13);
    }

    .category-image-wrap {
        position: relative;
        height: 235px;
        overflow: hidden;
        background: var(--cat-soft);
    }

    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .55s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.08);
    }

    .category-image-wrap::after {
        position: absolute;
        inset: 55% 0 0;
        background: linear-gradient(to top, rgba(45,17,18,.24), transparent);
        content: "";
        pointer-events: none;
    }

    .category-image-placeholder {
        display: flex;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: var(--cat-wine);
        background:
            radial-gradient(circle, #FFF7F4 0%, #F8E1DB 100%);
    }

    .category-image-placeholder i {
        font-size: 3rem;
        opacity: .65;
    }

    .category-image-placeholder span {
        margin-top: 9px;
        font-size: .75rem;
        font-weight: 600;
    }

    .category-card-body {
        display: flex;
        min-height: 205px;
        align-items: center;
        flex-direction: column;
        padding: 24px 19px 22px;
        text-align: center;
    }

    .category-card-body h3 {
        margin: 0 0 10px;
        color: var(--cat-wine);
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.45rem;
    }

    .category-card-body p {
        margin: 0 0 22px;
        color: var(--cat-muted);
        font-size: .83rem;
        line-height: 1.75;
    }

    .btn-category {
        display: inline-flex;
        min-height: 43px;
        align-items: center;
        justify-content: center;
        gap: 9px;
        margin-top: auto;
        padding: 10px 19px;
        border: 1px solid var(--cat-wine);
        border-radius: 50px;
        background: var(--cat-wine);
        color: #fff;
        font-size: .78rem;
        font-weight: 700;
        transition: all .25s ease;
    }

    .btn-category:hover {
        transform: translateY(-2px);
        border-color: var(--cat-wine-dark);
        background: var(--cat-wine-dark);
        color: #fff;
        box-shadow: 0 7px 17px rgba(89,33,34,.18);
    }

    .btn-category i {
        transition: transform .25s ease;
    }

    .btn-category:hover i {
        transform: translateX(4px);
    }

    /* EMPTY STATE */

    .categories-empty {
        padding: 65px 20px;
        border: 1px dashed var(--cat-border);
        border-radius: 24px;
        background: var(--cat-card);
        text-align: center;
    }

    .categories-empty-icon {
        display: inline-flex;
        width: 85px;
        height: 85px;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        border-radius: 50%;
        background: var(--cat-soft);
        color: var(--cat-wine);
        font-size: 2.5rem;
    }

    .categories-empty h3 {
        margin-bottom: 10px;
        color: var(--cat-wine);
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 1.8rem;
    }

    .categories-empty p {
        margin-bottom: 22px;
        color: var(--cat-muted);
        font-size: .9rem;
        line-height: 1.7;
    }

    .btn-category-home {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 23px;
        border-radius: 50px;
        background: var(--cat-wine);
        color: #fff;
        font-size: .85rem;
        font-weight: 700;
        transition: all .25s ease;
    }

    .btn-category-home:hover {
        transform: translateY(-2px);
        background: var(--cat-wine-dark);
        color: #fff;
    }

    /* BOTTOM DECORATIVE STRIP */

    .categories-bottom-note {
        padding: 30px 20px;
        border-top: 1px solid var(--cat-border);
        background: #F9E5E0;
        text-align: center;
    }

    .categories-bottom-note p {
        margin: 0;
        color: var(--cat-wine);
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.35rem;
        font-style: italic;
    }

    .categories-bottom-note i {
        margin: 0 7px;
    }

    /* RESPONSIVE */

    @media (max-width: 767px) {
        .categories-hero {
            padding: 65px 17px 70px;
        }

        .categories-section {
            padding: 55px 0 65px;
        }

        .categories-section-heading {
            margin-bottom: 32px;
        }

        .category-image-wrap {
            height: 175px;
        }

        .category-card-body {
            min-height: 200px;
            padding: 19px 12px;
        }

        .category-card-body h3 {
            font-size: 1.2rem;
        }

        .category-card-body p {
            font-size: .77rem;
        }

        .btn-category {
            width: 100%;
            padding: 9px 8px;
            font-size: .7rem;
        }
    }

    @media (max-width: 380px) {
        .category-image-wrap {
            height: 145px;
        }

        .category-card-body {
            min-height: 215px;
        }

        .category-card-body h3 {
            font-size: 1.05rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .categories-page *,
        .categories-page *::before,
        .categories-page *::after {
            transition-duration: .01ms !important;
        }
    }
</style>


<div class="categories-page">

    <!-- HERO BANNER -->

    <section class="categories-hero">

        <div class="categories-hero-content">

            <span class="categories-eyebrow">
                <i class="bi bi-stars"></i>
                Explore Our Bakery
                <i class="bi bi-stars"></i>
            </span>

            <h1>
                A World of <em>Sweetness</em>
            </h1>

            <p>
                From delightful cakes to freshly baked treats,
                discover something special for every craving
                and every celebration.
            </p>

            <div class="hero-decoration">
                <i class="bi bi-flower1"></i>
                <i class="bi bi-heart-fill mx-2"></i>
                <i class="bi bi-flower1"></i>
            </div>

        </div>

    </section>


    <!-- CATEGORY COLLECTION -->

    <section class="categories-section">

        <div class="container">

            <div class="categories-section-heading">

                <span class="small-label">
                    Made with love, baked with passion
                </span>

                <h2>
                    Explore Our <em>Collections</em>
                </h2>

                <p>
                    Every category has something delicious waiting for you.
                    Find your favourite treats and make every moment sweeter.
                </p>

            </div>


            <div class="row g-4">

                @forelse($categories as $category)

                    <div class="col-6 col-md-4 col-lg-3">

                        <div class="category-card">

                            <!-- Category Image -->

                            <div class="category-image-wrap">

                                @if($category->image)

                                    <img
                                        src="{{ $category->image }}"
                                        class="category-image"
                                        alt="{{ $category->name }}"
                                        loading="lazy">

                                @else

                                    <div class="category-image-placeholder">
                                        <i class="bi bi-cake2"></i>
                                        <span>Sweetness coming soon</span>
                                    </div>

                                @endif

                            </div>


                            <!-- Category Details -->

                            <div class="category-card-body">

                                <h3>
                                    {{ $category->name }}
                                </h3>

                                <p>
                                    Discover our delicious
                                    {{ strtolower($category->name) }}
                                    collection, freshly prepared with love
                                    and the finest ingredients.
                                </p>

                                <a
                                    href="{{ route('customer.products', ['category' => $category->id]) }}"
                                    class="btn-category">

                                    Explore Collection
                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="categories-empty">

                            <div class="categories-empty-icon">
                                <i class="bi bi-basket2"></i>
                            </div>

                            <h3>
                                Something Delicious Is Coming!
                            </h3>

                            <p>
                                Our bakery collections are being prepared
                                with love. Please visit us again soon.
                            </p>

                            <a
                                href="{{ route('customer.home') }}"
                                class="btn-category-home">

                                <i class="bi bi-house-heart"></i>
                                Back to Home

                            </a>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    <!-- BOTTOM MESSAGE -->

    <section class="categories-bottom-note">

        <p>
            <i class="bi bi-heart-fill"></i>
            Life is sweeter with a little cake.
            <i class="bi bi-heart-fill"></i>
        </p>

    </section>

</div>

@endsection
