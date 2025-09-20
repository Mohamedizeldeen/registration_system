<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="<?php echo e(route('events.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
                    <p class="text-gray-600 mt-2">Update the details for "<?php echo e($event->name); ?>"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Info Section -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-amber-900">Event Editing Guidelines</h3>
                    <div class="mt-2 text-amber-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Changes to event date/time may affect existing bookings</li>
                            <li>Venue changes should be communicated to attendees</li>
                            <li>Review all details before saving changes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Event Details</h2>
            </div>

            <form class="p-6 space-y-6" method="POST" action="<?php echo e(route('events.update', $event->id)); ?>"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Event Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Event Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name', $event->name)); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <?php $__errorArgs = ['name'];
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

                <!-- Event Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required><?php echo e(old('description', $event->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
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

                <!-- Event Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Event Type *</label>
                    <select id="type" name="type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="">Select Event Type</option>
                        <option value="hybrid" <?php echo e(old('type', $event->type) == 'hybrid' ? 'selected' : ''); ?>>Hybrid</option>
                        <option value="virtual" <?php echo e(old('type', $event->type) == 'virtual' ? 'selected' : ''); ?>>Virtual
                        </option>
                        <option value="onsite" <?php echo e(old('type', $event->type) == 'onsite' ? 'selected' : ''); ?>>Onsite</option>
                    </select>
                    <?php $__errorArgs = ['type'];
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

                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Event Logo</label>
                    <?php if($event->logo): ?>
                        <div class="mb-3">
                            <img src="<?php echo e(asset('storage/logos/' . $event->logo)); ?>" alt="Current Logo"
                                class="h-16 w-16 object-cover rounded-md">
                            <p class="text-sm text-gray-500 mt-1">Current logo</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="logo" name="logo" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Upload a new logo to replace the current one (optional)</p>
                    <?php $__errorArgs = ['logo'];
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

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                        <input type="date" id="event_date" name="event_date"
                            value="<?php echo e(old('event_date', $event->event_date)); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <?php $__errorArgs = ['event_date'];
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
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                        <input type="time" id="start_time" name="start_time"
                            value="<?php echo e(old('start_time', $event->start_time)); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <?php $__errorArgs = ['start_time'];
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
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                        <input type="time" id="end_time" name="end_time" value="<?php echo e(old('end_time', $event->end_time)); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <?php $__errorArgs = ['end_time'];
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
                </div>

                <!-- Banner URL -->
                <div>
                    <label for="banner_url" class="block text-sm font-medium text-gray-700 mb-2">Banner URL</label>
                    <input type="url" id="banner_url" name="banner_url" value="<?php echo e(old('banner_url', $event->banner_url)); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter banner URL">
                    <?php $__errorArgs = ['banner_url'];
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

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email', $event->email)); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter email">
                        <?php $__errorArgs = ['email'];
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
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone', $event->phone)); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter phone number">
                        <?php $__errorArgs = ['phone'];
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
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" id="location" name="location" value="<?php echo e(old('location', $event->location)); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter event location">
                    <?php $__errorArgs = ['location'];
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

                <!-- Social Media Links -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Social Media Links</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Facebook -->
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-600 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    Facebook
                                </div>
                            </label>
                            <input type="url" id="facebook" name="facebook" value="<?php echo e(old('facebook', $event->facebook)); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="https://facebook.com/your-page">
                            <?php $__errorArgs = ['facebook'];
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

                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-600 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                    Instagram
                                </div>
                            </label>
                            <input type="url" id="instagram" name="instagram"
                                value="<?php echo e(old('instagram', $event->instagram)); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="https://instagram.com/your-account">
                            <?php $__errorArgs = ['instagram'];
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

                        <!-- LinkedIn -->
                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-600 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                    </svg>
                                    LinkedIn
                                </div>
                            </label>
                            <input type="url" id="linkedin" name="linkedin" value="<?php echo e(old('linkedin', $event->linkedin)); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="https://linkedin.com/company/your-company">
                            <?php $__errorArgs = ['linkedin'];
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

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-600 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9">
                                        </path>
                                    </svg>
                                    Website
                                </div>
                            </label>
                            <input type="url" id="website" name="website" value="<?php echo e(old('website', $event->website)); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="https://your-website.com">
                            <?php $__errorArgs = ['website'];
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
                    </div>
                </div>

                <!-- Event Stats (Read-only) -->
                <?php if($event->tickets->count() > 0 || $event->attendees->count() > 0): ?>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Event Statistics</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <p class="text-2xl font-bold text-blue-600"><?php echo e($event->event_zones_count ?? 0); ?></p>
                                <p class="text-sm text-gray-600">Zones</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-green-600"><?php echo e($event->tickets_count ?? 0); ?></p>
                                <p class="text-sm text-gray-600">Ticket Types</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-purple-600"><?php echo e($event->attendees_count ?? 0); ?></p>
                                <p class="text-sm text-gray-600">Attendees</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-orange-600">
                                    $<?php echo e(number_format($event->tickets->sum('price') ?? 0, 2)); ?></p>
                                <p class="text-sm text-gray-600">Total Value</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="<?php echo e(route('events.show', $event->id)); ?>"
                        class="px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition duration-200">
                        <i class="fas fa-eye mr-2"></i>View Event
                    </a>
                    <div class="flex space-x-3">
                        <a href="<?php echo e(route('events.index')); ?>"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/events/edit.blade.php ENDPATH**/ ?>