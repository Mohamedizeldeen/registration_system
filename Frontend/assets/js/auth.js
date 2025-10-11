/**
 * Authentication Service for MFW Events Frontend
 * Handles login, logout, token management, and authentication state
 */

// Configuration
const CONFIG = {
    API_BASE_URL: 'http://localhost:3001', // Backend server URL
    TOKEN_KEY: 'mfw_auth_token',
    USER_KEY: 'mfw_user_data',
    TOKEN_EXPIRY_KEY: 'mfw_token_expiry'
};

/**
 * Authentication Service Class
 */
class AuthService {
    constructor() {
        this.token = localStorage.getItem(CONFIG.TOKEN_KEY);
        this.user = this.getStoredUser();
    }

    /**
     * Get stored user data from localStorage
     */
    getStoredUser() {
        const userData = localStorage.getItem(CONFIG.USER_KEY);
        return userData ? JSON.parse(userData) : null;
    }

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        if (!this.token) return false;
        
        // Check if token has expired
        const expiry = localStorage.getItem(CONFIG.TOKEN_EXPIRY_KEY);
        if (expiry && new Date().getTime() > parseInt(expiry)) {
            this.logout();
            return false;
        }
        
        return true;
    }

    /**
     * Login with email and password
     */
    async login(email, password) {
        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }

            // Store authentication data
            this.token = data.token;
            this.user = data.user;
            
            localStorage.setItem(CONFIG.TOKEN_KEY, data.token);
            localStorage.setItem(CONFIG.USER_KEY, JSON.stringify(data.user));
            
            // Set token expiry (24 hours from now based on backend JWT config)
            const expiryTime = new Date().getTime() + (24 * 60 * 60 * 1000);
            localStorage.setItem(CONFIG.TOKEN_EXPIRY_KEY, expiryTime.toString());

            return { success: true, user: data.user };
        } catch (error) {
            console.error('Login error:', error);
            throw error;
        }
    }

    /**
     * Logout user
     */
    async logout() {
        try {
            // Call backend logout if token exists
            if (this.token) {
                await fetch(`${CONFIG.API_BASE_URL}/auth/sign-out`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.token}`,
                        'Content-Type': 'application/json',
                    }
                });
            }
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            // Clear local storage regardless of API call success
            this.clearAuthData();
        }
    }

    /**
     * Clear authentication data
     */
    clearAuthData() {
        this.token = null;
        this.user = null;
        localStorage.removeItem(CONFIG.TOKEN_KEY);
        localStorage.removeItem(CONFIG.USER_KEY);
        localStorage.removeItem(CONFIG.TOKEN_EXPIRY_KEY);
    }

    /**
     * Get authentication headers for API calls
     */
    getAuthHeaders() {
        if (!this.token) return {};
        
        return {
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': 'application/json'
        };
    }

    /**
     * Make authenticated API request
     */
    async apiRequest(url, options = {}) {
        const headers = {
            ...this.getAuthHeaders(),
            ...options.headers
        };

        const response = await fetch(`${CONFIG.API_BASE_URL}${url}`, {
            ...options,
            headers
        });

        // If unauthorized, clear auth data and redirect to login
        if (response.status === 401) {
            this.clearAuthData();
            this.redirectToLogin();
            throw new Error('Unauthorized');
        }

        return response;
    }

    /**
     * Get current user data
     */
    getCurrentUser() {
        return this.user;
    }

    /**
     * Get current token
     */
    getToken() {
        return this.token;
    }

    /**
     * Redirect to login page
     */
    redirectToLogin() {
        const currentPath = window.location.pathname;
        const isLoginPage = currentPath.includes('login.html');
        
        if (!isLoginPage) {
            // Store the current page to redirect back after login
            sessionStorage.setItem('redirect_after_login', window.location.href);
            window.location.href = '/login.html';
        }
    }

    /**
     * Redirect to intended page after login
     */
    redirectAfterLogin() {
        const redirectUrl = sessionStorage.getItem('redirect_after_login');
        sessionStorage.removeItem('redirect_after_login');
        
        if (redirectUrl) {
            window.location.href = redirectUrl;
        } else {
            // Default redirect to SuperAdmin dashboard
            window.location.href = '/SuperAdmin-dashboard/index.html';
        }
    }

    /**
     * Check user role
     */
    hasRole(requiredRole) {
        if (!this.user) return false;
        return this.user.role === requiredRole;
    }

    /**
     * Check if user is super admin
     */
    isSuperAdmin() {
        return this.hasRole('super_admin');
    }
}

// Create global auth service instance
const authService = new AuthService();

/**
 * Authentication guard for protected pages
 */
function requireAuth(requiredRole = null) {
    if (!authService.isAuthenticated()) {
        authService.redirectToLogin();
        return false;
    }

    if (requiredRole && !authService.hasRole(requiredRole)) {
        alert('Access denied. Insufficient permissions.');
        window.location.href = '/index.html';
        return false;
    }

    return true;
}

/**
 * Initialize login functionality
 */
function initializeLogin() {
    // Check if already authenticated
    if (authService.isAuthenticated()) {
        authService.redirectAfterLogin();
        return;
    }

    // Set up form validation
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    if (emailInput && passwordInput) {
        emailInput.addEventListener('blur', validateEmail);
        passwordInput.addEventListener('blur', validatePassword);
    }
}

/**
 * Perform login
 */
async function performLogin() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const buttonLoading = document.getElementById('buttonLoading');
    const alertContainer = document.getElementById('alertContainer');

    // Clear previous alerts
    clearAlert();

    // Validate inputs
    if (!email || !password) {
        showAlert('Please fill in all fields', 'error');
        return;
    }

    if (!isValidEmail(email)) {
        showAlert('Please enter a valid email address', 'error');
        return;
    }

    // Show loading state
    loginButton.disabled = true;
    buttonText.classList.add('hidden');
    buttonLoading.classList.remove('hidden');

    try {
        await authService.login(email, password);
        showAlert('Login successful! Redirecting...', 'success');
        
        // Redirect after short delay
        setTimeout(() => {
            authService.redirectAfterLogin();
        }, 1500);

    } catch (error) {
        showAlert(error.message || 'Login failed. Please try again.', 'error');
    } finally {
        // Reset button state
        loginButton.disabled = false;
        buttonText.classList.remove('hidden');
        buttonLoading.classList.add('hidden');
    }
}

/**
 * Show alert message
 */
function showAlert(message, type = 'error') {
    const alertContainer = document.getElementById('alertContainer');
    const alertClass = type === 'error' ? 'alert-error' : 'alert-success';
    const iconClass = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
    
    alertContainer.innerHTML = `
        <div class="alert ${alertClass}">
            <i class="fas ${iconClass} mr-2"></i>
            ${message}
        </div>
    `;
    alertContainer.classList.remove('hidden');
}

/**
 * Clear alert message
 */
function clearAlert() {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = '';
    alertContainer.classList.add('hidden');
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate email field
 */
function validateEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    
    if (email && !isValidEmail(email)) {
        emailInput.classList.add('border-red-500');
        return false;
    } else {
        emailInput.classList.remove('border-red-500');
        return true;
    }
}

/**
 * Validate password field
 */
function validatePassword() {
    const passwordInput = document.getElementById('password');
    const password = passwordInput.value;
    
    if (password && password.length < 6) {
        passwordInput.classList.add('border-red-500');
        return false;
    } else {
        passwordInput.classList.remove('border-red-500');
        return true;
    }
}

/**
 * Initialize logout functionality
 */
function initializeLogout() {
    const logoutButtons = document.querySelectorAll('[data-logout]');
    logoutButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (confirm('Are you sure you want to logout?')) {
                await authService.logout();
                window.location.href = '/login.html';
            }
        });
    });
}

/**
 * Initialize user info display
 */
function initializeUserInfo() {
    const user = authService.getCurrentUser();
    if (user) {
        // Update user info in the UI
        const userNameElements = document.querySelectorAll('[data-user-name]');
        const userEmailElements = document.querySelectorAll('[data-user-email]');
        const userRoleElements = document.querySelectorAll('[data-user-role]');
        
        userNameElements.forEach(el => el.textContent = user.name || user.email);
        userEmailElements.forEach(el => el.textContent = user.email);
        userRoleElements.forEach(el => el.textContent = user.role);
    }
}

// Export for global use
window.authService = authService;
window.requireAuth = requireAuth;
window.initializeLogin = initializeLogin;
window.performLogin = performLogin;
window.initializeLogout = initializeLogout;
window.initializeUserInfo = initializeUserInfo;