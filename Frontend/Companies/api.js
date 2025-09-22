// API Configuration for MFW Events Backend
const API_CONFIG = {
    BASE_URL: 'http://localhost:3001',
    ENDPOINTS: {
        COMPANIES: '/companies',
        EVENTS: '/events',
        USERS: '/users',
        TICKETS: '/tickets',
        COUPONS: '/coupons',
        ATTENDEES: '/attendees',
        PAYMENTS: '/payments',
        EVENT_ZONES: '/event-zones',
        SUPER_ADMIN: '/super-admin'
    }
};

// API Client class for handling HTTP requests
class ApiClient {
    constructor(baseURL = API_CONFIG.BASE_URL) {
        this.baseURL = baseURL;
        this.setupAxios();
    }

    setupAxios() {
        // Set default headers
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        
        // Request interceptor
        axios.interceptors.request.use(
            (config) => {
                // Add authorization token if available
                const token = localStorage.getItem('auth_token');
                if (token) {
                    config.headers.Authorization = `Bearer ${token}`;
                }
                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );

        // Response interceptor
        axios.interceptors.response.use(
            (response) => {
                return response;
            },
            (error) => {
                if (error.response?.status === 401) {
                    // Handle unauthorized access
                    localStorage.removeItem('auth_token');
                    // Redirect to login if needed
                    console.warn('Unauthorized access. Please login again.');
                }
                return Promise.reject(error);
            }
        );
    }

    // Generic HTTP methods
    async get(endpoint, params = {}) {
        try {
            const response = await axios.get(`${this.baseURL}${endpoint}`, { params });
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async post(endpoint, data = {}) {
        try {
            const response = await axios.post(`${this.baseURL}${endpoint}`, data);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async put(endpoint, data = {}) {
        try {
            const response = await axios.put(`${this.baseURL}${endpoint}`, data);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async delete(endpoint) {
        try {
            const response = await axios.delete(`${this.baseURL}${endpoint}`);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    handleError(error) {
        console.error('API Error:', error);
        
        if (error.response) {
            // Server responded with error status
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
        } else if (error.request) {
            // Request was made but no response received
            console.error('No response received:', error.request);
        } else {
            // Something else happened
            console.error('Error message:', error.message);
        }
    }
}

// Company API methods
class CompanyAPI {
    constructor(apiClient) {
        this.api = apiClient;
        this.endpoint = API_CONFIG.ENDPOINTS.COMPANIES;
    }

    // Get all companies
    async getAll() {
        return await this.api.get(this.endpoint);
    }

    // Get company by ID
    async getById(id) {
        return await this.api.get(`${this.endpoint}/${id}`);
    }

    // Create new company
    async create(companyData) {
        const { name, address, phone, email } = companyData;
        return await this.api.post(this.endpoint, {
            name,
            address,
            phone,
            email
        });
    }

    // Update company
    async update(id, companyData) {
        const { name, address, phone, email } = companyData;
        return await this.api.put(`${this.endpoint}/${id}`, {
            name,
            address,
            phone,
            email
        });
    }

    // Delete company
    async delete(id) {
        return await this.api.delete(`${this.endpoint}/${id}`);
    }
}

// Initialize API client and company API
const apiClient = new ApiClient();
const companyAPI = new CompanyAPI(apiClient);

// Utility functions
const Utils = {
    // Show loading state
    showLoading(elementId, originalText = 'Loading...') {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${originalText}`;
            element.disabled = true;
        }
    },

    // Hide loading state
    hideLoading(elementId, originalText) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = originalText;
            element.disabled = false;
        }
    },

    // Show message
    showMessage(message, type = 'success', duration = 5000) {
        const messageEl = document.getElementById(`${type}-message`);
        const textEl = document.getElementById(`${type}-text`);
        
        if (messageEl && textEl) {
            textEl.textContent = message;
            messageEl.classList.remove('hidden');
            
            setTimeout(() => {
                messageEl.classList.add('hidden');
            }, duration);
        }
    },

    // Show error message
    showError(message, duration = 5000) {
        this.showMessage(message, 'error', duration);
    },

    // Format date
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    // Get initials from name
    getInitials(name) {
        return name.split(' ').map(word => word.charAt(0)).join('').slice(0, 2).toUpperCase();
    },

    // Truncate text
    truncate(text, length = 50) {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
    },

    // Get URL parameter
    getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    },

    // Validate email
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
};

// Export for use in other files
if (typeof window !== 'undefined') {
    window.API_CONFIG = API_CONFIG;
    window.apiClient = apiClient;
    window.companyAPI = companyAPI;
    window.Utils = Utils;
}