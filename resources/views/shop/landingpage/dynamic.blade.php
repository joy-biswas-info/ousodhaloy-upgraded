<!doctype html>
<html lang="bn">
@php
    $metaTitle = $landingPage->meta_title ?: $landingPage->headline;
    $metaDescription = $landingPage->meta_description ?: ($landingPage->subheadline ?: $product->name);
    $metaImage = $landingPage->hero_image ? asset('storage/' . $landingPage->hero_image) : ($product->thumbnail_url ?? asset('images/no-image.png'));
    $accent = $landingPage->theme['accent'] ?? '#B5566F';
    $cta = $landingPage->theme['cta'] ?? '#0D7674';
    $price = $landingPage->effective_price;
    $comparePrice = $landingPage->effective_compare_at_price;
    $discountPercent = $landingPage->discount_percent;
    $messengerUrl = \App\Models\Setting::get('messenger_url');
    $pixelViewContent = \App\Models\Setting::get('meta_pixel_view_content', 'true') === 'true';
@endphp

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}" />
    @if($isPreview)
        <meta name="robots" content="noindex,nofollow" />
    @endif
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml" />

    <meta property="og:type" content="product" />
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />
    <meta property="og:image" content="{{ $metaImage }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:locale" content="bn_BD" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $metaTitle }}" />
    <meta name="twitter:description" content="{{ $metaDescription }}" />
    <meta name="twitter:image" content="{{ $metaImage }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #FAF3F1;
            --bg2: #FDF8F6;
            --card: #FFFFFF;
            --accent: {{ $accent }};
            --accent-bright: color-mix(in srgb, {{ $accent }} 82%, black);
            --accent-glow: color-mix(in srgb, {{ $accent }} 35%, white);
            --cta: {{ $cta }};
            --cta-bright: color-mix(in srgb, {{ $cta }} 82%, black);
            --ink: #2B2220;
            --muted: #55483F;
            --muted2: #A6938D;
            --danger: #C2486A;
            --r: 14px;
            --r2: 20px;
        }
        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
        body {
            font-family: "Hind Siliguri", -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif;
            background: var(--bg); color: var(--ink); line-height: 1.55; overflow-x: hidden;
            padding-bottom: 72px;
        }
        img { display: block; max-width: 100%; height: auto; }
        a { text-decoration: none; color: inherit; }
        button { font-family: inherit; cursor: pointer; border: none; outline: none; }
        input, select, textarea { font-family: inherit; font-size: 14px; }

        .hero { padding: 44px 20px 0; background: radial-gradient(ellipse 80% 55% at 50% -5%, color-mix(in srgb, var(--accent) 16%, transparent) 0%, transparent 70%), var(--bg); text-align: center; overflow: hidden; }
        .eyebrow { display: inline-flex; align-items: center; gap: 7px; background: color-mix(in srgb, var(--accent) 12%, transparent); border: 1px solid color-mix(in srgb, var(--accent) 28%, transparent); color: var(--accent-bright); padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 20px; }
        .blink { width: 7px; height: 7px; border-radius: 50%; background: var(--danger); animation: bl 1s infinite; }
        @keyframes bl { 0%,100% { opacity: 1; } 50% { opacity: .15; } }
        .hero-h1 { font-size: clamp(26px, 7vw, 40px); font-weight: 700; line-height: 1.15; letter-spacing: -.5px; margin-bottom: 14px; max-width: 680px; margin-left: auto; margin-right: auto; }
        .hero-h1 .hi { color: var(--accent-bright); display: block; margin-top: 4px; }
        .hero-sub { font-size: clamp(15px, 3.5vw, 17px); color: var(--muted); line-height: 1.65; max-width: 460px; margin: 0 auto 30px; }
        .problems { display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 36px; }
        .prob { background: rgba(139,90,80,.08); border: 1px solid rgba(139,90,80,.16); border-radius: 10px; padding: 10px 16px; font-size: 13px; font-weight: 600; color: var(--muted); }

        .hero-img-wrap { position: relative; display: inline-block; margin-bottom: -10px; }
        .hero-img-wrap img { width: clamp(260px, 90vw, 560px); border-radius: 16px; filter: drop-shadow(0 20px 55px rgba(197,90,120,.28)); animation: flt 4s ease-in-out infinite; margin: 0 auto; }
        @keyframes flt { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
        .fbadge { position: absolute; background: rgba(255,255,255,.96); border: 1px solid rgba(139,90,80,.16); border-radius: 12px; padding: 9px 13px; display: flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; animation: flt2 3.5s ease-in-out infinite; box-shadow: 0 4px 20px rgba(139,90,80,.18); }
        .fbadge.f1 { top: 30px; right: -20px; animation-delay: .4s; }
        .fbadge.f2 { bottom: 60px; left: -20px; animation-delay: 1.8s; }
        @keyframes flt2 { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        .fbadge-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .fbadge-t { font-size: 11px; font-weight: 700; }
        .fbadge-s { font-size: 10px; color: var(--muted); }
        @media (max-width: 540px) { .fbadge.f1 { right: -6px; } .fbadge.f2 { left: -6px; } }

        .trust-pills { display: flex; flex-wrap: wrap; justify-content: center; gap: 7px; padding: 28px 0; }
        .tpill { background: rgba(13,118,116,.1); border: 1px solid rgba(13,118,116,.25); color: var(--cta-bright); font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 20px; }

        .price-section { padding: 12px 20px 0; }
        .price-card { max-width: 680px; margin: 0 auto; background: var(--card); border: 1px solid color-mix(in srgb, var(--cta) 22%, transparent); border-radius: var(--r2); padding: 26px 22px; box-shadow: 0 0 40px rgba(197,90,120,.14); }
        .sale-badge { display: inline-flex; align-items: center; gap: 6px; background: var(--danger); color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 6px; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 14px; }
        .price-row { display: flex; align-items: baseline; gap: 12px; margin-bottom: 18px; flex-wrap: wrap; }
        .price-now { font-size: 44px; font-weight: 700; color: var(--cta-bright); letter-spacing: -1px; line-height: 1; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .price-was { font-size: 18px; color: var(--muted2); text-decoration: line-through; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .price-save { font-size: 13px; font-weight: 700; background: rgba(239,68,68,.15); color: var(--danger); padding: 3px 10px; border-radius: 6px; }

        .countdown { background: rgba(239,68,68,.09); border: 1px solid rgba(239,68,68,.22); border-radius: 10px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; }
        .cd-label { font-size: 12px; color: var(--danger); font-weight: 600; }
        .cd-timer { display: flex; gap: 5px; margin-left: auto; }
        .cd-unit { background: var(--bg); border-radius: 6px; padding: 4px 7px; text-align: center; min-width: 34px; }
        .cd-num { font-size: 15px; font-weight: 700; line-height: 1; color: var(--danger); font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .cd-sep { font-size: 15px; font-weight: 700; color: var(--danger); align-self: center; }
        .cd-lbl { font-size: 9px; color: var(--muted); text-transform: uppercase; margin-top: 1px; }

        .qty-row { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .qty-wrap { display: flex; align-items: center; background: rgba(139,90,80,.1); border: 1px solid rgba(139,90,80,.16); border-radius: 10px; overflow: hidden; }
        .qty-btn { width: 42px; height: 46px; background: none; color: var(--ink); font-size: 20px; font-weight: 700; }
        .qty-btn:hover { background: rgba(139,90,80,.14); }
        .qty-num { width: 42px; text-align: center; font-size: 17px; font-weight: 700; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .qty-subtotal { font-size: 13px; color: var(--muted); }

        .order-form { display: flex; flex-direction: column; gap: 10px; margin-bottom: 12px; }
        .of-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .of-input, .of-select, .of-textarea { width: 100%; border: 1px solid rgba(139,90,80,.22); border-radius: 10px; padding: 11px 13px; font-size: 14px; outline: none; background: var(--card); color: var(--ink); }
        .of-input:focus, .of-select:focus, .of-textarea:focus { border-color: var(--cta); box-shadow: 0 0 0 3px color-mix(in srgb, var(--cta) 12%, transparent); }
        .of-textarea { resize: none; }
        .of-error { font-size: 11.5px; color: var(--danger); margin-top: -4px; }
        .of-pay { display: flex; gap: 8px; flex-wrap: wrap; }
        .of-pay label { flex: 1; min-width: 120px; display: flex; align-items: center; gap: 8px; border: 2px solid rgba(139,90,80,.16); border-radius: 10px; padding: 10px 12px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .of-pay input:checked + span { color: var(--cta-bright); }
        .of-pay label:has(input:checked) { border-color: var(--cta); background: color-mix(in srgb, var(--cta) 6%, transparent); }
        .of-file-label { border: 2px dashed rgba(139,90,80,.3); border-radius: 10px; padding: 14px; text-align: center; font-size: 12.5px; color: var(--muted); cursor: pointer; }

        .cta-main { width: 100%; height: 52px; background: var(--cta); color: #fff; border-radius: 10px; font-size: 15.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 7px; transition: background .15s, transform .1s; box-shadow: 0 4px 24px color-mix(in srgb, var(--cta) 38%, transparent); }
        .cta-main:active { transform: scale(.98); }
        .cta-main:hover { background: var(--cta-bright); }
        .cta-main:disabled { opacity: .6; cursor: default; }

        .order-totals { display: flex; justify-content: space-between; font-size: 13px; color: var(--muted); margin-top: 10px; padding-top: 10px; border-top: 1px dashed rgba(139,90,80,.2); }
        .order-totals strong { color: var(--ink); font-size: 15px; }

        .order-success { display: none; text-align: center; padding: 20px 10px; }
        .order-success .ok-icon { font-size: 44px; margin-bottom: 10px; }
        .order-success h3 { font-size: 18px; margin-bottom: 6px; }
        .order-success p { font-size: 13px; color: var(--muted); margin-bottom: 14px; }

        .ship-note { text-align: center; font-size: 12px; color: var(--muted); margin-top: 12px; }
        .caution-box { max-width: 680px; margin: 14px auto 0; background: rgba(178,134,60,.1); border: 1px solid rgba(178,134,60,.28); border-radius: var(--r); padding: 14px 18px; font-size: 12.5px; color: #6B4F2A; line-height: 1.6; }
        .caution-box strong { color: var(--accent-bright); }
        .messenger-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; font-size: 12.5px; color: var(--cta-bright); font-weight: 600; }

        .sec { padding: 52px 20px; }
        .sec-label { text-align: center; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--accent-bright); margin-bottom: 8px; }
        .sec-h2 { text-align: center; font-size: clamp(22px, 5vw, 34px); font-weight: 700; letter-spacing: -.3px; line-height: 1.15; margin-bottom: 8px; }
        .sec-sub { text-align: center; font-size: 15px; color: var(--muted); max-width: 460px; margin: 0 auto 38px; line-height: 1.65; }

        .grid-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; max-width: 680px; margin: 0 auto; }
        .g-card { background: var(--card); border-radius: var(--r2); padding: 22px 16px; text-align: center; border: 1px solid rgba(139,90,80,.12); }
        .g-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 12px; background: color-mix(in srgb, var(--accent) 14%, transparent); }
        .g-title { font-size: 13px; font-weight: 700; margin-bottom: 3px; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .g-tag { font-size: 10px; color: var(--accent-bright); font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .5px; }
        .g-desc { font-size: 12px; color: var(--muted); line-height: 1.55; }

        .benefit-list { max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; gap: 12px; }
        .benefit { display: flex; align-items: flex-start; gap: 14px; background: var(--card); border: 1px solid rgba(139,90,80,.12); border-radius: var(--r); padding: 18px; }
        .b-icon { font-size: 24px; flex-shrink: 0; }
        .b-title { font-size: 14px; font-weight: 700; margin-bottom: 3px; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .b-desc { font-size: 13px; color: var(--muted); line-height: 1.6; }

        .how-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; max-width: 620px; margin: 0 auto; }
        .how-card { background: var(--card); border-radius: var(--r2); padding: 20px 14px; text-align: center; border: 1px solid rgba(139,90,80,.12); }
        .how-num { width: 34px; height: 34px; border-radius: 50%; background: var(--accent); color: #fff; font-size: 15px; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .how-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .how-desc { font-size: 12px; color: var(--muted); line-height: 1.55; }

        .inci-box { max-width: 680px; margin: 0 auto; background: var(--card); border: 1px solid rgba(139,90,80,.12); border-radius: var(--r2); padding: 20px 22px; }
        .inci-box p { font-size: 12.5px; line-height: 1.9; color: var(--muted); white-space: pre-line; }

        .stars-row { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 26px; flex-wrap: wrap; }
        .stars { color: var(--danger); font-size: 20px; letter-spacing: 2px; }
        .stars-txt { font-size: 14px; color: var(--muted); }
        .reviews { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; max-width: 640px; margin: 0 auto 32px; }
        .review { background: var(--card); border: 1px solid rgba(139,90,80,.12); border-radius: var(--r2); padding: 18px; }
        .rev-stars { color: var(--danger); font-size: 12px; margin-bottom: 7px; }
        .rev-body { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 12px; }
        .rev-author { display: flex; align-items: center; gap: 9px; }
        .rev-av { width: 32px; height: 32px; border-radius: 50%; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: var(--accent); color: #fff; }
        .rev-name { font-size: 12px; font-weight: 700; }

        .vstrip { display: flex; justify-content: center; flex-wrap: wrap; gap: 16px; padding: 22px 20px; background: rgba(13,118,116,.06); border-top: 1px solid rgba(13,118,116,.14); border-bottom: 1px solid rgba(13,118,116,.14); }
        .vi { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: var(--cta-bright); }

        .gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; max-width: 680px; margin: 0 auto; }
        .gallery-grid img { border-radius: var(--r); border: 1px solid rgba(139,90,80,.14); aspect-ratio: 1/1; object-fit: cover; }
        @media (max-width: 540px) { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }

        .faq-list { max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; gap: 8px; }
        .faq-item { background: var(--card); border-radius: var(--r); border: 1px solid rgba(139,90,80,.12); overflow: hidden; }
        .faq-q { width: 100%; background: none; padding: 15px 18px; font-size: 14px; font-weight: 600; display: flex; justify-content: space-between; align-items: center; gap: 10px; text-align: left; color: var(--ink); }
        .faq-q .ico { color: var(--accent-bright); font-size: 18px; transition: transform .2s; }
        .faq-item.open .faq-q .ico { transform: rotate(45deg); }
        .faq-a { display: none; padding: 0 18px 14px; font-size: 13px; color: var(--muted); line-height: 1.65; border-top: 1px solid rgba(139,90,80,.08); padding-top: 12px; }
        .faq-item.open .faq-a { display: block; }

        .final-cta { padding: 52px 20px 40px; text-align: center; background: radial-gradient(ellipse 70% 50% at 50% 0%, color-mix(in srgb, var(--accent) 14%, transparent) 0%, transparent 70%); }
        .final-cta h2 { font-size: clamp(24px, 6vw, 38px); font-weight: 700; letter-spacing: -.3px; line-height: 1.15; margin-bottom: 10px; }
        .final-cta h2 em { color: var(--cta-bright); font-style: normal; }
        .final-cta p { font-size: 15px; color: var(--muted); margin-bottom: 26px; max-width: 400px; margin: 0 auto 26px; }
        .final-cta-btn { display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: var(--cta); color: #fff; padding: 17px 40px; border-radius: 12px; font-size: 17px; font-weight: 700; box-shadow: 0 6px 28px color-mix(in srgb, var(--cta) 40%, transparent); max-width: 380px; width: 100%; }
        .final-cta-btn:hover { background: var(--cta-bright); }

        footer { padding: 22px 20px; text-align: center; border-top: 1px solid rgba(139,90,80,.12); }
        footer p { font-size: 12px; color: var(--muted2); margin-bottom: 8px; }
        .footer-links { display: flex; justify-content: center; gap: 18px; flex-wrap: wrap; }
        .footer-links a { font-size: 12px; color: var(--muted2); }
        .footer-links a:hover { color: var(--muted); }

        .sticky-bar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 80; background: var(--bg2); border-top: 1px solid rgba(139,90,80,.16); padding: 10px 16px; display: flex; align-items: center; gap: 10px; box-shadow: 0 -4px 24px rgba(139,90,80,.15); transform: translateY(100%); transition: transform .25s ease; }
        .sticky-bar.show { transform: translateY(0); }
        .s-info { flex: 1; min-width: 0; }
        .s-name { font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--muted); }
        .s-price { font-size: 15px; font-weight: 700; color: var(--cta-bright); font-family: -apple-system,BlinkMacSystemFont,sans-serif; }
        .s-cta { flex-shrink: 0; background: var(--cta); color: #fff; padding: 11px 20px; border-radius: 9px; font-size: 13px; font-weight: 700; white-space: nowrap; }
        .s-cta:hover { background: var(--cta-bright); }
        @if($isPreview)
        .preview-banner { position: sticky; top: 0; z-index: 200; background: #1f2937; color: #fff; text-align: center; font-size: 12px; font-weight: 700; padding: 6px; }
        @endif
    </style>
    @include('partials.meta-pixel')
    {{-- Standalone template, no @vite — see the optimization notes for why. --}}
</head>

<body>
    @if($isPreview)
    <div class="preview-banner">👁 PREVIEW — this page is a draft, not live to the public</div>
    @endif

    <section class="hero">
        <div class="hero-img-wrap">
            <img src="{{ $metaImage }}" alt="{{ $product->name }}" width="370" height="370" loading="eager"
                fetchpriority="high" decoding="async"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
            <div style="display:none;width:200px;height:200px;margin:0 auto;background:color-mix(in srgb, var(--accent) 10%, transparent);border-radius:24px;align-items:center;justify-content:center;font-size:72px;">💊</div>

            <div class="fbadge f1">
                <div class="fbadge-icon" style="background: rgba(13,118,116,.14)">⚡</div>
                <div><div class="fbadge-t">Fast Delivery</div><div class="fbadge-s">24–48 ঘণ্টায়</div></div>
            </div>
            @if(($product->rating_count ?? 0) > 0)
            <div class="fbadge f2">
                <div class="fbadge-icon" style="background: color-mix(in srgb, var(--accent) 14%, transparent)">★</div>
                <div><div class="fbadge-t">{{ number_format($product->average_rating, 1) }}/5 Rating</div><div class="fbadge-s">{{ $product->rating_count }}+ রিভিউ</div></div>
            </div>
            @endif
        </div>
    </section>

    <section class="price-section" id="order">
        <div class="price-card">
            @if($landingPage->badge_text)
            <div class="sale-badge">{{ $landingPage->badge_text }}</div>
            @endif

            <div class="price-row">
                <div class="price-now">৳{{ number_format($price, 0) }}</div>
                @if($comparePrice)
                <div class="price-was">৳{{ number_format($comparePrice, 0) }}</div>
                @endif
                @if($discountPercent > 0)
                <div class="price-save">{{ $discountPercent }}% OFF</div>
                @endif
            </div>

            @if($landingPage->countdown_end_at)
            <div class="countdown">
                <div class="cd-label">⏰ অফার শেষ হবে:</div>
                <div class="cd-timer">
                    <div class="cd-unit"><div class="cd-num" id="cd-d">00</div><div class="cd-lbl">দিন</div></div>
                    <div class="cd-sep">:</div>
                    <div class="cd-unit"><div class="cd-num" id="cd-h">00</div><div class="cd-lbl">ঘণ্টা</div></div>
                    <div class="cd-sep">:</div>
                    <div class="cd-unit"><div class="cd-num" id="cd-m">00</div><div class="cd-lbl">মিনিট</div></div>
                    <div class="cd-sep">:</div>
                    <div class="cd-unit"><div class="cd-num" id="cd-s">00</div><div class="cd-lbl">সেকেন্ড</div></div>
                </div>
            </div>
            @endif

            <div id="order-form-wrap">
                <div class="qty-row">
                    <div class="qty-wrap">
                        <button type="button" class="qty-btn" id="qty-down" aria-label="কমান">−</button>
                        <div class="qty-num" id="qty-num">1</div>
                        <button type="button" class="qty-btn" id="qty-up" aria-label="বাড়ান">+</button>
                    </div>
                    <span class="qty-subtotal" id="qty-subtotal-txt">৳{{ number_format($price, 0) }}</span>
                </div>

                <form class="order-form" id="order-form" novalidate>
                    <div class="of-row">
                        <div>
                            <input type="text" name="shipping_name" class="of-input" placeholder="আপনার নাম *" required>
                            <p class="of-error" data-error-for="shipping_name"></p>
                        </div>
                        <div>
                            <input type="tel" name="shipping_phone" class="of-input" placeholder="01XXXXXXXXX *" required>
                            <p class="of-error" data-error-for="shipping_phone"></p>
                        </div>
                    </div>
                    <div class="of-row">
                        <div>
                            <select name="shipping_division" id="division-select" class="of-select" required>
                                <option value="">বিভাগ নির্বাচন করুন *</option>
                                @foreach(config('bd.divisions', []) as $div)
                                <option value="{{ $div }}">{{ $div }}</option>
                                @endforeach
                            </select>
                            <p class="of-error" data-error-for="shipping_division"></p>
                        </div>
                        <div>
                            <select name="shipping_district" id="district-select" class="of-select" required>
                                <option value="">জেলা নির্বাচন করুন *</option>
                            </select>
                            <p class="of-error" data-error-for="shipping_district"></p>
                        </div>
                    </div>
                    <div>
                        <textarea name="shipping_address" class="of-textarea" rows="2" placeholder="সম্পূর্ণ ঠিকানা (বাসা, রোড, এলাকা) *" required></textarea>
                        <p class="of-error" data-error-for="shipping_address"></p>
                    </div>

                    @if($product->requires_prescription)
                    <div>
                        <label class="of-file-label" for="lp-prescription">
                            <i class="fas fa-file-medical"></i> প্রেসক্রিপশন আপলোড করুন (আবশ্যক) — <span id="rx-filename">JPG, PNG বা PDF, সর্বোচ্চ 5MB</span>
                        </label>
                        <input type="file" id="lp-prescription" name="prescription" accept="image/*,.pdf" style="display:none"
                            onchange="document.getElementById('rx-filename').textContent = this.files[0]?.name || 'JPG, PNG বা PDF, সর্বোচ্চ 5MB'">
                        <p class="of-error" data-error-for="prescription"></p>
                    </div>
                    @endif

                    <div class="of-pay">
                        @if($codEnabled)
                        <label><input type="radio" name="payment_method" value="cod" checked><span>💵 ক্যাশ অন ডেলিভারি</span></label>
                        @endif
                        @if($sslEnabled)
                        <label><input type="radio" name="payment_method" value="ssl_commerz" {{ !$codEnabled ? 'checked' : '' }}><span>💳 Card/bKash/Nagad</span></label>
                        @endif
                    </div>
                    <p class="of-error" data-error-for="general"></p>

                    <button type="submit" class="cta-main" id="submit-btn">
                        <span id="submit-btn-text">এখনই অর্ডার করুন ⚡</span>
                    </button>

                    <div class="order-totals">
                        <span>Delivery: <span id="delivery-amt">—</span></span>
                        <strong>Total: ৳<span id="total-amt">{{ number_format($price, 0) }}</span></strong>
                    </div>
                </form>
            </div>

            <div class="order-success" id="order-success">
                <div class="ok-icon">✅</div>
                <h3>অর্ডার সফল হয়েছে!</h3>
                <p>অর্ডার নম্বর: <strong id="success-order-number"></strong><br>আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।</p>
                <a id="success-track-link" href="#" class="cta-main" style="display:inline-flex;max-width:280px">অর্ডার ট্র্যাক করুন</a>
            </div>

            <p class="ship-note">{{ $landingPage->shipping_note ?: 'ডেলিভারি ২৪–৪৮ ঘণ্টার মধ্যে পৌঁছাবে।' }}</p>

            @if($messengerUrl)
            <div style="text-align:center">
                <a href="{{ $messengerUrl }}" target="_blank" class="messenger-link">
                    <i class="fab fa-facebook-messenger"></i> প্রশ্ন থাকলে ম্যাসেঞ্জারে জিজ্ঞাসা করুন
                </a>
            </div>
            @endif

            <div class="caution-box">
                <strong>⚠️ রিটার্ন পলিসি</strong><br>
                {{ $landingPage->return_policy_note ?: 'পণ্য রিটার্ন করতে চাইলে অবশ্যই ডেলিভারি ম্যানের সামনে প্যাকেট খুলে চেক করতে হবে।' }}
            </div>
        </div>
    </section>

    @if($landingPage->sectionEnabled('trust_badges') && count($landingPage->section('trust_badges')['items'] ?? []))
    <div class="trust-pills">
        @foreach($landingPage->section('trust_badges')['items'] as $badge)
        <div class="tpill">{{ $badge }}</div>
        @endforeach
    </div>
    @endif

    <section class="hero">
        @if($landingPage->eyebrow_text)
        <div class="eyebrow"><div class="blink"></div>{{ $landingPage->eyebrow_text }}</div>
        @endif
        <h1 class="hero-h1">{{ $landingPage->headline }}</h1>
        @if($landingPage->subheadline)
        <p class="hero-sub">{{ $landingPage->subheadline }}</p>
        @endif

        @if($landingPage->sectionEnabled('problems') && count($landingPage->section('problems')['items'] ?? []))
        <div class="problems">
            @foreach($landingPage->section('problems')['items'] as $prob)
            <div class="prob">{{ $prob['icon'] ?? '' }} {{ $prob['text'] ?? '' }}</div>
            @endforeach
        </div>
        @endif
    </section>

    @if($landingPage->sectionEnabled('formula') && count($landingPage->section('formula')['items'] ?? []))
    <section class="sec" style="background: radial-gradient(ellipse 60% 50% at 50% 100%, color-mix(in srgb, var(--accent) 7%, transparent) 0%, transparent 70%);">
        <div class="sec-label">ফর্মুলার ভেতরে কী আছে</div>
        <h2 class="sec-h2">মূল উপাদানসমূহ</h2>
        <div class="grid-cards">
            @foreach($landingPage->section('formula')['items'] as $f)
            <div class="g-card">
                <div class="g-icon">{{ $f['icon'] ?? '✨' }}</div>
                <div class="g-title">{{ $f['title_en'] ?? '' }}</div>
                @if(!empty($f['tag']))<div class="g-tag">{{ $f['tag'] }}</div>@endif
                <div class="g-desc">{{ $f['desc'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($landingPage->sectionEnabled('benefits') && count($landingPage->section('benefits')['items'] ?? []))
    <section class="sec" style="padding-top: 8px">
        <div class="sec-label">আপনি কী অনুভব করবেন</div>
        <h2 class="sec-h2">যা যা পাবেন</h2>
        <div class="benefit-list">
            @foreach($landingPage->section('benefits')['items'] as $b)
            <div class="benefit">
                <div class="b-icon">{{ $b['icon'] ?? '✨' }}</div>
                <div>
                    <div class="b-title">{{ $b['title_en'] ?? '' }}</div>
                    <div class="b-desc">{{ $b['desc'] ?? '' }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($landingPage->sectionEnabled('how_to_use') && count($landingPage->section('how_to_use')['items'] ?? []))
    <section class="sec" style="padding-top: 0">
        <div class="sec-label">কীভাবে ব্যবহার করবেন</div>
        <h2 class="sec-h2">ব্যবহার করা অত্যন্ত সহজ</h2>
        <div class="how-grid">
            @foreach($landingPage->section('how_to_use')['items'] as $i => $h)
            <div class="how-card">
                <div class="how-num">{{ $i + 1 }}</div>
                <div class="how-title">{{ $h['title_en'] ?? '' }}</div>
                <div class="how-desc">{{ $h['desc'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($landingPage->sectionEnabled('ingredients') && !empty($landingPage->section('ingredients')['text']))
    <section class="sec" style="padding-top: 0">
        <div class="sec-label">বিস্তারিত</div>
        <h2 class="sec-h2" style="margin-bottom: 22px">উপাদান / স্পেসিফিকেশন</h2>
        <div class="inci-box"><p>{{ $landingPage->section('ingredients')['text'] }}</p></div>
        @if(!empty($landingPage->section('ingredients')['caution']))
        <div class="caution-box" style="max-width:680px"><strong>⚠ সতর্কতা:</strong> {{ $landingPage->section('ingredients')['caution'] }}</div>
        @endif
    </section>
    @endif

    @if($landingPage->sectionEnabled('reviews') && $reviews->isNotEmpty())
    <section class="sec" style="padding-top: 0">
        <div class="stars-row">
            <div class="stars">{{ str_repeat('★', round($product->average_rating)) }}{{ str_repeat('☆', 5 - round($product->average_rating)) }}</div>
            <div class="stars-txt"><strong>{{ number_format($product->average_rating, 1) }}/৫</strong> — {{ $product->rating_count }}+ Verified Buyer-এর রেটিং</div>
        </div>
        <div class="reviews">
            @foreach($reviews as $r)
            <div class="review">
                <div class="rev-stars">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</div>
                @if($r->body)<div class="rev-body">"{{ $r->body }}"</div>@endif
                <div class="rev-author">
                    <div class="rev-av">{{ strtoupper(substr($r->display_name, 0, 1)) }}</div>
                    <div class="rev-name">{{ $r->display_name }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($landingPage->sectionEnabled('trust_badges') && count($landingPage->section('trust_badges')['items'] ?? []))
    <div class="vstrip">
        @foreach($landingPage->section('trust_badges')['items'] as $badge)
        <div class="vi">{{ $badge }}</div>
        @endforeach
    </div>
    @endif

    @if($landingPage->sectionEnabled('gallery') && count($landingPage->section('gallery')['images'] ?? []))
    <section class="sec" style="padding-top: 0">
        <div class="sec-label">প্রোডাক্ট গ্যালারি</div>
        <h2 class="sec-h2" style="margin-bottom: 26px">আসল প্রোডাক্টের ছবি</h2>
        <div class="gallery-grid">
            @foreach($landingPage->section('gallery')['images'] as $img)
            <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}">
            @endforeach
        </div>
    </section>
    @endif

    @if($landingPage->sectionEnabled('faq') && count($landingPage->section('faq')['items'] ?? []))
    <section class="sec">
        <div class="sec-label">সাধারণ প্রশ্ন</div>
        <h2 class="sec-h2" style="margin-bottom: 32px">কিছু জিজ্ঞাসা আছে?</h2>
        <div class="faq-list">
            @foreach($landingPage->section('faq')['items'] as $faq)
            <div class="faq-item">
                <button class="faq-q" type="button">{{ $faq['q'] ?? '' }} <span class="ico">+</span></button>
                <div class="faq-a">{{ $faq['a'] ?? '' }}</div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <section class="final-cta">
        <h2>আজই অর্ডার করুন <em>{{ $product->name }}</em></h2>
        <p>{{ $landingPage->subheadline ?: 'সীমিত স্টক, সীমিত সময়ের অফার।' }}</p>
        <a href="#order" class="final-cta-btn">⚡ মাত্র ৳{{ number_format($price, 0) }} — Order Now</a>
    </section>

    <footer>
        <p>© {{ date('Y') }} Ousodhaloy.com · Bangladesh</p>
        <div class="footer-links">
            <a href="{{ route('legal.privacy') }}">Privacy Policy</a>
            <a href="{{ route('legal.terms') }}">Terms of Use</a>
            <a href="{{ route('legal.returns') }}">Return Policy</a>
        </div>
    </footer>

    <div class="sticky-bar" id="sticky">
        <div class="s-info">
            <div class="s-name">{{ Str::limit($product->name, 30) }}</div>
            <div class="s-price">৳{{ number_format($price, 0) }}</div>
        </div>
        <a href="#order" class="s-cta">Order করুন ⚡</a>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── Countdown (only if the admin set one) ──
        @if($landingPage->countdown_end_at)
        var end = new Date("{{ $landingPage->countdown_end_at->toIso8601String() }}").getTime();
        function tick() {
            var diff = Math.max(0, end - Date.now());
            var d = Math.floor(diff / 86400000), h = Math.floor((diff % 86400000) / 3600000);
            var m = Math.floor((diff % 3600000) / 60000), s = Math.floor((diff % 60000) / 1000);
            var dd = document.getElementById('cd-d'), hh = document.getElementById('cd-h');
            var mm = document.getElementById('cd-m'), ss = document.getElementById('cd-s');
            if (!dd) return;
            dd.textContent = String(d).padStart(2,'0'); hh.textContent = String(h).padStart(2,'0');
            mm.textContent = String(m).padStart(2,'0'); ss.textContent = String(s).padStart(2,'0');
        }
        tick(); setInterval(tick, 1000);
        @endif

        // ── ViewContent pixel ──
        @if($pixelViewContent)
        if (window.fbTrack) {
            window.fbTrack('ViewContent', {
                content_ids: ['{{ $product->id }}'], content_name: @json($product->name),
                content_type: 'product', value: {{ $price }}, currency: 'BDT'
            });
        }
        @endif
    });

    // ── Qty + live delivery/total ──
    var qty = 1;
    var unitPrice = {{ $price }};
    var BD_DISTRICTS = @json(config('bd.districts', []));
    var landingSlug = @json($landingPage->slug);
    var csrfToken = document.querySelector('meta[name=csrf-token]').content;

    function updateQty() {
        document.getElementById('qty-num').textContent = qty;
        document.getElementById('qty-subtotal-txt').textContent = '৳' + (unitPrice * qty).toLocaleString();
        recalcDelivery();
    }
    document.getElementById('qty-down').addEventListener('click', function () { if (qty > 1) { qty--; updateQty(); } });
    document.getElementById('qty-up').addEventListener('click', function () { if (qty < 10) { qty++; updateQty(); } });

    document.getElementById('division-select').addEventListener('change', function () {
        var sel = document.getElementById('district-select');
        sel.innerHTML = '<option value="">জেলা নির্বাচন করুন *</option>';
        (BD_DISTRICTS[this.value] || []).forEach(function (d) {
            var opt = document.createElement('option'); opt.value = d; opt.textContent = d; sel.appendChild(opt);
        });
        recalcDelivery();
    });
    document.getElementById('district-select').addEventListener('change', recalcDelivery);

    async function recalcDelivery() {
        var division = document.getElementById('division-select').value;
        var district = document.getElementById('district-select').value;
        if (!division) return;
        try {
            var res = await fetch(`/order/lp/${landingSlug}/delivery-charge`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ division: division, district: district, qty: qty })
            });
            var data = await res.json();
            document.getElementById('delivery-amt').textContent = data.delivery_charge == 0 ? 'FREE 🎉' : '৳' + Math.round(data.delivery_charge);
            document.getElementById('total-amt').textContent = Math.round(data.total).toLocaleString();
        } catch (e) { /* fail silently, order creation still computes the real charge */ }
    }

    // ── Submit ──
    document.getElementById('order-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        document.querySelectorAll('.of-error').forEach(function (el) { el.textContent = ''; });

        var btn = document.getElementById('submit-btn');
        var btnText = document.getElementById('submit-btn-text');
        btn.disabled = true;
        var originalText = btnText.textContent;
        btnText.textContent = 'প্রসেস হচ্ছে...';

        var fd = new FormData(this);
        fd.append('qty', qty);

        try {
            var res = await fetch(`/order/lp/${landingSlug}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: fd
            });
            var data = await res.json();

            if (!res.ok) {
                if (data.errors) {
                    Object.keys(data.errors).forEach(function (field) {
                        var el = document.querySelector(`[data-error-for="${field}"]`);
                        if (el) el.textContent = data.errors[field][0];
                    });
                } else {
                    var el = document.querySelector('[data-error-for="general"]');
                    if (el) el.textContent = data.message || 'কিছু ভুল হয়েছে, আবার চেষ্টা করুন।';
                }
                btn.disabled = false;
                btnText.textContent = originalText;
                return;
            }

            if (data.gateway_url) {
                window.location.href = data.gateway_url;
                return;
            }

            if (window.fbTrack) {
                window.fbTrack('Purchase', {
                    content_ids: ['{{ $product->id }}'], content_type: 'product',
                    num_items: qty, value: unitPrice * qty, currency: 'BDT'
                });
            }

            document.getElementById('order-form-wrap').style.display = 'none';
            document.getElementById('success-order-number').textContent = data.order_number;
            document.getElementById('success-track-link').href = data.track_url;
            document.getElementById('order-success').style.display = 'block';
        } catch (err) {
            var el = document.querySelector('[data-error-for="general"]');
            if (el) el.textContent = 'নেটওয়ার্ক সমস্যা হয়েছে, আবার চেষ্টা করুন।';
            btn.disabled = false;
            btnText.textContent = originalText;
        }
    });

    // ── Sticky bar ──
    var stickyObserver = new IntersectionObserver(function (entries) {
        document.getElementById('sticky').classList.toggle('show', !entries[0].isIntersecting);
    }, { threshold: 0 });
    stickyObserver.observe(document.getElementById('order'));

    // ── FAQ accordion ──
    document.querySelectorAll('.faq-q').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = btn.parentElement;
            var open = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(function (i) { i.classList.remove('open'); });
            if (!open) item.classList.add('open');
        });
    });
    </script>
</body>
</html>
