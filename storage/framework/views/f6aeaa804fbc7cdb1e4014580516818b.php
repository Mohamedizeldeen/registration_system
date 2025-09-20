<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="<?php echo e(route('tickets.index')); ?>" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Ticket</h1>
                    <p class="text-gray-600 mt-2">Set up a new ticket type with pricing and availability</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Form Header -->
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-ticket-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Ticket Information</h2>
                        <p class="text-gray-600">Configure ticket details, pricing, and availability</p>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 m-6 rounded">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?php echo e(route('tickets.store')); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ticket Name -->

                    <div class="md:col-span-1">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Ticket Name *
                        </label>
                        <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., VIP Premium, General Admission">
                    </div>

                    <!-- Price -->
                    <div class="md:col-span-1">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="price" name="price" value="<?php echo e(old('price')); ?>" required min="0"
                                step="0.01"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- Event and Zone Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Event Selection -->
                    <div class="md:col-span-1">
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Event *
                        </label>
                        <select id="event_id" name="event_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select an event</option>
                            <?php if(isset($events)): ?>
                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($event->id); ?>" <?php echo e(old('event_id') == $event->id ? 'selected' : ''); ?>>
                                        <?php echo e($event->name); ?> - <?php echo e(date('M d, Y', strtotime($event->event_date))); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <!-- Sample options for demo -->
                                <option value="1">Tech Conference 2024 - Jan 15, 2024</option>
                                <option value="2">Music Festival Summer - Jul 20, 2024</option>
                                <option value="3">Business Summit - Mar 10, 2024</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Zone Selection -->
                    <div class="md:col-span-1">
                        <label for="event_zone_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>Zone *
                        </label>
                        <select id="event_zone_id" name="event_zone_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a zone</option>
                            <?php if(isset($eventZones)): ?>
                                <?php $__currentLoopData = $eventZones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($zone->id); ?>" <?php echo e(old('event_zone_id') == $zone->id ? 'selected' : ''); ?>>
                                        <?php echo e($zone->name); ?> (Capacity: <?php echo e(number_format($zone->capacity)); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <!-- Sample options for demo -->
                                <option value="1">VIP Section (Capacity: 100)</option>
                                <option value="2">General Admission (Capacity: 500)</option>
                                <option value="3">Balcony (Capacity: 150)</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Quantity and Availability -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Quantity -->
                    <div class="md:col-span-1">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-2 text-indigo-500"></i>Available Quantity *
                        </label>
                        <input type="number" id="quantity" name="quantity" value="<?php echo e(old('quantity')); ?>" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Number of tickets available">
                    </div>

                    <!-- Sales Start Date -->
                    <div class="md:col-span-1">
                        <label for="sale_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle mr-2 text-green-500"></i>Sale Start Date
                        </label>
                        <input type="datetime-local" id="sale_start_date" name="sale_start_date"
                            value="<?php echo e(old('sale_start_date')); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Sales End Date and Max Per Person -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Sales End Date -->
                    <div class="md:col-span-1">
                        <label for="sale_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stop-circle mr-2 text-red-500"></i>Sale End Date
                        </label>
                        <input type="datetime-local" id="sale_end_date" name="sale_end_date"
                            value="<?php echo e(old('sale_end_date')); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Max Per Person -->
                    <div class="md:col-span-1">
                        <label for="max_per_person" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-purple-500"></i>Max Per Person
                        </label>
                        <input type="number" id="max_per_person" name="max_per_person"
                            value="<?php echo e(old('max_per_person', 5)); ?>" min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Maximum tickets per person">
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-500"></i>Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Describe what's included with this ticket type..."><?php echo e(old('description')); ?></textarea>
                </div>


                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="<?php echo e(route('tickets.index')); ?>"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/ticket/create.blade.php ENDPATH**/ ?>