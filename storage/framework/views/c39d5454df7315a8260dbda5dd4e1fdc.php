<!-- Navigation -->
<nav class="bg-white shadow-lg border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-gray-900">MFW Events</h1>
                        <p class="text-sm text-gray-600"><?php echo e($subtitle ?? 'Dashboard'); ?></p>
                    </div>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex lg:items-center lg:space-x-2 lg:ml-8">
                    <!-- Universal Dashboard Link -->
                    <?php if(auth()->user()->role === 'super_admin'): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                <?php elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin'): ?>
                    <a href="<?php echo e(route('company-admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('company-admin.dashboard') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                <?php elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer'): ?>
                    <a href="<?php echo e(route('company-organizer.dashboard')); ?>" class="<?php echo e(request()->routeIs('company-organizer.dashboard') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                <?php endif; ?>

                <!-- Role-specific Navigation -->
                <?php if(auth()->user()->role === 'super_admin'): ?>
                    <a href="<?php echo e(route('companies.index')); ?>" class="<?php echo e(request()->routeIs('companies.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-building mr-2"></i>Companies
                    </a>
                    <a href="<?php echo e(route('events.index')); ?>" class="<?php echo e(request()->routeIs('events.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Events
                    </a>
                    <a href="<?php echo e(route('eventZones.index')); ?>" class="<?php echo e(request()->routeIs('eventZones.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>Zones
                    </a>
                    <a href="<?php echo e(route('tickets.index')); ?>" class="<?php echo e(request()->routeIs('tickets.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-ticket-alt mr-2"></i>Tickets
                    </a>
                    <a href="<?php echo e(route('attendees.index')); ?>" class="<?php echo e(request()->routeIs('attendees.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-users mr-2"></i>Attendees
                    </a>
                    <a href="<?php echo e(route('coupons.index')); ?>" class="<?php echo e(request()->routeIs('coupons.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-tags mr-2"></i>Coupons
                    </a>
                    <a href="<?php echo e(route('currencies.index')); ?>" class="<?php echo e(request()->routeIs('currencies.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-dollar-sign mr-2"></i>Currencies
                    </a>
                <?php elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin'): ?>
                    <a href="<?php echo e(route('company-admin.events')); ?>" class="<?php echo e(request()->routeIs('company-admin.events') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Events
                    </a>
                    <a href="<?php echo e(route('eventZones.index')); ?>" class="<?php echo e(request()->routeIs('eventZones.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>Zones
                    </a>
                    <a href="<?php echo e(route('tickets.index')); ?>" class="<?php echo e(request()->routeIs('tickets.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-ticket-alt mr-2"></i>Tickets
                    </a>
                    <a href="<?php echo e(route('company-admin.attendees')); ?>" class="<?php echo e(request()->routeIs('company-admin.attendees') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-users mr-2"></i>Attendees
                    </a>
                    <a href="<?php echo e(route('company-admin.print-tickets.form')); ?>" class="<?php echo e(request()->routeIs('company-admin.print-tickets*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-print mr-2"></i>Print
                    </a>
                <?php elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer'): ?>
                    <a href="<?php echo e(route('company-organizer.events')); ?>" class="<?php echo e(request()->routeIs('company-organizer.events') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Events
                    </a>
                    <a href="<?php echo e(route('company-organizer.tickets')); ?>" class="<?php echo e(request()->routeIs('company-organizer.tickets*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-ticket-alt mr-2"></i>Tickets
                    </a>
                    <a href="<?php echo e(route('company-organizer.attendees')); ?>" class="<?php echo e(request()->routeIs('company-organizer.attendees') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-users mr-2"></i>Attendees
                    </a>
                    <a href="<?php echo e(route('company-organizer.print-tickets.form')); ?>" class="<?php echo e(request()->routeIs('company-organizer.print-tickets*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-print mr-2"></i>Print
                    </a>
                    <a href="<?php echo e(route('company-organizer.payments')); ?>" class="<?php echo e(request()->routeIs('company-organizer.payments') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>Payments
                    </a>
                <?php endif; ?>
                </div>
            </div>

            <!-- Right side - User menu and mobile menu button -->
            <div class="flex items-center space-x-3">
                <!-- User Dropdown -->
                <div class="relative">
                    <button onclick="toggleUserDropdown()" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition duration-200">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-600">
                                <?php echo e(ucfirst(str_replace('_', ' ', auth()->user()->role))); ?>

                                <?php if(auth()->user()->company): ?>
                                    - <?php echo e(auth()->user()->company->name); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>
                    
                    <!-- User Dropdown Menu -->
                    <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                        <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-600">
                                <?php echo e(ucfirst(str_replace('_', ' ', auth()->user()->role))); ?>

                                <?php if(auth()->user()->company): ?>
                                    - <?php echo e(auth()->user()->company->name); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-cog mr-2"></i>Profile Settings
                        </a>
                        <hr class="border-gray-100">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition duration-200">
                        <i id="mobileMenuIcon" class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="lg:hidden hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-gray-50 border-t border-gray-200">
            <!-- Universal Dashboard Link -->
            <?php if(auth()->user()->role === 'super_admin'): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
            <?php elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin'): ?>
                <a href="<?php echo e(route('company-admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('company-admin.dashboard') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
            <?php elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer'): ?>
                <a href="<?php echo e(route('company-organizer.dashboard')); ?>" class="<?php echo e(request()->routeIs('company-organizer.dashboard') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
            <?php endif; ?>

            <!-- Role-specific Navigation -->
            <?php if(auth()->user()->role === 'super_admin'): ?>
                <a href="<?php echo e(route('companies.index')); ?>" class="<?php echo e(request()->routeIs('companies.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-building mr-3"></i>Companies
                </a>
                <a href="<?php echo e(route('events.index')); ?>" class="<?php echo e(request()->routeIs('events.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-calendar-alt mr-3"></i>Events
                </a>
                <a href="<?php echo e(route('eventZones.index')); ?>" class="<?php echo e(request()->routeIs('eventZones.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-map-marker-alt mr-3"></i>Event Zones
                </a>
                <a href="<?php echo e(route('tickets.index')); ?>" class="<?php echo e(request()->routeIs('tickets.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-ticket-alt mr-3"></i>Tickets
                </a>
                <a href="<?php echo e(route('attendees.index')); ?>" class="<?php echo e(request()->routeIs('attendees.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-users mr-3"></i>Attendees
                </a>
                <a href="<?php echo e(route('coupons.index')); ?>" class="<?php echo e(request()->routeIs('coupons.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-tags mr-3"></i>Coupons
                </a>
                <a href="<?php echo e(route('currencies.index')); ?>" class="<?php echo e(request()->routeIs('currencies.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-dollar-sign mr-3"></i>Currencies
                </a>
            <?php elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin'): ?>
                <a href="<?php echo e(route('company-admin.events')); ?>" class="<?php echo e(request()->routeIs('company-admin.events') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-calendar-alt mr-3"></i>Events
                </a>
                <a href="<?php echo e(route('eventZones.index')); ?>" class="<?php echo e(request()->routeIs('eventZones.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-map-marker-alt mr-3"></i>Event Zones
                </a>
                <a href="<?php echo e(route('tickets.index')); ?>" class="<?php echo e(request()->routeIs('tickets.*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-ticket-alt mr-3"></i>Tickets
                </a>
                <a href="<?php echo e(route('company-admin.attendees')); ?>" class="<?php echo e(request()->routeIs('company-admin.attendees') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-users mr-3"></i>Attendees
                </a>
                <a href="<?php echo e(route('company-admin.print-tickets.form')); ?>" class="<?php echo e(request()->routeIs('company-admin.print-tickets*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-print mr-3"></i>Print Tickets
                </a>
                <a href="<?php echo e(route('company-admin.payments')); ?>" class="<?php echo e(request()->routeIs('company-admin.payments') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-credit-card mr-3"></i>Payments
                </a>
            <?php elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer'): ?>
                <a href="<?php echo e(route('company-organizer.events')); ?>" class="<?php echo e(request()->routeIs('company-organizer.events') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-calendar-alt mr-3"></i>Events
                </a>
                <a href="<?php echo e(route('company-organizer.tickets')); ?>" class="<?php echo e(request()->routeIs('company-organizer.tickets*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-ticket-alt mr-3"></i>Tickets
                </a>
                <a href="<?php echo e(route('company-organizer.attendees')); ?>" class="<?php echo e(request()->routeIs('company-organizer.attendees') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-users mr-3"></i>Attendees
                </a>
                <a href="<?php echo e(route('company-organizer.print-tickets.form')); ?>" class="<?php echo e(request()->routeIs('company-organizer.print-tickets*') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-print mr-3"></i>Print Tickets
                </a>
                <a href="<?php echo e(route('company-organizer.payments')); ?>" class="<?php echo e(request()->routeIs('company-organizer.payments') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'); ?> block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-credit-card mr-3"></i>Payments
                </a>
            <?php endif; ?>

            <!-- Mobile-only items -->
            <div class="border-t border-gray-200 pt-3 mt-3">
                <a href="#" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-user-cog mr-3"></i>Profile Settings
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuIcon = document.getElementById('mobileMenuIcon');
    
    if (mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.remove('hidden');
        mobileMenuIcon.classList.remove('fa-bars');
        mobileMenuIcon.classList.add('fa-times');
    } else {
        mobileMenu.classList.add('hidden');
        mobileMenuIcon.classList.remove('fa-times');
        mobileMenuIcon.classList.add('fa-bars');
    }
}

function toggleUserDropdown() {
    const userDropdown = document.getElementById('userDropdown');
    userDropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userDropdown = document.getElementById('userDropdown');
    const userButton = event.target.closest('[onclick="toggleUserDropdown()"]');
    
    if (!userButton && !userDropdown.contains(event.target)) {
        userDropdown.classList.add('hidden');
    }
});

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileButton = event.target.closest('[onclick="toggleMobileMenu()"]');
    
    if (!mobileButton && !mobileMenu.contains(event.target)) {
        mobileMenu.classList.add('hidden');
        const mobileMenuIcon = document.getElementById('mobileMenuIcon');
        mobileMenuIcon.classList.remove('fa-times');
        mobileMenuIcon.classList.add('fa-bars');
    }
});
</script>
<?php /**PATH C:\laragon\www\registration_system\resources\views/components/dashboard-header.blade.php ENDPATH**/ ?>