{{-- ── Cookie Consent Banner ──────────────────────────────────────────────── --}}
{{-- Stored in: resources/views/partials/cookie-banner.blade.php             --}}
{{-- Include once in layouts/shop.blade.php before </body>                   --}}

<div id="cookie-banner"
    style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9000;
           background:#fff;border-top:1px solid #e5e7eb;
           box-shadow:0 -4px 24px rgba(0,0,0,.10);
           padding:14px 16px;"
    role="dialog" aria-label="Cookie consent">
    <div style="max-width:900px;margin:0 auto;display:flex;align-items:center;
                gap:14px;flex-wrap:wrap;">

        {{-- Icon --}}
        <span style="font-size:24px;flex-shrink:0;">🍪</span>

        {{-- Text --}}
        <p style="flex:1;min-width:220px;font-size:13px;color:#374151;line-height:1.5;margin:0;">
            We use cookies to improve your experience, remember your cart, and analyse site traffic.
            By using <strong>{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}</strong> you agree to our
            <a href="{{ route('legal.privacy') }}"
                style="color:var(--teal);font-weight:600;text-decoration:underline;">Privacy Policy</a>
            and
            <a href="{{ route('legal.terms') }}"
                style="color:var(--teal);font-weight:600;text-decoration:underline;">Terms of Use</a>.
        </p>

        {{-- Buttons --}}
        <div style="display:flex;gap:8px;flex-shrink:0;flex-wrap:wrap;">
            <button id="cookie-reject"
                style="padding:8px 16px;font-size:12px;font-weight:600;border-radius:8px;
                       border:1.5px solid #d1d5db;background:#fff;color:#6b7280;cursor:pointer;">
                Necessary only
            </button>
            <button id="cookie-accept"
                style="padding:8px 18px;font-size:12px;font-weight:700;border-radius:8px;
                       border:none;background:var(--teal);color:#fff;cursor:pointer;">
                Accept all
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        var COOKIE_KEY = 'ousodhaloy_cookie_consent';
        var banner = document.getElementById('cookie-banner');

        function getConsent() {
            try {
                return JSON.parse(localStorage.getItem(COOKIE_KEY));
            } catch (e) {
                return null;
            }
        }

        function setConsent(val) {
            localStorage.setItem(COOKIE_KEY, JSON.stringify({
                value: val,
                ts: Date.now()
            }));
        }

        // Show banner if no prior consent
        if (!getConsent()) {
            banner.style.display = 'block';
        }

        document.getElementById('cookie-accept').addEventListener('click', function() {
            setConsent('all');
            banner.style.display = 'none';
            // Enable analytics / tracking pixels here if needed
        });

        document.getElementById('cookie-reject').addEventListener('click', function() {
            setConsent('necessary');
            banner.style.display = 'none';
            // Optionally disable non-essential cookies/pixels here
        });
    })();
</script>
