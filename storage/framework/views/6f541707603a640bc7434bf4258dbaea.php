<?php $__env->startSection('title', 'Event Zones'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Event Zones</h1>
                    <p class="text-gray-600 mt-2">Manage seating areas and capacity zones for your events</p>
                </div>
                <a href="<?php echo e(route('eventZones.create')); ?>" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create New Zone
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <?php if(isset($eventZones) && $eventZones->count() > 0): ?>
            <!-- Event Zones Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $eventZones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900"><?php echo e($zone->name); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo e($zone->event->name ?? 'No Event'); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-users mr-2 text-blue-500"></i>
                                        <span class="text-sm">Capacity</span>
                                    </div>
                                    <span class="font-semibold text-lg"><?php echo e(number_format($zone->capacity)); ?></span>
                                </div>
                                
                                <?php if($zone->event): ?>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-calendar mr-2 text-green-500"></i>
                                            <span class="text-sm">Event Date</span>
                                        </div>
                                        <span class="font-semibold text-sm"><?php echo e(date('M d, Y', strtotime($zone->event->event_date))); ?></span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-clock mr-2 text-orange-500"></i>
                                            <span class="text-sm">Time</span>
                                        </div>
                                        <span class="font-semibold text-sm"><?php echo e($zone->event?->start_time->format('H:i')); ?> - <?php echo e($zone->event?->end_time->format('H:i')); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if(isset($zone->tickets_count)): ?>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-ticket-alt mr-2 text-purple-500"></i>
                                            <span class="text-sm">Tickets</span>
                                        </div>
                                        <span class="font-semibold"><?php echo e($zone->tickets_count); ?> types</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Capacity Progress Bar -->
                            <?php if(isset($zone->tickets_sold)): ?>
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Occupancy</span>
                                        <span class="text-sm font-semibold"><?php echo e($zone->tickets_sold); ?>/<?php echo e($zone->capacity); ?></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" 
                                             style="width: <?php echo e(min(100, ($zone->tickets_sold / $zone->capacity) * 100)); ?>%"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    Created <?php echo e($zone->created_at->diffForHumans()); ?>

                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('eventZones.show', $zone->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('eventZones.edit', $zone->id)); ?>" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('eventZones.destroy', $zone->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this zone?')">
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

            <!-- Summary Statistics -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-purple-600"><?php echo e($eventZones->count()); ?></div>
                    <div class="text-gray-600 mt-1">Total Zones</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600"><?php echo e(number_format($eventZones->sum('capacity'))); ?></div>
                    <div class="text-gray-600 mt-1">Total Capacity</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-green-600"><?php echo e(number_format($eventZones->avg('capacity'))); ?></div>
                    <div class="text-gray-600 mt-1">Avg. Capacity</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-orange-600"><?php echo e($eventZones->unique('event_id')->count()); ?></div>
                    <div class="text-gray-600 mt-1">Events</div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Event Zones Found</h3>
                <p class="text-gray-500 mb-6">Start by creating zones to organize seating and capacity for your events.</p>
                <a href="<?php echo e(route('eventZones.create')); ?>" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                    Create Your First Zone
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/eventZone/index.blade.php ENDPATH**/ ?>