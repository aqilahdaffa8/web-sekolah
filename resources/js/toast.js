/**
 * Toast Notification System
 * Usage: toast.success('Berhasil!') | toast.error('Gagal') | toast.warning('...')
 */

let container = null;

function getContainer() {
    if (!container) {
        container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(container);
        }
    }
    return container;
}

const ICONS = {
    success: `<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>`,
    error: `<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
    </svg>`,
    warning: `<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
    </svg>`,
    info: `<svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
    </svg>`,
};

/**
 * Show a toast notification
 * @param {string} message
 * @param {'success'|'error'|'warning'|'info'} type
 * @param {number} duration ms (0 = sticky)
 */
function show(message, type = 'info', duration = 4000) {
    const el = document.createElement('div');
    el.className = `toast-${type} animate-slide-down pointer-events-auto`;
    el.setAttribute('role', 'alert');
    el.innerHTML = `
        ${ICONS[type] || ICONS.info}
        <span class="flex-1">${message}</span>
        <button onclick="this.closest('[role=alert]').remove()"
                class="ml-2 opacity-60 hover:opacity-100 transition-opacity flex-shrink-0"
                aria-label="Tutup">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;

    getContainer().appendChild(el);

    if (duration > 0) {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateX(100%)';
            el.style.transition = 'all 0.3s ease';
            setTimeout(() => el.remove(), 300);
        }, duration);
    }

    return el;
}

export const toast = {
    success: (msg, dur)  => show(msg, 'success', dur),
    error:   (msg, dur)  => show(msg, 'error',   dur),
    warning: (msg, dur)  => show(msg, 'warning', dur),
    info:    (msg, dur)  => show(msg, 'info',    dur),

    /** Show errors from Laravel 422 validation response */
    validationErrors(errors) {
        const msgs = Object.values(errors).flat();
        msgs.forEach(m => show(m, 'error'));
    },

    /** Show error from caught API error */
    apiError(err) {
        if (err.status === 422 && err.errors) {
            this.validationErrors(err.errors);
        } else if (err.status === 401) {
            this.error('Sesi berakhir. Silakan login kembali.');
            setTimeout(() => window.location.href = '/login', 2000);
        } else if (err.status === 403) {
            this.error('Anda tidak memiliki izin untuk aksi ini.');
        } else if (err.status === 429) {
            this.warning('Terlalu banyak percobaan. Coba lagi dalam beberapa menit.');
        } else {
            this.error(err.message || 'Terjadi kesalahan. Silakan coba lagi.');
        }
    },
};

// Expose globally for non-module scripts
window.toast = toast;
