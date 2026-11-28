<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Branch Admin Dashboard') | ZAPCODEX Bakery</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sb-cream: #f9efe3;
            --sb-cream-2: #f6ebdd;
            --sb-paper: #fffaf4;
            --sb-brown: #4A2616;
            --sb-brown-2: #3a1d11;
            --sb-choc: #5a3427;
            --sb-caramel: #d9a066;
            --sb-blush: #f4cdd2;
            --sb-peach: #f6c9b9;
            --sb-line: #ead8c8;
            --sb-text: #4a2f26;
            --sb-muted: #8d7468;
            --sb-radius: 14px;
        }

        body.sb-body {
            font-family: 'DM Sans', system-ui, sans-serif;
            color: var(--sb-text);
            background:
                radial-gradient(900px 400px at 100% -10%, rgba(246, 201, 185, .45), transparent 60%),
                radial-gradient(700px 400px at 0% 110%, rgba(244, 205, 210, .35), transparent 60%),
                var(--sb-cream-2) !important;
        }

        body.sb-body h1, body.sb-body h2, body.sb-body h3,
        body.sb-body h4, body.sb-body h5 {
            font-family: 'DM Serif Display', Georgia, serif;
            font-weight: 400;
        }

        /* Stop every sidebar animation while the page is loading,
           so the menu never looks like it "re-opens" after a click */
        body.sb-preload .sb-sidebar *,
        body.sb-preload .sb-sidebar *::before,
        body.sb-preload .sb-sidebar *::after {
            transition: none !important;
            animation: none !important;
        }

        /* ================= SIDEBAR ================= */
        .app-sidebar.sb-sidebar {
            background:
                radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1.5px) 0 0 / 8px 8px,
                linear-gradient(180deg, var(--sb-brown) 0%, var(--sb-brown-2) 100%) !important;
            border-right: 0;
            box-shadow: 6px 0 30px rgba(67, 34, 25, .28) !important;
        }

        .sb-sidebar .sidebar-brand {
            height: auto;
            padding: 22px 20px 18px;
            border-bottom: 1px dashed rgba(255, 255, 255, .25) !important;
        }

        .sb-sidebar .brand-link {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            padding: 0;
        }

        .sb-brand-mark {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            background: linear-gradient(135deg, var(--sb-blush), var(--sb-peach));
            color: var(--sb-brown);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, .12);
        }

        .sb-brand-name {
            display: block;
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.55rem;
            line-height: 1;
            color: #fff;
            letter-spacing: .5px;
        }

        .sb-brand-sub {
            display: block;
            margin-top: 4px;
            font-family: 'DM Serif Display', Georgia, serif;
            font-style: italic;
            font-size: .95rem;
            color: var(--sb-peach);
        }

        .sb-sidebar .sidebar-wrapper {
            padding: 6px 12px 28px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .3) transparent;
        }

        .sb-sidebar .nav-header {
            text-transform: none;
            font-family: 'DM Serif Display', Georgia, serif;
            font-style: italic;
            font-size: 1.02rem;
            color: var(--sb-peach) !important;
            padding: 20px 12px 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sb-sidebar .nav-header::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(246, 201, 185, .5), transparent);
        }

        .sb-sidebar .sidebar-menu .nav-link {
            position: relative;
            font-family: 'DM Sans', sans-serif;
            font-size: .94rem;
            color: rgba(255, 255, 255, .86) !important;
            border-radius: var(--sb-radius);
            padding: 10px 14px;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        .sb-sidebar .sidebar-menu .nav-link p {
            margin: 0;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sb-sidebar .sidebar-menu .nav-link .nav-icon {
            color: var(--sb-blush);
            font-size: 1.05rem;
            width: 1.6rem;
            transition: color .2s ease;
        }

        .sb-sidebar .sidebar-menu .nav-link:hover {
            background: rgba(255, 255, 255, .13) !important;
            color: #fff !important;
            transform: translateX(3px);
        }

        .sb-sidebar .sidebar-menu .nav-link:focus-visible {
            outline: 2px solid var(--sb-peach);
            outline-offset: 2px;
        }

        /* Active item: cream pill with a caramel marker */
        .sb-sidebar .sidebar-menu .nav-link.active {
            background: var(--sb-cream) !important;
            color: var(--sb-choc) !important;
            font-weight: 600;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .25);
            transform: none;
        }

        .sb-sidebar .sidebar-menu .nav-link.active::before {
            content: "";
            position: absolute;
            left: -12px;
            top: 20%;
            bottom: 20%;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--sb-caramel);
        }

        .sb-sidebar .sidebar-menu .nav-link.active .nav-icon {
            color: var(--sb-brown);
        }

        /* Sub menus */
        .sb-sidebar .nav-treeview {
            margin: 2px 0 6px 18px;
            padding-left: 10px;
            border-left: 1px dashed rgba(255, 255, 255, .22);
        }

        .sb-sidebar .nav-treeview > .nav-item > .nav-link {
            font-size: .88rem;
            padding: 8px 12px;
        }

        .sb-sidebar .nav-treeview > .nav-item > .nav-link .nav-icon {
            font-size: .45rem;
            width: 1.2rem;
        }

        .sb-sidebar .nav-treeview > .nav-item > .nav-link.active .nav-icon {
            color: var(--sb-caramel);
        }

        .sb-sidebar .nav-arrow {
            font-size: .8rem;
            transition: transform .2s ease;
        }

        .sb-sidebar .menu-open > .nav-link .nav-arrow {
            transform: rotate(90deg);
        }

        /* ================= TOP NAVBAR ================= */
        .app-header.sb-header {
            background: rgba(249, 239, 227, .88) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--sb-line);
            box-shadow: 0 4px 22px rgba(90, 52, 39, .07);
        }

        .sb-header .nav-link {
            color: var(--sb-choc);
            transition: color .2s ease;
        }

        .sb-header .nav-link:hover { color: var(--sb-brown); }

        .sb-header .sb-panel-title {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.35rem;
            padding-left: 14px;
            margin-left: 4px;
            border-left: 3px solid var(--sb-caramel);
            line-height: 1.1;
        }

        .sb-icon-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: grid !important;
            place-items: center;
            border: 1px solid var(--sb-line);
            background: var(--sb-paper);
            font-size: 1.05rem;
            padding: 0 !important;
            transition: all .2s ease;
        }

        .sb-icon-btn:hover {
            background: var(--sb-brown);
            border-color: var(--sb-brown);
            color: #fff !important;
        }

        .sb-dot {
            position: absolute;
            top: 9px;
            right: 10px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #d9534f;
            border: 2px solid var(--sb-paper);
        }

        .sb-profile {
            display: flex !important;
            align-items: center;
            gap: 10px;
            padding: 5px 16px 5px 6px !important;
            border: 1px solid var(--sb-line);
            border-radius: 999px;
            background: var(--sb-paper);
            margin-left: 6px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .sb-profile:hover {
            border-color: var(--sb-caramel);
            box-shadow: 0 4px 14px rgba(90, 52, 39, .12);
        }

        .sb-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--sb-choc), var(--sb-brown));
            color: var(--sb-peach);
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.1rem;
        }

        .sb-profile-name {
            font-weight: 600;
            font-size: .92rem;
            line-height: 1.1;
        }

        .sb-profile-name small {
            display: block;
            color: var(--sb-muted);
            font-size: .72rem;
            font-weight: 400;
        }

        .sb-logout {
            border-color: #c9524a;
            color: #c9524a;
            font-weight: 500;
            transition: all .2s ease;
        }

        .sb-logout:hover {
            background: #c9524a;
            color: #fff;
        }

        /* ================= MAIN CONTENT ================= */
        .app-main { background: transparent; }

        .app-content-header { padding: 26px 0 8px; }

        .sb-page-title {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 2.4rem;
            margin: 0;
            color: var(--sb-choc);
            display: inline-block;
            position: relative;
            padding-bottom: 14px;
        }

        .sb-page-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 56px;
            height: 8px;
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='56' height='8' viewBox='0 0 56 8'><path d='M1 4 Q7 0 14 4 T28 4 T42 4 T55 4' fill='none' stroke='%23d9a066' stroke-width='1.8' stroke-linecap='round'/></svg>") no-repeat;
        }

        .app-content .card {
            border: 1px solid var(--sb-line);
            border-radius: 18px;
            background: var(--sb-paper);
            box-shadow: 0 10px 28px rgba(90, 52, 39, .07);
        }

        .app-content .card-header {
            background: transparent;
            border-bottom: 1px dashed var(--sb-line);
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: 1.15rem;
        }

        .app-content .table thead th {
            color: var(--sb-muted);
            font-weight: 600;
            font-size: .82rem;
            border-bottom: 1px solid var(--sb-line);
        }

        .app-content .btn-primary {
            background: var(--sb-brown);
            border-color: var(--sb-brown);
            border-radius: 12px;
        }

        .app-content .btn-primary:hover {
            background: var(--sb-choc);
            border-color: var(--sb-choc);
        }

        /* ================= FOOTER ================= */
        .app-footer.sb-footer {
            position: relative;
            background:
                radial-gradient(rgba(255, 255, 255, .06) 1px, transparent 1.5px) 0 0 / 8px 8px,
                linear-gradient(135deg, var(--sb-brown-2), var(--sb-brown)) !important;
            color: rgba(255, 255, 255, .85);
            border-top: 0;
            padding: 34px 24px 18px;
            font-size: .9rem;
            clip-path: polygon(0 18%, 5% 6%, 11% 20%, 18% 4%, 26% 18%, 34% 2%, 42% 16%, 50% 5%, 58% 20%, 66% 4%, 74% 18%, 82% 3%, 90% 17%, 96% 6%, 100% 16%, 100% 100%, 0 100%);
        }

        .sb-footer strong { color: #fff; font-weight: 600; }

        @media (max-width: 575.98px) {
            .sb-page-title { font-size: 1.9rem; }
            .sb-profile { padding-right: 6px !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sb-sidebar .sidebar-menu .nav-link,
            .sb-sidebar .nav-arrow,
            .sb-icon-btn { transition: none; }
        }
    </style>

    @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sb-body sb-preload">

<div class="app-wrapper">

    <!-- Top Navbar -->
    <nav class="app-header navbar navbar-expand sb-header">
        <div class="container-fluid">

            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
                        <i class="bi bi-list fs-4"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-md-block">
                    <span class="nav-link sb-panel-title">Branch Admin Panel</span>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a href="#" class="nav-link sb-icon-btn" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="sb-dot"></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.profile.edit') }}" class="nav-link sb-profile">
                        <span class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'Branch Admin', 0, 1)) }}</span>
                        <span class="sb-profile-name d-none d-sm-block">
                            {{ auth()->user()->name ?? 'Branch Admin' }}
                            <small>Super Admin</small>
                        </span>
                    </a>
                </li>

                <li class="nav-item ms-2">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger sb-logout rounded-pill px-3">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>

        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="app-sidebar sb-sidebar shadow" data-bs-theme="dark">

        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <span class="sb-brand-mark"><i class="bi bi-cake2"></i></span>
                <span>
                    <span class="sb-brand-name">ZAPCODEX</span>
                    <span class="sb-brand-sub">Bakery &amp; Cake Shop</span>
                </span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">

                <ul class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu"
                    data-accordion="false">

                    <!-- 1. DASHBOARD -->
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- 2. SALES MANAGEMENT -->
                    <li class="nav-header">Sales management</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pos.index') }}"
                           class="nav-link {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-receipt"></i>
                            <p>POS Billing</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cart-check"></i>
                            <p>Orders</p>
                        </a>
                    </li>
<li class="nav-item">
    <a href="{{ url('/admin/custom-cake-orders') }}"
       class="nav-link {{ request()->is('admin/custom-cake-orders*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-cake2"></i>
        <p>Custom Cake Orders</p>
    </a>
</li>
<li class="nav-item {{ request()->routeIs('admin.customers.*', 'admin.customer-ledger.*', 'admin.customer-payments.*') ? 'menu-open' : '' }}">
                        <a href="#"
                           class="nav-link {{ request()->routeIs('admin.customers.*', 'admin.customer-ledger.*', 'admin.customer-payments.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Customers <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.customers.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Customer List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customer-ledger.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.customer-ledger.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Customer Ledger</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customer-payments.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.customer-payments.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Customer Payments</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 3. PRODUCT MANAGEMENT -->
                    <li class="nav-header">Product management</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i>
                            <p>Categories</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-seam"></i>
                            <p>Products</p>
                        </a>
                    </li>

                    <!-- 4. INVENTORY / STOCK -->
                    <li class="nav-header">Inventory</li>

                    <li class="nav-item {{ request()->routeIs('admin.stock.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-boxes"></i>
                            <p>Inventory / Stock <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.stock.raw-materials') }}"
                                   class="nav-link {{ request()->routeIs('admin.stock.raw-materials') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Raw Materials</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.stock.finished-goods') }}"
                                   class="nav-link {{ request()->routeIs('admin.stock.finished-goods') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Finished Goods</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.stock.ledger') }}"
                                   class="nav-link {{ request()->routeIs('admin.stock.ledger') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Stock Operations</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Wastage Management</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon bi bi-circle-fill"></i>
                                    <p>Inventory Reports</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 5. PURCHASE MANAGEMENT -->
                    <li class="nav-header">Purchase management</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}"
                           class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>Suppliers</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.supplier-purchases.index') }}"
                           class="nav-link {{ request()->routeIs('admin.supplier-purchases.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bag-plus"></i>
                            <p>Purchases</p>
                        </a>
                    </li>

                    <!-- 6. STAFF & FINANCE -->
                    <li class="nav-header">Staff &amp; finance</li>

                    <li class="nav-item">
    <a href="{{ route('admin.employees.index') }}"
       class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-person-badge"></i>
        <p>Employees</p>
    </a>
</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-cash-stack"></i>
                            <p>Expenses</p>
                        </a>
                    </li>

                    <!-- 7. REPORTS & INTEGRATIONS -->
                    <li class="nav-header">Reports &amp; integrations</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-graph-up"></i>
                            <p>Sales Reports</p>
                        </a>
                    </li>

                    <li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi bi-journal-richtext"></i>
        <p>Recipes</p>
    </a>
</li>

                    <!-- 8. SETTINGS -->
                    <li class="nav-header">Settings</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-gear"></i>
                            <p>Branch Settings</p>
                        </a>
                    </li>

                </ul>

            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <h3 class="sb-page-title">@yield('page-heading', 'Dashboard')</h3>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="app-footer sb-footer">
        <strong>Copyright &copy; {{ date('Y') }} ZAPCODEX Bakery &amp; Cake Shop.</strong>
        All rights reserved.
    </footer>

</div>

<!-- STABLE SIDEBAR: remembers scroll position between page loads -->
<script>
(function () {
    var KEY = 'zapcodex:sidebar-scroll';
    var wrapper = document.querySelector('.sb-sidebar .sidebar-wrapper');
    if (!wrapper) return;

    var ready = false; // only save after the sidebar has finished initialising

    function scroller() {
        // AdminLTE 4 wraps the menu with OverlayScrollbars after load
        return wrapper.querySelector('[data-overlayscrollbars-viewport]') || wrapper;
    }

    function read() {
        try {
            var v = sessionStorage.getItem(KEY);
            return v === null ? null : parseInt(v, 10);
        } catch (e) { return null; }
    }

    function write() {
        if (!ready) return;
        try { sessionStorage.setItem(KEY, String(scroller().scrollTop)); } catch (e) {}
    }

    function restore() {
        var s = scroller();
        var saved = read();

        if (saved !== null && !isNaN(saved)) {
            s.scrollTop = saved;
            return;
        }

        // First visit: bring the active link into view
        var active = wrapper.querySelector('.nav-link.active');
        if (active) {
            var top = active.getBoundingClientRect().top - s.getBoundingClientRect().top + s.scrollTop;
            s.scrollTop = Math.max(0, top - s.clientHeight / 2);
        }
    }

    // 1) restore immediately (before first paint of the menu)
    restore();

    // 2) restore again once AdminLTE / OverlayScrollbars are ready
    window.addEventListener('load', function () {
        restore();
        setTimeout(restore, 80);
        setTimeout(restore, 250);

        setTimeout(function () {
            ready = true;
            document.body.classList.remove('sb-preload');
        }, 350);
    });

    // 3) save while scrolling, on click and when leaving the page
    wrapper.addEventListener('scroll', write, true);
    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('a.nav-link')) write();
    });
    window.addEventListener('pagehide', write);
})();
</script>

@stack('scripts')

</body>
</html>
