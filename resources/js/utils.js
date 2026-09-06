<<<<<<< HEAD
/**
 * Utility functions — formatting, DOM helpers, debounce
 */

// ── Date / Time ───────────────────────────────────────────────
const MONTHS_ID = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
];

export function formatDate(dateStr, options = {}) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    if (isNaN(d)) return dateStr;

    const { short = false, withTime = false } = options;
    const day   = d.getDate();
    const month = short ? MONTHS_ID[d.getMonth()].slice(0,3) : MONTHS_ID[d.getMonth()];
    const year  = d.getFullYear();
    let result  = `${day} ${month} ${year}`;

    if (withTime) {
        const hh = String(d.getHours()).padStart(2,'0');
        const mm = String(d.getMinutes()).padStart(2,'0');
        result += ` ${hh}:${mm}`;
    }
    return result;
}

export function formatRelativeTime(dateStr) {
    if (!dateStr) return '-';
    const d     = new Date(dateStr);
    const now   = new Date();
    const diffMs = now - d;
    const diffS  = Math.floor(diffMs / 1000);
    const diffM  = Math.floor(diffS / 60);
    const diffH  = Math.floor(diffM / 60);
    const diffD  = Math.floor(diffH / 24);

    if (diffS < 60)   return 'baru saja';
    if (diffM < 60)   return `${diffM} menit lalu`;
    if (diffH < 24)   return `${diffH} jam lalu`;
    if (diffD < 7)    return `${diffD} hari lalu`;
    return formatDate(dateStr, { short: true });
}

// ── Currency ──────────────────────────────────────────────────
export function formatCurrency(amount) {
    if (amount == null) return '-';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);
}

export function formatNumber(n) {
    if (n == null) return '-';
    return new Intl.NumberFormat('id-ID').format(n);
}

// ── String helpers ────────────────────────────────────────────
export function slugify(text) {
    return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

export function truncate(str, length = 100) {
    if (!str) return '';
    return str.length > length ? str.slice(0, length) + '…' : str;
}

export function stripHtml(html) {
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.textContent || div.innerText || '';
}

export function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// ── DOM helpers ───────────────────────────────────────────────
export function $(selector, context = document) {
    return context.querySelector(selector);
}

export function $$(selector, context = document) {
    return [...context.querySelectorAll(selector)];
}

export function setHTML(selector, html) {
    const el = typeof selector === 'string' ? $(selector) : selector;
    if (el) el.innerHTML = html;
}

export function show(el) {
    const node = typeof el === 'string' ? $(el) : el;
    if (node) node.classList.remove('hidden');
}

export function hide(el) {
    const node = typeof el === 'string' ? $(el) : el;
    if (node) node.classList.add('hidden');
}

export function toggle(el, condition) {
    const node = typeof el === 'string' ? $(el) : el;
    if (!node) return;
    if (condition === undefined) {
        node.classList.toggle('hidden');
    } else {
        condition ? show(node) : hide(node);
    }
}

// ── Form helpers ──────────────────────────────────────────────
export function formToObject(form) {
    const data = {};
    new FormData(form).forEach((value, key) => {
        if (data[key] !== undefined) {
            if (!Array.isArray(data[key])) data[key] = [data[key]];
            data[key].push(value);
        } else {
            data[key] = value;
        }
    });
    return data;
}

export function setFormErrors(errors = {}) {
    // Clear existing errors
    document.querySelectorAll('.form-error-msg').forEach(el => el.remove());
    document.querySelectorAll('.form-input-error').forEach(el => {
        el.classList.remove('form-input-error');
        el.classList.add('form-input');
    });

    // Set new errors
    Object.entries(errors).forEach(([field, messages]) => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.remove('form-input');
            input.classList.add('form-input-error');
            const errEl = document.createElement('p');
            errEl.className = 'form-error-msg';
            errEl.innerHTML = `
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                ${Array.isArray(messages) ? messages[0] : messages}
            `;
            input.parentNode.insertBefore(errEl, input.nextSibling);
        }
    });
}

export function clearFormErrors() {
    setFormErrors({});
}

// ── Loading state helpers ─────────────────────────────────────
export function setButtonLoading(btn, loading, text = '') {
    if (!btn) return;
    if (loading) {
        btn._originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            ${text || 'Memproses...'}
        `;
    } else {
        btn.disabled = false;
        btn.innerHTML = btn._originalText || text || 'Simpan';
    }
}

// ── Skeleton helpers ──────────────────────────────────────────
export function skeletonCard(count = 3) {
    return Array(count).fill(0).map(() => `
        <div class="card p-4 space-y-3">
            <div class="skeleton h-40 w-full rounded-xl"></div>
            <div class="skeleton h-4 w-3/4 rounded"></div>
            <div class="skeleton h-3 w-1/2 rounded"></div>
            <div class="skeleton h-3 w-full rounded"></div>
        </div>
    `).join('');
}

export function skeletonTableRows(cols = 4, rows = 5) {
    const cells = Array(cols).fill(0).map(() =>
        `<td class="px-4 py-3"><div class="skeleton h-4 w-full rounded"></div></td>`
    ).join('');
    return Array(rows).fill(0).map(() => `<tr>${cells}</tr>`).join('');
}

// ── Functional helpers ────────────────────────────────────────
export function debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

export function throttle(fn, limit = 300) {
    let inThrottle;
    return (...args) => {
        if (!inThrottle) {
            fn(...args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ── Animated counter ──────────────────────────────────────────
export function animateCounter(el, target, duration = 1500) {
    const start    = parseInt(el.textContent.replace(/\D/g,'')) || 0;
    const range    = target - start;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed  = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        // Ease out cubic
        const eased    = 1 - Math.pow(1 - progress, 3);
        el.textContent = formatNumber(Math.round(start + range * eased));
        if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
}

// ── Intersection Observer for scroll animations ───────────────
export function observeElements(selector, callback, options = {}) {
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                callback(entry.target);
                if (!options.repeat) obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2, ...options });

    document.querySelectorAll(selector).forEach(el => obs.observe(el));
    return obs;
}

// ── Lazy-load images ──────────────────────────────────────────
export function initLazyImages() {
    const imgs = document.querySelectorAll('img[data-src]');
    const obs  = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                img.classList.add('animate-fade-in');
                obs.unobserve(img);
            }
        });
    });
    imgs.forEach(img => obs.observe(img));
}

// ── Pagination helper ─────────────────────────────────────────
export function renderPagination(containerId, meta, onPageChange) {
    const el = document.getElementById(containerId);
    if (!el || !meta) return;

    const { current_page, last_page, from, to, total } = meta;
    if (last_page <= 1) { el.innerHTML = ''; return; }

    const pages = [];
    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || Math.abs(i - current_page) <= 2) {
            pages.push(i);
        } else if (pages[pages.length - 1] !== '...') {
            pages.push('...');
        }
    }

    el.innerHTML = `
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <p class="text-sm text-gray-500">
                Menampilkan ${from}–${to} dari ${formatNumber(total)} data
            </p>
            <div class="flex items-center gap-1">
                <button onclick="${onPageChange.name || 'changePage'}(${current_page - 1})"
                    class="btn btn-ghost btn-sm ${current_page === 1 ? 'opacity-40 cursor-not-allowed' : ''}"
                    ${current_page === 1 ? 'disabled' : ''}>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                ${pages.map(p => p === '...'
                    ? `<span class="px-2 text-gray-400">…</span>`
                    : `<button onclick="${onPageChange.name || 'changePage'}(${p})"
                          class="btn btn-sm ${p === current_page ? 'btn-primary' : 'btn-ghost'}">${p}</button>`
                ).join('')}
                <button onclick="${onPageChange.name || 'changePage'}(${current_page + 1})"
                    class="btn btn-ghost btn-sm ${current_page === last_page ? 'opacity-40 cursor-not-allowed' : ''}"
                    ${current_page === last_page ? 'disabled' : ''}>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    `;
}

/**
 * Client-side pagination (for locally filtered arrays)
 * @param {HTMLElement} container
 * @param {number} totalItems
 * @param {number} perPage
 * @param {number} currentPage
 * @param {Function} onPageChange callback(newPage)
 */
export function paginate(container, totalItems, perPage, currentPage, onPageChange) {
    if (!container) return;
    const totalPages = Math.ceil(totalItems / perPage);
    if (totalPages <= 1) { container.innerHTML = ''; return; }

    const pages = [];
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 2) pages.push(i);
        else if (pages[pages.length - 1] !== '...') pages.push('...');
    }

    container.innerHTML = `
        <div class="flex items-center justify-center gap-1">
            <button class="btn btn-ghost btn-sm ${currentPage === 1 ? 'opacity-40 cursor-not-allowed' : ''}"
                    ${currentPage === 1 ? 'disabled' : ''}
                    data-page="${currentPage - 1}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </button>
            ${pages.map(p => p === '...'
                ? `<span class="px-2 text-gray-400">…</span>`
                : `<button class="btn btn-sm ${p === currentPage ? 'btn-primary' : 'btn-ghost'}" data-page="${p}">${p}</button>`
            ).join('')}
            <button class="btn btn-ghost btn-sm ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : ''}"
                    ${currentPage === totalPages ? 'disabled' : ''}
                    data-page="${currentPage + 1}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </button>
        </div>
    `;

    container.querySelectorAll('[data-page]').forEach(btn => {
        if (!btn.disabled) {
            btn.addEventListener('click', () => onPageChange(+btn.dataset.page));
        }
    });
}


// ── Storage image URL helper ──────────────────────────────────
export function storageUrl(path) {
    if (!path) return 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&auto=format&fit=crop&q=80';
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
}

// ── Badge HTML helpers ────────────────────────────────────────
const STATUS_MAP = {
    // Order statuses
    pending:   { cls: 'badge-warning', label: 'Pending'   },
    paid:      { cls: 'badge-info',    label: 'Dibayar'   },
    completed: { cls: 'badge-success', label: 'Selesai'   },
    cancelled: { cls: 'badge-danger',  label: 'Dibatalkan'},
    // Job statuses
    open:      { cls: 'badge-success', label: 'Buka'      },
    closed:    { cls: 'badge-danger',  label: 'Tutup'     },
    // Post statuses
    published: { cls: 'badge-success', label: 'Terbit'    },
    draft:     { cls: 'badge-gray',    label: 'Draft'     },
    // Registration statuses
    approved:  { cls: 'badge-success', label: 'Disetujui' },
    rejected:  { cls: 'badge-danger',  label: 'Ditolak'   },
    // Generic
    active:    { cls: 'badge-success', label: 'Aktif'     },
    inactive:  { cls: 'badge-gray',    label: 'Nonaktif'  },
};

export function statusBadge(status) {
    const s = STATUS_MAP[status?.toLowerCase()] || { cls: 'badge-gray', label: status };
    return `<span class="${s.cls}">${s.label}</span>`;
}
=======
// resources/js/utils.js

export const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
};

export const formatRupiah = (amount) => {
    if (amount === null || amount === undefined) return '-';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

export const showToast = (message, type = 'success') => {
    const event = new CustomEvent('toast-message', {
        detail: { message, type }
    });
    window.dispatchEvent(event);
};
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
