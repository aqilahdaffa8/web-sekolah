/**
 * Auth Module — Login, logout, user session, role/permission checks
 */
import { authApi } from './api.js';

const STORAGE_KEYS = {
    TOKEN: 'smk_token',
    USER:  'smk_user',
    ROLES: 'smk_roles',
    PERMS: 'smk_permissions',
};

// ── Store / Retrieve ──────────────────────────────────────────
export function setSession({ token, user, roles = [], permissions = [] }) {
    localStorage.setItem(STORAGE_KEYS.TOKEN, token);
    localStorage.setItem(STORAGE_KEYS.USER,  JSON.stringify(user));
    localStorage.setItem(STORAGE_KEYS.ROLES, JSON.stringify(roles));
    localStorage.setItem(STORAGE_KEYS.PERMS, JSON.stringify(permissions));
}

export function clearSession() {
    Object.values(STORAGE_KEYS).forEach(k => localStorage.removeItem(k));
}

export function getToken()       { return localStorage.getItem(STORAGE_KEYS.TOKEN); }
export function getUser()        { return JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || 'null'); }
export function getRoles()       { return JSON.parse(localStorage.getItem(STORAGE_KEYS.ROLES) || '[]'); }
export function getPermissions() { return JSON.parse(localStorage.getItem(STORAGE_KEYS.PERMS) || '[]'); }

export function isLoggedIn()     { return !!getToken() && !!getUser(); }

// ── Role / Permission Checks ──────────────────────────────────
export function hasRole(role) {
    const roles = getRoles();
    if (Array.isArray(role)) return role.some(r => roles.includes(r));
    return roles.includes(role);
}

export function hasPermission(perm) {
    if (hasRole('Super Admin')) return true; // Super Admin bypasses all
    const perms = getPermissions();
    if (Array.isArray(perm)) return perm.some(p => perms.includes(p));
    return perms.includes(perm);
}

export function isSuperAdmin() { return hasRole('Super Admin'); }
export function isHubin()      { return hasRole(['Hubin', 'Super Admin']); }
export function isKoperasi()   { return hasRole(['Koperasi', 'Super Admin']); }
export function isGuru()       { return hasRole(['Guru', 'Super Admin']); }
export function isEskul()      { return hasRole(['Eskul', 'Super Admin']); }
export function isSiswa()      { return hasRole('Siswa'); }

export function getStudent() {
    return getUser()?.student ?? null;
}

export function isActiveStudent() {
    return isSiswa() && getStudent()?.status === 'aktif';
}

function safeRedirectPath() {
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');
    if (redirect && redirect.startsWith('/') && !redirect.startsWith('//')) {
        return redirect;
    }

    return null;
}

// ── Get default dashboard route per role ──────────────────────
export function getDashboardRoute() {
    const redirect = safeRedirectPath();
    if (redirect) return redirect;
    if (isSiswa() && !isSuperAdmin()) return '/siswa/nilai';
    if (isSuperAdmin()) return '/dashboard';
    if (isHubin())      return '/dashboard/hubin/mitra';
    if (isKoperasi())   return '/dashboard/koperasi/produk';
    if (isGuru())       return '/dashboard/guru/nilai';
    if (isEskul())      return '/dashboard/eskul/kelola';
    return '/dashboard';
}

// ── Login ─────────────────────────────────────────────────────
export async function login(email, password) {
    const data = await authApi.login({ email, password });

    // Normalise roles — some backends return array of strings, others objects
    const rawRoles = data.roles ?? data.user?.roles ?? [];
    const roles = rawRoles.map(r => (typeof r === 'object' ? (r.role_name ?? r.name) : r));

    const rawPerms = data.permissions ?? data.user?.permissions ?? [];
    const permissions = rawPerms.map(p => (typeof p === 'object' ? (p.name) : p));

    setSession({
        token:       data.token ?? data.access_token,
        user:        data.user,
        roles,
        permissions,
    });

    window.location.href = _getDashboardRoute(roles);

    return data;
}

/** Internal helper used at login time (before global helpers are available) */
function _getDashboardRoute(roles) {
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');
    if (redirect && redirect.startsWith('/') && !redirect.startsWith('//')) {
        return redirect;
    }

    if (roles.includes('Siswa') && !roles.includes('Super Admin')) return '/siswa/nilai';
    if (roles.includes('Super Admin')) return '/dashboard';
    if (roles.includes('Hubin'))      return '/dashboard/hubin/mitra';
    if (roles.includes('Koperasi'))   return '/dashboard/koperasi/produk';
    if (roles.includes('Guru'))       return '/dashboard/guru/nilai';
    if (roles.includes('Eskul'))      return '/dashboard/eskul/kelola';
    return '/dashboard';
}

// ── Logout ────────────────────────────────────────────────────
export async function logout() {
    try {
        await authApi.logout();
    } catch (_) { /* ignore if already expired */ }
    clearSession();
    window.location.href = '/login';
}

// ── Guard — redirect if not logged in ────────────────────────
export function requireAuth(redirectTo = '/login') {
    if (!isLoggedIn()) {
        window.location.href = redirectTo;
        return false;
    }
    return true;
}

// ── Guard — redirect if no required role ─────────────────────
export function requireRole(role, redirectTo = '/dashboard') {
    if (!requireAuth()) return false;
    if (!hasRole(role)) {
        window.location.href = redirectTo;
        return false;
    }
    return true;
}

// ── Populate topbar with user info ────────────────────────────
export function populateTopbar() {
    const user  = getUser();
    const roles = getRoles();
    if (!user) return;

    const nameEl  = document.getElementById('topbar-name');
    const roleEl  = document.getElementById('topbar-role');
    const avatarEl = document.getElementById('topbar-avatar');

    if (nameEl)   nameEl.textContent  = user.name || user.email;
    if (roleEl)   roleEl.textContent  = roles[0]  || 'Admin';
    if (avatarEl) avatarEl.textContent = (user.name || user.email || 'A')[0].toUpperCase();
}

// ── Hide elements user doesn't have permission to see ─────────
export function applyPermissionVisibility() {
    // data-requires-role="Super Admin" → hide if not that role
    document.querySelectorAll('[data-requires-role]').forEach(el => {
        const requiredRole = el.dataset.requiresRole;
        if (!hasRole(requiredRole.split(','))) el.style.display = 'none';
    });

    // data-requires-permission="users.create" → hide if no permission
    document.querySelectorAll('[data-requires-permission]').forEach(el => {
        const requiredPerm = el.dataset.requiresPermission;
        if (!hasPermission(requiredPerm.split(','))) el.style.display = 'none';
    });
}
