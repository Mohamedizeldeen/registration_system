<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Attendee Management</h1>
                    <p class="text-gray-600 mt-2">Manage event registrations and attendee information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('attendees.export')); ?><?php echo e(request()->has('event_id') ? '?event_id=' . request('event_id') : ''); ?>" 
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                    <a href="<?php echo e(route('attendees.create')); ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Register Attendee
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if(session('error')): ?>
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo e(session('error')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <?php if($attendees->count() > 0): ?>
            <!-- Search and Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input type="text" id="searchAttendee" placeholder="Search attendees..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <select id="filterEvent" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Events</option>
                                <?php $__currentLoopData = $attendees->unique('event.id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($attendee->event->name); ?>"><?php echo e($attendee->event->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="checked-in">Checked In</option>
                                <option value="not-checked-in">Not Checked In</option>
                            </select>
                        </div>
                        <div>
                            <button onclick="clearFilters()" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                    
                    <!-- Export Row -->
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <span id="visibleCount"><?php echo e($attendees->count()); ?></span> attendees displayed
                                <?php if(request()->has('event_id')): ?>
                                    <span class="text-blue-600">• Filtered by event</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="exportFiltered()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 text-sm flex items-center">
                                    <i class="fas fa-filter mr-2"></i>Export Filtered
                                </button>
                                <a href="<?php echo e(route('attendees.export')); ?><?php echo e(request()->has('event_id') ? '?event_id=' . request('event_id') : ''); ?>" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 text-sm flex items-center">
                                    <i class="fas fa-download mr-2"></i>Export All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Attendees</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo e($attendees->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Checked In</p>
                            <p class="text-3xl font-bold text-green-900"><?php echo e($attendees->where('checked_in', true)->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-3xl font-bold text-orange-900"><?php echo e($attendees->where('checked_in', false)->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Revenue</p>
                            <p class="text-3xl font-bold text-purple-900">$<?php echo e(number_format($attendees->sum(function($attendee) { return $attendee->payments->sum('amount'); }), 2)); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendees Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Attendee List</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full" id="attendeesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $attendees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="attendee-row hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium"><?php echo e(substr($attendee->first_name, 0, 1)); ?><?php echo e(substr($attendee->last_name, 0, 1)); ?></span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($attendee->first_name); ?> <?php echo e($attendee->last_name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($attendee->email); ?></div>
                                            <?php if($attendee->phone): ?>
                                                <div class="text-xs text-gray-400"><?php echo e($attendee->phone); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($attendee->event->name); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($attendee->event->event_date->format('M j, Y')); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($attendee->ticket->name); ?></div>
                                    <div class="text-sm text-gray-500">$<?php echo e(number_format($attendee->ticket->price, 2)); ?></div>
                                    <div class="text-xs text-gray-400"><?php echo e($attendee->ticket->eventZone->name ?? 'N/A'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($attendee->company): ?>
                                        <div class="text-sm text-gray-900"><?php echo e($attendee->company); ?></div>
                                        <?php if($attendee->job_title): ?>
                                            <div class="text-sm text-gray-500"><?php echo e($attendee->job_title); ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                    <?php if($attendee->country): ?>
                                        <div class="text-xs text-gray-400"><?php echo e($attendee->country); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($attendee->checked_in): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Checked In
                                        </span>
                                        <?php if($attendee->checked_in_at): ?>
                                            <div class="text-xs text-gray-500 mt-1"><?php echo e($attendee->checked_in_at->format('M j, g:i A')); ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('attendees.show', $attendee->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('attendees.edit', $attendee->id)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if(!$attendee->checked_in): ?>
                                            <button onclick="checkIn(<?php echo e($attendee->id); ?>)" class="text-green-600 hover:text-green-900" title="Check In">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('attendees.destroy', $attendee->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this attendee?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Attendees Found</h3>
                <p class="text-gray-500 mb-6">Start by registering attendees for your events.</p>
                <a href="<?php echo e(route('attendees.create')); ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                    Register First Attendee
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript for filtering and search -->
    <script>
        function clearFilters() {
            document.getElementById('searchAttendee').value = '';
            document.getElementById('filterEvent').value = '';
            document.getElementById('filterStatus').value = '';
            filterTable();
        }

        function filterTable() {
            const searchValue = document.getElementById('searchAttendee').value.toLowerCase();
            const eventFilter = document.getElementById('filterEvent').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('.attendee-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const attendeeName = row.querySelector('.text-sm.font-medium.text-gray-900').textContent.toLowerCase();
                const attendeeEmail = row.querySelector('.text-sm.text-gray-500').textContent.toLowerCase();
                const eventName = row.querySelectorAll('.text-sm.font-medium.text-gray-900')[1].textContent.toLowerCase();
                const statusElement = row.querySelector('.inline-flex');
                const isCheckedIn = statusElement.textContent.includes('Checked In');

                let showRow = true;

                // Search filter
                if (searchValue && !attendeeName.includes(searchValue) && !attendeeEmail.includes(searchValue)) {
                    showRow = false;
                }

                // Event filter
                if (eventFilter && !eventName.includes(eventFilter)) {
                    showRow = false;
                }

                // Status filter
                if (statusFilter === 'checked-in' && !isCheckedIn) {
                    showRow = false;
                } else if (statusFilter === 'not-checked-in' && isCheckedIn) {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleCount++;
            });

            // Update visible count
            document.getElementById('visibleCount').textContent = visibleCount;
        }

        function exportFiltered() {
            // Get current filter values
            const searchValue = document.getElementById('searchAttendee').value;
            const eventFilter = document.getElementById('filterEvent').value;
            const statusFilter = document.getElementById('filterStatus').value;
            
            // Build URL with filter parameters
            let exportUrl = '<?php echo e(route("attendees.export")); ?>?';
            const params = new URLSearchParams();
            
            // Add existing event_id if present
            <?php if(request()->has('event_id')): ?>
                params.append('event_id', '<?php echo e(request("event_id")); ?>');
            <?php endif; ?>
            
            if (searchValue) params.append('search', searchValue);
            if (eventFilter) params.append('event_filter', eventFilter);
            if (statusFilter) params.append('status_filter', statusFilter);
            
            exportUrl += params.toString();
            
            // Trigger download
            window.open(exportUrl, '_blank');
        }
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        function checkIn(attendeeId) {
            if (confirm('Mark this attendee as checked in?')) {
                // This would typically be an AJAX call to update the check-in status
                // For now, we'll just reload the page
                window.location.reload();
            }
        }

        // Event listeners
        document.getElementById('searchAttendee').addEventListener('input', filterTable);
        document.getElementById('filterEvent').addEventListener('change', filterTable);
        document.getElementById('filterStatus').addEventListener('change', filterTable);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/attendee/index.blade.php ENDPATH**/ ?>