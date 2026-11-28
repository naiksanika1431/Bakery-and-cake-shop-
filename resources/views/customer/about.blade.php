
@extends('layouts.customer.app')

@section('title', 'About Us | ZAPCODEX Bakery')

@section('content')

<style>
    .about-page {
        --ap-wine: #63382e;
        --ap-dark: #40241f;
        --ap-accent: #ad6555;
        --ap-pink: #f4d8cc;
        --ap-cream: #fff8f3;
        --ap-muted: #85746d;
        color: var(--ap-muted);
        overflow: hidden;
    }

    .about-page * {
        box-sizing: border-box;
    }

    .about-page h1,
    .about-page h2,
    .about-page h3,
    .about-page h4,
    .about-page h5 {
        color: var(--ap-wine);
    }

    .about-page .ap-label {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        color: var(--ap-accent);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2.4px;
        text-transform: uppercase;
    }

    .about-page .ap-label::before {
        content: "";
        width: 24px;
        height: 2px;
        border-radius: 5px;
        background: var(--ap-accent);
    }

    .about-page .ap-heading {
        font-size: clamp(30px, 4vw, 45px);
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: -1.5px;
    }

    .about-page .ap-gradient-text {
        background: linear-gradient(100deg, #a34e42, #d28a70);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .about-page .ap-section-intro {
        max-width: 640px;
        margin: 15px auto 0;
        color: var(--ap-muted);
        font-size: 15px;
        line-height: 1.9;
    }

    /* HERO */

    .ap-hero {
        position: relative;
        isolation: isolate;
        min-height: 490px;
        display: flex;
        align-items: center;
        padding: 85px 0;
        background:
            radial-gradient(circle at 12% 25%, rgba(255,255,255,.9), transparent 25%),
            radial-gradient(circle at 85% 75%, rgba(255,255,255,.65), transparent 23%),
            linear-gradient(125deg, #fff1e8 0%, #f6d9cc 48%, #edc7b8 100%);
    }

    .ap-hero::before,
    .ap-hero::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 1px solid rgba(143,81,66,.13);
        border-radius: 50%;
    }

    .ap-hero::before {
        width: 370px;
        height: 370px;
        top: -160px;
        right: -100px;
    }

    .ap-hero::after {
        width: 260px;
        height: 260px;
        bottom: -140px;
        left: -70px;
    }

    .ap-hero-content {
        max-width: 850px;
        margin: auto;
        text-align: center;
    }

    .ap-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 19px;
        border: 1px solid rgba(132,76,62,.17);
        border-radius: 50px;
        background: rgba(255,255,255,.58);
        color: var(--ap-wine);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }

    .ap-hero-badge i {
        color: var(--ap-accent);
        font-size: 15px;
    }

    .ap-hero h1 {
        margin: 27px 0 20px;
        font-size: clamp(43px, 7vw, 76px);
        font-weight: 900;
        line-height: 1.06;
        letter-spacing: -3px;
    }

    .ap-hero p {
        max-width: 660px;
        margin: auto;
        color: #79645c;
        font-size: 17px;
        line-height: 1.9;
    }

    .ap-hero-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 13px;
        margin-top: 32px;
    }

    .ap-btn-primary,
    .ap-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 49px;
        padding: 13px 25px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: .3s ease;
    }

    .ap-btn-primary {
        background: var(--ap-wine);
        color: #fff;
        box-shadow: 0 8px 22px rgba(99,56,46,.2);
    }

    .ap-btn-primary:hover {
        transform: translateY(-3px);
        background: var(--ap-dark);
        color: #fff;
        box-shadow: 0 12px 25px rgba(99,56,46,.25);
    }

    .ap-btn-outline {
        border: 1px solid rgba(99,56,46,.3);
        color: var(--ap-wine);
        background: rgba(255,255,255,.35);
    }

    .ap-btn-outline:hover {
        transform: translateY(-3px);
        background: #fff;
        color: var(--ap-wine);
    }

    /* HIGHLIGHT STRIP */

    .ap-highlights {
        position: relative;
        z-index: 2;
        margin-top: -1px;
        padding: 0 0 25px;
        background: var(--ap-cream);
    }

    .ap-highlight-box {
        padding: 28px 18px;
        border: 1px solid #f0e3dc;
        border-radius: 18px;
        background: #fff;
        text-align: center;
        box-shadow: 0 12px 35px rgba(78,42,31,.055);
    }

    .ap-highlight-box i {
        display: block;
        margin-bottom: 9px;
        color: var(--ap-accent);
        font-size: 25px;
    }

    .ap-highlight-box strong {
        display: block;
        color: var(--ap-wine);
        font-size: 15px;
        font-weight: 800;
    }

    .ap-highlight-box span {
        display: block;
        margin-top: 5px;
        color: #94847d;
        font-size: 12px;
    }

    /* STORY */

    .ap-story {
        padding: 100px 0;
        background: var(--ap-cream);
    }

    .ap-story-panel {
        position: relative;
        height: 100%;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 45px 30px;
        border: 1px solid #f0dfd5;
        border-radius: 28px;
        background:
            radial-gradient(circle at 20% 20%, rgba(255,255,255,.9), transparent 30%),
            linear-gradient(145deg, #f8e4d9, #efcfc1);
    }

    .ap-story-decoration {
        position: absolute;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(99,56,46,.12);
        border-radius: 50%;
    }

    .ap-story-decoration.one {
        top: -100px;
        left: -90px;
    }

    .ap-story-decoration.two {
        right: -100px;
        bottom: -120px;
        width: 320px;
        height: 320px;
    }

    .ap-story-symbol {
        position: relative;
        z-index: 1;
        width: 190px;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255,255,255,.9);
        border-radius: 50%;
        background: rgba(255,255,255,.42);
        box-shadow: 0 20px 55px rgba(99,56,46,.1);
        backdrop-filter: blur(8px);
    }

    .ap-story-symbol i {
        color: var(--ap-wine);
        font-size: 85px;
    }

    .ap-story-floating {
        position: absolute;
        z-index: 2;
        right: 18px;
        bottom: 22px;
        padding: 15px 19px;
        border: 1px solid #f0e3dc;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(99,56,46,.1);
        color: var(--ap-wine);
        font-size: 12px;
        font-weight: 800;
    }

    .ap-story-floating i {
        margin-right: 6px;
        color: var(--ap-accent);
    }

    .ap-story-content {
        padding-left: 25px;
    }

    .ap-story-content p {
        margin-top: 17px;
        color: #81736c;
        font-size: 14px;
        line-height: 2;
    }

    .ap-story-quote {
        display: flex;
        gap: 14px;
        align-items: center;
        margin-top: 25px;
        padding: 18px;
        border-left: 4px solid var(--ap-accent);
        border-radius: 8px;
        background: #f9eae2;
        color: var(--ap-wine);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.7;
    }

    .ap-story-quote i {
        flex-shrink: 0;
        color: var(--ap-accent);
        font-size: 23px;
    }

    /* MISSION AND VISION */

    .ap-purpose {
        position: relative;
        padding: 95px 0;
        background: #fff;
    }

    .ap-purpose-card {
        position: relative;
        height: 100%;
        overflow: hidden;
        padding: 38px 32px;
        border: 1px solid #f0e5df;
        border-radius: 22px;
        background: linear-gradient(145deg, #fff, #fff9f5);
        transition: .3s ease;
    }

    .ap-purpose-card:hover {
        transform: translateY(-7px);
        border-color: #e5c6b9;
        box-shadow: 0 18px 40px rgba(99,56,46,.09);
    }

    .ap-purpose-card::after {
        content: "";
        position: absolute;
        width: 140px;
        height: 140px;
        right: -60px;
        bottom: -60px;
        border-radius: 50%;
        background: #faeee8;
    }

    .ap-purpose-icon {
        width: 63px;
        height: 63px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 23px;
        border-radius: 18px;
        background: #f8e7de;
        color: var(--ap-accent);
        font-size: 27px;
    }

    .ap-purpose-card h3 {
        margin-bottom: 13px;
        font-size: 23px;
        font-weight: 900;
    }

    .ap-purpose-card p {
        position: relative;
        z-index: 1;
        margin: 0;
        color: #84766f;
        font-size: 14px;
        line-height: 1.9;
    }

    .ap-purpose-number {
        position: absolute;
        top: 22px;
        right: 27px;
        color: rgba(99,56,46,.08);
        font-size: 48px;
        font-weight: 900;
    }

    /* VALUES */

    .ap-values {
        padding: 95px 0;
        background: linear-gradient(130deg, #fff5ef, #f8e9e1);
    }

    .ap-value-card {
        height: 100%;
        padding: 29px 22px;
        border: 1px solid rgba(237,215,204,.9);
        border-radius: 18px;
        background: rgba(255,255,255,.8);
        text-align: center;
        transition: .3s ease;
    }

    .ap-value-card:hover {
        transform: translateY(-7px);
        background: #fff;
        box-shadow: 0 15px 32px rgba(99,56,46,.08);
    }

    .ap-value-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 19px;
        border-radius: 20px;
        background: #f8e5dc;
        color: var(--ap-accent);
        font-size: 27px;
        transition: .3s ease;
    }

    .ap-value-card:hover .ap-value-icon {
        transform: rotate(-7deg) scale(1.06);
        background: var(--ap-wine);
        color: #fff;
    }

    .ap-value-card h5 {
        margin-bottom: 10px;
        font-size: 16px;
        font-weight: 900;
    }

    .ap-value-card p {
        margin: 0;
        color: #877971;
        font-size: 13px;
        line-height: 1.8;
    }

    /* EXPERIENCE SECTION */

    .ap-experience {
        padding: 95px 0;
        background: #fff;
    }

    .ap-experience-card {
        display: flex;
        gap: 17px;
        align-items: flex-start;
        height: 100%;
        padding: 23px 20px;
        border: 1px solid #f0e6e0;
        border-radius: 16px;
        background: #fff;
        transition: .3s ease;
    }

    .ap-experience-card:hover {
        transform: translateY(-4px);
        border-color: #e6c6b8;
        box-shadow: 0 12px 30px rgba(99,56,46,.07);
    }

    .ap-experience-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #f9e9e1;
        color: var(--ap-accent);
        font-size: 21px;
    }

    .ap-experience-card h5 {
        margin: 2px 0 7px;
        font-size: 15px;
        font-weight: 900;
    }

    .ap-experience-card p {
        margin: 0;
        color: #8b7b73;
        font-size: 12px;
        line-height: 1.8;
    }

    /* CTA */

    .ap-cta {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 90px 20px;
        background: linear-gradient(120deg, #70463b, #42251f);
        text-align: center;
    }

    .ap-cta::before,
    .ap-cta::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
    }

    .ap-cta::before {
        width: 340px;
        height: 340px;
        top: -190px;
        left: -100px;
    }

    .ap-cta::after {
        width: 400px;
        height: 400px;
        right: -140px;
        bottom: -240px;
    }

    .ap-cta-label {
        color: #efc3b2;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
    }

    .ap-cta h2 {
        max-width: 750px;
        margin: 18px auto;
        color: #fff;
        font-size: clamp(30px, 5vw, 48px);
        font-weight: 900;
        line-height: 1.2;
    }

    .ap-cta p {
        max-width: 600px;
        margin: 0 auto 28px;
        color: rgba(255,255,255,.72);
        font-size: 14px;
        line-height: 1.9;
    }

    .ap-cta .ap-btn-light {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 29px;
        border-radius: 50px;
        background: #fff;
        color: var(--ap-wine);
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        transition: .3s ease;
    }

    .ap-cta .ap-btn-light:hover {
        transform: translateY(-4px);
        background: #f7e5dc;
    }

    /* RESPONSIVE */

    @media (max-width: 991px) {
        .ap-story-content {
            padding-left: 12px;
        }

        .ap-story-panel {
            min-height: 330px;
        }
    }

    @media (max-width: 767px) {
        .ap-hero {
            min-height: auto;
            padding: 75px 0;
        }

        .ap-hero h1 {
            letter-spacing: -1.8px;
        }

        .ap-hero p {
            font-size: 14px;
        }

        .ap-story,
        .ap-purpose,
        .ap-values,
        .ap-experience {
            padding: 65px 0;
        }

        .ap-story-panel {
            min-height: 290px;
            margin-bottom: 25px;
        }

        .ap-story-symbol {
            width: 145px;
            height: 145px;
        }

        .ap-story-symbol i {
            font-size: 65px;
        }

        .ap-story-content {
            padding-left: 0;
        }

        .ap-highlight-box {
            padding: 22px 12px;
        }

        .ap-cta {
            padding: 70px 18px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .about-page *,
        .about-page *::before,
        .about-page *::after {
            transition: none !important;
        }
    }
</style>

<div class="about-page">

    <!-- HERO -->
    <section class="ap-hero">
        <div class="container">
            <div class="ap-hero-content">

                <span class="ap-hero-badge">
                    <i class="bi bi-stars"></i>
                    Welcome to ZAPCODEX Bakery
                </span>

                <h1>
                    Where Every Bite<br>
                    <span class="ap-gradient-text">Feels Like Happiness.</span>
                </h1>

                <p>
                    More than just a bakery, we are a part of your
                    celebrations, little joys and unforgettable memories.
                    Discover the love and passion behind every creation.
                </p>

                <div class="ap-hero-actions">
                    <a href="{{ route('customer.products') }}"
                       class="ap-btn-primary">
                        Explore Our Products
                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <a href="#our-story" class="ap-btn-outline">
                        Discover Our Story
                        <i class="bi bi-chevron-down"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- HIGHLIGHTS -->
    <section class="ap-highlights">
        <div class="container">
            <div class="row g-3">

                <div class="col-6 col-lg-3">
                    <div class="ap-highlight-box">
                        <i class="bi bi-heart-fill"></i>
                        <strong>Made With Love</strong>
                        <span>Care in every creation</span>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="ap-highlight-box">
                        <i class="bi bi-award-fill"></i>
                        <strong>Quality First</strong>
                        <span>Thoughtful ingredient choices</span>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="ap-highlight-box">
                        <i class="bi bi-palette-fill"></i>
                        <strong>Creative Designs</strong>
                        <span>Special touches for celebrations</span>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="ap-highlight-box">
                        <i class="bi bi-emoji-smile-fill"></i>
                        <strong>Happy Moments</strong>
                        <span>Memories worth celebrating</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- OUR STORY -->
    <section class="ap-story" id="our-story">
        <div class="container">
            <div class="row align-items-center g-4">

                <div class="col-lg-6">
                    <div class="ap-story-panel">
                        <div class="ap-story-decoration one"></div>
                        <div class="ap-story-decoration two"></div>

                        <div class="ap-story-symbol">
                            <i class="bi bi-cake2-fill"></i>
                        </div>

                        <div class="ap-story-floating">
                            <i class="bi bi-heart-fill"></i>
                            Baked With Passion
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ap-story-content">

                        <span class="ap-label">A Little About Us</span>

                        <h2 class="ap-heading mt-3">
                            Our Story Is Made Of
                            <span class="ap-gradient-text">Sweet Moments.</span>
                        </h2>

                        <p>
                            At ZAPCODEX Bakery, we believe that every
                            celebration deserves something extraordinary.
                            A birthday, an anniversary, a family gathering
                            or an everyday craving — every moment becomes
                            more special with something delicious.
                        </p>

                        <p>
                            Our love for baking inspires us to bring
                            together delightful flavours, carefully
                            selected ingredients and creative designs.
                            From celebration cakes to breads, pastries
                            and cookies, we want every experience to
                            feel memorable.
                        </p>

                        <p>
                            We are committed to making our customers
                            feel valued through delicious products,
                            thoughtful service and a welcoming bakery
                            experience.
                        </p>

                        <div class="ap-story-quote">
                            <i class="bi bi-quote"></i>
                            <span>
                                We don't just create bakery products.
                                We create little reasons to celebrate.
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- MISSION & VISION -->
    <section class="ap-purpose">
        <div class="container">

            <div class="text-center mb-5">
                <span class="ap-label">Our Purpose</span>

                <h2 class="ap-heading mt-3">
                    The Values Behind <span class="ap-gradient-text">Our Work</span>
                </h2>

                <p class="ap-section-intro">
                    Every decision we make is guided by our commitment
                    to quality, creativity and the happiness of our customers.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="ap-purpose-card">
                        <span class="ap-purpose-number">01</span>

                        <div class="ap-purpose-icon">
                            <i class="bi bi-bullseye"></i>
                        </div>

                        <h3>Our Mission</h3>

                        <p>
                            To serve fresh, delicious and high-quality
                            bakery products while delivering a wonderful
                            customer experience. We aim to make every
                            occasion sweeter through quality, care
                            and creativity.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="ap-purpose-card">
                        <span class="ap-purpose-number">02</span>

                        <div class="ap-purpose-icon">
                            <i class="bi bi-eye-fill"></i>
                        </div>

                        <h3>Our Vision</h3>

                        <p>
                            To become a trusted and recognised bakery
                            brand known for delicious flavours,
                            innovative designs, consistent quality
                            and memorable experiences across every branch.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- OUR VALUES -->
    <section class="ap-values">
        <div class="container">

            <div class="text-center mb-5">
                <span class="ap-label">What Matters To Us</span>

                <h2 class="ap-heading mt-3">
                    What Makes Us <span class="ap-gradient-text">Special?</span>
                </h2>

                <p class="ap-section-intro">
                    It's the little details, the care we put into our work
                    and the happiness we share that make the difference.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-6 col-lg-3">
                    <div class="ap-value-card">
                        <div class="ap-value-icon">
                            <i class="bi bi-stars"></i>
                        </div>

                        <h5>Freshness & Taste</h5>

                        <p>
                            We focus on delivering delicious bakery
                            products with freshness and care.
                        </p>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="ap-value-card">
                        <div class="ap-value-icon">
                            <i class="bi bi-gem"></i>
                        </div>

                        <h5>Quality Matters</h5>

                        <p>
                            We value thoughtful ingredient selection
                            and attention to every detail.
                        </p>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="ap-value-card">
                        <div class="ap-value-icon">
                            <i class="bi bi-palette-fill"></i>
                        </div>

                        <h5>Creative Touch</h5>

                        <p>
                            We bring creativity and beautiful designs
                            to your special celebrations.
                        </p>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="ap-value-card">
                        <div class="ap-value-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>

                        <h5>Customer Happiness</h5>

                        <p>
                            We believe every customer deserves
                            a welcoming and memorable experience.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="ap-experience">
        <div class="container">

            <div class="text-center mb-5">
                <span class="ap-label">The ZAPCODEX Experience</span>

                <h2 class="ap-heading mt-3">
                    A Little More Than <span class="ap-gradient-text">Just Baking.</span>
                </h2>

                <p class="ap-section-intro">
                    We want every visit, every order and every celebration
                    to be an experience worth remembering.
                </p>
            </div>

            <div class="row g-4">

                <div class="col-md-6 col-lg-3">
                    <div class="ap-experience-card">
                        <div class="ap-experience-icon">
                            <i class="bi bi-cup-hot-fill"></i>
                        </div>

                        <div>
                            <h5>Something For Everyone</h5>
                            <p>
                                A variety of bakery favourites
                                for different tastes and occasions.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="ap-experience-card">
                        <div class="ap-experience-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>

                        <div>
                            <h5>Made For Celebrations</h5>
                            <p>
                                Special cakes and sweet treats
                                for your memorable occasions.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="ap-experience-card">
                        <div class="ap-experience-icon">
                            <i class="bi bi-chat-heart-fill"></i>
                        </div>

                        <div>
                            <h5>Personal Attention</h5>
                            <p>
                                We value your preferences,
                                feedback and individual needs.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="ap-experience-card">
                        <div class="ap-experience-icon">
                            <i class="bi bi-shop"></i>
                        </div>

                        <div>
                            <h5>A Welcoming Experience</h5>
                            <p>
                                We want every customer to feel
                                welcome and appreciated.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="ap-cta">
        <div class="container">

            <span class="ap-cta-label">
                A World Of Sweet Possibilities
            </span>

            <h2>
                Life Is Better With A Little More Sweetness.
            </h2>

            <p>
                From everyday treats to once-in-a-lifetime celebrations,
                discover something delicious at ZAPCODEX Bakery.
                Your next favourite treat is waiting for you!
            </p>

            <a href="{{ route('customer.products') }}"
               class="ap-btn-light">
                Discover Our Collection
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>
    </section>

</div>

@endsection
