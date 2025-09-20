<?php $__env->startSection('title', 'Coupons'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Discount Coupons</h1>
                    <p class="text-gray-600 mt-2">Manage promotional coupons and discounts</p>
                </div>
                <a href="<?php echo e(route('coupons.create')); ?>" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create New Coupon
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <?php if(isset($coupons) && $coupons->count() > 0): ?>
            <!-- Coupons Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    <?php echo e($coupon->code); ?>

                                </div>
                                <span class="text-2xl font-bold text-green-600"><?php echo e($coupon->discount); ?>%</span>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-ticket-alt mr-2 text-blue-500"></i>
                                    <span><?php echo e($coupon->ticket->name ?? 'General'); ?></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-red-500"></i>
                                    <span>Expires: <?php echo e(date('M d, Y', strtotime($coupon->expiry_date))); ?></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users mr-2 text-purple-500"></i>
                                    <span>Used: <?php echo e($coupon->usage_count); ?>/<?php echo e($coupon->max_usage); ?></span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    <?php if($coupon->expiry_date < now()): ?>
                                        <span class="text-red-600 font-semibold">Expired</span>
                                    <?php elseif($coupon->usage_count >= $coupon->max_usage): ?>
                                        <span class="text-orange-600 font-semibold">Limit Reached</span>
                                    <?php else: ?>
                                        <span class="text-green-600 font-semibold">Active</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('coupons.show', $coupon->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('coupons.edit', $coupon->id)); ?>" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('coupons.destroy', $coupon->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Coupons Found</h3>
                <p class="text-gray-500 mb-6">Start creating discount coupons to boost your event sales.</p>
                <a href="<?php echo e(route('coupons.create')); ?>" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                    Create Your First Coupon
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/coupon/index.blade.php ENDPATH**/ ?>