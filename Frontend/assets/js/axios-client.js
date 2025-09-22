/**
 * Axios HTTP Client Module
 * Enhanced HTTP client using Axios for advanced features
 * Version: 2.0.0 - Updated to use local Axios package
 */

// Axios Configuration
const axiosConfig = {
    baseURL: 'http://localhost:3001',
    timeout: 10000,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
};

// Create Axios instance
let axiosInstance;

// Initialize Axios (works with both CDN and local package)
function initializeAxios() {
    if (typeof axios !== 'undefined') {
        axiosInstance = axios.create(axiosConfig);
        
        // Request interceptor
        axiosInstance.interceptors.request.use(
            (config) => {
                // Add auth token if available
                const token = localStorage.getItem('auth_token');
                if (token) {
                    config.headers.Authorization = `Bearer ${token}`;
                }
                return config;
            },
            (error) => Promise.reject(error)
        );
        
        // Response interceptor
        axiosInstance.interceptors.response.use(
            (response) => response,
            (error) => {
                if (error.response?.status === 401) {
                    // Handle unauthorized access
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                }
                return Promise.reject(error);
            }
        );
    } else {
        console.warn('Axios not loaded. Please include Axios library.');
    }
}

// Enhanced API Client using Axios
const AxiosAPI = {
    /**
     * Initialize the Axios client
     */
    init() {
        initializeAxios();
    },

    /**
     * Make a GET request with Axios
     * @param {string} endpoint - API endpoint
     * @param {object} params - Query parameters
     * @returns {Promise} API response
     */
    async get(endpoint, params = {}) {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        
        try {
            const response = await axiosInstance.get(endpoint, { params });
            return {
                success: true,
                data: response.data,
                status: response.status,
                headers: response.headers
            };
        } catch (error) {
            return this.handleError(error);
        }
    },

    /**
     * Make a POST request with Axios
     * @param {string} endpoint - API endpoint
     * @param {object} data - Request body data
     * @returns {Promise} API response
     */
    async post(endpoint, data = {}) {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        
        try {
            const response = await axiosInstance.post(endpoint, data);
            return {
                success: true,
                data: response.data,
                status: response.status,
                headers: response.headers
            };
        } catch (error) {
            return this.handleError(error);
        }
    },

    /**
     * Make a PUT request with Axios
     * @param {string} endpoint - API endpoint
     * @param {object} data - Request body data
     * @returns {Promise} API response
     */
    async put(endpoint, data = {}) {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        
        try {
            const response = await axiosInstance.put(endpoint, data);
            return {
                success: true,
                data: response.data,
                status: response.status,
                headers: response.headers
            };
        } catch (error) {
            return this.handleError(error);
        }
    },

    /**
     * Make a DELETE request with Axios
     * @param {string} endpoint - API endpoint
     * @returns {Promise} API response
     */
    async delete(endpoint) {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        
        try {
            const response = await axiosInstance.delete(endpoint);
            return {
                success: true,
                data: response.data,
                status: response.status,
                headers: response.headers
            };
        } catch (error) {
            return this.handleError(error);
        }
    },

    /**
     * Upload file with progress tracking
     * @param {string} endpoint - API endpoint
     * @param {FormData} formData - Form data with file
     * @param {Function} onProgress - Progress callback
     * @returns {Promise} API response
     */
    async uploadFile(endpoint, formData, onProgress = null) {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        
        try {
            const config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            };
            
            if (onProgress) {
                config.onUploadProgress = (progressEvent) => {
                    const percentCompleted = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total
                    );
                    onProgress(percentCompleted);
                };
            }
            
            const response = await axiosInstance.post(endpoint, formData, config);
            return {
                success: true,
                data: response.data,
                status: response.status,
                headers: response.headers
            };
        } catch (error) {
            return this.handleError(error);
        }
    },

    /**
     * Download file with progress tracking
     * @param {string} endpoint - API endpoint
     * @param {Function} onProgress - Progress callback
     * @returns {Promise} Blob response
     */
    async downloadFile(endpoint, onProgress = null) {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        
        try {
            const config = {
                responseType: 'blob'
            };
            
            if (onProgress) {
                config.onDownloadProgress = (progressEvent) => {
                    const percentCompleted = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total
                    );
                    onProgress(percentCompleted);
                };
            }
            
            const response = await axiosInstance.get(endpoint, config);
            return {
                success: true,
                data: response.data,
                status: response.status,
                headers: response.headers
            };
        } catch (error) {
            return this.handleError(error);
        }
    },

    /**
     * Handle API errors
     * @param {Error} error - Axios error object
     * @returns {object} Formatted error response
     */
    handleError(error) {
        console.error('API Error:', error);
        
        if (error.response) {
            // Server responded with error status
            return {
                success: false,
                error: error.response.data?.message || 'Server error occurred',
                status: error.response.status,
                data: error.response.data
            };
        } else if (error.request) {
            // Request was made but no response received
            return {
                success: false,
                error: 'Network error - no response from server',
                status: null,
                data: null
            };
        } else {
            // Something else happened
            return {
                success: false,
                error: error.message || 'Unknown error occurred',
                status: null,
                data: null
            };
        }
    },

    /**
     * Cancel requests using cancel tokens
     * @returns {object} Cancel token source
     */
    createCancelToken() {
        if (!axiosInstance) {
            throw new Error('Axios not initialized. Call AxiosAPI.init() first.');
        }
        return axios.CancelToken.source();
    },

    /**
     * Set authentication token
     * @param {string} token - JWT or bearer token
     */
    setAuthToken(token) {
        localStorage.setItem('auth_token', token);
        if (axiosInstance) {
            axiosInstance.defaults.headers.Authorization = `Bearer ${token}`;
        }
    },

    /**
     * Clear authentication token
     */
    clearAuthToken() {
        localStorage.removeItem('auth_token');
        if (axiosInstance) {
            delete axiosInstance.defaults.headers.Authorization;
        }
    }
};

// Make AxiosAPI available globally
window.AxiosAPI = AxiosAPI;

// Auto-initialize if axios is already loaded
if (typeof axios !== 'undefined') {
    AxiosAPI.init();
}