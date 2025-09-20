<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Company Management</h1>
                    <p class="text-gray-600 mt-2">Manage companies and their events</p>
                </div>
                <a href="<?php echo e(route('companies.create')); ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Company
                </a>
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
        <?php if($companies->count() > 0): ?>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Companies</p>
                            <p class="text-3xl font-bold text-gray-900"><?php echo e($companies->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Events</p>
                            <p class="text-3xl font-bold text-green-900"><?php echo e($companies->sum(function($company) { return $company->events->count(); })); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-3xl font-bold text-purple-900"><?php echo e($companies->sum(function($company) { return $company->users->count(); })); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Companies</p>
                            <p class="text-3xl font-bold text-orange-900"><?php echo e($companies->filter(function($company) { return $company->events->count() > 0; })->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Companies Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Company List</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium"><?php echo e(substr($company->name, 0, 2)); ?></span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($company->name); ?></div>
                                            <?php if($company->address): ?>
                                                <div class="text-sm text-gray-500"><?php echo e(Str::limit($company->address, 50)); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($company->email): ?>
                                        <div class="text-sm text-gray-900"><?php echo e($company->email); ?></div>
                                    <?php endif; ?>
                                    <?php if($company->phone): ?>
                                        <div class="text-sm text-gray-500"><?php echo e($company->phone); ?></div>
                                    <?php endif; ?>
                                    <?php if(!$company->email && !$company->phone): ?>
                                        <span class="text-gray-400">No contact info</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($company->events->count()); ?> events</div>
                                    <?php if($company->events->count() > 0): ?>
                                        <div class="text-sm text-gray-500">Latest: <?php echo e($company->events->first()->name ?? 'N/A'); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($company->users->count()); ?> users</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('companies.show', $company->id)); ?>" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('companies.edit', $company->id)); ?>" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('companies.destroy', $company->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company? This will also delete all associated events and users.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
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
                <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Companies Found</h3>
                <p class="text-gray-500 mb-6">Start by adding your first company to manage events and users.</p>
                <a href="<?php echo e(route('companies.create')); ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                    Add First Company
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\registration_system\resources\views/companies/index.blade.php ENDPATH**/ ?>