<?php $__env->startSection('title', 'Company Events'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Events</h1>
                    <p class="text-gray-600">Manage events for <?php echo e(auth()->user()->company->name ?? 'your company'); ?></p>
                </div>

                <a href="<?php echo e(route('events.create')); ?>"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center hidden">
                    <i class="fas fa-plus mr-2"></i>
                    Create Event
                </a>
            </div>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <!-- Event Image Placeholder -->
                        <div class="h-48 rounded-t-lg overflow-hidden">
                            <img src="<?php echo e(asset($event->banner_url)); ?>" alt="<?php echo e($event->name); ?>"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Event Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($event->name); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo e(Str::limit($event->description, 100)); ?></p>

                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    <span><?php echo e($event->event_date?->format('M d, Y')); ?> - <?php echo e($event->event_end_date); ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    <span><?php echo e($event->location ?? 'Location TBD'); ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2 text-green-500"></i>
                                    <span><?php echo e($event->attendees_count ?? $event->attendees->count()); ?> attendees</span>
                                </div>
                            </div>

                            <!-- Event Status -->
                            <div class="mb-4">
                                <?php if($event->start_date && $event->start_date->isFuture()): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-clock mr-1"></i>Upcoming
                                    </span>
                                <?php elseif($event->start_date && $event->end_date && $event->start_date->isPast() && $event->end_date->isFuture()): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-play mr-1"></i>Live
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-check mr-1"></i>Completed
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('events.show', $event)); ?>"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                <a href="<?php echo e(route('events.edit', $event)); ?>"
                                    class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
                            <p class="text-gray-600 mb-4">Get started by creating your first event for your company.</p>
                            <a href="<?php echo e(route('events.create')); ?>"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Create Event
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($events->hasPages()): ?>
                <div class="mt-8">
                    <?php echo e($events->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/company-admin/events.blade.php ENDPATH**/ ?>