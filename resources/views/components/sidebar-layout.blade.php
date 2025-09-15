<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MFW Events') - MFW Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('head-scripts')
</head>
<body class="bg-gray-50 min-h-screen">
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-white shadow-lg border-r border-gray-200 w-64 fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition duration-300 ease-in-out">
            <!-- Logo & Brand -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">MFW Events</h1>
                        <p class="text-xs text-gray-600">Management System</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden p-1 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-600 truncate">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </p>
                        @if(auth()->user()->company)
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->company->name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 px-2 space-y-1 flex-1 overflow-y-auto">
                <!-- Universal Dashboard Link -->
                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-tachometer-alt mr-3 w-5"></i>Dashboard
                    </a>
                @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin')
                    <a href="{{ route('company-admin.dashboard') }}" class="{{ request()->routeIs('company-admin.dashboard') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-tachometer-alt mr-3 w-5"></i>Dashboard
                    </a>
                @elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer')
                    <a href="{{ route('company-organizer.dashboard') }}" class="{{ request()->routeIs('company-organizer.dashboard') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-tachometer-alt mr-3 w-5"></i>Dashboard
                    </a>
                @endif

                <!-- Role-specific Navigation -->
                @if(auth()->user()->role === 'super_admin')
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('companies.index') }}" class="{{ request()->routeIs('companies.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-building mr-3 w-5"></i>Companies
                            </a>
                            <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-calendar-alt mr-3 w-5"></i>Events
                            </a>
                            <a href="{{ route('eventZones.index') }}" class="{{ request()->routeIs('eventZones.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-map-marker-alt mr-3 w-5"></i>Event Zones
                            </a>
                            <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-ticket-alt mr-3 w-5"></i>Tickets
                            </a>
                            <a href="{{ route('attendees.index') }}" class="{{ request()->routeIs('attendees.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-users mr-3 w-5"></i>Attendees
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">System</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('coupons.index') }}" class="{{ request()->routeIs('coupons.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-tags mr-3 w-5"></i>Coupons
                            </a>
                            <a href="{{ route('currencies.index') }}" class="{{ request()->routeIs('currencies.*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-dollar-sign mr-3 w-5"></i>Currencies
                            </a>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin')
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Event Management</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('company-admin.events') }}" class="{{ request()->routeIs('company-admin.events') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-calendar-alt mr-3 w-5"></i>Events
                            </a>
                            <a href="{{ route('company-admin.tickets') }}" class="{{ request()->routeIs('company-admin.tickets*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-ticket-alt mr-3 w-5"></i>Tickets
                            </a>
                            <a href="{{ route('company-admin.attendees') }}" class="{{ request()->routeIs('company-admin.attendees') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-users mr-3 w-5"></i>Attendees
                            </a>
                            <a href="{{ route('company-admin.payments') }}" class="{{ request()->routeIs('company-admin.payments') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-credit-card mr-3 w-5"></i>Payments
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tools</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('company-admin.print-tickets.form') }}" class="{{ request()->routeIs('company-admin.print-tickets*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-print mr-3 w-5"></i>Print Tickets
                            </a>
                        </div>
                    </div>
                @elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer')
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Event Management</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('company-organizer.events') }}" class="{{ request()->routeIs('company-organizer.events') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-calendar-alt mr-3 w-5"></i>Events
                            </a>
                            <a href="{{ route('company-organizer.tickets') }}" class="{{ request()->routeIs('company-organizer.tickets*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-ticket-alt mr-3 w-5"></i>Tickets
                            </a>
                            <a href="{{ route('company-organizer.attendees') }}" class="{{ request()->routeIs('company-organizer.attendees') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-users mr-3 w-5"></i>Attendees
                            </a>
                            <a href="{{ route('company-organizer.payments') }}" class="{{ request()->routeIs('company-organizer.payments') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-credit-card mr-3 w-5"></i>Payments
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tools</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('company-organizer.print-tickets.form') }}" class="{{ request()->routeIs('company-organizer.print-tickets*') ? 'bg-blue-100 text-blue-700 border-r-3 border-blue-700' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100' }} flex items-center px-3 py-2 text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-print mr-3 w-5"></i>Print Tickets
                            </a>
                        </div>
                    </div>
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-gray-200 p-4">
                <div class="flex items-center space-x-2">
                    <button class="flex-1 flex items-center justify-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition duration-200">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-3 py-2 text-sm text-red-600 hover:text-red-900 hover:bg-red-50 rounded-md transition duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-40 lg:hidden hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <button onclick="toggleSidebar()" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-lg font-semibold text-gray-900">MFW Events</h1>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- User menu button could go here if needed -->
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="p-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="p-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    // Close sidebar when clicking overlay
    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        toggleSidebar();
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });
    </script>
</body>
</html>
