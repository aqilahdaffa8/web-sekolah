<<<<<<< HEAD
/**
 * app.js — Main entry point
 * Initializes global modules and page-specific logic
 */
import { toast } from './toast.js';
import * as utils from './utils.js';
import * as auth from './auth.js';
import { api, authApi, adminApi, hubinApi, koperasiApi, guruApi, eskulApi, publicApi } from './api.js';

// Expose modules globally for inline scripts in Blade
window.api = api;
window.authApi = authApi;
window.adminApi = adminApi;
window.hubinApi = hubinApi;
window.koperasiApi = koperasiApi;
window.guruApi = guruApi;
window.eskulApi = eskulApi;
window.publicApi = publicApi;
window.auth = auth;
window.utils = utils;
window.toast = toast;

const { initLazyImages } = utils;

// ── Global initialization ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initLazyImages();
    initMobileMenu();
    initTabs();
    initModals();
    initDropdowns();
});

// ── Mobile hamburger menu ─────────────────────────────────────
function initMobileMenu() {
    const toggle  = document.getElementById('mobile-menu-toggle');
    const menu    = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (!toggle || !menu) return;

    function open() {
        menu.classList.remove('translate-x-full', '-translate-x-full');
        menu.classList.add('translate-x-0');
        overlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        toggle.setAttribute('aria-expanded', 'true');
    }

    function close() {
        const isRight = menu.classList.contains('right-0');
        menu.classList.remove('translate-x-0');
        menu.classList.add(isRight ? 'translate-x-full' : '-translate-x-full');
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        isOpen ? close() : open();
    });

    overlay?.addEventListener('click', close);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') close();
    });
}

// ── Tab system (generic) ──────────────────────────────────────
function initTabs() {
    document.querySelectorAll('[data-tab-group]').forEach(group => {
        const groupId = group.dataset.tabGroup;
        const buttons = group.querySelectorAll('[data-tab]');
        const panels  = document.querySelectorAll(`[data-tab-panel][data-group="${groupId}"]`);

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;
                buttons.forEach(b  => b.classList.remove('active'));
                panels.forEach(p   => p.classList.remove('active'));
                btn.classList.add('active');
                document.querySelector(`[data-tab-panel="${target}"]`)?.classList.add('active');
            });
        });
    });
}

// ── Modal system ──────────────────────────────────────────────
function initModals() {
    // Open modal via data-modal-open="modal-id"
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = document.getElementById(btn.dataset.modalOpen);
            openModal(modal);
        });
    });

    // Close modal via data-modal-close
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('[data-modal]');
            closeModal(modal);
        });
    });

    // Close on overlay click
    document.querySelectorAll('[data-modal]').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal(modal);
        });
    });

    // ESC key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-modal].flex').forEach(closeModal);
        }
    });
}

export function openModal(modal) {
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    const box = modal.querySelector('.modal-box');
    if (box) {
        box.classList.add('animate-slide-up');
        setTimeout(() => box.classList.remove('animate-slide-up'), 400);
    }
    document.body.classList.add('overflow-hidden');
}

export function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// ── Dropdown menus ────────────────────────────────────────────
function initDropdowns() {
    document.querySelectorAll('[data-dropdown-toggle]').forEach(toggle => {
        const targetId = toggle.dataset.dropdownToggle;
        const dropdown = document.getElementById(targetId);
        if (!dropdown) return;

        toggle.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = !dropdown.classList.contains('hidden');
            // Close all others
            document.querySelectorAll('[data-dropdown]').forEach(d => {
                d.classList.add('hidden');
            });
            if (!isOpen) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('animate-slide-down');
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('[data-dropdown]').forEach(d => {
            d.classList.add('hidden');
        });
    });
}

// ── Expose utilities globally for inline scripts ──────────────
window.openModal  = openModal;
window.closeModal = closeModal;
=======
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
    // ── Populate topbar user info ──────────────────────────────────────────
    const user = auth.getUser();
    if (user) {
        const nameEl     = document.getElementById('user-name-display');
        const rolEl      = document.getElementById('user-role-display');
        const nameMobile = document.getElementById('user-name-mobile');
        const rolMobile  = document.getElementById('user-role-mobile');
        const avatarEl   = document.getElementById('user-avatar-initials');

        const displayName  = user.name ?? 'User';
        const displayRoles = Array.isArray(user.roles) ? user.roles.join(', ') : (user.roles ?? '');
        const initials     = displayName.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();

        if (nameEl)     nameEl.textContent     = displayName;
        if (rolEl)      rolEl.textContent      = displayRoles;
        if (nameMobile) nameMobile.textContent = displayName;
        if (rolMobile)  rolMobile.textContent  = displayRoles;
        if (avatarEl)   avatarEl.textContent   = initials;
    }

    // ── Logout button ──────────────────────────────────────────────────────
    const btnLogout = document.getElementById('btn-logout');
    if (btnLogout) {
        btnLogout.addEventListener('click', async () => {
            btnLogout.disabled = true;
            btnLogout.textContent = 'Keluar...';
            await auth.logout();
        });
    }

    // ── Toast listener ─────────────────────────────────────────────────────
    window.addEventListener('toast-message', (e) => {
        const { message, type } = e.detail;

        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        let bgColor = 'bg-blue-600';
        if (type === 'success') bgColor = 'bg-green-600';
        if (type === 'error')   bgColor = 'bg-red-600';
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

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    });

    // ── Modal close logic ──────────────────────────────────────────────────
    document.querySelectorAll('[data-modal-close]').forEach(button => {
        button.addEventListener('click', (e) => {
            const modalId = e.currentTarget.getAttribute('data-modal-close');
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        });
    });
});
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
