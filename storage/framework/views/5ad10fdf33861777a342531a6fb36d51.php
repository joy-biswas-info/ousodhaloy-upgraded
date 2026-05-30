        
        <div class="bg-white rounded-2xl border mb-5 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-black text-gray-800 text-lg">
                    Customer Reviews
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->rating_count > 0): ?>
                        <span class="text-sm font-normal text-gray-400 ml-1">(<?php echo e($product->rating_count); ?>)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->rating_count > 0): ?>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-black" style="color:var(--teal)"><?php echo e($product->average_rating); ?></span>
                        <div class="flex flex-col gap-0.5">
                            <div class="flex gap-0.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                    <i
                                        class="fas fa-star text-xs <?php echo e($i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-200'); ?>"></i>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-400">out of 5</span>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border-b border-teal-50 last:border-0 py-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                            style="background:var(--teal)">
                            <?php echo e(strtoupper(substr($review->user->name, 0, 1))); ?>

                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div>
                                    <span class="text-sm font-bold text-gray-800"><?php echo e($review->user->name); ?></span>
                                    <div class="flex gap-0.5 mt-0.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                            <i
                                                class="fas fa-star text-[10px] <?php echo e($i <= $review->rating ? 'text-amber-400' : 'text-gray-200'); ?>"></i>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400"><?php echo e($review->created_at->format('d M Y')); ?></span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->title): ?>
                                <p class="text-sm font-semibold text-gray-700 mt-1"><?php echo e($review->title); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->body): ?>
                                <p class="text-sm text-gray-600 mt-1 leading-relaxed"><?php echo e($review->body); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-8 text-center">
                    <div class="text-4xl mb-3">⭐</div>
                    <p class="text-sm font-semibold text-gray-700">No reviews yet</p>
                    <p class="text-xs text-gray-400 mt-1">Be the first to review this product</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="pt-5 mt-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <h3 class="font-bold text-gray-800 text-sm mb-4">Write a Review</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('review_success')): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-4">
                            <i class="fas fa-check-circle mr-1.5"></i><?php echo e(session('review_success')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <form method="POST" action="<?php echo e(route('shop.product.review', $product->slug)); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-2">Your Rating *</label>
                            <div class="flex gap-1" x-data="{ rating: <?php echo e(old('rating', 0)); ?>, hover: 0 }">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($s = 1; $s <= 5; $s++): ?>
                                    <button type="button" @click="rating = <?php echo e($s); ?>"
                                        @mouseenter="hover = <?php echo e($s); ?>" @mouseleave="hover = 0"
                                        class="text-2xl transition-colors leading-none focus:outline-none">
                                        <i class="fas fa-star"
                                            :class="(hover || rating) >= <?php echo e($s); ?> ? 'text-amber-400' :
                                                'text-gray-200'"></i>
                                    </button>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">Review Title</label>
                            <input type="text" name="title" value="<?php echo e(old('title')); ?>"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-50"
                                placeholder="Summarise your experience">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">Your Review *</label>
                            <textarea name="body" rows="3"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-50 resize-none"
                                placeholder="Share your experience with this product…" required><?php echo e(old('body')); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <button type="submit"
                            class="py-2.5 px-6 rounded-xl text-white text-sm font-bold transition-all active:scale-95"
                            style="background:var(--teal)">
                            <i class="fas fa-paper-plane mr-1.5"></i>Submit Review
                        </button>
                    </form>
                <?php else: ?>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-600 mb-3">Sign in to leave a review</p>
                        <a href="<?php echo e(route('auth.login')); ?>"
                            class="inline-flex items-center gap-2 py-2 px-5 rounded-xl text-white text-sm font-bold"
                            style="background:var(--teal)">
                            <i class="fas fa-sign-in-alt"></i> Login to Review
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        </div>
<?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/partials/review-card.blade.php ENDPATH**/ ?>