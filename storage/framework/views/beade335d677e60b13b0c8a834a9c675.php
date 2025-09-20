<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'MFW Events'); ?> - MFW Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php echo $__env->yieldPushContent('head-scripts'); ?>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Include Dashboard Header -->
    <?php echo $__env->make('components.dashboard-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if(session('error')): ?>
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo e(session('error')); ?></span>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\registration_system\resources\views/layouts/app.blade.php ENDPATH**/ ?>