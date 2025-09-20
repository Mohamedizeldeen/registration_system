<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events - Event Registration</title>
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
                        <p class="text-sm text-gray-600">Register for upcoming events</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Title -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
            <p class="text-lg text-gray-600">Choose an event to see available tickets and register</p>
        </div>

        <!-- Events Grid -->
        <?php if($events->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                        <!-- Event Image Placeholder -->
                        <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-4xl"></i>
                        </div>
                        
                        <!-- Event Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($event->name); ?></h3>
                            <p class="text-gray-600 mb-4 line-clamp-3"><?php echo e($event->description ?? 'Join us for this exciting event!'); ?></p>
                            
                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    <span><?php echo e($event->event_date->format('M j, Y')); ?></span>
                                </div>
                                
                                <?php if($event->start_time): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                                        <span><?php echo e($event->start_time); ?></span>
                                        <?php if($event->end_time): ?>
                                            - <?php echo e($event->end_time); ?>

                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($event->location): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                        <span><?php echo e($event->location); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-building mr-2 text-blue-500"></i>
                                    <span><?php echo e($event->company->name); ?></span>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <?php echo e($event->event_date->diffForHumans()); ?>

                                </div>
                                <a href="<?php echo e(route('public.tickets', $event->id)); ?>" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center">
                                    View Tickets
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- No Events -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No Upcoming Events</h3>
                <p class="text-gray-600">There are currently no events available for registration.</p>
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
<?php /**PATH C:\laragon\www\registration_system\resources\views/public/events.blade.php ENDPATH**/ ?>