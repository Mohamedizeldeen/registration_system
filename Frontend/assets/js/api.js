// Shared API Configuration for MFW Events Backend
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
        this.axiosSetup = false;
        this.ensureAxiosSetup();
    }

    ensureAxiosSetup() {
        if (this.axiosSetup || typeof axios === 'undefined') {
            return;
        }
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
                    console.warn('Unauthorized access. Please login again.');
                }
                return Promise.reject(error);
            }
        );
        
        this.axiosSetup = true;
        console.log('✅ Axios setup completed');
    }

    // Generic HTTP methods
    async get(endpoint, params = {}) {
        this.ensureAxiosSetup();
        try {
            const response = await axios.get(`${this.baseURL}${endpoint}`, { params });
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async post(endpoint, data = {}) {
        this.ensureAxiosSetup();
        try {
            const response = await axios.post(`${this.baseURL}${endpoint}`, data);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async put(endpoint, data = {}) {
        this.ensureAxiosSetup();
        try {
            const response = await axios.put(`${this.baseURL}${endpoint}`, data);
            return response.data;
        } catch (error) {
            this.handleError(error);
            throw error;
        }
    }

    async delete(endpoint) {
        this.ensureAxiosSetup();
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
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
        } else if (error.request) {
            console.error('No response received:', error.request);
        } else {
            console.error('Error message:', error.message);
        }
    }
}

// Base API class for common CRUD operations
class BaseAPI {
    constructor(apiClient, endpoint) {
        this.api = apiClient;
        this.endpoint = endpoint;
    }

    async getAll() {
        return await this.api.get(this.endpoint);
    }

    async getById(id) {
        return await this.api.get(`${this.endpoint}/${id}`);
    }

    async create(data) {
        return await this.api.post(this.endpoint, data);
    }

    async update(id, data) {
        return await this.api.put(`${this.endpoint}/${id}`, data);
    }

    async delete(id) {
        return await this.api.delete(`${this.endpoint}/${id}`);
    }
}

// Specific API classes for each module
class CompanyAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.COMPANIES);
    }

    async create(companyData) {
        const { name, address, phone, email } = companyData;
        return await this.api.post(this.endpoint, {
            name, address, phone, email
        });
    }

    async update(id, companyData) {
        const { name, address, phone, email } = companyData;
        return await this.api.put(`${this.endpoint}/${id}`, {
            name, address, phone, email
        });
    }
}

class EventAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.EVENTS);
    }

    async getAll() {
        const response = await this.api.get(this.endpoint);
        // Backend returns array directly
        return Array.isArray(response) ? response : (response.allEvents || response.data || response);
    }

    async getById(id) {
        const response = await this.api.get(`${this.endpoint}/${id}`);
        // Backend returns object directly
        return response.event || response.data || response;
    }

    async create(eventData) {
        const { name, description, type, eventDate, eventEndDate, startTime, endTime, location, companyId, bannerUrl, email, phone, instagram, facebook, website, linkedin, logo } = eventData;
        const response = await this.api.post(this.endpoint, {
            name, 
            description, 
            type, 
            eventDate, 
            eventEndDate, 
            startTime, 
            endTime, 
            location, 
            companyId, 
            bannerUrl, 
            email, 
            phone, 
            instagram, 
            facebook, 
            website, 
            linkedin, 
            logo
        });
        // Backend returns object directly
        return response;
    }

    async update(id, eventData) {
        const { name, description, type, eventDate, eventEndDate, startTime, endTime, location, companyId, bannerUrl, email, phone, instagram, facebook, website, linkedin, logo } = eventData;
        const response = await this.api.put(`${this.endpoint}/${id}`, {
            name, 
            description, 
            type, 
            eventDate, 
            eventEndDate, 
            startTime, 
            endTime, 
            location, 
            companyId, 
            bannerUrl, 
            email, 
            phone, 
            instagram, 
            facebook, 
            website, 
            linkedin, 
            logo
        });
        // Backend returns object directly
        return response;
    }

    async delete(id) {
        const response = await this.api.delete(`${this.endpoint}/${id}`);
        return response.message || response;
    }
}

class UserAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.USERS);
    }
}

class TicketAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.TICKETS);
    }

    async getAll() {
        const response = await this.api.get(this.endpoint);
        return response.allTickets || response.data || response;
    }

    async getById(id) {
        const response = await this.api.get(`${this.endpoint}/${id}`);
        return response.ticket || response.data || response;
    }

    async create(ticketData) {
        const { eventId, eventZoneId, couponId, name, info, price, quantity } = ticketData;
        const response = await this.api.post(this.endpoint, {
            eventId, eventZoneId, couponId, name, info, price, quantity
        });
        return response.ticket || response.data || response;
    }

    async update(id, ticketData) {
        const { eventId, eventZoneId, couponId, name, info, price, quantity } = ticketData;
        const response = await this.api.put(`${this.endpoint}/${id}`, {
            eventId, eventZoneId, couponId, name, info, price, quantity
        });
        return response.ticket || response.data || response;
    }

    async delete(id) {
        const response = await this.api.delete(`${this.endpoint}/${id}`);
        return response.message || response.data || response;
    }
}

class CouponAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.COUPONS);
    }

    async getAll() {
        const response = await this.api.get(this.endpoint);
        // Backend returns array directly
        return Array.isArray(response) ? response : (response.allCoupons || response.data || response);
    }

    async getById(id) {
        const response = await this.api.get(`${this.endpoint}/${id}`);
        // Backend returns object directly  
        return response.coupon || response.data || response;
    }

    async create(couponData) {
        const { code, discount, expiryDate, usageCount, maxUsage } = couponData;
        const response = await this.api.post(this.endpoint, {
            code, discount, expiryDate, usageCount, maxUsage
        });
        return response.coupon || response.data || response;
    }

    async update(id, couponData) {
        const { code, discount, expiryDate, usageCount, maxUsage } = couponData;
        const response = await this.api.put(`${this.endpoint}/${id}`, {
            code, discount, expiryDate, usageCount, maxUsage
        });
        return response.coupon || response.data || response;
    }

    async delete(id) {
        const response = await this.api.delete(`${this.endpoint}/${id}`);
        return response.message || response.data || response;
    }
}

class AttendeeAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.ATTENDEES);
    }

    async getAll() {
        const response = await this.api.get(this.endpoint);
        return response.allAttendees || response.data || response;
    }

    async getById(id) {
        const response = await this.api.get(`${this.endpoint}/${id}`);
        return response.attendee || response.data || response;
    }

    async create(attendeeData) {
        const { firstName, lastName, email, phone, company, jobTitle, country, eventId, ticketId } = attendeeData;
        const response = await this.api.post(this.endpoint, {
            firstName, lastName, email, phone, company, jobTitle, country, eventId, ticketId
        });
        return response.createdAttendee || response.attendee || response.data || response;
    }

    async update(id, attendeeData) {
        const { firstName, lastName, email, phone, company, jobTitle, country, eventId, ticketId } = attendeeData;
        const response = await this.api.put(`${this.endpoint}/${id}`, {
            firstName, lastName, email, phone, company, jobTitle, country, eventId, ticketId
        });
        return response.modifiedAttendee || response.attendee || response.data || response;
    }

    async delete(id) {
        const response = await this.api.delete(`${this.endpoint}/${id}`);
        return response.message || response.data || response;
    }
}

class EventZoneAPI extends BaseAPI {
    constructor(apiClient) {
        super(apiClient, API_CONFIG.ENDPOINTS.EVENT_ZONES);
    }

    async getAll() {
        const response = await this.api.get(this.endpoint);
        // Backend returns array directly
        return Array.isArray(response) ? response : (response.allEventZones || response.data || response);
    }

    async getById(id) {
        const response = await this.api.get(`${this.endpoint}/${id}`);
        return { data: response.data || response };
    }

    async create(eventZoneData) {
        // Map event_id to eventId for backend consistency
        const { event_id, name, capacity } = eventZoneData;
        const response = await this.api.post(this.endpoint, {
            eventId: event_id, 
            name, 
            capacity: parseInt(capacity)
        });
        return response.data || response;
    }

    async update(id, eventZoneData) {
        // Map event_id to eventId for backend consistency  
        const { event_id, name, capacity } = eventZoneData;
        const response = await this.api.put(`${this.endpoint}/${id}`, {
            eventId: event_id,
            name, 
            capacity: parseInt(capacity)
        });
        return response.data || response;
    }

    async delete(id) {
        const response = await this.api.delete(`${this.endpoint}/${id}`);
        return response.message || response.data || response;
    }
}

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

    // Format event date
    formatEventDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
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
    },

    // Format currency
    formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },

    // Format time
    formatTime(timeString) {
        const time = new Date(`1970-01-01T${timeString}Z`);
        return time.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
};

// Initialize API client and all API instances (only when axios is available)
function initializeAPIs() {
    console.log('🚀 Starting API initialization...');
    
    // Check if axios is available
    if (typeof axios === 'undefined') {
        console.error('❌ Axios is not loaded yet. Cannot initialize APIs.');
        return false;
    }
    
    console.log('✅ Axios is available, proceeding with initialization...');
    
    try {
        console.log('📝 Creating API client...');
        const apiClient = new ApiClient();
        
        console.log('📝 Creating API instances...');
        const companyAPI = new CompanyAPI(apiClient);
        const eventAPI = new EventAPI(apiClient);
        const userAPI = new UserAPI(apiClient);
        const ticketAPI = new TicketAPI(apiClient);
        
        console.log('🎫 Creating CouponAPI instance...');
        const couponAPI = new CouponAPI(apiClient);
        console.log('🎫 CouponAPI created:', couponAPI);
        console.log('🎫 CouponAPI methods:', Object.getOwnPropertyNames(Object.getPrototypeOf(couponAPI)));
        console.log('🎫 CouponAPI.getAll type:', typeof couponAPI.getAll);
        
        const attendeeAPI = new AttendeeAPI(apiClient);
        const eventZoneAPI = new EventZoneAPI(apiClient);

        console.log('📝 API instances created:', {
            companyAPI: typeof companyAPI,
            eventAPI: typeof eventAPI,
            couponAPI: typeof couponAPI,
            couponAPIHasGetAll: typeof couponAPI.getAll,
            eventZoneAPI: typeof eventZoneAPI,
            eventAPIHasGetAll: typeof eventAPI.getAll,
            eventZoneAPIHasGetAll: typeof eventZoneAPI.getAll
        });

        // Export for use in other files
        if (typeof window !== 'undefined') {
            window.API_CONFIG = API_CONFIG;
            window.apiClient = apiClient;
            
            // Export as class names (uppercase) to match form usage
            window.CompanyAPI = companyAPI;
            window.EventAPI = eventAPI;
            window.UserAPI = userAPI;
            window.TicketAPI = ticketAPI;
            window.CouponAPI = couponAPI;
            
            console.log('🎫 After assignment to window:');
            console.log('🎫 window.CouponAPI:', window.CouponAPI);
            console.log('🎫 window.CouponAPI.getAll:', typeof window.CouponAPI.getAll);
            
            window.AttendeeAPI = attendeeAPI;
            window.EventZoneAPI = eventZoneAPI;
            window.Utils = Utils;
            
            // Also export as lowercase for backward compatibility
            window.companyAPI = companyAPI;
            window.eventAPI = eventAPI;
            window.userAPI = userAPI;
            window.ticketAPI = ticketAPI;
            window.couponAPI = couponAPI;
            window.attendeeAPI = attendeeAPI;
            window.eventZoneAPI = eventZoneAPI;
            
            console.log('✅ All APIs initialized and exported to window');
            console.log('🔍 Window exports verification:', {
                EventAPI: typeof window.EventAPI,
                EventZoneAPI: typeof window.EventZoneAPI,
                EventAPIGetAll: window.EventAPI ? typeof window.EventAPI.getAll : 'N/A',
                EventZoneAPIGetAll: window.EventZoneAPI ? typeof window.EventZoneAPI.getAll : 'N/A'
            });
            return true;
        }
    } catch (error) {
        console.error('Error initializing APIs:', error);
        return false;
    }
}

// Manual initialization function for debugging
window.manualInitAPIs = function() {
    console.log('Manual API initialization requested...');
    console.log('axios type:', typeof axios);
    console.log('window.axios type:', typeof window.axios);
    
    if (typeof axios === 'undefined' && typeof window.axios !== 'undefined') {
        console.log('Using window.axios...');
        window.axios = window.axios;
    }
    
    return initializeAPIs();
};

// Debug function to check current API status
window.checkAPIStatus = function() {
    const apis = ['CompanyAPI', 'EventAPI', 'TicketAPI', 'AttendeeAPI', 'CouponAPI', 'EventZoneAPI'];
    console.log('=== API Status Check ===');
    console.log('axios:', typeof axios);
    console.log('API_CONFIG:', typeof API_CONFIG);
    
    apis.forEach(apiName => {
        const api = window[apiName];
        console.log(`${apiName}:`, typeof api, api && typeof api.create === 'function' ? '(has create)' : '(no create)');
    });
    
    return apis.every(apiName => window[apiName] && typeof window[apiName].create === 'function');
};

// Universal API waiting utility
window.waitForAPI = async function(apiName, method = 'create', maxAttempts = 50) {
    for (let i = 0; i < maxAttempts; i++) {
        const api = window[apiName];
        if (api && typeof api[method] === 'function') {
            console.log(`${apiName}.${method} is ready after ${i + 1} attempts`);
            return true;
        }
        console.log(`Waiting for ${apiName}.${method}... attempt ${i + 1}`);
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    console.error(`${apiName}.${method} not available after ${maxAttempts} attempts`);
    return false;
};

// Try to initialize immediately if axios is already available
console.log('API script loaded. Checking axios availability...');
console.log('typeof axios:', typeof axios);

if (typeof axios !== 'undefined') {
    console.log('Axios is available, initializing APIs immediately...');
    initializeAPIs();
} else {
    console.log('Axios not available yet, setting up polling...');
    // Wait for axios to be available
    let attempts = 0;
    const maxAttempts = 50; // 5 seconds max
    const interval = setInterval(() => {
        attempts++;
        console.log(`Attempt ${attempts}: checking for axios...`);
        
        if (typeof axios !== 'undefined') {
            console.log('Axios found! Initializing APIs...');
            clearInterval(interval);
            initializeAPIs();
        } else if (attempts >= maxAttempts) {
            clearInterval(interval);
            console.error('Timeout waiting for axios to load after 5 seconds');
            
            // Try one more time with window.axios
            if (typeof window.axios !== 'undefined') {
                console.log('Found window.axios, trying initialization...');
                window.axios = window.axios; // Make sure it's in global scope
                initializeAPIs();
            }
        }
    }, 100);
}