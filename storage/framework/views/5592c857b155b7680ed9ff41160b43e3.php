<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="<?php echo e(route('coupons.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Coupon</h1>
                    <p class="text-gray-600 mt-2">Set up a new discount coupon for your events</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="<?php echo e(route('coupons.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coupon Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2"></i>Coupon Code
                        </label>
                        <input type="text" id="code" name="code" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="e.g., SAVE20, EARLY50"
                            value="<?php echo e(old('code')); ?>">
                        <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Discount Percentage -->
                    <div>
                        <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percent mr-2"></i>Discount Percentage
                        </label>
                        <input type="number" id="discount" name="discount" required min="1" max="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="e.g., 20"
                            value="<?php echo e(old('discount')); ?>">
                        <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Ticket -->
                    <div>
                        <label for="ticket_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-ticket-alt mr-2"></i>Applicable Ticket
                        </label>
                        <select id="ticket_id" name="ticket_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select a ticket</option>
                            <?php if(isset($tickets)): ?>
                                <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ticket->id); ?>" <?php echo e(old('ticket_id') == $ticket->id ? 'selected' : ''); ?>>
                                        <?php echo e($ticket->name); ?> - $<?php echo e($ticket->price); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['ticket_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Expiry Date
                        </label>
                        <input type="date" id="expiry_date" name="expiry_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            value="<?php echo e(old('expiry_date')); ?>"
                            min="<?php echo e(date('Y-m-d')); ?>">
                        <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Max Usage -->
                    <div>
                        <label for="max_usage" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2"></i>Maximum Usage
                        </label>
                        <input type="number" id="max_usage" name="max_usage" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="e.g., 100"
                            value="<?php echo e(old('max_usage', 1)); ?>">
                        <?php $__errorArgs = ['max_usage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Usage Count (Hidden field, default 0) -->
                    <input type="hidden" name="usage_count" value="0">
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="<?php echo e(route('coupons.index')); ?>" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>Create Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/coupon/create.blade.php ENDPATH**/ ?>