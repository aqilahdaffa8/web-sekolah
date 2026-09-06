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
