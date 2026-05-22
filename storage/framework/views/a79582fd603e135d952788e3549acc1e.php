<?php
    $pixelId = \App\Models\Setting::get('meta_pixel_id');
    $mode = \App\Models\Setting::get('meta_pixel_mode', 'test');
    $testCode = \App\Models\Setting::get('meta_pixel_test_event_code');
    $pageView = \App\Models\Setting::get('meta_pixel_page_view', 'true') === 'true';
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pixelId): ?>
    <!-- Meta Pixel — <?php echo e(strtoupper($mode)); ?> MODE -->
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

        fbq('init', '<?php echo e($pixelId); ?>');

        <?php if($pageView): ?>
            <?php if($testCode && $mode === 'test'): ?>
                fbq('track', 'PageView', {}, { eventID: 'pv_<?php echo e(uniqid()); ?>', test_event_code: '<?php echo e($testCode); ?>' });
            <?php else: ?>
                fbq('track', 'PageView');
            <?php endif; ?>
        <?php endif; ?>

        /**
         * Global fbTrack() helper.
         * All pixel events go through this — never call fbq() directly in views.
         * In test mode, test_event_code is injected automatically.
         */
        window.fbTrack = function (eventName, data) {
            if (typeof fbq !== 'function') return;
            data = data || {};

            <?php if($testCode && $mode === 'test'): ?>
                // Test mode: pass test_event_code in the options (4th arg), NOT in data
                fbq('track', eventName, data, { test_event_code: '<?php echo e($testCode); ?>' });
            <?php else: ?>
                fbq('track', eventName, data);
            <?php endif; ?>
        };

        // Expose mode + pixel ID for debugging
        window._pixelMode = '<?php echo e($mode); ?>';
        window._pixelId = '<?php echo e($pixelId); ?>';
        <?php if($testCode && $mode === 'test'): ?>
            window._testCode = '<?php echo e($testCode); ?>';
            console.info('[Meta Pixel] TEST MODE — code: <?php echo e($testCode); ?> — events visible in Events Manager → Test Events');
        <?php endif; ?>
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php echo e($pixelId); ?>&ev=PageView&noscript=1" />
    </noscript>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/partials/meta-pixel.blade.php ENDPATH**/ ?>