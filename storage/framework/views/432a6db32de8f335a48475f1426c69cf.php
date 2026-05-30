<?php $__env->startSection('page-title', 'Product Reviews'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="grid grid-cols-3 gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['Total Reviews',    \App\Models\ProductReview::count(),                            'blue',   'star'],
            ['Pending Approval', \App\Models\ProductReview::where('is_approved',false)->count(),'yellow', 'clock'],
            ['Approved',         \App\Models\ProductReview::where('is_approved',true)->count(), 'green',  'check-circle'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-<?php echo e($color); ?>-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-<?php echo e($icon); ?> text-<?php echo e($color); ?>-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium"><?php echo e($label); ?></p>
                <p class="text-xl font-black text-gray-800"><?php echo e($value); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-2 space-y-4">

            
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                    class="form-input w-48" placeholder="Search product or customer…">
                <select name="status" class="form-select w-36">
                    <option value="">All Status</option>
                    <option value="pending"  <?php if(request('status')==='pending'): echo 'selected'; endif; ?>>Pending</option>
                    <option value="approved" <?php if(request('status')==='approved'): echo 'selected'; endif; ?>>Approved</option>
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
                <a href="<?php echo e(route('admin.reviews')); ?>" class="btn-outline btn-sm">Reset</a>
            </form>

            
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr x-data="{ editing: false }">
                                <td>
                                    <a href="<?php echo e(route('shop.product', $review->product->slug)); ?>"
                                        target="_blank"
                                        class="font-semibold text-sm text-gray-800 hover:text-teal-700">
                                        <?php echo e(Str::limit($review->product->name, 28)); ?>

                                    </a>
                                </td>
                                <td>
                                    <p class="font-semibold text-sm"><?php echo e($review->user->name); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($review->user->phone); ?></p>
                                </td>
                                <td>
                                    <div class="flex gap-0.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1; $i<=5; $i++): ?>
                                        <i class="fas fa-star text-xs <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-200'); ?>"></i>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <span class="text-xs text-gray-500"><?php echo e($review->rating); ?>/5</span>
                                </td>
                                <td class="max-w-xs">
                                    
                                    <div x-show="!editing">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->title): ?>
                                        <p class="font-semibold text-sm text-gray-800"><?php echo e($review->title); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->body): ?>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2"><?php echo e($review->body); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    
                                    <form method="POST" action="<?php echo e(route('admin.reviews.update', $review)); ?>"
                                        x-show="editing" x-cloak class="space-y-2 py-1">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <div class="flex gap-0.5" x-data="{ r: <?php echo e($review->rating); ?> }">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1; $i<=5; $i++): ?>
                                            <button type="button" @click="r=<?php echo e($i); ?>"
                                                class="text-lg leading-none focus:outline-none">
                                                <i class="fas fa-star" :class="r >= <?php echo e($i); ?> ? 'text-yellow-400' : 'text-gray-200'"></i>
                                            </button>
                                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <input type="hidden" name="rating" :value="r">
                                        </div>
                                        <input type="text" name="title" value="<?php echo e($review->title); ?>"
                                            class="form-input py-1 text-xs w-full" placeholder="Title">
                                        <textarea name="body" rows="2"
                                            class="form-input py-1 text-xs w-full resize-none"
                                            placeholder="Review body" required><?php echo e($review->body); ?></textarea>
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" name="is_approved" value="1"
                                                <?php echo e($review->is_approved ? 'checked' : ''); ?>

                                                class="accent-teal-600">
                                            <span class="text-xs text-gray-600">Approved</span>
                                        </label>
                                        <div class="flex gap-1.5">
                                            <button type="submit" class="btn-primary btn-sm text-xs flex-1">Save</button>
                                            <button type="button" @click="editing=false" class="btn-outline btn-sm text-xs flex-1">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <span class="text-xs font-semibold <?php echo e($review->is_approved ? 'text-green-600' : 'text-yellow-600'); ?>">
                                        <?php echo e($review->is_approved ? '✅ Approved' : '⏳ Pending'); ?>

                                    </span>
                                </td>
                                <td class="text-xs text-gray-400 whitespace-nowrap"><?php echo e($review->created_at->format('d M Y')); ?></td>
                                <td>
                                    <div class="flex gap-1.5 flex-wrap" x-show="!editing">
                                        <button type="button" @click="editing=true"
                                            class="btn-secondary btn-sm text-xs">
                                            <i class="fas fa-edit text-[10px]"></i>
                                        </button>
                                        <form method="POST" action="<?php echo e(route('admin.reviews.approve', $review)); ?>" class="inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button type="submit"
                                                class="<?php echo e($review->is_approved ? 'btn-outline' : 'btn-secondary'); ?> btn-sm text-xs">
                                                <?php echo e($review->is_approved ? 'Hide' : 'Approve'); ?>

                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $review)); ?>"
                                            class="inline" onsubmit="return confirm('Delete this review?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-danger btn-sm text-xs">Del</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">No reviews yet</td>
                            </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4"><?php echo e($reviews->links()); ?></div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border p-5 h-fit">
            <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Review</h3>
            <form method="POST" action="<?php echo e(route('admin.reviews.store')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="form-label">Product *</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">Select product…</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Product::active()->orderBy('name')->get(['id','name']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>" <?php if(old('product_id')==$p->id): echo 'selected'; endif; ?>><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="form-label">Customer *</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select customer…</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\User::where('role','customer')->orderBy('name')->get(['id','name','phone']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if(old('user_id')==$u->id): echo 'selected'; endif; ?>>
                            <?php echo e($u->name); ?> <?php echo e($u->phone ? '('.$u->phone.')' : ''); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="form-label">Rating *</label>
                    <div class="flex gap-1" x-data="{ rating: <?php echo e(old('rating', 5)); ?> }">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1; $i<=5; $i++): ?>
                        <button type="button" @click="rating=<?php echo e($i); ?>"
                            class="text-2xl leading-none focus:outline-none transition-colors">
                            <i class="fas fa-star" :class="rating >= <?php echo e($i); ?> ? 'text-yellow-400' : 'text-gray-200'"></i>
                        </button>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input type="hidden" name="rating" :value="rating">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="<?php echo e(old('title')); ?>"
                        class="form-input" placeholder="e.g. Great product!">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="form-label">Review Body *</label>
                    <textarea name="body" rows="3" class="form-input resize-none"
                        placeholder="Write the review content…" required><?php echo e(old('body')); ?></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_approved" value="1" checked class="accent-teal-600">
                    <span class="text-sm text-gray-700">Approve immediately</span>
                </label>

                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-plus mr-1.5"></i>Add Review
                </button>
            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/reviews/index.blade.php ENDPATH**/ ?>