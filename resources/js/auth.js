// resources/js/auth.js
import api from './api';

const auth = {
    async login(email, password) {
        try {
            const response = await api.post('/auth/login', { email, password });
            if (response.token) {
                this.setToken(response.token);
                this.setUser(response.user);
            }
            return response;
        } catch (error) {
            throw error;
        }
    },

    async logout() {
        try {
            await api.post('/auth/logout');
        } catch (error) {
            console.error('Logout error', error);
        } finally {
            this.clearAuth();
            window.location.href = '/';
        }
    },

    setToken(token) {
        localStorage.setItem('auth_token', token);
    },

    getToken() {
        return localStorage.getItem('auth_token');
    },

    setUser(user) {
        localStorage.setItem('auth_user', JSON.stringify(user));
    },

    getUser() {
        const user = localStorage.getItem('auth_user');
        return user ? JSON.parse(user) : null;
    },

    getRoles() {
        const user = this.getUser();
        return user?.roles || [];
    },

    getPermissions() {
        const user = this.getUser();
        return user?.permissions || [];
    },

    clearAuth() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
    },
    
    isAuthenticated() {
        return !!this.getToken();
    }
};

export default auth;
