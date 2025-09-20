<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="<?php echo e(route('eventZones.index')); ?>" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Event Zone</h1>
                    <p class="text-gray-600 mt-2">Set up a new seating area or capacity zone for your event</p>
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
                        class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Zone Information</h2>
                        <p class="text-gray-600">Configure zone details and capacity</p>
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
            <form action="<?php echo e(route('eventZones.store')); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Zone Name -->
                    <div class="md:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-purple-500"></i>Zone Name *
                        </label>
                        <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="e.g., VIP Section, General Admission">
                    </div>

                    <!-- Capacity -->
                    <div class="md:col-span-1">
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-blue-500"></i>Capacity *
                        </label>
                        <input type="number" id="capacity" name="capacity" value="<?php echo e(old('capacity')); ?>" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Maximum number of attendees">
                    </div>
                </div>

                <!-- Event Selection -->
                <div class="mt-6">
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-green-500"></i>Associated Event *
                    </label>
                    <select id="event_id" name="event_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
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

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-orange-500"></i>Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Describe the zone location, amenities, or special features..."><?php echo e(old('description')); ?></textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="<?php echo e(route('eventZones.index')); ?>"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Create Zone
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/eventZone/create.blade.php ENDPATH**/ ?>