/**
 * Authentication Middleware for Frontend Pages
 * This script should be included in all protected pages to enforce authentication
 */

/**
 * Page-level authentication guard
 * Call this function at the top of protected pages
 */
function initializePageAuth(requiredRole = null) {
    // Check authentication immediately
    if (!authService.isAuthenticated()) {
        authService.redirectToLogin();
        return false;
    }

    // Check role-based access if required
    if (requiredRole && !authService.hasRole(requiredRole)) {
        alert('Access denied. You do not have permission to access this page.');
        window.location.href = '/index.html';
        return false;
    }

    // Initialize user interface updates
    initializeAuthenticatedUI();
    return true;
}

/**
 * Initialize UI elements for authenticated users
 */
function initializeAuthenticatedUI() {
    // Initialize logout functionality
    initializeLogout();
    
    // Initialize user info display
    initializeUserInfo();
    
    // Add auth headers to all API requests
    setupAuthenticatedRequests();
    
    // Set up token refresh mechanism
    setupTokenRefresh();
}

/**
 * Setup authenticated requests
 */
function setupAuthenticatedRequests() {
    // Override fetch to include auth headers automatically
    const originalFetch = window.fetch;
    
    window.fetch = async function(url, options = {}) {
        // Only add auth headers for API requests to our backend
        if (url.includes('/api/') || url.startsWith(CONFIG.API_BASE_URL)) {
            const authHeaders = authService.getAuthHeaders();
            options.headers = {
                ...authHeaders,
                ...options.headers
            };
        }
        
        try {
            const response = await originalFetch(url, options);
            
            // Handle unauthorized responses
            if (response.status === 401) {
                authService.clearAuthData();
                authService.redirectToLogin();
            }
            
            return response;
        } catch (error) {
            console.error('Request error:', error);
            throw error;
        }
    };
}

/**
 * Setup token refresh mechanism
 */
function setupTokenRefresh() {
    // Check token expiry every 5 minutes
    setInterval(() => {
        if (!authService.isAuthenticated()) {
            authService.redirectToLogin();
        }
    }, 5 * 60 * 1000); // 5 minutes
}

/**
 * Add logout button to navigation
 */
function addLogoutToNavigation() {
    const navElements = document.querySelectorAll('nav .flex');
    
    navElements.forEach(nav => {
        // Check if logout button already exists
        if (nav.querySelector('[data-logout]')) return;
        
        // Create logout button
        const logoutButton = document.createElement('button');
        logoutButton.setAttribute('data-logout', 'true');
        logoutButton.className = 'text-gray-700 hover:text-red-600 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center';
        logoutButton.innerHTML = '<i class="fas fa-sign-out-alt mr-2"></i>Logout';
        
        // Add to navigation
        nav.appendChild(logoutButton);
    });
}

/**
 * Add user info to navigation
 */
function addUserInfoToNavigation() {
    const user = authService.getCurrentUser();
    if (!user) return;
    
    const userInfoElements = document.querySelectorAll('.user-info-placeholder');
    
    userInfoElements.forEach(element => {
        element.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-medium text-gray-900" data-user-name>${user.email}</p>
                        <p class="text-xs text-gray-600" data-user-role>${user.role}</p>
                    </div>
                </div>
                <button data-logout="true" class="text-gray-400 hover:text-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        `;
    });
}

/**
 * Protect specific DOM elements based on user role
 */
function protectElementsByRole() {
    const user = authService.getCurrentUser();
    if (!user) return;
    
    // Hide elements that require specific roles
    const protectedElements = document.querySelectorAll('[data-require-role]');
    
    protectedElements.forEach(element => {
        const requiredRole = element.getAttribute('data-require-role');
        if (!authService.hasRole(requiredRole)) {
            element.style.display = 'none';
        }
    });
}

/**
 * Show loading state while checking authentication
 */
function showAuthLoading() {
    const loadingHTML = `
        <div id="auth-loading" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700">Verifying authentication...</span>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', loadingHTML);
}

/**
 * Hide authentication loading state
 */
function hideAuthLoading() {
    const loadingElement = document.getElementById('auth-loading');
    if (loadingElement) {
        loadingElement.remove();
    }
}

/**
 * Initialize authentication for SuperAdmin pages
 */
function initializeSuperAdminAuth() {
    return initializePageAuth('super_admin');
}

/**
 * Initialize authentication for regular admin pages
 */
function initializeAdminAuth() {
    return initializePageAuth('admin');
}

/**
 * Initialize authentication for any authenticated user
 */
function initializeUserAuth() {
    return initializePageAuth();
}

// Global initialization function for all protected pages
window.initializePageAuth = initializePageAuth;
window.initializeSuperAdminAuth = initializeSuperAdminAuth;
window.initializeAdminAuth = initializeAdminAuth;
window.initializeUserAuth = initializeUserAuth;