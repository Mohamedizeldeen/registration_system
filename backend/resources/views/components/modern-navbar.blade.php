<!-- Modern Top Navigation -->
<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side - Logo and main nav -->
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900 hidden sm:block">MFW Events</span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:ml-6 md:flex md:space-x-1">
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('companies.index') }}" class="{{ request()->routeIs('companies.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            <span>Companies</span>
                        </a>
                        <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Events</span>
                        </a>
                        <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            <span>Tickets</span>
                        </a>
                        <a href="{{ route('attendees.index') }}" class="{{ request()->routeIs('attendees.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Attendees</span>
                        </a>
                    @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin')
                        <a href="{{ route('company-admin.dashboard') }}" class="{{ request()->routeIs('company-admin.dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('company-admin.events') }}" class="{{ request()->routeIs('company-admin.events') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Events</span>
                        </a>
                        <a href="{{ route('company-admin.tickets') }}" class="{{ request()->routeIs('company-admin.tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            <span>Tickets</span>
                        </a>
                        <a href="{{ route('company-admin.attendees') }}" class="{{ request()->routeIs('company-admin.attendees') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Attendees</span>
                        </a>
                        <a href="{{ route('company-admin.print-tickets.form') }}" class="{{ request()->routeIs('company-admin.print-tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-print mr-2"></i>
                            <span>Print</span>
                        </a>
                    @elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer')
                        <a href="{{ route('company-organizer.dashboard') }}" class="{{ request()->routeIs('company-organizer.dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('company-organizer.events') }}" class="{{ request()->routeIs('company-organizer.events') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Events</span>
                        </a>
                        <a href="{{ route('company-organizer.tickets') }}" class="{{ request()->routeIs('company-organizer.tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            <span>Tickets</span>
                        </a>
                        <a href="{{ route('company-organizer.attendees') }}" class="{{ request()->routeIs('company-organizer.attendees') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Attendees</span>
                        </a>
                        <a href="{{ route('company-organizer.print-tickets.form') }}" class="{{ request()->routeIs('company-organizer.print-tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} border-b-2 px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-print mr-2"></i>
                            <span>Print</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right side - User menu and mobile menu button -->
            <div class="flex items-center space-x-4">
                <!-- User dropdown -->
                <div class="relative">
                    <button onclick="toggleUserDropdown()" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="h-8 w-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                                @if(auth()->user()->company) - {{ auth()->user()->company->name }} @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs hidden lg:block"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="userDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 hidden">
                        <div class="px-4 py-3 border-b border-gray-100 lg:hidden">
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                                @if(auth()->user()->company) - {{ auth()->user()->company->name }} @endif
                            </div>
                        </div>
                        
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-cog mr-3 w-4"></i>Account Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-question-circle mr-3 w-4"></i>Help Center
                        </a>
                        
                        <div class="border-t border-gray-100 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-3 w-4"></i>Sign out
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <i id="mobileMenuIcon" class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-white border-b border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="{{ route('companies.index') }}" class="{{ request()->routeIs('companies.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-building mr-3"></i>Companies
                </a>
                <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-calendar-alt mr-3"></i>Events
                </a>
                <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-ticket-alt mr-3"></i>Tickets
                </a>
                <a href="{{ route('attendees.index') }}" class="{{ request()->routeIs('attendees.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-users mr-3"></i>Attendees
                </a>
            @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin')
                <a href="{{ route('company-admin.dashboard') }}" class="{{ request()->routeIs('company-admin.dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="{{ route('company-admin.events') }}" class="{{ request()->routeIs('company-admin.events') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-calendar-alt mr-3"></i>Events
                </a>
                <a href="{{ route('company-admin.tickets') }}" class="{{ request()->routeIs('company-admin.tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-ticket-alt mr-3"></i>Tickets
                </a>
                <a href="{{ route('company-admin.attendees') }}" class="{{ request()->routeIs('company-admin.attendees') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-users mr-3"></i>Attendees
                </a>
                <a href="{{ route('company-admin.print-tickets.form') }}" class="{{ request()->routeIs('company-admin.print-tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-print mr-3"></i>Print Tickets
                </a>
            @elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer')
                <a href="{{ route('company-organizer.dashboard') }}" class="{{ request()->routeIs('company-organizer.dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="{{ route('company-organizer.events') }}" class="{{ request()->routeIs('company-organizer.events') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-calendar-alt mr-3"></i>Events
                </a>
                <a href="{{ route('company-organizer.tickets') }}" class="{{ request()->routeIs('company-organizer.tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-ticket-alt mr-3"></i>Tickets
                </a>
                <a href="{{ route('company-organizer.attendees') }}" class="{{ request()->routeIs('company-organizer.attendees') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-users mr-3"></i>Attendees
                </a>
                <a href="{{ route('company-organizer.print-tickets.form') }}" class="{{ request()->routeIs('company-organizer.print-tickets*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-print mr-3"></i>Print Tickets
                </a>
            @endif
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const icon = document.getElementById('mobileMenuIcon');
    
    mobileMenu.classList.toggle('hidden');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
}

function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userDropdown = document.getElementById('userDropdown');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Close user dropdown
    if (!event.target.closest('[onclick="toggleUserDropdown()"]') && !userDropdown.contains(event.target)) {
        userDropdown.classList.add('hidden');
    }
    
    // Close mobile menu
    if (!event.target.closest('[onclick="toggleMobileMenu()"]') && !mobileMenu.contains(event.target)) {
        mobileMenu.classList.add('hidden');
        const icon = document.getElementById('mobileMenuIcon');
        icon.classList.add('fa-bars');
        icon.classList.remove('fa-times');
    }
});
</script>
