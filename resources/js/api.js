/**
 * API Module — Centralized fetch wrapper for Laravel REST API
 * Handles auth headers, JSON parsing, error normalization
 */

const API_BASE = '/api';

/**
 * Get stored auth token
 */
export function getToken() {
    return localStorage.getItem('smk_token');
}

/**
 * Build request headers
 */
function buildHeaders(extra = {}) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...extra,
    };
    const token = getToken();
    if (token) headers['Authorization'] = `Bearer ${token}`;
    return headers;
}

/**
 * Parse API response — normalize errors from Laravel
 */
async function parseResponse(res) {
    let data;
    const contentType = res.headers.get('content-type') || '';
    if (contentType.includes('application/json')) {
        data = await res.json();
    } else {
        data = { message: await res.text() };
    }

    if (!res.ok) {
        const error = new Error(data.message || 'Terjadi kesalahan server');
        error.status   = res.status;
        error.errors   = data.errors || {};  // Laravel Form Request validation errors
        error.data     = data;
        throw error;
    }

    return data;
}

/**
 * Core fetch wrapper
 */
async function request(method, path, body = null, headers = {}) {
    const options = {
        method,
        headers: buildHeaders(headers),
        credentials: 'same-origin',
    };

    if (body && method !== 'GET') {
        if (body instanceof FormData) {
            delete options.headers['Content-Type']; // Let browser set multipart boundary
            options.body = body;
        } else {
            options.body = JSON.stringify(body);
        }
    }

    const url = path.startsWith('http') ? path : `${API_BASE}${path}`;
    const res = await fetch(url, options);
    return parseResponse(res);
}

// ── HTTP Methods ──────────────────────────────────────────────
export const api = {
    get:    (path, params = {}) => {
        const qs = new URLSearchParams(params).toString();
        return request('GET', qs ? `${path}?${qs}` : path);
    },
    post:   (path, body)  => request('POST', path, body),
    put:    (path, body)  => request('PUT', path, body),
    patch:  (path, body)  => request('PATCH', path, body),
    delete: (path)        => request('DELETE', path),
    upload: (path, formData) => request('POST', path, formData),
    uploadPut: (path, formData) => request('PUT', path, formData),
};

// ── Public endpoints ──────────────────────────────────────────
export const publicApi = {
    home:               ()        => api.get('/public/home'),
    profile:            ()        => api.get('/public/profile'),
    hubin:              ()        => api.get('/public/hubin'),
    tefa:               ()        => api.get('/public/tefa'),
    extracurriculars:   ()        => api.get('/public/extracurriculars'),
    registerExtracurricular: (data) => api.post('/public/extracurricular-registrations', data),
    achievements:       ()        => api.get('/public/achievements'),
    news:               (params)  => api.get('/public/news', params),
    newsDetail:         (slug)    => api.get(`/public/news/${slug}`),
    events:             ()        => api.get('/public/events'),
    agenda:             ()        => api.get('/public/agenda'),
    placeOrder:         (data)    => api.post('/public/orders', data),
    contact:            (data)    => api.post('/public/contact', data),
};

// ── Auth endpoints ────────────────────────────────────────────
export const authApi = {
    login:  (credentials) => api.post('/auth/login', credentials),
    logout: ()             => api.post('/auth/logout'),
    me:     ()             => api.get('/auth/me'),
};

// ── Admin endpoints ───────────────────────────────────────────
export const adminApi = {
    // Users
    users:          (p)    => api.get('/admin/users', p),
    createUser:     (d)    => api.post('/admin/users', d),
    updateUser:     (id,d) => api.put(`/admin/users/${id}`, d),
    deleteUser:     (id)   => api.delete(`/admin/users/${id}`),
    assignRole:     (uid,d)=> api.post(`/admin/users/${uid}/roles`, d),
    revokeRole:     (uid,rid)=>api.delete(`/admin/users/${uid}/roles/${rid}`),

    // Roles & Permissions
    roles:              (p)      => api.get('/admin/roles', p),
    createRole:         (d)      => api.post('/admin/roles', d),
    updateRole:         (id,d)   => api.put(`/admin/roles/${id}`, d),
    deleteRole:         (id)     => api.delete(`/admin/roles/${id}`),
    assignPermission:   (rid,d)  => api.post(`/admin/roles/${rid}/permissions`, d),
    revokePermission:   (rid,pid)=> api.delete(`/admin/roles/${rid}/permissions/${pid}`),
    permissions:        ()       => api.get('/admin/permissions'),
    createPermission:   (d)      => api.post('/admin/permissions', d),

    // Content
    banners:        (p)    => api.get('/admin/banners', p),
    createBanner:   (d)    => api.upload('/admin/banners', d),
    updateBanner:   (id,d) => api.uploadPut(`/admin/banners/${id}`, d),
    deleteBanner:   (id)   => api.delete(`/admin/banners/${id}`),

    posts:          (p)    => api.get('/admin/posts', p),
    createPost:     (d)    => api.post('/admin/posts', d),
    updatePost:     (id,d) => api.put(`/admin/posts/${id}`, d),
    deletePost:     (id)   => api.delete(`/admin/posts/${id}`),

    siteSettings:   ()     => api.get('/admin/site-settings'),
    saveSettings:   (d)    => api.post('/admin/site-settings', d),

    activityLogs:   (p)    => api.get('/admin/activity-logs', p),

    backups:        ()     => api.get('/admin/backup'),
    createBackup:   ()     => api.post('/admin/backup', {}),
    downloadBackup: (filename) => `/api/admin/backup/${filename}`,
};

// ── Hubin endpoints ───────────────────────────────────────────
export const hubinApi = {
    dudiPartners:       (p)    => api.get('/hubin/dudi-partners', p),
    createDudi:         (d)    => api.upload('/hubin/dudi-partners', d),
    updateDudi:         (id,d) => api.uploadPut(`/hubin/dudi-partners/${id}`, d),
    deleteDudi:         (id)   => api.delete(`/hubin/dudi-partners/${id}`),

    jobVacancies:       (p)    => api.get('/hubin/job-vacancies', p),
    vacancies:           (p)    => api.get('/hubin/job-vacancies', p),
    createJob:          (d)    => api.post('/hubin/job-vacancies', d),
    createVacancy:      (d)    => api.post('/hubin/job-vacancies', d),
    updateJob:          (id,d) => api.put(`/hubin/job-vacancies/${id}`, d),
    updateVacancy:      (id,d) => api.put(`/hubin/job-vacancies/${id}`, d),
    deleteJob:          (id)   => api.delete(`/hubin/job-vacancies/${id}`),
    deleteVacancy:      (id)   => api.delete(`/hubin/job-vacancies/${id}`),

    tracerStudies:      (p)    => api.get('/hubin/tracer-studies', p),
    createTracer:       (d)    => api.post('/hubin/tracer-studies', d),
    createTracerStudy:  (d)    => api.post('/hubin/tracer-studies', d),
    updateTracer:       (id,d) => api.put(`/hubin/tracer-studies/${id}`, d),
    updateTracerStudy:  (id,d) => api.put(`/hubin/tracer-studies/${id}`, d),
    deleteTracer:       (id)   => api.delete(`/hubin/tracer-studies/${id}`),
    deleteTracerStudy:  (id)   => api.delete(`/hubin/tracer-studies/${id}`),
};

// ── Koperasi endpoints ────────────────────────────────────────
export const koperasiApi = {
    products:       (p)    => api.get('/koperasi/products', p),
    createProduct:  (d)    => api.upload('/koperasi/products', d),
    updateProduct:  (id,d) => api.uploadPut(`/koperasi/products/${id}`, d),
    deleteProduct:  (id)   => api.delete(`/koperasi/products/${id}`),

    orders:         (p)    => api.get('/koperasi/orders', p),
    orderDetail:    (id)   => api.get(`/koperasi/orders/${id}`),
    updateStatus:   (id,d) => api.patch(`/koperasi/orders/${id}/status`, d),
};

// ── Guru endpoints ────────────────────────────────────────────
export const guruApi = {
    grades:         (p)    => api.get('/guru/grades', p),
    upsertGrades:   (d)    => api.post('/guru/grades', d),

    modules:        (p)    => api.get('/guru/learning-modules', p),
    learningModules: (p)   => api.get('/guru/learning-modules', p),
    createModule:   (d)    => api.upload('/guru/learning-modules', d),
    createLearningModule: (d) => api.upload('/guru/learning-modules', d),
    updateModule:   (id,d) => api.uploadPut(`/guru/learning-modules/${id}`, d),
    updateLearningModule: (id,d) => api.uploadPut(`/guru/learning-modules/${id}`, d),
    deleteModule:   (id)   => api.delete(`/guru/learning-modules/${id}`),
    deleteLearningModule: (id) => api.delete(`/guru/learning-modules/${id}`),

    facilities:     (p)    => api.get('/guru/facilities', p),
    createFacility: (d)    => api.upload('/guru/facilities', d),
    updateFacility: (id,d) => api.uploadPut(`/guru/facilities/${id}`, d),
    deleteFacility: (id)   => api.delete(`/guru/facilities/${id}`),
};

// ── Eskul endpoints ───────────────────────────────────────────
export const eskulApi = {
    extracurriculars:   (p)    => api.get('/eskul/extracurriculars', p),
    createEskul:        (d)    => api.post('/eskul/extracurriculars', d),
    updateEskul:        (id,d) => api.put(`/eskul/extracurriculars/${id}`, d),
    updateExtracurricular: (id,d) => api.put(`/eskul/extracurriculars/${id}`, d),
    deleteEskul:        (id)   => api.delete(`/eskul/extracurriculars/${id}`),
    deleteExtracurricular: (id) => api.delete(`/eskul/extracurriculars/${id}`),

    registrations:      (p)    => api.get('/eskul/registrations', p),
    updateRegStatus:    (id,d) => api.patch(`/eskul/registrations/${id}/status`, d),
    updateRegistrationStatus: (id, status) => api.patch(`/eskul/registrations/${id}/status`, { status }),

    achievements:       (p)    => api.get('/eskul/achievements', p),
    createAchievement:  (d)    => api.upload('/eskul/achievements', d),
    updateAchievement:  (id,d) => api.uploadPut(`/eskul/achievements/${id}`, d),
    deleteAchievement:  (id)   => api.delete(`/eskul/achievements/${id}`),
};
