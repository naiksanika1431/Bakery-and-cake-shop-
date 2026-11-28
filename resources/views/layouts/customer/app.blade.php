<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Home') | ZAPCODEX Bakery &amp; Cake Shop</title>

    <meta name="theme-color" content="#FCEEEB">
    <meta name="description" content="Discover freshly baked cakes, pastries, desserts and more at ZAPCODEX Bakery & Cake Shop.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --pink-bg: #FCEEEB;
            --pink-light: #FCEFE9;
            --pink-soft: #FAE7E3;
            --pink-card: #FFF7F4;
            --pink-border: #EBD5D1;
            --pink-shadow: #E9C9C4;

            --wine: #592122;
            --wine-dark: #421719;
            --wine-light: #793638;

            --ink: #211414;
            --muted: #806C68;
            --white: #FFFFFF;

            --font-heading: 'DM Serif Display', Georgia, serif;
            --font-body: 'DM Sans', sans-serif;
            --font-elegant: 'Cormorant Garamond', Georgia, serif;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            overflow-x: hidden;
            background: var(--pink-bg);
            color: var(--ink);
            font-family: var(--font-body);
            -webkit-font-smoothing: antialiased;
        }

        ::selection {
            background: var(--wine);
            color: var(--white);
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--ink);
            font-family: var(--font-heading);
            font-weight: 400;
        }

        button, input, select, textarea {
            font-family: var(--font-body);
        }

        :focus-visible {
            outline: 2px solid var(--wine);
            outline-offset: 4px;
        }

        /* NAVBAR */

        .customer-navbar {
            position: sticky;
            top: 0;
            z-index: 1050;
            width: 100%;
            padding: 13px 0;
            background: rgba(252, 238, 235, .96);
            border-bottom: 1px solid rgba(89, 33, 34, .09);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 4px 20px rgba(89, 33, 34, .035);
        }

        .customer-navbar .container {
            max-width: 1440px;
        }

        .customer-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            color: var(--ink);
        }

        .customer-brand:hover {
            color: var(--wine);
        }

        .brand-flower {
            color: var(--wine);
            font-size: 1.8rem;
        }

        .brand-logo {
            display: flex;
            flex-direction: column;
            line-height: .95;
        }

        .brand-name {
            color: var(--ink);
            font-family: var(--font-heading);
            font-size: 1.85rem;
            letter-spacing: -.8px;
        }

        .brand-tagline {
            margin-top: 7px;
            color: var(--wine);
            font-size: .55rem;
            font-weight: 700;
            letter-spacing: .25em;
            text-align: center;
        }

        .customer-navbar .navbar-nav {
            align-items: center;
            gap: 3px;
        }

        .customer-navbar .nav-link {
            position: relative;
            padding: 11px 9px !important;
            color: var(--ink);
            font-size: .83rem;
            font-weight: 600;
            white-space: nowrap;
            transition: color .25s ease;
        }

        .customer-navbar .nav-link::after {
            position: absolute;
            right: 9px;
            bottom: 4px;
            left: 9px;
            height: 2px;
            background: var(--wine);
            border-radius: 5px;
            content: "";
            transform: scaleX(0);
            transform-origin: center;
            transition: transform .25s ease;
        }

        .customer-navbar .nav-link:hover,
        .customer-navbar .nav-link.active {
            color: var(--wine);
        }

        .customer-navbar .nav-link:hover::after,
        .customer-navbar .nav-link.active::after {
            transform: scaleX(1);
        }

        .customer-actions {
            gap: 9px;
        }

        .sb-branch-select {
            width: 140px;
            min-height: 39px;
            padding: 7px 28px 7px 12px;
            border: 1px solid var(--pink-border);
            border-radius: 30px;
            background-color: var(--pink-card);
            color: var(--wine);
            font-size: .76rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: none !important;
        }

        .sb-branch-select:focus {
            border-color: var(--wine);
        }

        .sb-icon-link {
            position: relative;
            display: inline-flex;
            width: 39px;
            height: 39px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--ink);
            font-size: 1.15rem;
            transition: all .25s ease;
        }

        .sb-icon-link:hover {
            background: var(--pink-soft);
            color: var(--wine);
            transform: translateY(-2px);
        }

        .sb-cart-badge {
            position: absolute;
            top: 0;
            right: -2px;
            display: grid;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            place-items: center;
            border: 2px solid var(--pink-bg);
            border-radius: 50px;
            background: var(--wine);
            color: var(--white);
            font-size: .62rem;
            font-weight: 700;
        }

        .sb-login-link {
            color: var(--ink);
            font-size: .82rem;
            font-weight: 700;
            white-space: nowrap;
            transition: color .2s ease;
        }

        .sb-login-link:hover {
            color: var(--wine-light);
        }

        .btn-order-online {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 17px;
            border: 1px solid var(--wine);
            border-radius: 50px;
            background: var(--wine);
            color: var(--white);
            font-size: .8rem;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 5px 14px rgba(89, 33, 34, .13);
            transition: all .25s ease;
        }

        .btn-order-online:hover {
            transform: translateY(-2px);
            border-color: var(--wine-dark);
            background: var(--wine-dark);
            color: var(--white);
            box-shadow: 0 9px 20px rgba(89, 33, 34, .2);
        }

        .btn-order-online i {
            transition: transform .25s ease;
        }

        .btn-order-online:hover i {
            transform: translateX(4px);
        }

        /* CUSTOM MENU BUTTON - NO THREE LINES */

        .customer-navbar .navbar-toggler {
            display: inline-flex;
            width: auto;
            min-width: 88px;
            height: 42px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 14px;
            border: 1px solid var(--pink-border);
            border-radius: 30px;
            background: var(--pink-card);
            color: var(--wine);
            box-shadow: none;
        }

        .customer-navbar .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(89, 33, 34, .12);
        }

        .menu-toggle-label {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
        }

        .menu-chevron {
            font-size: .85rem;
            transition: transform .25s ease;
        }

        .customer-navbar .navbar-toggler[aria-expanded="true"] .menu-chevron {
            transform: rotate(180deg);
        }

        /* MAIN CONTENT */

        main {
            position: relative;
            min-height: 55vh;
            background: var(--pink-bg);
        }

        .sb-section-label {
            display: block;
            margin-bottom: 12px;
            color: var(--wine);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .32em;
            text-transform: uppercase;
        }

        .sb-section-heading {
            margin: 0;
            color: var(--ink);
            font-family: var(--font-heading);
            font-size: clamp(2.2rem, 4vw, 3.7rem);
            line-height: 1.05;
        }

        .sb-section-heading em {
            color: var(--wine);
            font-family: var(--font-elegant);
            font-weight: 600;
        }

        .sb-elegant-text {
            color: var(--muted);
            font-family: var(--font-elegant);
            font-size: 1.3rem;
            font-style: italic;
        }

        .sb-primary-btn,
        .sb-outline-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 25px;
            border: 1px solid var(--wine);
            border-radius: 50px;
            font-size: .9rem;
            font-weight: 600;
            transition: all .25s ease;
        }

        .sb-primary-btn {
            background: var(--wine);
            color: var(--white);
        }

        .sb-primary-btn:hover {
            transform: translateY(-2px);
            background: var(--wine-dark);
            color: var(--white);
            box-shadow: 0 8px 18px rgba(89, 33, 34, .18);
        }

        .sb-outline-btn {
            background: transparent;
            color: var(--wine);
        }

        .sb-outline-btn:hover {
            transform: translateY(-2px);
            background: var(--wine);
            color: var(--white);
        }

        .sb-soft-card {
            overflow: hidden;
            border: 1px solid rgba(89, 33, 34, .07);
            border-radius: 20px;
            background: var(--pink-card);
            box-shadow: 0 7px 24px rgba(89, 33, 34, .045);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .sb-soft-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 32px rgba(89, 33, 34, .1);
        }

        main .form-control,
        main .form-select {
            min-height: 44px;
            border: 1px solid var(--pink-border);
            border-radius: 10px;
            background-color: var(--pink-card);
            color: var(--ink);
            box-shadow: none;
        }

        main .form-control:focus,
        main .form-select:focus {
            border-color: var(--wine);
            box-shadow: 0 0 0 3px rgba(89, 33, 34, .08);
        }

        main .form-control::placeholder {
            color: #A58D87;
        }

        /* FOOTER */

        .customer-footer {
            position: relative;
            overflow: hidden;
            margin-top: 65px;
            padding: 65px 0 22px;
            background: var(--wine);
            color: rgba(255, 255, 255, .82);
        }

        .customer-footer::before {
            position: absolute;
            top: -90px;
            right: -80px;
            width: 270px;
            height: 270px;
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 50%;
            box-shadow:
                0 0 0 25px rgba(255, 255, 255, .025),
                0 0 0 50px rgba(255, 255, 255, .02);
            content: "";
            pointer-events: none;
        }

        .customer-footer::after {
            position: absolute;
            bottom: -120px;
            left: -100px;
            width: 260px;
            height: 260px;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 50%;
            content: "";
            pointer-events: none;
        }

        .customer-footer .container {
            position: relative;
            z-index: 1;
        }

        .sb-footer-brand {
            display: inline-block;
            margin-bottom: 5px;
            color: var(--white);
            font-family: var(--font-heading);
            font-size: 2.35rem;
            line-height: 1;
        }

        .sb-footer-brand:hover {
            color: #F5C8C8;
        }

        .sb-footer-tagline {
            display: block;
            margin-bottom: 19px;
            color: #E9BFC0;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .25em;
        }

        .customer-footer p {
            max-width: 350px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, .76);
            font-size: .9rem;
            line-height: 1.8;
        }

        .customer-footer h5 {
            margin-bottom: 20px;
            color: var(--white);
            font-family: var(--font-heading);
            font-size: 1.35rem;
        }

        .customer-footer a {
            color: rgba(255, 255, 255, .78);
            transition: color .2s ease, transform .2s ease;
        }

        .customer-footer a:hover {
            color: #F5C8C8;
        }

        .sb-footer-links {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .sb-footer-links li {
            margin-bottom: 12px;
        }

        .sb-footer-links a {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .9rem;
        }

        .sb-footer-links a i {
            color: #E9BFC0;
            font-size: .75rem;
            transition: transform .2s ease;
        }

        .sb-footer-links a:hover i {
            transform: translateX(4px);
        }

        .sb-footer-contact p {
            display: flex;
            align-items: flex-start;
            gap: 11px;
        }

        .sb-footer-contact i {
            margin-top: 4px;
            color: #E9BFC0;
        }

        .sb-social {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .sb-social a {
            display: inline-flex;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 50%;
            color: var(--white);
            transition: all .25s ease;
        }

        .sb-social a:hover {
            transform: translateY(-4px);
            border-color: var(--pink-bg);
            background: var(--pink-bg);
            color: var(--wine);
        }

        .sb-footer-divider {
            height: 1px;
            margin: 38px 0 18px;
            border: 0;
            background: rgba(255, 255, 255, .2);
        }

        .sb-copyright {
            margin: 0;
            color: rgba(255, 255, 255, .7);
            font-size: .82rem;
            text-align: center;
        }

        .sb-copyright span {
            color: #F0C2C1;
        }

        /* RESPONSIVE */

        @media (max-width: 1399px) {
            .customer-navbar .nav-link {
                padding-right: 7px !important;
                padding-left: 7px !important;
                font-size: .79rem;
            }

            .customer-actions {
                gap: 7px;
            }

            .sb-branch-select {
                width: 125px;
            }

            .btn-order-online {
                padding: 9px 12px;
            }
        }

        @media (max-width: 991px) {
            .customer-navbar {
                padding: 10px 0;
            }

            .customer-navbar .navbar-collapse {
                margin-top: 14px;
                padding: 15px;
                border: 1px solid var(--pink-border);
                border-radius: 18px;
                background: var(--pink-card);
                box-shadow: 0 12px 25px rgba(89, 33, 34, .07);
            }

            .customer-navbar .navbar-nav {
                align-items: stretch;
                gap: 0;
            }

            .customer-navbar .nav-link {
                padding: 12px 8px !important;
                border-bottom: 1px solid rgba(89, 33, 34, .07);
                font-size: .94rem;
            }

            .customer-navbar .nav-link::after {
                display: none;
            }

            .customer-navbar .nav-link.active {
                color: var(--wine);
                font-weight: 700;
            }

            .customer-actions {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                padding-top: 15px;
            }

            .sb-branch-select {
                width: 180px;
            }

            .customer-footer {
                margin-top: 45px;
                padding-top: 50px;
            }
        }

        @media (max-width: 575px) {
            .brand-name {
                font-size: 1.55rem;
            }

            .brand-tagline {
                font-size: .48rem;
            }

            .brand-flower {
                font-size: 1.45rem;
            }

            .customer-actions {
                gap: 12px;
            }

            .customer-actions form {
                width: 100%;
            }

            .sb-branch-select {
                width: 100%;
            }

            .btn-order-online {
                padding: 10px 17px;
                font-size: .82rem;
            }

            .customer-footer {
                padding: 45px 0 20px;
            }

            .sb-footer-brand {
                font-size: 2rem;
            }

            .sb-copyright {
                font-size: .75rem;
                line-height: 1.7;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }

        @media print {
            .customer-navbar,
            .customer-footer {
                display: none !important;
            }

            body,
            main {
                background: #fff !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar navbar-expand-lg customer-navbar">
        <div class="container">

            <a class="customer-brand" href="{{ route('customer.home') }}">
                <span class="brand-flower">
                    <i class="bi bi-flower1"></i>
                </span>

                <span class="brand-logo">
                    <span class="brand-name">ZAPCODEX</span>
                    <span class="brand-tagline">PATISSERIE &amp; CAF&Eacute;</span>
                </span>
            </a>

            <!-- Mobile menu button without hamburger lines -->
            
            <div class="collapse navbar-collapse" id="customerNavbar">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}"
                           href="{{ route('customer.home') }}">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.categories') ? 'active' : '' }}"
                           href="{{ route('customer.categories') }}">
                            Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.products') ? 'active' : '' }}"
                           href="{{ route('customer.products') }}">
                            Our Products
                        </a>
                    </li>


                    @auth('customer')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('custom-cake-booking*') ? 'active' : '' }}"
                           href="{{ url('/custom-cake-booking') }}">
                            Custom Cake Booking
                        </a>
                    </li>
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.about') ? 'active' : '' }}"
                           href="{{ route('customer.about') }}">
                            About Us
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.contact') ? 'active' : '' }}"
                           href="{{ route('customer.contact') }}">
                            Contact
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Track My Order
                        </a>
                    </li>

                </ul>

                <div class="d-flex align-items-center customer-actions">

                    <!-- Branch Selector -->
                    <form method="POST" action="{{ route('customer.branch.select') }}">
                        @csrf

                        <select name="branch_id"
                                class="form-select sb-branch-select"
                                onchange="this.form.submit()"
                                aria-label="Choose your bakery branch"
                                required>

                            <option value="">Choose Branch</option>

                            @foreach($customerBranches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ optional($customerSelectedBranch)->id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach

                        </select>
                    </form>

                    <!-- Search -->
                    <a href="#" class="sb-icon-link" aria-label="Search">
                        <i class="bi bi-search"></i>
                    </a>

                    <!-- Cart -->
                    <a href="#" class="sb-icon-link" aria-label="Shopping cart">
                        <i class="bi bi-cart3"></i>
                        <span class="sb-cart-badge">0</span>
                    </a>

                    @auth('customer')
    <a href="{{ route('customer.account') }}" class="sb-login-link">
        <i class="bi bi-person-circle"></i>
        {{ Auth::guard('customer')->user()->name }}
    </a>

    <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn-order-online">
            Logout
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>
@else
    <!-- Login -->
    <a href="{{ route('customer.login') }}" class="sb-login-link">
        Login
    </a>

    <!-- Register -->
    <a href="{{ route('customer.register') }}" class="btn-order-online">
        Register
        <i class="bi bi-arrow-right"></i>
    </a>
@endauth

                </div>
            </div>
        </div>
    </nav>


    <!-- PAGE CONTENT -->

    <main>
        @yield('content')
    </main>


    <!-- FOOTER -->

    <footer class="customer-footer">
        <div class="container">

            <div class="row g-4">

                <!-- Brand -->
                <div class="col-lg-4 col-md-6">

                    <a href="{{ route('customer.home') }}" class="sb-footer-brand">
                        ZAPCODEX
                    </a>

                    <span class="sb-footer-tagline">
                        PATISSERIE &amp; CAF&Eacute;
                    </span>

                    <p>
                        A little sweetness makes every moment special.
                        Discover freshly baked cakes, delicate pastries,
                        artisan breads and delightful desserts made with love.
                    </p>

                    <div class="sb-social">
                        <a href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <a href="#" aria-label="Pinterest">
                            <i class="bi bi-pinterest"></i>
                        </a>

                        <a href="#" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>

                </div>

                <!-- Explore -->
                <div class="col-lg-2 col-md-6 col-6">

                    <h5>Explore</h5>

                    <ul class="sb-footer-links">
                        <li>
                            <a href="{{ route('customer.home') }}">
                                <i class="bi bi-arrow-right"></i> Home
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customer.categories') }}">
                                <i class="bi bi-arrow-right"></i> Categories
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customer.products') }}">
                                <i class="bi bi-arrow-right"></i> Our Products
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customer.about') }}">
                                <i class="bi bi-arrow-right"></i> About Us
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customer.contact') }}">
                                <i class="bi bi-arrow-right"></i> Contact
                            </a>
                        </li>
                    </ul>

                </div>

                <!-- Customer Care -->
                <div class="col-lg-3 col-md-6 col-6">

                    <h5>Customer Care</h5>

                    <ul class="sb-footer-links">
                        <li>
                            <a href="{{ route('customer.account') }}">
                                <i class="bi bi-arrow-right"></i> My Account
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="bi bi-arrow-right"></i> Track My Order
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="bi bi-arrow-right"></i> Shopping Cart
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="bi bi-arrow-right"></i> Privacy Policy
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="bi bi-arrow-right"></i> Terms &amp; Conditions
                            </a>
                        </li>
                    </ul>

                </div>

                <!-- Contact -->
                <div class="col-lg-3 col-md-6">

                    <h5>Visit Our Bakery</h5>

                    <div class="sb-footer-contact">

                        <p>
                            <i class="bi bi-geo-alt"></i>
                            <span>Visit our bakery store for freshly baked delights.</span>
                        </p>

                        <p>
                            <i class="bi bi-telephone"></i>
                            <span>Contact our team for orders and enquiries.</span>
                        </p>

                        <p>
                            <i class="bi bi-envelope"></i>
                            <span>We would love to hear from you.</span>
                        </p>

                        <p>
                            <i class="bi bi-clock"></i>
                            <span>Freshly baked happiness, every day.</span>
                        </p>

                    </div>

                </div>

            </div>

            <hr class="sb-footer-divider">

            <p class="sb-copyright">
                Copyright &copy; {{ date('Y') }}
                <span>ZAPCODEX Bakery &amp; Cake Shop</span>.
                Made with <i class="bi bi-heart-fill"></i> for dessert lovers.
                All rights reserved.
            </p>

        </div>
    </footer>

    @stack('scripts')

</body>
</html>

