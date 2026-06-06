<?php $__env->startSection('title', 'Privacy Policy – ' . \App\Models\Setting::get('site_name')); ?>
<?php $__env->startSection('meta_description', 'Read the privacy policy for ' . \App\Models\Setting::get('site_name') . '. Learn how we
    collect, use, and protect your personal data.'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $site = \App\Models\Setting::get('site_name', 'Ousodhaloy');
        $email = \App\Models\Setting::get('site_email', 'info@ousodhaloy.com');
    ?>
    <div class="max-w-3xl mx-auto px-4 py-8">

        
        <nav class="text-xs text-gray-400 mb-6 flex items-center gap-1.5">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-teal-600">Home</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-gray-600">Privacy Policy</span>
        </nav>

        <div class="bg-white rounded-2xl border shadow-sm p-6 sm:p-8">
            <h1 class="text-2xl font-black text-gray-900 mb-1">Privacy Policy</h1>
            <p class="text-xs text-gray-400 mb-6">Last updated: June 2025</p>

            <div class="prose max-w-none">

                <p><?php echo e($site); ?> ("we", "us", or "our") is committed to protecting your personal information. This
                    policy explains what data we collect, why we collect it, and how it is used when you visit
                    <strong>ousodhaloy.com</strong>.</p>

                <h2>1. Information We Collect</h2>
                <p>We collect information you provide directly when you register, place an order, or contact us — including
                    your name, phone number, email address, delivery address, and payment method. We also collect data
                    automatically such as your IP address, browser type, pages visited, and device information via cookies
                    and similar technologies.</p>

                <h2>2. How We Use Your Information</h2>
                <p>We use your data to process and deliver your orders, send order confirmations and updates via SMS or
                    email, respond to your queries, improve our website and services, comply with legal obligations, and
                    detect and prevent fraud.</p>

                <h2>3. Sharing of Information</h2>
                <p>We do not sell your personal data to third parties. We may share your information with trusted service
                    providers who help us operate our business — including courier partners (Pathao, Steadfast), payment
                    processors, and SMS gateway providers — strictly to fulfil your orders. These parties are contractually
                    bound to keep your data secure.</p>

                <h2>4. Prescription Data</h2>
                <p>If you upload a prescription, it is used solely to verify your order for prescription-required medicines.
                    Prescription images are stored securely and are only accessible to our licensed pharmacists. We do not
                    share prescription data with third parties other than as required by law.</p>

                <h2>5. Cookies</h2>
                <p>We use cookies to keep your session active, remember your cart, and understand how visitors use our site.
                    You may choose "Necessary only" in our cookie consent banner to limit non-essential cookies. Disabling
                    cookies may affect your ability to use certain features such as the shopping cart.</p>

                <h2>6. Data Retention</h2>
                <p>We retain your order history and account data for as long as your account remains active, or as required
                    by law. You may request deletion of your account by contacting us — see Section 9.</p>

                <h2>7. Security</h2>
                <p>We use SSL encryption for all data transmitted on this website. Payment information is processed by
                    certified payment gateways and is never stored on our servers. We regularly review our security
                    practices to protect against unauthorised access.</p>

                <h2>8. Children's Privacy</h2>
                <p>Our services are not directed to children under 13. We do not knowingly collect personal data from
                    children. If you believe a child has provided us with personal information, please contact us and we
                    will delete it promptly.</p>

                <h2>9. Your Rights</h2>
                <p>You have the right to access, correct, or delete your personal data. To exercise these rights or ask
                    questions about this policy, contact us at <a href="mailto:<?php echo e($email); ?>"><?php echo e($email); ?></a>.
                </p>

                <h2>10. Changes to This Policy</h2>
                <p>We may update this policy from time to time. Any significant changes will be notified on our website.
                    Continued use of the site after changes constitutes your acceptance of the revised policy.</p>

            </div>

            <div class="mt-8 pt-6 border-t flex flex-wrap gap-3">
                <a href="<?php echo e(route('legal.terms')); ?>" class="btn-secondary btn-sm">Terms of Use →</a>
                <a href="<?php echo e(route('legal.returns')); ?>" class="btn-secondary btn-sm">Return Policy →</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/legal/privacy.blade.php ENDPATH**/ ?>