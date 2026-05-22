@php
    $pixelId = \App\Models\Setting::get('meta_pixel_id');
    $mode = \App\Models\Setting::get('meta_pixel_mode', 'test');
    $testCode = \App\Models\Setting::get('meta_pixel_test_event_code');
    $pageView = \App\Models\Setting::get('meta_pixel_page_view', 'true') === 'true';
@endphp
@if($pixelId)
    <!-- Meta Pixel — {{ strtoupper($mode) }} MODE -->
    <script>
        // Standard Meta Pixel base code (unmodified — never wrap fbq itself)
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            }; if (!f._fbq) f._fbq = n;
            n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '{{ $pixelId }}');

        @if($pageView)
            @if($testCode && $mode === 'test')
                fbq('track', 'PageView', {}, { eventID: 'pv_{{ uniqid() }}', test_event_code: '{{ $testCode }}' });
            @else
                fbq('track', 'PageView');
            @endif
        @endif

        /**
         * Global fbTrack() helper.
         * All pixel events go through this — never call fbq() directly in views.
         * In test mode, test_event_code is injected automatically.
         */
        window.fbTrack = function (eventName, data) {
            if (typeof fbq !== 'function') return;
            data = data || {};

            @if($testCode && $mode === 'test')
                // Test mode: pass test_event_code in the options (4th arg), NOT in data
                fbq('track', eventName, data, { test_event_code: '{{ $testCode }}' });
            @else
                fbq('track', eventName, data);
            @endif
        };

        // Expose mode + pixel ID for debugging
        window._pixelMode = '{{ $mode }}';
        window._pixelId = '{{ $pixelId }}';
        @if($testCode && $mode === 'test')
            window._testCode = '{{ $testCode }}';
            console.info('[Meta Pixel] TEST MODE — code: {{ $testCode }} — events visible in Events Manager → Test Events');
        @endif
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1" />
    </noscript>
@endif