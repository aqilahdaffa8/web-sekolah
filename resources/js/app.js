/**
 * app.js — Main entry point
 * Initializes global modules and page-specific logic
 */
import { toast } from './toast.js';
import * as utils from './utils.js';
import * as auth from './auth.js';
import { api, authApi, adminApi, hubinApi, koperasiApi, guruApi, eskulApi, publicApi, studentApi } from './api.js';

// Expose modules globally for inline scripts in Blade
window.api = api;
window.authApi = authApi;
window.adminApi = adminApi;
window.hubinApi = hubinApi;
window.koperasiApi = koperasiApi;
window.guruApi = guruApi;
window.eskulApi = eskulApi;
window.publicApi = publicApi;
window.studentApi = studentApi;
window.auth = auth;
window.utils = utils;
window.toast = toast;
window.formatDate = utils.formatDate;
window.formatRupiah = utils.formatCurrency;
window.showToast = (msg, type = 'info') => toast[type] ? toast[type](msg) : toast.info(msg);

const { initLazyImages } = utils;

// ── Global initialization ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initLazyImages();
    initMobileMenu();
    initTabs();
    initModals();
    initDropdowns();
    initPublicNavbarAuth();

    // ── Populate topbar user info ──────────────────────────────────────────
    const user = auth.getUser ? auth.getUser() : null;
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
            if (auth.logout) await auth.logout();
        });
    }

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

    // Close modal via data-modal-close. Delegation also covers dynamically rendered modal content.
    document.addEventListener('click', event => {
        const button = event.target.closest('[data-modal-close]');
        if (!button) return;

        event.preventDefault();
        const modalId = button.dataset.modalClose;
        const modal = modalId
            ? document.getElementById(modalId)
            : button.closest('[data-modal]');

        closeModal(modal);
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

function initPublicNavbarAuth() {
    const desktop = document.getElementById('nav-desktop-auth');
    const mobile = document.getElementById('nav-mobile-auth');
    if (!desktop && !mobile) return;

    if (!auth.isLoggedIn()) return;

    const user = auth.getUser();
    const student = auth.getStudent();
    const isSiswa = auth.isSiswa();
    const displayName = student?.name || user?.name || user?.email || 'Pengguna';
    const initials = displayName.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
    const statusLabel = isSiswa
        ? (student?.status === 'aktif' ? 'Siswa aktif' : 'Siswa')
        : (auth.getRoles()[0] || 'Staf');
    const nisBadge = student?.nis
        ? `<span class="inline-flex items-center px-2 py-0.5 rounded-md bg-brand-50 text-brand-800 text-[11px] font-bold tracking-wide">NIS ${student.nis}</span>`
        : '';
    const extraAction = isSiswa
        ? '<a href="/siswa/nilai" class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 transition-colors">Nilai Saya</a>'
        : `<a href="${auth.getDashboardRoute()}" class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 transition-colors">Dashboard</a>`;

    if (desktop) {
        desktop.innerHTML = `
            <div class="flex items-center gap-2 pl-2 pr-1 py-1 rounded-2xl border border-brand-100 bg-white shadow-sm">
                <div class="w-9 h-9 rounded-xl bg-brand-gradient text-white text-xs font-bold flex items-center justify-center">${initials}</div>
                <div class="leading-tight pr-1">
                    <p class="text-sm font-bold text-brand-900">${displayName}</p>
                    <p class="text-[11px] text-brand-600 font-medium">${statusLabel}${student?.nis ? ` · ${student.nis}` : ''}</p>
                </div>
                ${extraAction}
                <button type="button" data-public-logout class="px-3 py-2 rounded-xl text-sm font-semibold text-brand-800 hover:text-red-700 hover:bg-red-50 transition-colors">Keluar</button>
            </div>
        `;
    }

    if (mobile) {
        mobile.innerHTML = `
            <div class="rounded-2xl border border-brand-100 bg-white p-4 mb-3">
                <p class="text-sm font-bold text-brand-900">${displayName}</p>
                <p class="text-xs text-brand-600 mt-0.5">${statusLabel}</p>
                ${nisBadge ? `<div class="mt-2">${nisBadge}</div>` : ''}
            </div>
            ${extraAction ? extraAction.replace('inline-flex', 'flex w-full justify-center mb-2') : ''}
            <button type="button" data-public-logout class="flex items-center justify-center w-full px-4 py-3 rounded-xl border border-brand-100 text-sm font-bold text-brand-800 hover:bg-brand-50">Keluar</button>
        `;
    }

    document.querySelectorAll('[data-public-logout]').forEach(button => {
        button.addEventListener('click', async () => {
            button.disabled = true;
            button.textContent = 'Keluar...';
            try {
                await authApi.logout();
            } catch (_) { /* ignore expired sessions */ }
            auth.clearSession();
            window.location.href = '/';
        });
    });
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
