<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($event->name); ?> - Ticket Selection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Event Registration</h1>
                        <p class="text-sm text-gray-600">Select your ticket</p>
                    </div>
                </div>
                <a href="<?php echo e(route('public.events')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Events
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Event Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e($event->name); ?></h2>
                    <div class="space-y-2">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            <span><?php echo e($event->event_date->format('M j, Y')); ?></span>
                        </div>
                        <?php if($event->start_time): ?>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                <span><?php echo e($event->start_time); ?></span>
                                <?php if($event->end_time): ?> - <?php echo e($event->end_time); ?> <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($event->location): ?>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                <span><?php echo e($event->location); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            <span><?php echo e($event->company->name); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($event->description): ?>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-gray-700"><?php echo e($event->description); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Zone Access Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Zone Access Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <h4 class="font-medium text-gray-900 mb-2"><?php echo e($zone->name); ?></h4>
                        <?php if($zone->description): ?>
                            <p class="text-sm text-gray-600 mb-2"><?php echo e($zone->description); ?></p>
                        <?php endif; ?>
                        <div class="text-xs text-blue-600">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Zone Access Level
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Important:</strong> Each ticket type gives you access to specific zones. Please check which zones are included with your selected ticket below.
                </p>
            </div>
        </div>

        <!-- Tickets by Zone -->
        <div class="space-y-8">
            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($ticketsByZone[$zone->id]) && $ticketsByZone[$zone->id]->count() > 0): ?>
                    <div class="bg-white rounded-lg shadow-sm border">
                        <!-- Zone Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b">
                            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map-marker-alt mr-3 text-blue-600"></i>
                                <?php echo e($zone->name); ?>

                            </h3>
                            <?php if($zone->description): ?>
                                <p class="text-gray-600 mt-1"><?php echo e($zone->description); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Tickets in this Zone -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php $__currentLoopData = $ticketsByZone[$zone->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition duration-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <h4 class="font-semibold text-gray-900"><?php echo e($ticket->name); ?></h4>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-blue-600">
                                                    <?php echo e($ticket->currency->symbol ?? '$'); ?><?php echo e(number_format($ticket->price, 2)); ?>

                                                </div>
                                                <div class="text-xs text-gray-500"><?php echo e($ticket->currency->code ?? 'USD'); ?></div>
                                            </div>
                                        </div>

                                        <?php if($ticket->info): ?>
                                            <p class="text-sm text-gray-600 mb-4"><?php echo e($ticket->info); ?></p>
                                        <?php endif; ?>

                                        <!-- Zone Access Badge -->
                                        <div class="mb-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>
                                                Access to <?php echo e($zone->name); ?>

                                            </span>
                                        </div>

                                        <!-- Quantity Available -->
                                        <?php if($ticket->quantity > 0): ?>
                                            <div class="text-sm text-gray-600 mb-4">
                                                <i class="fas fa-ticket-alt mr-1"></i>
                                                <?php echo e($ticket->quantity); ?> tickets available
                                            </div>
                                        <?php endif; ?>

                                        <!-- Register Button -->
                                        <a href="<?php echo e(route('public.form', $ticket->id)); ?>" 
                                           class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition duration-200 block">
                                            Select This Ticket
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($ticketsByZone->isEmpty()): ?>
            <!-- No Tickets Available -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ticket-alt text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Tickets Available</h3>
                <p class="text-gray-600">There are currently no tickets available for this event.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; 2025 Event Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\laragon\www\registration_system\resources\views/public/tickets.blade.php ENDPATH**/ ?>