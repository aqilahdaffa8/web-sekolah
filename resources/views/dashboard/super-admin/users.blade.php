@extends('layouts.dashboard')
@section('title', 'Manajemen User')
@section('breadcrumb')
<li class="flex items-center gap-2 text-gray-400">
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    Super Admin
</li>
<li class="flex items-center gap-2">
    <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    Manajemen User
</li>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Manajemen User</h1>
        <p class="text-gray-500 mt-1">Kelola akun pengguna dan hak akses sistem.</p>
    </div>
    <button onclick="openCreateUserModal()" class="btn btn-primary">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </button>
</div>

{{-- Search & filter --}}
<div class="card mb-5">
    <div class="p-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="search" id="user-search" placeholder="Cari nama atau email..." class="form-input pl-9 py-2 text-sm">
        </div>
        <select id="role-filter" class="form-select w-auto text-sm py-2 min-w-[160px]">
            <option value="">Semua Role</option>
            <option>Super Admin</option>
            <option>Hubin</option>
            <option>Koperasi</option>
            <option>Guru</option>
            <option>Eskul</option>
        </select>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="users-tbody">
                {{-- Skeleton --}}
                @for($i=0;$i<5;$i++)
                <tr>
                    <td><div class="flex items-center gap-3">
                        <div class="skeleton w-9 h-9 rounded-full"></div>
                        <div class="skeleton h-4 w-32 rounded"></div>
                    </div></td>
                    <td><div class="skeleton h-3 w-40 rounded"></div></td>
                    <td><div class="skeleton h-5 w-20 rounded-full"></div></td>
                    <td><div class="skeleton h-5 w-16 rounded-full"></div></td>
                    <td><div class="skeleton h-3 w-24 rounded"></div></td>
                    <td><div class="flex justify-end gap-1">
                        <div class="skeleton w-8 h-8 rounded-lg"></div>
                        <div class="skeleton w-8 h-8 rounded-lg"></div>
                    </div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="users-pagination" class="p-4 border-t border-gray-100"></div>
</div>

{{-- Create/Edit User Modal --}}
<div id="user-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="user-modal-title" class="text-lg font-bold text-gray-900">Tambah User</h3>
            <button data-modal-close class="btn-icon text-gray-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="user-id">
            <div>
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="u-name" name="name" class="form-input" placeholder="Nama lengkap">
            </div>
            <div>
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="u-email" name="email" class="form-input" placeholder="email@smk.sch.id">
            </div>
            <div id="password-field">
                <label class="form-label">Password <span class="text-danger" id="pwd-required">*</span></label>
                <input type="password" id="u-password" name="password" class="form-input" placeholder="Min. 8 karakter">
            </div>
            <div>
                <label class="form-label">Role</label>
                <select id="u-role" class="form-select">
                    <option value="">Pilih role...</option>
                    <option value="Super Admin">Super Admin</option>
                    <option value="Hubin">Hubin</option>
                    <option value="Koperasi">Koperasi</option>
                    <option value="Guru">Guru</option>
                    <option value="Eskul">Eskul</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-modal-close class="btn-ghost">Batal</button>
            <button type="button" id="btn-save-user" onclick="saveUser()" class="btn btn-primary btn-save">Simpan</button>
        </div>
    </div>
</div>

{{-- Delete Confirm --}}
<div id="delete-user-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-sm text-center">
        <div class="p-6">
            <div class="w-14 h-14 bg-danger-light rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus User?</h3>
            <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak dapat diurungkan. Data user akan dihapus permanen.</p>
            <input type="hidden" id="delete-user-id">
            <div class="flex gap-3">
                <button data-modal-close class="btn-ghost flex-1">Batal</button>
                <button onclick="confirmDeleteUser()" id="btn-confirm-delete" class="btn-danger flex-1">Hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
let editingId = null;
let currentPage = 1;
const PER_PAGE = 15;

async function loadUsers(page = 1) {
    currentPage = page;
    const search = document.getElementById('user-search').value;
    const role   = document.getElementById('role-filter').value;

    try {
        const res  = await window.adminApi.users({ page, per_page: PER_PAGE, search, role });
        const data = res.data || [];
        const meta = res.meta || { current_page: page, last_page: 1, from: 1, to: data.length, total: data.length };

        renderTable(data);
        window.utils.renderPagination('users-pagination', meta, loadUsers);
    } catch (err) {
        window.toast.apiError(err);
    }
}

function renderTable(users) {
    document.getElementById('users-tbody').innerHTML = users.length ? users.map(u => `
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-brand-gradient rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        ${(u.name || '?')[0].toUpperCase()}
                    </div>
                    <span class="font-medium text-gray-900">${u.name}</span>
                </div>
            </td>
            <td class="text-gray-500">${u.email}</td>
            <td>
                ${u.roles?.map(r => `<span class="badge-brand mr-1">${r.name}</span>`).join('') || '<span class="text-gray-400 text-xs">—</span>'}
            </td>
            <td>${window.utils.statusBadge(u.active ? 'active' : 'inactive')}</td>
            <td class="text-gray-500 text-xs">${window.utils.formatDate(u.created_at)}</td>
            <td>
                <div class="flex items-center gap-1.5 justify-end">
                    <button onclick="openEditUser(${JSON.stringify(u).replace(/"/g,'&quot;')})"
                            class="btn-table-edit" title="Edit User">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                    </button>
                    <button onclick="openDeleteUser(${u.id})"
                            class="btn-table-delete" title="Hapus User">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('') : `<tr><td colspan="6" class="text-center py-12 text-gray-400">Tidak ada user ditemukan.</td></tr>`;
}

window.openCreateUserModal = () => {
    editingId = null;
    document.getElementById('user-modal-title').textContent = 'Tambah User';
    document.getElementById('user-id').value   = '';
    document.getElementById('u-name').value    = '';
    document.getElementById('u-email').value   = '';
    document.getElementById('u-password').value= '';
    document.getElementById('u-role').value    = '';
    document.getElementById('pwd-required').classList.remove('hidden');
    window.openModal(document.getElementById('user-modal'));
};

window.openEditUser = (user) => {
    if (typeof user === 'string') user = JSON.parse(user);
    editingId = user.id;
    document.getElementById('user-modal-title').textContent = 'Edit User';
    document.getElementById('user-id').value   = user.id;
    document.getElementById('u-name').value    = user.name;
    document.getElementById('u-email').value   = user.email;
    document.getElementById('u-password').value= '';
    document.getElementById('u-role').value    = user.roles?.[0]?.name || '';
    document.getElementById('pwd-required').classList.add('hidden');
    window.openModal(document.getElementById('user-modal'));
};

window.saveUser = async () => {
    const btn = document.getElementById('btn-save-user');
    const payload = {
        name:  document.getElementById('u-name').value,
        email: document.getElementById('u-email').value,
        password: document.getElementById('u-password').value || undefined,
        role:  document.getElementById('u-role').value,
    };

    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.adminApi.updateUser(editingId, payload);
            window.toast.success('User berhasil diperbarui.');
        } else {
            await window.adminApi.createUser(payload);
            window.toast.success('User berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('user-modal'));
        loadUsers(currentPage);
    } catch (err) {
        if (err.status === 422) window.utils.setFormErrors(err.errors);
        else window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.openDeleteUser = (id) => {
    document.getElementById('delete-user-id').value = id;
    window.openModal(document.getElementById('delete-user-modal'));
};

window.confirmDeleteUser = async () => {
    const id  = document.getElementById('delete-user-id').value;
    const btn = document.getElementById('btn-confirm-delete');
    try {
        window.utils.setButtonLoading(btn, true, 'Menghapus...');
        await window.adminApi.deleteUser(id);
        window.closeModal(document.getElementById('delete-user-modal'));
        window.toast.success('User berhasil dihapus.');
        loadUsers(currentPage);
    } catch (err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Hapus');
    }
};

// Filters
document.getElementById('user-search').addEventListener('input', window.utils.debounce(() => loadUsers(1), 400));
document.getElementById('role-filter').addEventListener('change', () => loadUsers(1));

loadUsers();
</script>
@endpush
