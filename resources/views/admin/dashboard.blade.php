@extends('layouts.admin.app')

@section('title', 'Branch Dashboard')
@section('page-heading', 'Branch Dashboard Overview')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600;700&display=swap');

    .sb {
        /* Palette taken from the "Step Into Style" sneaker poster */
        --sb-cream: #EADFD3;        /* poster wall */
        --sb-cream-2: #DDCEBF;      /* table header tone */
        --sb-paper: #F4EDE5;        /* light cream surface */
        --sb-mauve: #6B3A24;        /* chocolate brown (primary) */
        --sb-mauve-deep: #4A2616;   /* pressed brown */
        --sb-choc: #301c0d;         /* espresso ink */
        --sb-choc-deep: #1f1108;
        --sb-blush: #E5D5C6;        /* soft beige tint */
        --sb-beige: #D6C5B3;        /* podium beige */
        --sb-lilac: #BFA990;        /* podium shadow */
        --sb-peach: #CDB8A4;        /* mid beige */
        --sb-line: #D9C8B7;
        --sb-text: #301c0d;
        --sb-muted: #85705F;

        font-family: 'DM Sans', system-ui, sans-serif;
        color: var(--sb-text);
        background: var(--sb-cream);
        border-radius: 28px;
        padding: 28px;
    }

    .sb h1, .sb h2, .sb h3, .sb h4, .sb h5 {
        font-family: 'DM Serif Display', Georgia, serif;
        font-weight: 400;
        letter-spacing: 0;
        color: var(--sb-mauve);
    }

    /* ---------- Hero (chocolate block with torn edges) ---------- */
    .sb-hero {
        position: relative;
        padding: 56px 48px;
        color: #fff;
        background:
            radial-gradient(rgba(255, 255, 255, .06) 1px, transparent 1.5px) 0 0 / 7px 7px,
            radial-gradient(circle at 88% 30%, rgba(214, 197, 179, .25), transparent 45%),
            linear-gradient(135deg, #4A2616, var(--sb-mauve) 55%, var(--sb-mauve-deep));
        clip-path: polygon(0 5%, 4% 1%, 9% 6%, 15% 1%, 22% 5%, 30% 0, 38% 5%, 46% 1%, 54% 6%, 62% 1%, 70% 5%, 78% 0, 86% 5%, 93% 1%, 100% 5%,
                           100% 95%, 94% 99%, 87% 94%, 79% 100%, 71% 95%, 63% 99%, 55% 94%, 47% 100%, 39% 95%, 31% 99%, 23% 94%, 15% 100%, 8% 95%, 0 99%);
        display: flex; align-items: center; justify-content: space-between;
        gap: 24px; flex-wrap: wrap;
        animation: sbFade .8s ease both;
    }
    .sb-hero::after {
        content: "\F4D0";
        font-family: 'bootstrap-icons';
        position: absolute; right: 60px; top: 50%;
        transform: translateY(-50%);
        font-size: 170px; line-height: 1;
        color: rgba(255, 255, 255, .13);
        pointer-events: none;
    }
    .sb-hero-text { position: relative; z-index: 1; }
    .sb-hero h2 { color: #fff; font-size: 2.7rem; line-height: 1.1; margin: 0 0 12px; }
    .sb-hero h2 em { font-style: normal; color: var(--sb-beige); display: block; }
    .sb-hero p { margin: 0 0 22px; color: rgba(255, 255, 255, .85); max-width: 42ch; }
    .sb-hero-btn {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--sb-choc); color: #fff; text-decoration: none;
        padding: 12px 26px; border-radius: 999px; font-weight: 600; font-size: .95rem;
        box-shadow: 0 10px 20px rgba(48, 28, 13, .3);
        transition: transform .2s ease, background .2s ease;
    }
    .sb-hero-btn:hover { background: var(--sb-choc-deep); color: #fff; transform: translateY(-2px); }
    .sb-date {
        position: relative; z-index: 1;
        background: rgba(255, 255, 255, .16);
        border: 1px solid rgba(255, 255, 255, .35);
        border-radius: 999px; padding: 10px 20px;
        font-weight: 500; white-space: nowrap;
    }
    .sb-date i { margin-right: 8px; color: var(--sb-beige); }

    @keyframes sbFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

    /* ---------- Stat cards ---------- */
    .sb-stat {
        height: 100%;
        background: var(--sb-paper);
        border: 1px solid var(--sb-line);
        border-radius: 24px;
        padding: 22px 22px 20px;
        box-shadow: 0 10px 26px rgba(48, 28, 13, .08);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .sb-stat:hover { transform: translateY(-4px); box-shadow: 0 18px 34px rgba(48, 28, 13, .16); }
    .sb-stat-top { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
    .sb-stat-icon {
        width: 54px; height: 54px; border-radius: 50%; flex-shrink: 0;
        display: grid; place-items: center; font-size: 1.35rem;
        background: var(--sb-blush); color: var(--sb-mauve);
        transition: background .25s ease, color .25s ease;
    }
    .sb-stat:hover .sb-stat-icon { background: var(--sb-mauve); color: #fff; }
    .sb-stat-label { font-weight: 500; color: var(--sb-muted); line-height: 1.2; }
    .sb-stat-value {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 2.8rem; line-height: 1; color: var(--sb-mauve); margin: 0 0 18px;
    }
    .sb-stat-foot {
        display: flex; align-items: center; justify-content: space-between;
        padding-top: 14px; border-top: 1px dashed var(--sb-line);
    }
    .sb-stat-foot a { color: var(--sb-mauve); font-weight: 600; font-size: .9rem; text-decoration: none; }
    .sb-stat-foot a:hover { color: var(--sb-choc); }
    .sb-round {
        width: 36px; height: 36px; border-radius: 50%;
        display: grid; place-items: center;
        background: var(--sb-cream); color: var(--sb-mauve);
        border: 1px solid var(--sb-line); text-decoration: none;
        transition: all .2s ease;
    }
    .sb-stat:hover .sb-round, .sb-tile:hover .sb-round { background: var(--sb-mauve); color: #fff; border-color: var(--sb-mauve); transform: translateX(3px); }

    /* ---------- Panels ---------- */
    .sb-panel {
        height: 100%;
        background: var(--sb-paper);
        border: 1px solid var(--sb-line);
        border-radius: 24px;
        box-shadow: 0 10px 26px rgba(48, 28, 13, .07);
        overflow: hidden;
    }
    .sb-panel-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; flex-wrap: wrap;
        padding: 22px 28px 16px;
    }
    .sb-panel-head h3 { margin: 0; font-size: 1.7rem; padding-bottom: 12px; position: relative; }
    .sb-panel-head h3::after {
        content: ""; position: absolute; left: 0; bottom: 0; width: 44px; height: 8px;
        background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='44' height='8' viewBox='0 0 44 8'><path d='M1 4 Q6 0 11 4 T22 4 T33 4 T43 4' fill='none' stroke='%23AD8D77' stroke-width='1.6' stroke-linecap='round'/></svg>") no-repeat;
    }
    .sb-panel-body { padding: 8px 28px 28px; }

    .sb-tabs { display: inline-flex; gap: 4px; padding: 4px; background: var(--sb-cream); border: 1px solid var(--sb-line); border-radius: 999px; }
    .sb-tabs button {
        border: 0; background: transparent; color: var(--sb-muted);
        font: 600 .85rem 'DM Sans', sans-serif; padding: 7px 18px; border-radius: 999px; cursor: pointer; transition: all .2s ease;
    }
    .sb-tabs button:hover { color: var(--sb-choc); }
    .sb-tabs button.active { background: var(--sb-mauve); color: #fff; }
    .sb-tabs button:focus-visible { outline: 2px solid var(--sb-choc); outline-offset: 2px; }

    /* ---------- Empty states ---------- */
    .sb-empty { text-align: center; padding: 36px 16px; }
    .sb-empty-icon {
        width: 88px; height: 88px; margin: 0 auto 16px; border-radius: 50%;
        display: grid; place-items: center; font-size: 2.1rem;
        background: var(--sb-blush); color: var(--sb-mauve);
        box-shadow: 0 0 0 9px rgba(214, 197, 179, .45);
    }
    .sb-empty h5 { font-size: 1.5rem; margin-bottom: 6px; }
    .sb-empty p { color: var(--sb-muted); margin: 0 auto; max-width: 44ch; }

    /* ---------- Quick actions (beige tiles) ---------- */
    .sb-tile {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 18px; border-radius: 20px;
        text-decoration: none; color: var(--sb-choc);
        background: var(--tile);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .sb-tile + .sb-tile { margin-top: 12px; }
    .sb-tile:hover { transform: translateY(-3px); box-shadow: 0 14px 26px rgba(48, 28, 13, .16); color: var(--sb-choc); }
    .sb-tile .ico {
        width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
        display: grid; place-items: center; font-size: 1.15rem;
        background: rgba(255, 255, 255, .6); color: var(--sb-mauve);
    }
    .sb-tile .txt { flex: 1; line-height: 1.25; }
    .sb-tile .txt strong { display: block; font-family: 'DM Serif Display', Georgia, serif; font-weight: 400; font-size: 1.15rem; }
    .sb-tile .txt small { color: rgba(48, 28, 13, .74); }
    .sb-tile:focus-visible { outline: 2px solid var(--sb-choc); outline-offset: 2px; }
    .sb-tile.pink  { --tile: var(--sb-blush); }
    .sb-tile.beige { --tile: var(--sb-beige); }
    .sb-tile.lilac { --tile: var(--sb-lilac); }
    .sb-tile.peach { --tile: var(--sb-peach); }

    /* ---------- Tables ---------- */
    .sb-table { margin: 0; }
    .sb-table thead th {
        background: var(--sb-cream-2);
        color: var(--sb-mauve);
        font-size: .86rem; font-weight: 600;
        border-bottom: 1px solid var(--sb-line);
        padding: 14px 28px; white-space: nowrap;
    }
    .sb-table tbody td { padding: 14px 28px; border-color: var(--sb-line); color: var(--sb-text); background: transparent; }
    .sb-table tbody tr:hover > * { background: var(--sb-cream); }

    .sb-badge { display: inline-block; padding: 4px 14px; border-radius: 999px; font-size: .78rem; font-weight: 600; }
    .sb-badge.paid      { background: #e4efdf; color: #46703f; }
    .sb-badge.pending   { background: #f8e6c7; color: #946018; }
    .sb-badge.failed    { background: #f6d6d3; color: #9a3a33; }
    .sb-badge.delivered { background: #dfe8f3; color: #38608d; }
    .sb-badge.baking    { background: var(--sb-blush); color: var(--sb-mauve); }

    .sb-link-all {
        font-size: .88rem; font-weight: 600; text-decoration: none;
        color: #fff; background: var(--sb-mauve);
        padding: 8px 20px; border-radius: 999px;
        transition: background .2s ease, transform .2s ease;
    }
    .sb-link-all:hover { background: var(--sb-choc); color: #fff; transform: translateY(-2px); }

    @media (max-width: 575.98px) {
        .sb { padding: 14px; border-radius: 18px; }
        .sb-hero { padding: 44px 24px; }
        .sb-hero h2 { font-size: 2rem; }
        .sb-hero::after { display: none; }
        .sb-panel-head { padding: 18px 18px 12px; }
        .sb-panel-body { padding: 8px 18px 20px; }
        .sb-table thead th, .sb-table tbody td { padding: 12px 16px; }
        .sb-stat-value { font-size: 2.3rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .sb-hero { animation: none; }
        .sb-stat, .sb-tile, .sb-round, .sb-hero-btn, .sb-link-all { transition: none; }
    }
</style>

<div class="sb">

    <!-- Welcome Banner -->
    <div class="sb-hero mb-4">
        <div class="sb-hero-text">
            <h2>Good day,
                <em>{{ auth()->user()->name ?? 'Baker' }}.</em>
            </h2>
            <p>Deliciously handcrafted cakes and treats. Here is how your branch is doing today.</p>
        </div>
        <div class="sb-date">
            <i class="bi bi-calendar3"></i>{{ now()->format('l, d F Y') }}
        </div>
    </div>

    <!-- Dashboard Statistics -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="sb-stat">
                <div class="sb-stat-top">
                    <span class="sb-stat-icon"><i class="bi bi-currency-rupee"></i></span>
                    <span class="sb-stat-label">Today's Sales</span>
                </div>
                <p class="sb-stat-value">₹0</p>
                <div class="sb-stat-foot">
                    <a href="#">View sales</a>
                    <a href="#" class="sb-round" aria-label="View sales"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="sb-stat">
                <div class="sb-stat-top">
                    <span class="sb-stat-icon"><i class="bi bi-cart-check"></i></span>
                    <span class="sb-stat-label">Today's Orders</span>
                </div>
                <p class="sb-stat-value">0</p>
                <div class="sb-stat-foot">
                    <a href="#">View orders</a>
                    <a href="#" class="sb-round" aria-label="View orders"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="sb-stat">
                <div class="sb-stat-top">
                    <span class="sb-stat-icon"><i class="bi bi-people"></i></span>
                    <span class="sb-stat-label">Total Customers</span>
                </div>
                <p class="sb-stat-value">0</p>
                <div class="sb-stat-foot">
                    <a href="#">View customers</a>
                    <a href="#" class="sb-round" aria-label="View customers"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="sb-stat">
                <div class="sb-stat-top">
                    <span class="sb-stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
                    <span class="sb-stat-label">Low Stock Products</span>
                </div>
                <p class="sb-stat-value">0</p>
                <div class="sb-stat-foot">
                    <a href="#">Check inventory</a>
                    <a href="#" class="sb-round" aria-label="Check inventory"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        <!-- Sales Overview -->
        <div class="col-lg-8">
            <div class="sb-panel">
                <div class="sb-panel-head">
                    <h3>Sales Overview</h3>
                    <div class="sb-tabs" role="tablist" aria-label="Sales period">
                        <button type="button" class="active">Daily</button>
                        <button type="button">Weekly</button>
                        <button type="button">Monthly</button>
                    </div>
                </div>
                <div class="sb-panel-body">
                    <div class="sb-empty">
                        <div class="sb-empty-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <h5>No sales to chart yet</h5>
                        <p>Your daily, weekly and monthly sales analytics will appear here once billing begins.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="sb-panel">
                <div class="sb-panel-head">
                    <h3>Quick Actions</h3>
                </div>
                <div class="sb-panel-body">

                    <a href="{{ route('admin.products.index') }}" class="sb-tile pink">
                        <span class="ico"><i class="bi bi-plus-circle"></i></span>
                        <span class="txt"><strong>Add New Product</strong><small>Browse and manage bakery products</small></span>
                        <span class="sb-round"><i class="bi bi-arrow-right"></i></span>
                    </a>

                    <a href="#" class="sb-tile beige">
                        <span class="ico"><i class="bi bi-receipt"></i></span>
                        <span class="txt"><strong>Open POS Billing</strong><small>Counter sales and invoices</small></span>
                        <span class="sb-round"><i class="bi bi-arrow-right"></i></span>
                    </a>

                    <a href="#" class="sb-tile lilac">
                        <span class="ico"><i class="bi bi-box-seam"></i></span>
                        <span class="txt"><strong>Add New Product</strong><small>Cakes, pastries and breads</small></span>
                        <span class="sb-round"><i class="bi bi-arrow-right"></i></span>
                    </a>

                    <a href="#" class="sb-tile peach">
                        <span class="ico"><i class="bi bi-cart-plus"></i></span>
                        <span class="txt"><strong>Add Purchase</strong><small>Flour, cream and packaging</small></span>
                        <span class="sb-round"><i class="bi bi-arrow-right"></i></span>
                    </a>

                </div>
            </div>
        </div>

    </div>

    <!-- Recent Orders -->
    <div class="sb-panel mt-3">
        <div class="sb-panel-head">
            <h3>Recent Orders</h3>
            <a href="#" class="sb-link-all">View all orders</a>
        </div>

        <div class="table-responsive">
            <table class="table sb-table align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="sb-empty">
                                <div class="sb-empty-icon"><i class="bi bi-inbox"></i></div>
                                <h5>No orders yet</h5>
                                <p>New cake and bakery orders will appear here as soon as they are placed.</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Example row when you loop real data:
                    @foreach($recentOrders as $order)
                    <tr>
                        <td class="fw-semibold">#{{ $order->id }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td class="fw-semibold">₹{{ number_format($order->total, 2) }}</td>
                        <td><span class="sb-badge {{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td><span class="sb-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                    @endforeach
                    --}}
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="sb-panel mt-3">
        <div class="sb-panel-head">
            <h3>Top Selling Products</h3>
            <a href="#" class="sb-link-all">View all products</a>
        </div>

        <div class="table-responsive">
            <table class="table sb-table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Units Sold</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="4">
                            <div class="sb-empty">
                                <div class="sb-empty-icon"><i class="bi bi-cake2"></i></div>
                                <h5>No product sales yet</h5>
                                <p>Your best-selling cakes, pastries and breads will be ranked here.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection