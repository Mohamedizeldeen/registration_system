<?php $__env->startSection('title', 'Payments'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payments</h1>
                <p class="text-gray-600">Manage event payments and transactions</p>
            </div>
            
            <?php if(auth()->user()->role === 'super_admin'): ?>
                <a href="<?php echo e(route('payments.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create Payment
                </a>
            <?php endif; ?>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-dollar-sign text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Total Revenue</p>
                        <p class="text-xl font-bold">$<?php echo e(number_format($payments->sum('amount'), 2)); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-credit-card text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Total Payments</p>
                        <p class="text-xl font-bold"><?php echo e($payments->count()); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-clock text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Pending</p>
                        <p class="text-xl font-bold"><?php echo e($payments->where('status', 'pending')->count()); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Failed</p>
                        <p class="text-xl font-bold"><?php echo e($payments->where('status', 'failed')->count()); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <?php if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin'): ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($payment->transaction_id ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($payment->attendee->full_name ?? 'N/A'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($payment->attendee->email ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($payment->attendee->event->name ?? 'N/A'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($payment->attendee->event->start_date?->format('M d, Y') ?? 'No date'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($payment->currency ?? 'USD'); ?> <?php echo e(number_format($payment->amount, 2)); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e(ucfirst($payment->payment_method ?? 'N/A')); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($payment->status === 'completed'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Completed
                                    </span>
                                <?php elseif($payment->status === 'pending'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                <?php elseif($payment->status === 'failed'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Failed
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-question mr-1"></i><?php echo e(ucfirst($payment->status ?? 'Unknown')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($payment->payment_date?->format('M d, Y') ?? $payment->created_at->format('M d, Y')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($payment->payment_date?->format('H:i') ?? $payment->created_at->format('H:i')); ?></div>
                            </td>
                            <?php if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('payments.show', $payment)); ?>" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <?php if(auth()->user()->role === 'super_admin'): ?>
                                            <a href="<?php echo e(route('payments.edit', $payment)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form method="POST" action="<?php echo e(route('payments.destroy', $payment)); ?>" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                <?php if(auth()->user()->role === 'super_admin'): ?>
                                    No payments found. <a href="<?php echo e(route('payments.create')); ?>" class="text-blue-600 hover:text-blue-800">Create the first payment</a>.
                                <?php else: ?>
                                    No payments found for your company events.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($payments->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($payments->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/payments/index.blade.php ENDPATH**/ ?>