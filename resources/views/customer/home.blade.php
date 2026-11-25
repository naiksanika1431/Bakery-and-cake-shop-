@extends('layouts.customer.app')

@section('title', 'Home')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Allura&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .zb-home {
        --blush:      #FBE7E1;
        --blush-2:    #F7D9D1;
        --blush-3:    #F2C8C4;
        --petal:      #E9B4B6;
        --dusty:      #D49A9F;
        --cream:      #FFF6F1;
        --wine:       #6A1B2B;
        --wine-dk:    #4A1120;
        --wine-lt:    #8C3446;
        --ink:        #3B1119;
        --muted:      #8A5E63;
        --line:       rgba(106,27,43,.22);

        --f-head:   'Cormorant Garamond', Georgia, serif;
        --f-script: 'Allura', cursive;
        --f-body:   'Jost', system-ui, sans-serif;

        font-family: var(--f-body);
        color: var(--ink);
        background: var(--blush);
        overflow: hidden;
    }
    .zb-home h1, .zb-home h2, .zb-home h3, .zb-home h4, .zb-home h5 {
        font-family: var(--f-head); font-weight: 500; color: var(--wine-dk); margin: 0;
    }
    .zb-home a { text-decoration: none; }
    .zb-home p { margin: 0; }
    .zb-defs { position: absolute; width: 0; height: 0; overflow: hidden; }

    /* ---------- shared ---------- */
    .zb-eyebrow {
        display: block; font-size: .72rem; font-weight: 500; letter-spacing: .38em;
        text-transform: uppercase; color: var(--wine-lt);
    }
    .zb-btn {
        display: inline-flex; align-items: center; gap: .6rem;
        padding: .95rem 1.8rem; border-radius: 999px;
        font: 500 .92rem var(--f-body); letter-spacing: .02em;
        transition: transform .25s ease, background .25s ease, color .25s ease, box-shadow .25s ease;
    }
    .zb-btn.fill { background: var(--wine); color: #fff; box-shadow: 0 14px 26px -14px rgba(74,17,32,.8); }
    .zb-btn.fill:hover { background: var(--wine-dk); color: #fff; transform: translateY(-3px); }
    .zb-btn.line { border: 1px solid var(--wine); color: var(--wine-dk); background: rgba(255,255,255,.35); }
    .zb-btn.line:hover { background: var(--wine); color: #fff; transform: translateY(-3px); }
    .zb-btn.cream { background: var(--cream); color: var(--wine-dk); }
    .zb-btn.cream:hover { background: #fff; color: var(--wine-dk); transform: translateY(-3px); }

    /* ---------- hero ---------- */
    .zb-hero {
        position: relative;
        padding: 4.2rem 0 3.4rem;
        background:
            radial-gradient(60% 80% at 85% 20%, rgba(233,180,182,.55), transparent 70%),
            linear-gradient(160deg, #FDEEE9 0%, #F8DCD4 100%);
    }
    .zb-hero h1 {
        font-size: clamp(3rem, 6.6vw, 5.6rem); line-height: .94; font-weight: 500;
        letter-spacing: -.02em; color: var(--ink); margin: 1.1rem 0 .4rem;
    }
    .zb-hero h1 .l2 { display: block; padding-left: 2.2rem; }
    .zb-hero h1 .script {
        display: block; font-family: var(--f-script); font-weight: 400; color: var(--wine);
        font-size: 1.25em; line-height: 1; letter-spacing: 0; margin-top: -.05em; padding-left: 3rem;
    }
    .zb-hero .tagline {
        font-family: var(--f-head); font-style: italic; font-size: 1.5rem; line-height: 1.35;
        color: var(--wine-dk); max-width: 420px; margin: 1.2rem 0 1.8rem;
    }
    .zb-actions { display: flex; gap: .8rem; flex-wrap: wrap; }
    .zb-stats { display: flex; gap: 2.2rem; flex-wrap: wrap; margin-top: 2.4rem; padding-top: 1.4rem; border-top: 1px solid var(--line); max-width: 460px; }
    .zb-stats b { display: block; font: 600 1.6rem var(--f-head); color: var(--wine-dk); line-height: 1; }
    .zb-stats span { font-size: .78rem; letter-spacing: .06em; color: var(--muted); }

    /* hero stage: arch + illustrated cake */
    .zb-stage { position: relative; min-height: 470px; }
    .zb-arch {
        position: absolute; left: 12%; top: 0; width: 66%; height: 92%;
        border-radius: 999px 999px 0 0;
        background: linear-gradient(180deg, #D9A0A2 0%, #E6B7B5 70%, #EBC2BF 100%);
        overflow: hidden;
    }
    .zb-arch svg { position: absolute; inset: 0; width: 100%; height: 100%; }
    .zb-cake { position: absolute; left: 0; right: 0; bottom: 0; width: 100%; height: auto; filter: drop-shadow(0 26px 22px rgba(106,27,43,.22)); }
    .zb-branch { position: absolute; right: -2%; top: -2%; width: 36%; transform-origin: 90% 0; animation: zbSway 7s ease-in-out infinite; }
    .zb-note {
        position: absolute; left: 4%; bottom: 3%;
        background: rgba(255,246,241,.95); border-radius: 18px; padding: .65rem 1rem;
        font-size: .82rem; font-weight: 500; color: var(--wine-dk);
        display: flex; align-items: center; gap: .5rem;
        box-shadow: 0 16px 28px -16px rgba(74,17,32,.5);
    }
    .zb-note i { color: var(--wine-lt); }

    @keyframes zbSway { 0%,100% { transform: rotate(-1.5deg); } 50% { transform: rotate(2deg); } }
    @keyframes zbUp { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; } }
    .zb-hero .zb-eyebrow, .zb-hero h1, .zb-hero .tagline, .zb-hero .zb-actions, .zb-hero .zb-stats { animation: zbUp .8s ease both; }
    .zb-hero h1 { animation-delay: .08s; }
    .zb-hero .tagline { animation-delay: .18s; }
    .zb-hero .zb-actions { animation-delay: .28s; }
    .zb-hero .zb-stats { animation-delay: .38s; }

    /* ---------- feature strip ---------- */
    .zb-strip { background: var(--cream); padding: 2rem 0; border-top: 1px solid rgba(255,255,255,.6); }
    .zb-strip .col-item { display: flex; flex-direction: column; align-items: center; gap: .55rem; padding: .6rem 1rem; text-align: center; }
    .zb-strip .col-item i { font-size: 1.8rem; color: var(--wine); }
    .zb-strip .col-item span { font: 500 1.15rem var(--f-head); color: var(--wine-dk); }
    @media (min-width: 768px) {
        .zb-strip .col-item { border-right: 1px solid var(--line); }
        .zb-strip .row > div:last-child .col-item { border-right: 0; }
    }

    /* ---------- signatures / categories ---------- */
    .zb-sig { padding: 4.5rem 0 5rem; background: linear-gradient(180deg, var(--cream), var(--blush)); }
    .zb-sig-scene {
        position: relative; width: 100%; max-width: 330px; margin: 0 auto;
        border-radius: 999px 999px 24px 24px; overflow: hidden; aspect-ratio: 300 / 420;
        box-shadow: 0 30px 40px -28px rgba(74,17,32,.55);
    }
    .zb-sig-scene svg { display: block; width: 100%; height: 100%; }

    .zb-sig-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.6rem; }
    .zb-sig-head h2 { font-size: clamp(2.3rem, 4.4vw, 3.4rem); line-height: 1; margin-top: .6rem; }
    .zb-sig-head h2 em {
        display: block; font-family: var(--f-script); font-style: normal; font-weight: 400;
        color: var(--wine); font-size: 1.35em; line-height: 1; margin-top: .05em; padding-left: 1.4rem;
    }
    .zb-sig-sub { color: var(--muted); max-width: 380px; margin-top: .8rem; line-height: 1.65; font-size: .95rem; }
    .zb-arrows { display: flex; gap: .6rem; flex: none; }
    .zb-arrows button {
        width: 46px; height: 46px; border-radius: 50%; border: 1px solid var(--wine); background: transparent;
        color: var(--wine-dk); display: grid; place-items: center; cursor: pointer; transition: .25s ease;
    }
    .zb-arrows button:hover { background: var(--wine); color: #fff; }
    .zb-arrows button:focus-visible { outline: 2px solid var(--wine); outline-offset: 3px; }

    .zb-track {
        display: flex; gap: 1.1rem; overflow-x: auto; scroll-snap-type: x mandatory;
        padding: .5rem .2rem 1.6rem; scrollbar-width: none; scroll-behavior: smooth;
    }
    .zb-track::-webkit-scrollbar { display: none; }
    .zb-card {
        flex: 0 0 calc((100% - 2.2rem) / 3); scroll-snap-align: start;
        background: #fff; border-radius: 26px; padding: .7rem .7rem 1.1rem;
        box-shadow: 0 18px 30px -24px rgba(74,17,32,.55);
        display: block; color: var(--ink); text-align: center;
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .zb-card:hover { transform: translateY(-8px); box-shadow: 0 28px 40px -26px rgba(74,17,32,.6); color: var(--ink); }
    .zb-media {
        position: relative; aspect-ratio: 1 / 1.02; border-radius: 20px; overflow: hidden;
        display: grid; place-items: center; margin-bottom: .9rem;
    }
    .zb-media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
    .zb-card:hover .zb-media img { transform: scale(1.07); }
    .zb-media > i { font-size: 4.2rem; color: rgba(106,27,43,.78); transition: transform .4s ease; }
    .zb-card:hover .zb-media > i { transform: scale(1.1) rotate(-5deg); }
    .zb-media::after {
        content: ""; position: absolute; width: 140px; height: 140px; border-radius: 50%;
        right: -40px; top: -40px; background: rgba(255,255,255,.35);
    }
    .tone-0 .zb-media { background: linear-gradient(145deg, #F9DCD9, #EDB7B9); }
    .tone-1 .zb-media { background: linear-gradient(145deg, #FFF0E4, #F1D3BC); }
    .tone-2 .zb-media { background: linear-gradient(145deg, #EBD3DE, #CFA3B5); }
    .zb-no {
        position: absolute; left: .75rem; top: .75rem; z-index: 2; background: rgba(255,246,241,.92);
        font-size: .68rem; font-weight: 600; letter-spacing: .16em; color: var(--wine-dk);
        padding: .28rem .65rem; border-radius: 999px;
    }
    .zb-card h3 { font-size: 1.45rem; line-height: 1.15; overflow-wrap: anywhere; }
    .zb-card .go {
        display: inline-flex; align-items: center; gap: .4rem; margin-top: .35rem;
        font-size: .8rem; font-weight: 500; letter-spacing: .08em; color: var(--wine-lt);
    }
    .zb-card:hover .go i { transform: translateX(4px); }
    .zb-card .go i { transition: transform .25s ease; }

    .zb-empty { background: #fff; border-radius: 26px; text-align: center; padding: 3rem 1.5rem; color: var(--muted); }
    .zb-empty i { font-size: 3rem; color: var(--wine); }
    .zb-empty h4 { margin: .6rem 0 .3rem; font-size: 1.6rem; }

    /* ---------- closing banner ---------- */
    .zb-close { padding: 1rem 0 4.5rem; background: var(--blush); }
    .zb-banner {
        position: relative; overflow: hidden; border-radius: 30px;
        background: radial-gradient(70% 120% at 85% 40%, #8C3446 0%, transparent 70%), linear-gradient(120deg, #5A1524, #6A1B2B);
        padding: 3.4rem 3rem; color: #fff; min-height: 320px;
        display: grid; grid-template-columns: 1.1fr .9fr; align-items: center; gap: 1.5rem;
    }
    .zb-banner .zb-eyebrow { color: #E9B4B6; }
    .zb-banner .rule { width: 34px; height: 1px; background: #E9B4B6; margin: 1rem 0 1.2rem; }
    .zb-banner h2 {
        color: #FFF1EC; font-style: italic; font-weight: 500; font-size: clamp(2rem, 4vw, 3.2rem); line-height: 1.12; margin-bottom: 1.6rem;
    }
    .zb-banner-art { position: relative; min-height: 240px; display: grid; place-items: center; }
    .zb-banner-art .macarons { width: min(250px, 80%); filter: drop-shadow(0 22px 18px rgba(0,0,0,.35)); animation: zbFloat 6s ease-in-out infinite; }
    .zb-banner-art .bl { position: absolute; width: 150px; right: -20px; top: -40px; opacity: .95; }
    .zb-banner-art .bl.two { left: -10px; right: auto; top: auto; bottom: -50px; width: 120px; transform: rotate(160deg); opacity: .8; }
    @keyframes zbFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

    /* ---------- reveal on scroll (only when JS runs) ---------- */
    .zb-js .rv { opacity: 0; transform: translateY(26px); transition: opacity .8s ease, transform .8s ease; }
    .zb-js .rv.in { opacity: 1; transform: none; }

    /* ---------- responsive ---------- */
    @media (max-width: 991px) {
        .zb-hero { padding: 3rem 0 2.4rem; text-align: center; }
        .zb-hero h1 .l2, .zb-hero h1 .script { padding-left: 0; }
        .zb-hero .tagline { margin-left: auto; margin-right: auto; }
        .zb-actions, .zb-stats { justify-content: center; margin-left: auto; margin-right: auto; }
        .zb-stage { min-height: 400px; margin-top: 1rem; }
        .zb-card { flex-basis: calc((100% - 1.1rem) / 2); }
        .zb-banner { grid-template-columns: 1fr; text-align: center; padding: 2.6rem 1.6rem; }
        .zb-banner .rule { margin-left: auto; margin-right: auto; }
        .zb-sig-scene { max-width: 260px; margin-bottom: 2rem; }
    }
    @media (max-width: 575px) {
        .zb-stage { min-height: 330px; }
        .zb-card { flex-basis: 78%; }
        .zb-sig { padding: 3rem 0 3.4rem; }
        .zb-sig-head { align-items: flex-start; }
        .zb-strip .col-item { padding: .8rem .4rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .zb-home *, .zb-home *::before, .zb-home *::after { animation: none !important; transition: none !important; }
        .zb-js .rv { opacity: 1; transform: none; }
    }
</style>

@php
    // Icon + pastel tone fallbacks for categories that have no image
    $catIcons = ['bi-cake2', 'bi-cookie', 'bi-cup-hot', 'bi-basket2', 'bi-heart', 'bi-gift'];
@endphp

<div class="zb-home" id="zbHome">

    {{-- Reusable blossom symbol --}}
    <svg class="zb-defs" aria-hidden="true">
        <defs>
            <symbol id="zbBlossom" viewBox="-20 -20 40 40">
                <g fill="#F4B9C4">
                    <ellipse cx="0" cy="-10" rx="7" ry="10"/>
                    <ellipse cx="0" cy="-10" rx="7" ry="10" transform="rotate(72)"/>
                    <ellipse cx="0" cy="-10" rx="7" ry="10" transform="rotate(144)"/>
                    <ellipse cx="0" cy="-10" rx="7" ry="10" transform="rotate(216)"/>
                    <ellipse cx="0" cy="-10" rx="7" ry="10" transform="rotate(288)"/>
                </g>
                <circle r="3.2" fill="#C9526B"/>
            </symbol>
            <linearGradient id="zbMarble" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#FBE6E4"/><stop offset="1" stop-color="#EBC1C4"/>
            </linearGradient>
        </defs>
    </svg>

    {{-- ================= HERO ================= --}}
    <section class="zb-hero">
        <div class="container">
            <div class="row align-items-center g-4">

                <div class="col-lg-6">
                    <span class="zb-eyebrow">Bakery &amp; Cake Shop</span>

                    <h1>
                        Life Is Better
                        <span class="l2">With Something</span>
                        <span class="script">Sweet.</span>
                    </h1>

                    <p class="tagline">
                        Delicate bakes, creamy cakes and a little happiness in every bite.
                    </p>

                    <div class="zb-actions">
                        <a href="#categories" class="zb-btn fill">Explore Categories <i class="bi bi-arrow-right"></i></a>
                        <a href="#why" class="zb-btn line">Why Choose Us</a>
                    </div>

                    <div class="zb-stats">
                        <div><b>100%</b><span>Fresh Ingredients</span></div>
                        <div><b>Daily</b><span>Fresh Baking</span></div>
                        <div><b>Made</b><span>With Love</span></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="zb-stage" role="img" aria-label="Illustration of a strawberry cake slice on a marble stand">

                        {{-- arch with soft leaf shadows --}}
                        <div class="zb-arch">
                            <svg viewBox="0 0 400 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                                <g fill="#A8686E" opacity=".28">
                                    <path d="M40 60c40-20 80-10 100 20-40 20-80 10-100-20z"/>
                                    <path d="M120 20c30 10 50 40 40 70-30-10-50-40-40-70z"/>
                                    <path d="M230 40c40 0 70 30 70 60-40 0-70-30-70-60z"/>
                                    <path d="M300 120c30 10 60 40 50 70-30-10-60-40-50-70z"/>
                                    <path d="M60 150c30-5 60 10 70 35-30 5-60-10-70-35z"/>
                                </g>
                            </svg>
                        </div>

                        {{-- blossom branch --}}
                        <svg class="zb-branch" viewBox="0 0 200 260" aria-hidden="true">
                            <path d="M190 0C160 40 130 70 110 120S60 200 20 250" stroke="#8C5A5E" stroke-width="4" fill="none" stroke-linecap="round"/>
                            <path d="M150 50C130 55 115 70 105 85" stroke="#8C5A5E" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <path d="M115 120C95 120 75 130 62 148" stroke="#8C5A5E" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <use href="#zbBlossom" x="150" y="40" width="46" height="46"/>
                            <use href="#zbBlossom" x="112" y="60" width="40" height="40"/>
                            <use href="#zbBlossom" x="70" y="100" width="44" height="44"/>
                            <use href="#zbBlossom" x="100" y="115" width="36" height="36"/>
                            <use href="#zbBlossom" x="38" y="140" width="40" height="40"/>
                            <use href="#zbBlossom" x="150" y="0" width="34" height="34"/>
                            <use href="#zbBlossom" x="20" y="200" width="30" height="30"/>
                        </svg>

                        {{-- cake on marble stand --}}
                        <svg class="zb-cake" viewBox="0 0 520 380" fill="none" aria-hidden="true">
                            {{-- marble stand --}}
                            <rect x="14" y="296" width="492" height="84" rx="38" fill="url(#zbMarble)"/>
                            <path d="M60 330c60-14 110 6 170-8M300 350c50-18 100 2 160-10M120 365c40-8 70 2 110-4" stroke="#fff" stroke-opacity=".55" stroke-width="3" stroke-linecap="round"/>
                            {{-- plate --}}
                            <ellipse cx="262" cy="296" rx="220" ry="36" fill="#E9CFCF" opacity=".7"/>
                            <ellipse cx="262" cy="288" rx="220" ry="36" fill="#FFFFFF"/>
                            <ellipse cx="262" cy="286" rx="176" ry="26" fill="#F7EEEB"/>

                            {{-- cake slice --}}
                            <g transform="translate(144 58)">
                                <path d="M0 62L72 24 238 34 240 62Z" fill="#FDEFEC"/>
                                <clipPath id="zbSlice"><rect x="0" y="62" width="240" height="152" rx="9"/></clipPath>
                                <g clip-path="url(#zbSlice)">
                                    <rect y="62"  width="240" height="30" fill="#FBDDD8"/>
                                    <rect y="92"  width="240" height="32" fill="#EFCB8F"/>
                                    <rect y="124" width="240" height="18" fill="#FFF4E4"/>
                                    <rect y="142" width="240" height="34" fill="#F28C98"/>
                                    <rect y="176" width="240" height="38" fill="#E9BF7B"/>
                                    <g fill="#F9B6BE" opacity=".85">
                                        <circle cx="30" cy="158" r="3"/><circle cx="70" cy="150" r="2.4"/><circle cx="110" cy="162" r="3"/>
                                        <circle cx="150" cy="152" r="2.6"/><circle cx="190" cy="162" r="3"/><circle cx="220" cy="150" r="2.4"/>
                                    </g>
                                    <g fill="#D9A767" opacity=".6">
                                        <circle cx="40" cy="106" r="2"/><circle cx="90" cy="112" r="2"/><circle cx="140" cy="104" r="2"/><circle cx="200" cy="112" r="2"/>
                                        <circle cx="60" cy="196" r="2"/><circle cx="120" cy="200" r="2"/><circle cx="180" cy="194" r="2"/>
                                    </g>
                                </g>
                                {{-- strawberry halves --}}
                                <g transform="translate(98 22)">
                                    <path d="M0 0C-22-4-32 20 0 46 32 20 22-4 0 0Z" fill="#D8344B"/>
                                    <path d="M0 8C-12 6-17 20 0 34 17 20 12 6 0 8Z" fill="#F7A3AC"/>
                                    <path d="M-10 -2l10 -9 10 9" fill="#5E8B54"/>
                                </g>
                                <g transform="translate(160 30) rotate(8)">
                                    <path d="M0 0C-22-4-32 20 0 46 32 20 22-4 0 0Z" fill="#D8344B"/>
                                    <path d="M0 8C-12 6-17 20 0 34 17 20 12 6 0 8Z" fill="#F7A3AC"/>
                                    <path d="M-10 -2l10 -9 10 9" fill="#5E8B54"/>
                                </g>
                            </g>

                            {{-- whole strawberry on plate --}}
                            <g transform="translate(118 262) rotate(-14)">
                                <path d="M0 0C-26-4-36 24 0 56 36 24 26-4 0 0Z" fill="#D8344B"/>
                                <g fill="#FBD2D6"><circle cx="-8" cy="20" r="1.6"/><circle cx="8" cy="18" r="1.6"/><circle cx="0" cy="32" r="1.6"/><circle cx="-12" cy="34" r="1.6"/><circle cx="12" cy="34" r="1.6"/></g>
                                <path d="M-14 0l14-12 14 12-14 4Z" fill="#5E8B54"/>
                            </g>
                        </svg>

                        <div class="zb-note"><i class="bi bi-heart-fill"></i>Baked fresh every day</div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ================= FEATURE STRIP ================= --}}
    <section class="zb-strip" id="why">
        <div class="container">
            <div class="row g-0">
                <div class="col-6 col-md-3"><div class="col-item"><i class="bi bi-basket2"></i><span>Freshly Baked</span></div></div>
                <div class="col-6 col-md-3"><div class="col-item"><i class="bi bi-cup-hot"></i><span>Quality Ingredients</span></div></div>
                <div class="col-6 col-md-3"><div class="col-item"><i class="bi bi-cake"></i><span>Celebration Cakes</span></div></div>
                <div class="col-6 col-md-3"><div class="col-item"><i class="bi bi-heart"></i><span>Made With Love</span></div></div>
            </div>
        </div>
    </section>


    {{-- ================= CATEGORIES ================= --}}
    <section class="zb-sig" id="categories">
        <div class="container">
            <div class="row g-4 g-xl-5 align-items-center">

                {{-- decorative cafe arch --}}
                <div class="col-lg-4 rv">
                    <div class="zb-sig-scene" role="img" aria-label="Cafe table with flowers and a croissant">
                        <svg viewBox="0 0 300 420" aria-hidden="true">
                            <defs>
                                <linearGradient id="zbWall" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0" stop-color="#FCE9E2"/><stop offset="1" stop-color="#F3CFC6"/>
                                </linearGradient>
                            </defs>
                            <rect width="300" height="420" fill="url(#zbWall)"/>
                            {{-- window --}}
                            <rect x="190" y="40" width="90" height="230" rx="4" fill="#FFF8F3" stroke="#6A1B2B" stroke-opacity=".5" stroke-width="3"/>
                            <path d="M235 40v230M190 120h90M190 195h90" stroke="#6A1B2B" stroke-opacity=".45" stroke-width="2"/>
                            {{-- light patches --}}
                            <path d="M20 40l70 20-10 130-60-20z" fill="#fff" opacity=".35"/>
                            {{-- vine --}}
                            <path d="M10 20C30 50 40 90 30 130" stroke="#8A9A80" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <g fill="#9AAC8E" opacity=".9">
                                <ellipse cx="22" cy="50" rx="16" ry="8" transform="rotate(30 22 50)"/>
                                <ellipse cx="42" cy="78" rx="16" ry="8" transform="rotate(-25 42 78)"/>
                                <ellipse cx="22" cy="100" rx="16" ry="8" transform="rotate(25 22 100)"/>
                                <ellipse cx="40" cy="124" rx="14" ry="7" transform="rotate(-20 40 124)"/>
                            </g>
                            {{-- lamp --}}
                            <path d="M150 160v50" stroke="#B88B4A" stroke-width="4"/>
                            <path d="M130 160a20 20 0 0 1 40 0z" fill="#C79B52"/>
                            {{-- chair back --}}
                            <path d="M10 270c10-60 40-70 60-70s40 20 40 90" stroke="#9A6B3E" stroke-width="7" fill="none" stroke-linecap="round"/>
                            <g stroke="#B58652" stroke-width="2" opacity=".8">
                                <path d="M24 250l60 50M24 270l60 40M44 232l50 62M70 224l30 70"/>
                            </g>
                            {{-- table --}}
                            <ellipse cx="170" cy="318" rx="120" ry="26" fill="#FFFFFF"/>
                            <ellipse cx="170" cy="322" rx="120" ry="24" fill="#F0DCDA" opacity=".7"/>
                            <ellipse cx="170" cy="316" rx="120" ry="24" fill="#FBF1EF"/>
                            <rect x="162" y="336" width="16" height="70" fill="#3B1119"/>
                            <rect x="120" y="402" width="100" height="10" rx="5" fill="#3B1119"/>
                            {{-- hydrangea vase --}}
                            <path d="M80 300c-4-20 0-34 8-38h24c8 4 12 18 8 38z" fill="#E6EEF0" opacity=".85" stroke="#fff" stroke-width="2"/>
                            <g>
                                <circle cx="78" cy="236" r="22" fill="#E58AA7"/><circle cx="108" cy="228" r="24" fill="#EE9DB6"/>
                                <circle cx="96" cy="252" r="22" fill="#F4B3C6"/><circle cx="68" cy="258" r="16" fill="#DF7C9C"/>
                                <circle cx="122" cy="252" r="16" fill="#E58AA7"/>
                            </g>
                            {{-- cup --}}
                            <ellipse cx="170" cy="306" rx="30" ry="8" fill="#fff"/>
                            <path d="M154 280h32v14c0 8-6 14-16 14s-16-6-16-14z" fill="#fff" stroke="#E8D3CF" stroke-width="2"/>
                            <ellipse cx="170" cy="281" rx="16" ry="4" fill="#B97A55"/>
                            {{-- croissant on plate --}}
                            <ellipse cx="236" cy="312" rx="42" ry="9" fill="#fff"/>
                            <g fill="#D99B54">
                                <ellipse cx="236" cy="296" rx="14" ry="12"/>
                                <ellipse cx="218" cy="300" rx="11" ry="10" transform="rotate(-25 218 300)"/>
                                <ellipse cx="254" cy="300" rx="11" ry="10" transform="rotate(25 254 300)"/>
                                <ellipse cx="204" cy="308" rx="8" ry="7" transform="rotate(-45 204 308)"/>
                                <ellipse cx="268" cy="308" rx="8" ry="7" transform="rotate(45 268 308)"/>
                            </g>
                            <path d="M222 292c8-6 18-6 28 0M214 300c4-4 8-6 12-6M246 294c4 0 8 2 12 6" stroke="#B87A38" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>

                {{-- title + slider --}}
                <div class="col-lg-8">
                    <div class="zb-sig-head rv">
                        <div>
                            <span class="zb-eyebrow">Our Menu</span>
                            <h2>Bakery<em>Favorites</em></h2>
                            <p class="zb-sig-sub">Choose a category and explore what’s fresh from our oven today.</p>
                        </div>
                        <div class="zb-arrows" id="zbArrows">
                            <button type="button" id="zbPrev" aria-label="Previous categories"><i class="bi bi-arrow-left"></i></button>
                            <button type="button" id="zbNext" aria-label="Next categories"><i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    @if($categories->count())
                        <div class="zb-track rv" id="zbTrack">
                            @foreach($categories as $category)
                                <a href="{{ route('customer.products', ['category' => $category->id]) }}"
                                   class="zb-card tone-{{ $loop->index % 3 }}">

                                    <div class="zb-media">
                                        <span class="zb-no">No. {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                                        @if($category->image)
                                            <img src="{{ $category->image }}"
                                                 alt="{{ $category->name }}"
                                                 loading="lazy">
                                        @else
                                            <i class="bi {{ $catIcons[$loop->index % count($catIcons)] }}"></i>
                                        @endif
                                    </div>

                                    <h3>{{ $category->name }}</h3>
                                    <span class="go">Explore <i class="bi bi-arrow-right"></i></span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="zb-empty rv">
                            <i class="bi bi-basket2"></i>
                            <h4>Categories Coming Soon</h4>
                            <p>Please visit us again to explore our bakery collection.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>


    {{-- ================= CLOSING BANNER ================= --}}
    <section class="zb-close">
        <div class="container">
            <div class="zb-banner rv">
                <div>
                    <span class="zb-eyebrow">A Bakery Made With Love</span>
                    <div class="rule"></div>
                    <h2>Where Everyday Feels a Little More Delicious.</h2>
                    <a href="#categories" class="zb-btn cream">Explore Our Menu <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="zb-banner-art" aria-hidden="true">
                    <svg class="bl" viewBox="0 0 150 150">
                        <path d="M148 4C110 30 80 60 60 110" stroke="#B98088" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <use href="#zbBlossom" x="100" y="20" width="44" height="44"/>
                        <use href="#zbBlossom" x="64" y="52" width="38" height="38"/>
                        <use href="#zbBlossom" x="34" y="88" width="40" height="40"/>
                    </svg>
                    <svg class="bl two" viewBox="0 0 150 150">
                        <path d="M148 4C110 30 80 60 60 110" stroke="#B98088" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <use href="#zbBlossom" x="100" y="20" width="44" height="44"/>
                        <use href="#zbBlossom" x="64" y="52" width="38" height="38"/>
                    </svg>

                    {{-- macaron stack --}}
                    <svg class="macarons" viewBox="0 0 260 220" fill="none">
                        <ellipse cx="130" cy="200" rx="110" ry="14" fill="#000" opacity=".25"/>
                        <g>
                            <ellipse cx="130" cy="168" rx="92" ry="24" fill="#F4B3C6"/>
                            <rect x="40" y="148" width="180" height="20" fill="#F9C6D4"/>
                            <path d="M42 150c20 8 40 12 88 12s68-4 88-12v6c-20 8-40 12-88 12s-68-4-88-12z" fill="#FFF4E8"/>
                            <ellipse cx="130" cy="150" rx="92" ry="24" fill="#F9C6D4"/>
                        </g>
                        <g>
                            <ellipse cx="130" cy="118" rx="82" ry="22" fill="#E8D6C0"/>
                            <path d="M50 120c20 8 40 12 80 12s60-4 80-12v6c-20 8-40 12-80 12s-60-4-80-12z" fill="#FFF4E8"/>
                            <ellipse cx="130" cy="108" rx="82" ry="22" fill="#F3E4D2"/>
                        </g>
                        <g>
                            <ellipse cx="130" cy="82" rx="72" ry="20" fill="#E58AA7"/>
                            <path d="M58 84c18 7 36 10 72 10s54-3 72-10v6c-18 7-36 10-72 10s-54-3-72-10z" fill="#FFF4E8"/>
                            <ellipse cx="130" cy="72" rx="72" ry="20" fill="#F4B3C6"/>
                        </g>
                        <use href="#zbBlossom" x="106" y="26" width="48" height="48"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    (function () {
        var root = document.getElementById('zbHome');
        if (!root) return;

        /* scroll-reveal */
        if ('IntersectionObserver' in window) {
            root.classList.add('zb-js');
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
                });
            }, { threshold: 0.12 });
            root.querySelectorAll('.rv').forEach(function (el) { io.observe(el); });
        }

        /* category slider arrows */
        var track = document.getElementById('zbTrack');
        var arrows = document.getElementById('zbArrows');
        if (!track) { if (arrows) arrows.style.display = 'none'; return; }

        function step() {
            var card = track.querySelector('.zb-card');
            return card ? card.getBoundingClientRect().width + 17 : 300;
        }
        function toggleArrows() {
            arrows.style.visibility = track.scrollWidth > track.clientWidth + 4 ? 'visible' : 'hidden';
        }
        document.getElementById('zbPrev').addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
        document.getElementById('zbNext').addEventListener('click', function () { track.scrollBy({ left:  step(), behavior: 'smooth' }); });
        window.addEventListener('resize', toggleArrows);
        toggleArrows();
    })();
</script>

@endsection