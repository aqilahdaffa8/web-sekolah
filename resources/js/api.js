// resources/js/api.js

const API_BASE_URL = '/api';

const api = {
    async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        
        // Setup default headers
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...options.headers,
        };

        // Attach Bearer token if exists
        const token = localStorage.getItem('auth_token');
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const config = {
            ...options,
            headers,
        };

        try {
            const response = await fetch(url, config);
            
            // Parse response body if possible
            let data = null;
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                data = await response.json();
            }

            if (!response.ok) {
                // Global error handling for 401 Unauthorized
                if (response.status === 401) {
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_user');
                    window.location.href = '/login'; // Redirect to login
                }
                
                // Throw custom error object
                throw {
                    status: response.status,
                    message: data?.message || response.statusText,
                    errors: data?.errors || {},
                    data: data
                };
            }

            return data;
        } catch (error) {
            // Handle network errors or custom thrown errors
            console.error('API Error:', error);
            throw error;
        }
    },

    get(endpoint, options = {}) {
        return this.request(endpoint, { ...options, method: 'GET' });
    },

    post(endpoint, body, options = {}) {
        return this.request(endpoint, {
            ...options,
            method: 'POST',
            body: JSON.stringify(body)
        });
    },

    put(endpoint, body, options = {}) {
        return this.request(endpoint, {
            ...options,
            method: 'PUT',
            body: JSON.stringify(body)
        });
    },

    delete(endpoint, options = {}) {
        return this.request(endpoint, { ...options, method: 'DELETE' });
    }
};

export default api;
