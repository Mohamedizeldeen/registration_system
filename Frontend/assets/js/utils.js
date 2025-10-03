/**
 * Common Utility Functions
 * Shared JavaScript utilities for frontend components
 * Version: 1.0.0
 */

// API Configuration
const API_CONFIG = {
    baseURL: 'http://localhost:3001',
    timeout: 10000,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
};

// Common API Functions
const API = {
    /**
     * Make a GET request
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @returns {Promise} API response
     */
    async get(endpoint, params = {}) {
        const url = new URL(`${API_CONFIG.baseURL}${endpoint}`);
        Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
        
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: API_CONFIG.headers,
                signal: AbortSignal.timeout(API_CONFIG.timeout)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API GET Error:', error);
            throw error;
        }
    },

    /**
     * Make a POST request
     * @param {string} endpoint - API endpoint
     * @param {object} data - Request body data
     * @returns {Promise} API response
     */
    async post(endpoint, data = {}) {
        try {
            const response = await fetch(`${API_CONFIG.baseURL}${endpoint}`, {
                method: 'POST',
                headers: API_CONFIG.headers,
                body: JSON.stringify(data),
                signal: AbortSignal.timeout(API_CONFIG.timeout)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API POST Error:', error);
            throw error;
        }
    },

    /**
     * Make a PUT request
     * @param {string} endpoint - API endpoint
     * @param {object} data - Request body data
     * @returns {Promise} API response
     */
    async put(endpoint, data = {}) {
        try {
            const response = await fetch(`${API_CONFIG.baseURL}${endpoint}`, {
                method: 'PUT',
                headers: API_CONFIG.headers,
                body: JSON.stringify(data),
                signal: AbortSignal.timeout(API_CONFIG.timeout)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API PUT Error:', error);
            throw error;
        }
    },

    /**
     * Make a DELETE request
     * @param {string} endpoint - API endpoint
     * @returns {Promise} API response
     */
    async delete(endpoint) {
        try {
            const response = await fetch(`${API_CONFIG.baseURL}${endpoint}`, {
                method: 'DELETE',
                headers: API_CONFIG.headers,
                signal: AbortSignal.timeout(API_CONFIG.timeout)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API DELETE Error:', error);
            throw error;
        }
    }
};

// Utility Functions
const Utils = {
    /**
     * Format a date string to a readable format
     * @param {string} dateString - ISO date string
     * @param {object} options - Intl.DateTimeFormat options
     * @returns {string} Formatted date
     */
    formatDate(dateString, options = {}) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            const defaultOptions = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            
            return new Intl.DateTimeFormat('en-US', { ...defaultOptions, ...options }).format(date);
        } catch (error) {
            console.error('Date formatting error:', error);
            return dateString;
        }
    },

    /**
     * Format a number as currency
     * @param {number} amount - Amount to format
     * @param {string} currency - Currency code (default: USD)
     * @returns {string} Formatted currency
     */
    formatCurrency(amount, currency = 'USD') {
        if (amount === null || amount === undefined) return 'N/A';
        
        try {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(parseFloat(amount) || 0);
        } catch (error) {
            console.error('Currency formatting error:', error);
            return `$${parseFloat(amount || 0).toFixed(2)}`;
        }
    },

    /**
     * Format a time ago string
     * @param {string} dateString - ISO date string
     * @returns {string} Time ago format
     */
    formatTimeAgo(dateString) {
        if (!dateString) return 'Recently';
        
        try {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
            
            return this.formatDate(dateString);
        } catch (error) {
            console.error('Time ago formatting error:', error);
            return 'Recently';
        }
    },

    /**
     * Format event date for display
     * @param {string} dateString - ISO date string
     * @returns {string} Formatted event date
     */
    formatEventDate(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            
            return new Intl.DateTimeFormat('en-US', options).format(date);
        } catch (error) {
            console.error('Event date formatting error:', error);
            return dateString;
        }
    },

    /**
     * Format time for display
     * @param {string} timeString - ISO time string
     * @returns {string} Formatted time
     */
    formatTime(timeString) {
        if (!timeString) return 'N/A';
        
        try {
            const date = new Date(timeString);
            const options = {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };
            
            return new Intl.DateTimeFormat('en-US', options).format(date);
        } catch (error) {
            console.error('Time formatting error:', error);
            return timeString;
        }
    },

    /**
     * Debounce function calls
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in milliseconds
     * @returns {Function} Debounced function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Capitalize the first letter of each word
     * @param {string} str - String to capitalize
     * @returns {string} Capitalized string
     */
    capitalizeWords(str) {
        if (!str) return '';
        return str.replace(/\b\w/g, letter => letter.toUpperCase());
    },

    /**
     * Generate a random ID
     * @param {number} length - Length of the ID
     * @returns {string} Random ID
     */
    generateId(length = 8) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    },

    /**
     * Validate email format
     * @param {string} email - Email to validate
     * @returns {boolean} Is valid email
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    /**
     * Safely get nested object property
     * @param {object} obj - Object to search
     * @param {string} path - Dot notation path
     * @param {*} defaultValue - Default value if not found
     * @returns {*} Property value or default
     */
    getNestedProperty(obj, path, defaultValue = null) {
        try {
            return path.split('.').reduce((current, key) => current?.[key], obj) ?? defaultValue;
        } catch (error) {
            return defaultValue;
        }
    },

    /**
     * Get URL parameter value
     * @param {string} name - Parameter name
     * @returns {string|null} Parameter value or null if not found
     */
    getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    },

    /**
     * Get initials from a name
     * @param {string} name - Full name
     * @returns {string} Initials (max 2 characters)
     */
    getInitials(name) {
        if (!name) return '??';
        
        const words = name.trim().split(/\s+/);
        if (words.length === 1) {
            return words[0].charAt(0).toUpperCase();
        }
        
        return (words[0].charAt(0) + words[words.length - 1].charAt(0)).toUpperCase();
    },

    /**
     * Truncate text to specified length
     * @param {string} text - Text to truncate
     * @param {number} length - Maximum length
     * @param {string} suffix - Suffix to add when truncated
     * @returns {string} Truncated text
     */
    truncate(text, length = 50, suffix = '...') {
        if (!text) return '';
        if (text.length <= length) return text;
        return text.substring(0, length).trim() + suffix;
    },

    /**
     * Show loading state on an element
     * @param {string} elementId - Element ID to show loading on
     * @param {string} message - Loading message
     */
    showLoading(elementId, message = 'Loading...') {
        const element = document.getElementById(elementId);
        if (!element) return;

        // Store original content
        if (!element.hasAttribute('data-original-content')) {
            element.setAttribute('data-original-content', element.innerHTML);
        }

        // Show loading
        element.innerHTML = `
            <div class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${message}
            </div>
        `;
        element.disabled = true;
    },

    /**
     * Hide loading state and restore original content
     * @param {string} elementId - Element ID to hide loading on
     * @param {string} fallbackText - Fallback text if no original content
     */
    hideLoading(elementId, fallbackText = 'Submit') {
        const element = document.getElementById(elementId);
        if (!element) return;

        const originalContent = element.getAttribute('data-original-content');
        if (originalContent) {
            element.innerHTML = originalContent;
            element.removeAttribute('data-original-content');
        } else {
            element.innerHTML = fallbackText;
        }
        element.disabled = false;
    },

    /**
     * Show success/info message
     * @param {string} message - Message to show
     * @param {string} type - Message type (success, info, warning, error)
     */
    showMessage(message, type = 'info') {
        // Use the Notifications system
        if (typeof Notifications !== 'undefined') {
            Notifications[type] ? Notifications[type](message) : Notifications.info(message);
        } else {
            // Fallback to alert
            alert(message);
        }
    },

    /**
     * Show error message
     * @param {string} message - Error message to show
     */
    showError(message) {
        this.showMessage(message, 'error');
    }
};

// Notification System
const Notifications = {
    /**
     * Show a notification
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, warning, info)
     * @param {number} duration - Duration in milliseconds
     */
    show(message, type = 'info', duration = 3000) {
        // Remove existing notification
        const existing = document.querySelector('.notification');
        if (existing) {
            existing.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${this.getIcon(type)} mr-2"></i>
                ${message}
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }, duration);
    },

    /**
     * Get icon for notification type
     * @param {string} type - Notification type
     * @returns {string} Font Awesome icon name
     */
    getIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-triangle',
            warning: 'exclamation-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },

    success(message, duration = 3000) {
        this.show(message, 'success', duration);
    },

    error(message, duration = 5000) {
        this.show(message, 'error', duration);
    },

    warning(message, duration = 4000) {
        this.show(message, 'warning', duration);
    },

    info(message, duration = 3000) {
        this.show(message, 'info', duration);
    }
};

// Loading State Management
const LoadingManager = {
    /**
     * Show loading state for an element
     * @param {string|HTMLElement} element - Element selector or element
     */
    show(element) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        el.classList.add('loading');
        el.style.position = 'relative';
        
        const loader = document.createElement('div');
        loader.className = 'loading-overlay';
        loader.innerHTML = '<div class="loading-spinner"></div>';
        loader.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        `;
        
        el.appendChild(loader);
    },

    /**
     * Hide loading state for an element
     * @param {string|HTMLElement} element - Element selector or element
     */
    hide(element) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        el.classList.remove('loading');
        const loader = el.querySelector('.loading-overlay');
        if (loader) {
            loader.remove();
        }
    }
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { API, Utils, Notifications, LoadingManager };
}

// Make available globally
window.API = API;
window.Utils = Utils;
window.Notifications = Notifications;
window.LoadingManager = LoadingManager;