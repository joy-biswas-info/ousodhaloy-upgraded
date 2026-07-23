<!doctype html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>The Ordinary Niacinamide 10% + Zinc 1% — দাগমুক্ত, তেলমুক্ত ত্বক</title>
    <meta name="description"
        content="Niacinamide 10% + Zinc 1% সিরাম। ব্রণ, দাগ ও তেলতেলে ত্বকের জন্য। ১০০% অরিজিনাল। Flash sale মাত্র ৳1,099।" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --navy: #FAF3F1;
            --navy2: #FDF8F6;
            --navy3: #FFFFFF;
            --blue: #C97B8E;
            --blue-bright: #B5566F;
            --blue-glow: #E8A3B5;
            --amber: #0D7674;
            --amber-bright: #C2486A;
            --green: #8E7355;
            --white: #2B2220;
            --gray: #000;
            --gray2: #A6938D;
            --red: #C2486A;
            --r: 14px;
            --r2: 20px;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: "Hind Siliguri", -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif;
            background: var(--navy);
            color: var(--white);
            line-height: 1.55;
            overflow-x: hidden;
        }

        img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .en {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif;
        }


        /* NAV */
        nav {
            position: sticky;
            top: 0;
            z-index: 90;
            border-bottom: 1px solid rgba(139, 90, 80, 0.12);
            padding: 0 20px;
            background-color: #fff;

        }

        .inner-nav {
            max-width: 1020px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background-color: #fff;
            height: 54px;

        }

        .nav-logo {
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            white-space: nowrap;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-price {
            font-size: 15px;
            font-weight: 800;
            color: #000;
            white-space: nowrap;
        }

        .nav-cta {
            background: var(--amber);
            color: var(--navy);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .nav-cta:hover {
            background: var(--amber-bright);
        }

        /* HERO */
        .hero {
            padding: 44px 20px 0;
            background: radial-gradient(ellipse 80% 55% at 50% -5%, rgba(217, 100, 124, 0.16) 0%, transparent 70%), var(--navy);
            text-align: center;
            overflow: hidden;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(217, 100, 124, 0.12);
            border: 1px solid rgba(217, 100, 124, 0.28);
            color: var(--blue-bright);
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .blink {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--amber-bright);
            animation: bl 1s infinite;
        }

        @keyframes bl {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.15;
            }
        }

        .hero-h1 {
            font-size: clamp(28px, 7vw, 42px);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.5px;
            margin-bottom: 14px;
            max-width: 680px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-h1 .hi {
            color: var(--blue-bright);
            display: block;
            margin-top: 4px;
        }

        .hero-sub {
            font-size: clamp(15px, 3.5vw, 17px);
            color: var(--gray);
            line-height: 1.65;
            max-width: 440px;
            margin: 0 auto 30px;
        }

        .hero-sub strong {
            color: var(--white);
            font-weight: 600;
        }

        .problems {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }

        .prob {
            background: rgba(139, 90, 80, 0.08);
            border: 1px solid rgba(139, 90, 80, 0.16);
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .hero-img-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: -10px;
        }

        .hero-img-wrap img {
            width: clamp(260px, 90vw, 620px);
            border-radius: 16px;
            filter: drop-shadow(0 20px 55px rgba(197, 90, 120, 0.28));
            animation: flt 4s ease-in-out infinite;
            margin: 0 auto;
        }

        @keyframes flt {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-9px);
            }
        }

        .fbadge {
            position: absolute;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(139, 90, 80, 0.16);
            border-radius: 12px;
            padding: 9px 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            animation: flt2 3.5s ease-in-out infinite;
            box-shadow: 0 4px 20px rgba(139, 90, 80, 0.18);
        }

        .fbadge.f1 {
            top: 30px;
            right: -30px;
            animation-delay: 0.4s;
        }

        .fbadge.f2 {
            bottom: 70px;
            left: -40px;
            animation-delay: 1.8s;
        }

        @keyframes flt2 {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-7px);
            }
        }

        .fbadge-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .fbadge-t {
            font-size: 11px;
            font-weight: 700;
            color: var(--white);
            line-height: 1.2;
        }

        .fbadge-s {
            font-size: 10px;
            color: var(--gray);
            font-weight: 400;
        }

        .trust-pills {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 7px;
            padding: 28px 0 0;
        }

        .tpill {
            background: rgba(79, 122, 92, 0.10);
            border: 1px solid rgba(79, 122, 92, 0.25);
            color: var(--amber);
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
        }

        /* PRICE SECTION */
        .price-section {
            padding: 32px 20px 0;
        }

        .price-card {
            max-width: 680px;
            margin: 0 auto;
            background: var(--navy3);
            border: 1px solid rgba(59, 130, 246, 0.22);
            border-radius: var(--r2);
            padding: 26px 22px;
            box-shadow: 0 0 40px rgba(197, 90, 120, 0.14);
        }

        .sale-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--red);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 14px;
        }

        .price-row {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 5px;
            flex-wrap: wrap;
        }

        .price-now {
            font-size: 46px;
            font-weight: 700;
            color: var(--amber-bright);
            letter-spacing: -1px;
            line-height: 1;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .price-was {
            font-size: 18px;
            color: var(--gray2);
            text-decoration: line-through;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .price-save {
            font-size: 13px;
            font-weight: 700;
            background: rgba(239, 68, 68, 0.15);
            color: #B5566F;
            padding: 3px 10px;
            border-radius: 6px;
        }

        .price-per {
            font-size: 13px;
            color: var(--gray);
            margin-bottom: 18px;
        }

        .price-per strong {
            color: var(--white);
        }

        .countdown {
            background: rgba(239, 68, 68, 0.09);
            border: 1px solid rgba(239, 68, 68, 0.22);
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .cd-label {
            font-size: 12px;
            color: #B5566F;
            font-weight: 600;
            flex-shrink: 0;
        }

        .cd-timer {
            display: flex;
            gap: 5px;
            margin-left: auto;
        }

        .cd-unit {
            background: var(--navy);
            border-radius: 6px;
            padding: 4px 7px;
            text-align: center;
            min-width: 34px;
        }

        .cd-num {
            font-size: 15px;
            font-weight: 700;
            line-height: 1;
            color: var(--amber-bright);
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .cd-sep {
            font-size: 15px;
            font-weight: 700;
            color: var(--amber-bright);
            align-self: center;
        }

        .cd-lbl {
            font-size: 9px;
            color: var(--gray);
            text-transform: uppercase;
            margin-top: 1px;
        }

        .qty-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .qty-wrap {
            display: flex;
            align-items: center;
            background: rgba(139, 90, 80, 0.10);
            border: 1px solid rgba(139, 90, 80, 0.16);
            border-radius: 10px;
            overflow: hidden;
        }

        .qty-btn {
            width: 42px;
            height: 50px;
            background: none;
            color: var(--white);
            font-size: 20px;
            font-weight: 700;
            transition: background 0.1s;
        }

        .qty-btn:hover {
            background: rgba(139, 90, 80, 0.14);
        }

        .qty-num {
            width: 42px;
            text-align: center;
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .cta-main {
            flex: 1;
            height: 50px;
            background: var(--amber);
            color: var(--navy);
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: background 0.15s, transform 0.1s;
            box-shadow: 0 4px 24px rgba(245, 158, 11, 0.38);
        }

        .cta-main:active {
            transform: scale(0.98);
        }

        .cta-main:hover {
            background: var(--amber-bright);
        }

        .pay-row {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pay-badge {
            background: rgba(139, 90, 80, 0.12);
            border: 1px solid rgba(139, 90, 80, 0.15);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            color: var(--gray);
        }

        .ship-note {
            text-align: center;
            font-size: 12px;
            color: #000;
            margin-top: 10px;
        }

        .ship-note strong {
            color: #000;
        }

        /* SECTION COMMON */
        .sec {
            padding: 52px 20px;
        }

        .sec-label {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--blue-bright);
            margin-bottom: 8px;
        }

        .sec-h2 {
            text-align: center;
            font-size: clamp(22px, 5vw, 36px);
            font-weight: 700;
            letter-spacing: -0.3px;
            line-height: 1.15;
            margin-bottom: 8px;
        }

        .sec-sub {
            text-align: center;
            font-size: 15px;
            color: var(--gray);
            max-width: 440px;
            margin: 0 auto 38px;
            line-height: 1.65;
        }

        /* FORMULA GRID (replaces triple-grid — 2 actives + base, matching this product) */
        .triple-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            max-width: 680px;
            margin: 0 auto;
        }

        .triple-card {
            background: var(--navy3);
            border-radius: var(--r2);
            padding: 22px 16px;
            text-align: center;
            border: 1px solid rgba(139, 90, 80, 0.12);
            transition: border-color 0.2s;
        }

        .triple-card:hover {
            border-color: rgba(59, 130, 246, 0.35);
        }

        .t-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 12px;
        }

        .t-en {
            font-size: 13px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 3px;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .t-type {
            font-size: 10px;
            color: var(--blue-glow);
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .t-bn {
            font-size: 12px;
            color: var(--gray);
            line-height: 1.55;
        }

        /* BENEFITS */
        .benefit-list {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .benefit {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: var(--navy3);
            border: 1px solid rgba(139, 90, 80, 0.12);
            border-radius: var(--r);
            padding: 18px 18px;
        }

        .b-icon {
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .b-en {
            font-size: 14px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 3px;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .b-bn {
            font-size: 13px;
            color: var(--gray);
            line-height: 1.6;
        }

        /* PROOF */
        .stars-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 26px;
            flex-wrap: wrap;
        }

        .stars {
            color: var(--amber-bright);
            font-size: 20px;
            letter-spacing: 2px;
        }

        .stars-txt {
            font-size: 14px;
            color: var(--gray);
        }

        .stars-txt strong {
            color: var(--white);
        }

        .reviews {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            max-width: 640px;
            margin: 0 auto 32px;
        }

        .review {
            background: var(--navy3);
            border: 1px solid rgba(139, 90, 80, 0.12);
            border-radius: var(--r2);
            padding: 18px;
        }

        .rev-stars {
            color: var(--amber-bright);
            font-size: 12px;
            margin-bottom: 7px;
        }

        .rev-bn {
            font-size: 13px;
            color: #000;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .rev-author {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .rev-av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--blue);
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .rev-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--white);
        }

        .rev-loc {
            font-size: 11px;
            color: var(--gray);
        }

        /* VERIFIED STRIP */
        .vstrip {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 22px 20px;
            background: rgba(79, 122, 92, 0.06);
            border-top: 1px solid rgba(79, 122, 92, 0.14);
            border-bottom: 1px solid rgba(79, 122, 92, 0.14);
        }

        .vi {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 600;
            color: #4F7A5C;
        }

        /* INGREDIENTS BOX (specific to this product) */
        .inci-box {
            max-width: 680px;
            margin: 0 auto;
            background: var(--navy3);
            border: 1px solid rgba(139, 90, 80, 0.12);
            border-radius: var(--r2);
            padding: 20px 22px;
        }

        .inci-box p {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 11.5px;
            line-height: 1.9;
            color: var(--gray);
        }

        .caution-box {
            max-width: 680px;
            margin: 14px auto 0;
            background: rgba(178, 134, 60, 0.10);
            border: 1px solid rgba(178, 134, 60, 0.28);
            border-radius: var(--r);
            padding: 14px 18px;
            font-size: 12.5px;
            color: #6B4F2A;
            line-height: 1.6;
        }

        .caution-box strong {
            color: var(--amber-bright);
        }

        /* HOW TO USE */
        .how-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            max-width: 620px;
            margin: 0 auto;
        }

        .how-card {
            background: var(--navy3);
            border-radius: var(--r2);
            padding: 20px 14px;
            text-align: center;
            border: 1px solid rgba(139, 90, 80, 0.12);
        }

        .how-num {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--blue);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .how-en {
            font-size: 13px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 4px;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .how-bn {
            font-size: 12px;
            color: var(--gray);
            line-height: 1.55;
        }

        /* FAQ */
        .faq-list {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .faq-item {
            background: var(--navy3);
            border-radius: var(--r);
            border: 1px solid rgba(139, 90, 80, 0.12);
            overflow: hidden;
        }

        .faq-q {
            width: 100%;
            background: none;
            color: var(--white);
            padding: 15px 18px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .faq-q .ico {
            color: var(--blue-bright);
            font-size: 18px;
            transition: transform 0.2s;
            flex-shrink: 0;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            font-weight: 300;
        }

        .faq-item.open .faq-q .ico {
            transform: rotate(45deg);
        }

        .faq-a {
            display: none;
            padding: 0 18px 14px;
            font-size: 13px;
            color: var(--gray);
            line-height: 1.65;
            border-top: 1px solid rgba(139, 90, 80, 0.08);
            padding-top: 12px;
        }

        .faq-item.open .faq-a {
            display: block;
        }

        /* GALLERY */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            max-width: 680px;
            margin: 0 auto;
        }

        .gallery-grid img {
            border-radius: var(--r);
            border: 1px solid rgba(139, 90, 80, 0.14);
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        /* FINAL CTA */
        .final-cta {
            padding: 52px 20px 40px;
            text-align: center;
            background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(217, 100, 124, 0.14) 0%, transparent 70%);
        }

        .final-cta h2 {
            font-size: clamp(24px, 6vw, 40px);
            font-weight: 700;
            letter-spacing: -0.3px;
            line-height: 1.15;
            margin-bottom: 10px;
        }

        .final-cta h2 em {
            color: var(--amber-bright);
            font-style: normal;
        }

        .final-cta p {
            font-size: 15px;
            color: var(--gray);
            margin-bottom: 26px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-final-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            background: var(--amber);
            color: var(--navy);
            padding: 17px 40px;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 700;
            box-shadow: 0 6px 28px rgba(245, 158, 11, 0.42);
            transition: background 0.15s, transform 0.1s;
            max-width: 380px;
            width: 100%;
        }

        .cta-final-btn:active {
            transform: scale(0.98);
        }

        .cta-final-btn:hover {
            background: var(--amber-bright);
        }

        .money-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 13px;
            color: var(--gray);
            flex-wrap: wrap;
            justify-content: center;
        }

        .money-back strong {
            color: var(--green);
        }

        /* STICKY */
        .sticky-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 80;
            background: var(--navy2);
            border-top: 1px solid rgba(139, 90, 80, 0.16);
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 -4px 24px rgba(139, 90, 80, 0.15);
            transform: translateY(100%);
            transition: transform 0.25s ease;
        }

        .sticky-bar.show {
            transform: translateY(0);
        }

        .s-info {
            flex: 1;
            min-width: 0;
        }

        .s-name {
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--gray);
        }

        .s-price {
            font-size: 15px;
            font-weight: 700;
            color: var(--amber-bright);
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .s-cta {
            flex-shrink: 0;
            background: var(--amber);
            color: var(--navy);
            padding: 11px 20px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .s-cta:hover {
            background: var(--amber-bright);
        }

        /* FOOTER */
        footer {
            padding: 22px 20px;
            text-align: center;
            border-top: 1px solid rgba(139, 90, 80, 0.12);
        }

        footer p {
            font-size: 12px;
            color: var(--gray2);
            margin-bottom: 8px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .footer-links a {
            font-size: 12px;
            color: var(--gray2);
        }

        .footer-links a:hover {
            color: var(--gray);
        }

        /* RESPONSIVE */
        @media (max-width: 540px) {
            .triple-grid {
                grid-template-columns: 1fr;
            }

            .reviews {
                grid-template-columns: 1fr;
            }

            .how-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .fbadge.f1 {
                right: -10px;
            }

            .fbadge.f2 {
                left: -10px;
            }

            body {
                padding-bottom: 72px;
            }
        }

        @media (min-width: 541px) and (max-width: 760px) {
            .triple-grid {
                grid-template-columns: 1fr 1fr;
            }

            body {
                padding-bottom: 72px;
            }
        }

        @media (min-width: 761px) {
            body {
                padding-bottom: 72px;
            }
        }
    </style>
    @include('partials.meta-pixel')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
@php
    $pixelViewContent = \App\Models\Setting::get('meta_pixel_view_content', 'true') === 'true';
@endphp

<body>

    <!-- NAV -->
    <nav>
        <div class="inner-nav">
            <div class="nav-logo">
                <a href="{{ route('home') }}"
                    style="display:flex;align-items:center;text-decoration:none;color:var(--amber);flex:1;min-width:0">
                    <div style="display:flex;align-items:center;justify-content:center;font-weight:900"
                        class="sm:text-xl lg:text-2xl">
                        ঔষ<span class=" text-red-400">ধা</span>লয়
                    </div>
                </a>
            </div>
            <div class="nav-right">
                <div class="nav-price">৳1,099</div>
                <a href="{{ route('buy.now', ['product' => $product->id, 'qty' => 1]) }}" class="buynow-btn nav-cta"
                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                    data-price="{{ $product->effective_price }}">অর্ডার করুন</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="eyebrow ">
            <div class="blink"></div>
            Flash Sale · মাত্র 7 দিন বাকি
        </div>

        <h1 class="hero-h1">
            ব্রণ, দাগ আর তেলতেলে ত্বক?<br />
            <span class="hi">একটি সিরাম, তিনটি সমাধান।</span>
        </h1>

        <p class="hero-sub">
            <strong>Niacinamide 10% + Zinc 1%</strong> তেল নিয়ন্ত্রণ করে, দাগ হালকা করে,
            আর ত্বককে রাখে উজ্জ্বল ও সুরক্ষিত। The Ordinary-র সবচেয়ে জনপ্রিয় ফর্মুলা।
        </p>

        <div class="problems">
            <div class="prob">🛢️ তেলতেলে ত্বক</div>
            <div class="prob">😣 ব্রণের দাগ</div>
            <div class="prob">🔴 লালচে ভাব</div>
        </div>

        <div class="hero-img-wrap">
            <img src="{{ asset('storage/media/ordinary_ousodhaloy.jpg') }}"
                alt="The Ordinary Niacinamide 10% + Zinc 1% Serum 30ml" width="370" height="370" loading="eager"
                onerror="
        this.style.display = 'none';
        this.nextElementSibling.style.display = 'flex';
     " />
            <div
                style="
            display: none;
            width: 200px;
            height: 200px;
            margin: 0 auto;
            background: rgba(217, 100, 124, 0.10);
            border-radius: 24px;
            align-items: center;
            justify-content: center;
            font-size: 72px;
          ">
                🧴
            </div>

            <!-- Floating badges -->
            <div class="fbadge f1">
                <div class="fbadge-icon" style="background: rgba(79, 122, 92, 0.14)">
                    ⚡
                </div>
                <div>
                    <div class="fbadge-t">Fast Delivery</div>
                    <div class="fbadge-s">24–48 ঘণ্টায়</div>
                </div>
            </div>
            <div class="fbadge f2">
                <div class="fbadge-icon" style="background: rgba(217, 100, 124, 0.14)">
                    ★
                </div>
                <div>
                    <div class="fbadge-t">4.8/5 Rating</div>
                    <div class="fbadge-s">340+ রিভিউ</div>
                </div>
            </div>
        </div>

    </section>

    <!-- PRICE CARD -->
    <section class="price-section" id="order">
        <div class="price-card">
            <div class="sale-badge">🔥 Flash Sale — সীমিত সময়</div>

            <div class="price-row">
                <div class="price-now">৳1,090</div>
                <div class="price-was">৳1,620</div>
                <div class="price-save">37.04% OFF</div>
            </div>
            <div class="price-per">
                30ml · <strong>সকাল-রাত ব্যবহারের জন্য</strong>
            </div>

            <div class="countdown">
                <div class="cd-label">⏰ অফার শেষ হবে:</div>
                <div class="cd-timer">
                    <div class="cd-unit">
                        <div class="cd-num" id="cd-d">07</div>
                        <div class="cd-lbl">দিন</div>
                    </div>
                    <div class="cd-sep">:</div>
                    <div class="cd-unit">
                        <div class="cd-num" id="cd-h">14</div>
                        <div class="cd-lbl">ঘণ্টা</div>
                    </div>
                    <div class="cd-sep">:</div>
                    <div class="cd-unit">
                        <div class="cd-num" id="cd-m">22</div>
                        <div class="cd-lbl">মিনিট</div>
                    </div>
                    <div class="cd-sep">:</div>
                    <div class="cd-unit">
                        <div class="cd-num" id="cd-s">08</div>
                        <div class="cd-lbl">সেকেন্ড</div>
                    </div>
                </div>
            </div>

            <div class="qty-row">
                <div class="qty-wrap">
                    <button class="qty-btn" id="qty-down" aria-label="কমান">−</button>
                    <div class="qty-num" id="qty-num">1</div>
                    <button class="qty-btn" id="qty-up" aria-label="বাড়ান">+</button>
                </div>
                <a href="{{ route('buy.now', ['product' => $product->id, 'qty' => 1]) }}" class="buynow-btn cta-main"
                    id="buynow-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                    data-price="{{ $product->effective_price }}">

                    Buy Now

                </a>
            </div>

            <div class="pay-row">
                <div class="pay-badge">💵 Cash on Delivery</div>
            </div>
            <div class="ship-note">
                <strong>ডেলিভারি ফি ঢাকার ভিতর 80 টাকা, ঢাকার বাইরে 120 টাকা</strong> · ২৪–৪৮ ঘণ্টায়
                পৌঁছাবে
            </div>
            <div class="caution-box">
                <strong>⚠️ রিটার্ন পলিসি</strong> <br />
                পণ্য রিটার্ন করতে চাইলে অবশ্যই ডেলিভারি ম্যানের সামনে প্যাকেট খুলে চেক করতে হবে। ডেলিভারি ম্যান চলে
                যাওয়ার পর কোনো রিটার্ন গ্রহণযোগ্য হবে না।
            </div>
        </div>
    </section>
    <div class="trust-pills">
        <div class="tpill">✓ 100% Authentic</div>
        <div class="tpill">✓ 30ml Bottle</div>
        <div class="tpill">✓ Niacinamide 10% + Zinc 1%</div>
        <div class="tpill">✓ All Skin Types</div>
    </div>

    <!-- FORMULA EXPLAINER -->
    <section class="sec"
        style="background: radial-gradient(ellipse 60% 50% at 50% 100%, rgba(217, 100, 124, 0.07) 0%, transparent 70%);">
        <div class="sec-label">ফর্মুলার ভেতরে কী আছে</div>
        <h2 class="sec-h2">২টি সক্রিয় উপাদান।<br />একসাথে কাজ করে সর্বোচ্চ ফলাফলের জন্য।</h2>
        <p class="sec-sub">
            প্রতিটি উপাদান আলাদা সমস্যায় কাজ করে — একসাথে ব্যবহারে ত্বক হয় পরিষ্কার, উজ্জ্বল ও নিয়ন্ত্রিত।
        </p>

        <div class="triple-grid">
            <div class="triple-card">
                <div class="t-icon" style="background: rgba(181, 86, 111, 0.14)">
                    ✨
                </div>
                <div class="t-en">Brighten & Repair</div>
                <div class="t-type">Niacinamide 10%</div>
                <div class="t-bn">
                    ত্বকের উজ্জ্বলতা বাড়ায়, টেক্সচার ঠিক করে এবং ময়েশ্চার ব্যারিয়ার মজবুত করে।
                </div>
            </div>
            <div class="triple-card">
                <div class="t-icon" style="background: rgba(178, 134, 60, 0.14)">
                    🛢️
                </div>
                <div class="t-en">Oil Control</div>
                <div class="t-type">Zinc PCA 1%</div>
                <div class="t-bn">
                    অতিরিক্ত সেবাম উৎপাদন নিয়ন্ত্রণ করে, ত্বককে রাখে ম্যাট ও সতেজ — দিনভর।
                </div>
            </div>
            <div class="triple-card">
                <div class="t-icon" style="background: rgba(79, 122, 92, 0.14)">
                    💧
                </div>
                <div class="t-en">Lightweight Base</div>
                <div class="t-type">Water-Based</div>
                <div class="t-bn">
                    হালকা ও non-greasy ফর্মুলা, যা দ্রুত শোষিত হয় এবং কোনো চিটচিটে ভাব রাখে না।
                </div>
            </div>
        </div>
    </section>

    <!-- BENEFITS -->
    <section class="sec" style="padding-top: 8px">
        <div class="sec-label">আপনি কী অনুভব করবেন</div>
        <h2 class="sec-h2">২ সপ্তাহেই পার্থক্য বুঝবেন</h2>
        <p class="sec-sub">
            30ml বোতল · সকাল ও রাতে ব্যবহারের জন্য পর্যাপ্ত — ক্লিনিক্যালি প্রমাণিত ফর্মুলা
        </p>

        <div class="benefit-list">
            <div class="benefit">
                <div class="b-icon">🛢️</div>
                <div>
                    <div class="b-en">Less oil, less shine all day</div>
                    <div class="b-bn">
                        Zinc PCA সেবাম উৎপাদন নিয়ন্ত্রণ করে। দুপুরের পর মুখ চকচক করা কমে যায়, ত্বক থাকে ম্যাট।
                    </div>
                </div>
            </div>
            <div class="benefit">
                <div class="b-icon">✨</div>
                <div>
                    <div class="b-en">Fades acne marks & redness</div>
                    <div class="b-bn">
                        ব্রণ পরবর্তী দাগ এবং লালচে ভাব কমিয়ে ত্বকের টোন করে আরও সমান ও স্বচ্ছ।
                    </div>
                </div>
            </div>
            <div class="benefit">
                <div class="b-icon">🧱</div>
                <div>
                    <div class="b-en">Stronger moisture barrier</div>
                    <div class="b-bn">
                        ত্বকের প্রাকৃতিক বাধা মজবুত করে — ত্বক থাকে হাইড্রেটেড, রুক্ষতা ও সংবেদনশীলতা কমে।
                    </div>
                </div>
            </div>
            <div class="benefit">
                <div class="b-icon">🔍</div>
                <div>
                    <div class="b-en">Visibly smaller-looking pores</div>
                    <div class="b-bn">
                        নিয়মিত ব্যবহারে রোমকূপ সংকুচিত দেখায় এবং ত্বকের টেক্সচার হয় মসৃণ ও নরম।
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY -->
    {{-- <section class="sec" style="padding-top: 0">
        <div class="sec-label">প্রোডাক্ট গ্যালারি</div>
        <h2 class="sec-h2" style="margin-bottom: 26px">আসল প্রোডাক্টের ছবি</h2>
        <div class="gallery-grid">
            <img src="{{ asset('storage/media/niacinamide.jpg') }}" alt="Niacinamide serum bottle">
            <img src="{{ asset('storage/media/ousodhaloy_niacinamide_zinc.jpg') }}" alt="Niacinamide label closeup">
            <img src="{{ asset('storage/media/ordinary_ousodhaloy.jpg') }}" alt="Niacinamide unboxing with box">
            <img src="{{ asset('storage/media/ousodhaloy_niacinamide.jpg') }}"
                alt="Niacinamide serum with packaging">
        </div>
    </section> --}}

    <!-- HOW TO USE -->
    <section class="sec" style="padding-top: 0">
        <div class="sec-label">কীভাবে ব্যবহার করবেন</div>
        <h2 class="sec-h2">ব্যবহার করা অত্যন্ত সহজ</h2>
        <p class="sec-sub">
            প্রতিদিন সকাল-রাত মাত্র ২–৩ ড্রপ বাকিটা ফর্মুলা নিজেই করবে।
        </p>

        <div class="how-grid">
            <div class="how-card">
                <div class="how-num">১</div>
                <div class="how-en">Cleanse First</div>
                <div class="how-bn">
                    ফেসওয়াশ দিয়ে মুখ ধুয়ে হালকা শুকিয়ে নিন, একদম শুষ্ক হওয়ার আগেই সিরাম লাগান।
                </div>
            </div>
            <div class="how-card">
                <div class="how-num">২</div>
                <div class="how-en">Apply 2–3 Drops</div>
                <div class="how-bn">
                    সকাল ও রাতে সম্পূর্ণ মুখ ও গলায় আলতোভাবে লাগান। চোখের চারপাশ এড়িয়ে চলুন।
                </div>
            </div>
            <div class="how-card">
                <div class="how-num">৩</div>
                <div class="how-en">Moisturize + SPF</div>
                <div class="how-bn">
                    সিরাম শুকিয়ে গেলে ময়েশ্চারাইজার দিন। দিনে সানস্ক্রিন ব্যবহার করতে ভুলবেন না।
                </div>
            </div>
        </div>
    </section>

    <!-- INGREDIENTS -->
    <section class="sec" style="padding-top: 0">
        <div class="sec-label">উপাদান তালিকা</div>
        <h2 class="sec-h2" style="margin-bottom: 22px">সম্পূর্ণ ইনগ্রেডিয়েন্ট লিস্ট (INCI)</h2>
        <div class="inci-box">
            <p>Aqua (Water), Niacinamide, Pentylene Glycol, Zinc PCA, Dimethyl Isosorbide, Tamarindus Indica Seed Gum,
                Xanthan Gum, Isoceteth-20, Ethoxydiglycol, Phenoxyethanol, Chlorphenesin.</p>
        </div>
        <div class="caution-box">
            <strong>⚠ ব্যবহারে সতর্কতা:</strong> টপিকাল ভিটামিন সি (L-Ascorbic Acid) ব্যবহার করলে এই সিরাম থেকে আলাদা
            সময়ে ব্যবহার করুন ভিটামিন সি রাতে, নায়াসিনামাইড সকালে। এতে দুটি উপাদানই সর্বোচ্চ কার্যকারিতা দেখাবে।
        </div>
    </section>

    <!-- SOCIAL PROOF -->
    <section class="sec" style="padding-top: 0">
        <div class="stars-row">
            <div class="stars">★★★★★</div>
            <div class="stars-txt">
                <strong>৪.৮/৫</strong> — ৩৪০+ Verified Buyer-এর রেটিং
            </div>
        </div>

        <div class="reviews">
            <div class="review">
                <div class="rev-stars">★★★★★</div>
                <div class="rev-bn">
                    "২ সপ্তাহ ব্যবহার করেই তেলতেলে ভাব অনেক কমে গেছে। মুখ এখন সারাদিন ফ্রেশ লাগে। আগে দুপুরের পর মুখ
                    চকচক করত।"
                </div>
                <div class="rev-author">
                    <div class="rev-av">ম</div>
                    <div>
                        <div class="rev-name">Mahfuza R.</div>
                        <div class="rev-loc">ঢাকা · Verified Buyer</div>
                    </div>
                </div>
            </div>
            <div class="review">
                <div class="rev-stars">★★★★★</div>
                <div class="rev-bn">
                    "ব্রণের দাগ হালকা হয়ে এসেছে এক মাসে। অরিজিনাল প্রোডাক্ট, প্যাকেজিং একদম পারফেক্ট ছিল। নিশ্চিন্তে
                    অর্ডার করতে পারবেন।"
                </div>
                <div class="rev-author">
                    <div class="rev-av" style="background: #B5566F">র</div>
                    <div>
                        <div class="rev-name">Rakibul H.</div>
                        <div class="rev-loc">চট্টগ্রাম · Verified Buyer</div>
                    </div>
                </div>
            </div>
            <div class="review">
                <div class="rev-stars">★★★★★</div>
                <div class="rev-bn">
                    "ডেলিভারি খুব ফাস্ট ছিল, পরদিনই পেয়েছি। স্কিন টেক্সচার আগের চেয়ে অনেক স্মুথ লাগছে। সবাইকে রেকমেন্ড
                    করব।"
                </div>
                <div class="rev-author">
                    <div class="rev-av" style="background: #8E7355">স</div>
                    <div>
                        <div class="rev-name">Sadia I.</div>
                        <div class="rev-loc">সিলেট · Verified Buyer</div>
                    </div>
                </div>
            </div>
            <div class="review">
                <div class="rev-stars">★★★★★</div>
                <div class="rev-bn">
                    "পোরস আগের চেয়ে অনেক ছোট দেখায় এখন। প্রাইস অনুযায়ী রিজাল্ট সত্যিই দারুণ। ঔষধালয় থেকে
                    বিশ্বস্তভাবে কিনতে পেরেছি।"
                </div>
                <div class="rev-author">
                    <div class="rev-av" style="background: #C2486A">ন</div>
                    <div>
                        <div class="rev-name">Nusrat J.</div>
                        <div class="rev-loc">রাজশাহী · Verified Buyer</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VERIFIED STRIP -->
    <div class="vstrip">
        <div class="vi">✓ 100% Original Product</div>
        <div class="vi">✓ DECIEM Authentic</div>
        <div class="vi">✓ All Skin Types</div>
        <div class="vi">✓ ৭ দিনের Exchange Policy</div>
    </div>

    <!-- FAQ -->
    <section class="sec">
        <div class="sec-label">সাধারণ প্রশ্ন</div>
        <h2 class="sec-h2" style="margin-bottom: 32px">কিছু জিজ্ঞাসা আছে?</h2>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-q">
                    এই প্রোডাক্ট কি ১০০% অরিজিনাল? <span class="ico">+</span>
                </button>
                <div class="faq-a">
                    হ্যাঁ, ঔষধালয়-এর সব The Ordinary প্রোডাক্ট সরাসরি অনুমোদিত আমদানিকারক থেকে সংগ্রহ করা। প্রতিটি
                    বোতলে ব্যাচ কোড থাকে যা যাচাই করা যায়।
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-q">
                    কতদিনে ফলাফল পাব? <span class="ico">+</span>
                </button>
                <div class="faq-a">
                    নিয়মিত সকাল-রাত ব্যবহারে সাধারণত ২–৪ সপ্তাহের মধ্যে তেল নিয়ন্ত্রণ ও উজ্জ্বলতায় পরিবর্তন বোঝা
                    যায়। দাগ হালকা হতে ৬–৮ সপ্তাহ লাগতে পারে।
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-q">
                    সেনসিটিভ ত্বকে কি ব্যবহার করা যাবে? <span class="ico">+</span>
                </button>
                <div class="faq-a">
                    হ্যাঁ, এটি সব ধরনের ত্বকের জন্য তৈরি। তবে প্রথমবার ব্যবহারের আগে কানের পেছনে বা হাতের কনুইয়ে প্যাচ
                    টেস্ট করে নেওয়া ভালো।
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-q">
                    ভিটামিন সি-এর সাথে ব্যবহার করা যাবে? <span class="ico">+</span>
                </button>
                <div class="faq-a">
                    ব্যবহার করা যাবে, কিন্তু একসাথে নয়। ভিটামিন সি রাতে এবং নায়াসিনামাইড সিরাম সকালে ব্যবহার করুন
                    এতে দুটি উপাদানই সর্বোচ্চ কার্যকারিতা দেখাবে।
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-q">
                    ডেলিভারি কতদিনে হবে? Cash on Delivery আছে? <span class="ico">+</span>
                </button>
                <div class="faq-a">
                    ঢাকায় সাধারণত ১২–২৪ ঘণ্টা, সারাদেশে ২৪–৪৮ ঘণ্টার মধ্যে পৌঁছাবে। হ্যাঁ, Cash on Delivery সুবিধা আছে।
                    bKash, Nagad, Card-এও পেমেন্ট করা যাবে।
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-q">
                    পছন্দ না হলে কি ফেরত দেওয়া যাবে? <span class="ico">+</span>
                </button>
                <div class="faq-a">
                    পণ্য রিটার্ন করতে চাইলে অবশ্যই ডেলিভারি ম্যানের সামনে প্যাকেট খুলে চেক করতে হবে। ডেলিভারি ম্যান চলে
                    যাওয়ার পর কোনো রিটার্ন গ্রহণযোগ্য হবে না।
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="final-cta">
        <h2>দাগ আর তেল নিয়ে চিন্তা বন্ধ করুন।<br />আজ থেকেই শুরু করুন <em>ক্লিয়ার স্কিন।</em></h2>
        <p>
            ৩৪০+ গ্রাহক ইতিমধ্যে তাদের ত্বকের তেল ও দাগের সমস্যার সমাধান করেছেন এই একটি সিরাম দিয়ে।
        </p>
        <a href="{{ route('buy.now', ['product' => $product->id, 'qty' => 1]) }}" class="buynow-btn cta-final-btn"
            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
            data-price="{{ $product->effective_price }}"> ⚡ মাত্র ৳1,099 —
             Order Now
        </a>
        <div>
            <div class="money-back">
                <strong>✓ &nbsp;·&nbsp;Cash on
                    Delivery&nbsp;·&nbsp;Free shipping ৳1800+
                </strong>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>© 2026 Ousodhaloy.com · Bangladesh</p>
        <div class="footer-links">
            <a href="https://ousodhaloy.com/privacy-policy">Privacy Policy</a>
            <a href="https://ousodhaloy.com/terms-of-use">Terms of Use</a>
            <a href="https://ousodhaloy.com/return-policy">Return Policy</a>
        </div>
    </footer>

    <!-- STICKY BAR -->
    <div class="sticky-bar" id="sticky">
        <div class="s-info">
            <div class="s-name">Niacinamide 10% + Zinc 1% · 30ml</div>
            <div class="s-price">
                ৳1,099
                <span
                    style="font-size: 12px; color: var(--gray2); text-decoration: line-through; font-weight: 400;">৳১,২০০</span>
            </div>
        </div>

        <a href="{{ route('buy.now', ['product' => $product->id, 'qty' => 1]) }}" class="buynow-btn s-cta"
            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
            data-price="{{ $product->effective_price }}">Order করুন ⚡</a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var KEY = "niacinamide_sale_end";
            var newEnd = new Date("2026-08-10T23:59:59").getTime();
            var stored = localStorage.getItem(KEY);
            if (!stored || isNaN(+stored) || +stored < newEnd) {
                localStorage.setItem(KEY, newEnd);
            }

            var end = +localStorage.getItem(KEY);

            function tick() {
                var diff = Math.max(0, end - Date.now());
                var d = Math.floor(diff / 86400000);
                var h = Math.floor((diff % 86400000) / 3600000);
                var m = Math.floor((diff % 3600000) / 60000);
                var s = Math.floor((diff % 60000) / 1000);
                const dd = document.getElementById("cd-d");
                const hh = document.getElementById("cd-h");
                const mm = document.getElementById("cd-m");
                const ss = document.getElementById("cd-s");
                if (!dd || !hh || !mm || !ss) return;
                dd.textContent = String(d).padStart(2, "0");
                hh.textContent = String(h).padStart(2, "0");
                mm.textContent = String(m).padStart(2, "0");
                ss.textContent = String(s).padStart(2, "0");
            }

            tick();
            setInterval(tick, 1000);
        });

        let qty = 1;

        const productId = {{ $product->id }};

        function updateQty() {
            document.getElementById('qty-num').textContent = qty;

            document.getElementById('buynow-btn').href =

                `/buy-now/${productId}/${qty}`;

        }

        document.getElementById('qty-down').addEventListener('click', function(e) {
            e.preventDefault();
            if (qty > 1) {
                qty--;
                updateQty();
            }
        });

        document.getElementById('qty-up').addEventListener('click', function(e) {
            e.preventDefault();
            if (qty < 10) {
                qty++;
                updateQty();
            }

        });
        updateQty();
        var hero = document.querySelector(".hero");
        var sticky = document.getElementById("sticky");
        var io = new IntersectionObserver(
            function(e) {
                sticky.classList.toggle("show", !e[0].isIntersecting);
            }, {
                threshold: 0
            },
        );
        io.observe(hero);

        document.querySelectorAll(".faq-q").forEach(function(btn) {
            btn.addEventListener("click", function() {
                var item = btn.parentElement;
                var open = item.classList.contains("open");
                document.querySelectorAll(".faq-item").forEach(function(i) {
                    i.classList.remove("open");
                });
                if (!open) item.classList.add("open");
            });
        });
        const product = {

            id: {{ $product->id }},

            name: @json($product->name),

            price: {{ $product->effective_price }}

        };

        function trackAddToCart(product, qty = 1) {

            if (window.fbTrack) {

                window.fbTrack('AddToCart', {

                    content_ids: [product.id],

                    content_name: product.name,

                    content_type: 'product',

                    num_items: qty,

                    value: product.price,

                    currency: 'BDT'

                });

            }

        }
        document.getElementById('buynow-btn').addEventListener('click', function() {
            trackAddToCart(product, qty);
        });

        @if ($pixelViewContent ?? false)
            document.addEventListener('DOMContentLoaded', function() {
                if (window.fbTrack) {
                    window.fbTrack('ViewContent', {
                        content_ids: ['{{ $product->id }}'],
                        content_name: '{{ addslashes($product->name) }}',
                        content_type: 'product',
                        value: {{ $product->effective_price }},
                        currency: 'BDT'
                    });
                }
            });
        @endif
    </script>
</body>

</html>
