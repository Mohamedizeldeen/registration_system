<?php $__env->startSection('title', 'Company Admin Dashboard'); ?>

<?php $__env->startPush('head-scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main Dashboard Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, <?php echo e(auth()->user()->name); ?>!</h2>
            <p class="text-gray-600">Company Admin Dashboard - <?php echo e(auth()->user()->name); ?></p>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Events -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Company Events</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($totalEvents)); ?></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Attendees -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Attendees</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($totalAttendees)); ?></p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">$<?php echo e(number_format($totalRevenue, 2)); ?></p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Zones -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Event Zones</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($totalZones)); ?></p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Top Performing Events</h3>
            </div>
            
            <?php $__empty_1 = true; $__currentLoopData = $topEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-gray-900"><?php echo e($event->name); ?></h4>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($event->description, 80)); ?></p>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <?php echo e($event->attendees_count); ?> attendees
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php if($event->type === 'hybrid'): ?> bg-blue-100 text-blue-800
                                <?php elseif($event->type === 'virtual'): ?> bg-green-100 text-green-800  
                                <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                <?php echo e(ucfirst($event->type)); ?>

                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>No events with attendees yet</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                <a href="<?php echo e(route('company-admin.payments')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Payments</a>
            </div>
            
            <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900"><?php echo e($payment->attendee->user->name ?? 'Unknown'); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($payment->attendee->event->name ?? 'Unknown Event'); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">$<?php echo e(number_format($payment->amount, 2)); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($payment->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <p>No recent payments</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Company Events</h3>
                <a href="<?php echo e(route('company-admin.events')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Events</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Event Name</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Type</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Date</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Location</th>
                            <th class="text-right py-3 text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-100">
                                <td class="py-4">
                                    <div class="font-medium text-gray-900"><?php echo e($event->name); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e(Str::limit($event->description, 50)); ?></div>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php if($event->type === 'hybrid'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($event->type === 'virtual'): ?> bg-green-100 text-green-800  
                                        <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($event->type)); ?>

                                    </span>
                                </td>
                                <td class="py-4 text-gray-900">
                                    <?php echo e(\Carbon\Carbon::parse($event->event_date)->format('M d, Y')); ?>

                                </td>
                                <td class="py-4 text-gray-900"><?php echo e($event->location ?? 'TBD'); ?></td>
                                <td class="py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="<?php echo e(route('events.show', $event)); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</a>
                                        <span class="text-gray-300">|</span>
                                        <button onclick="copyRegistrationLink('<?php echo e(route('public.tickets', $event->id)); ?>')" 
                                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Share Link
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">No events found for your company</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Manage Events</h4>
                <p class="text-gray-600 mb-4">View and manage your company's events.</p>
                <a href="<?php echo e(route('company-admin.events')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Manage Events
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Create Tickets</h4>
                <p class="text-gray-600 mb-4">Create tickets for your company's events.</p>
                <div class="space-y-2">
                    <a href="<?php echo e(route('tickets.create')); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 w-full justify-center">
                        Create Ticket
                    </a>
                    <a href="<?php echo e(route('tickets.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 w-full justify-center">
                        View All Tickets
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Event Zones</h4>
                <p class="text-gray-600 mb-4">Create and manage zones for your events.</p>
                <div class="space-y-2">
                    <a href="<?php echo e(route('eventZones.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 w-full justify-center">
                        Create Zone
                    </a>
                    <a href="<?php echo e(route('eventZones.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 w-full justify-center">
                        View All Zones
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">View Attendees</h4>
                <p class="text-gray-600 mb-4">Check attendee registrations and manage check-ins.</p>
                <a href="<?php echo e(route('company-admin.attendees')); ?>" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                    View Attendees
                </a>
            </div>
        </div>

        <!-- QR Code Testing Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-qrcode text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">QR Code Testing</h3>
                    <p class="text-sm text-gray-600">Test the new QR code ticket functionality</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="<?php echo e(route('company-admin.test-qr')); ?>" target="_blank" class="bg-white border border-blue-200 rounded-lg p-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-eye text-blue-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">View QR Code</div>
                            <div class="text-sm text-gray-600">See generated QR code</div>
                        </div>
                    </div>
                </a>
                
                <a href="<?php echo e(route('company-admin.test-pdf')); ?>" target="_blank" class="bg-white border border-blue-200 rounded-lg p-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-red-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">Test PDF Ticket</div>
                            <div class="text-sm text-gray-600">Download sample ticket</div>
                        </div>
                    </div>
                </a>
                
                <a href="<?php echo e(route('company-admin.print-tickets.form')); ?>" class="bg-white border border-blue-200 rounded-lg p-4 hover:bg-blue-50 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-print text-green-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">Print Tickets</div>
                            <div class="text-sm text-gray-600">Generate real tickets</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- Copy Registration Link Script -->
    <script>
        function copyRegistrationLink(url) {
            // Create a temporary textarea element
            const tempTextarea = document.createElement('textarea');
            tempTextarea.value = url;
            document.body.appendChild(tempTextarea);
            
            // Select and copy the text
            tempTextarea.select();
            tempTextarea.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Show success message
                showCopyNotification('Registration link copied to clipboard!', 'success');
            } catch (err) {
                console.error('Failed to copy: ', err);
                showCopyNotification('Failed to copy link. Please try again.', 'error');
            }
            
            // Remove the temporary element
            document.body.removeChild(tempTextarea);
        }
        
        function showCopyNotification(message, type) {
            // Remove existing notification if any
            const existingNotification = document.querySelector('.copy-notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `copy-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
                    ${message}
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/company-admin/dashboard.blade.php ENDPATH**/ ?>