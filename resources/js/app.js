import api from './api';
import auth from './auth';
import { formatDate, formatRupiah, showToast } from './utils';

// Expose to window for global access
window.api = api;
window.auth = auth;
window.formatDate = formatDate;
window.formatRupiah = formatRupiah;
window.showToast = showToast;

document.addEventListener('DOMContentLoaded', () => {
    // Basic toast listener implementation
    window.addEventListener('toast-message', (e) => {
        const { message, type } = e.detail;
        
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        // Define colors based on type
        let bgColor = 'bg-blue-600'; // Default
        if (type === 'success') bgColor = 'bg-green-600';
        if (type === 'error') bgColor = 'bg-red-600';
        if (type === 'warning') bgColor = 'bg-yellow-500';

        toast.className = `max-w-xs ${bgColor} text-white text-sm rounded-md shadow-lg mb-3 px-4 py-3 transition-all duration-300 transform translate-y-0 opacity-100 flex items-center justify-between`;
        
        toast.innerHTML = `
            <div>${message}</div>
            <button class="ml-4 text-white hover:text-gray-200 focus:outline-none" onclick="this.parentElement.remove()">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        container.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    });

    // Modal Close Logic
    document.querySelectorAll('[data-modal-close]').forEach(button => {
        button.addEventListener('click', (e) => {
            const modalId = e.currentTarget.getAttribute('data-modal-close');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
            }
        });
    });
});