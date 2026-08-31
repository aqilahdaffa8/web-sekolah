@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola akun, role, dan akses sistem.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-user">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pengguna
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div class="w-1/3">
            <input type="text" name="search" id="search-input" placeholder="Cari nama atau email..." class="form-input w-full" />
        </div>
        <div>
            <!-- Filter role belum didukung penuh oleh API search saat ini, jadi disembunyikan/disederhanakan -->
        </div>
    </div>
    
    <x-ui.table id="users-table" :headers="['Nama Lengkap', 'Email', 'Role', 'Status', 'Aksi']">
        <!-- Will be populated via JS -->
    </x-ui.table>
</div>

<!-- Modal Create/Edit User -->
<x-ui.modal id="user-modal" title="Data Pengguna" maxWidth="md">
    <form id="user-form">
        <input type="hidden" name="id" id="user-id">
        <x-ui.form-input type="text" name="name" label="Nama Lengkap" required="true" />
        <x-ui.form-input type="email" name="email" label="Email" required="true" />
        <x-ui.form-input type="password" name="password" id="user-password" label="Password" placeholder="Biarkan kosong jika tidak diubah" />
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role Utama</label>
            <select name="role_id" id="role-select" class="form-input w-full" required>
                <option value="">-- Pilih Role --</option>
            </select>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="user-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-user">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('users-table');
        const loading = document.getElementById('users-table-loading');
        const empty = document.getElementById('users-table-empty');
        const form = document.getElementById('user-form');
        const modal = document.getElementById('user-modal');
        const searchInput = document.getElementById('search-input');
        const roleSelect = document.getElementById('role-select');
        
        let users = [];

        // Load Roles for Dropdown
        const loadRoles = async () => {
            try {
                const roles = await window.api.get('/admin/roles');
                let options = '<option value="">-- Pilih Role --</option>';
                roles.forEach(role => {
                    options += `<option value="${role.id}">${role.role_name}</option>`;
                });
                roleSelect.innerHTML = options;
            } catch (error) {
                console.error("Gagal memuat roles:", error);
            }
        };
        
        // Load Users
        const loadUsers = async (search = '') => {
            if(tbody) tbody.innerHTML = '';
            if(loading) loading.classList.remove('hidden');
            if(empty) empty.classList.add('hidden');
            
            try {
                const response = await window.api.get(`/admin/users?search=${search}`);
                const data = response.data; // Because it's paginated
                users = data;
                
                if(loading) loading.classList.add('hidden');
                
                if(!data || data.length === 0) {
                    if(empty) empty.classList.remove('hidden');
                    return;
                }
                
                let rows = '';
                data.forEach(user => {
                    const userRole = user.roles && user.roles.length > 0 ? user.roles[0].role_name : '-';
                    const roleBadge = userRole === 'Super Admin' ? 'badge-active' : 'badge-completed';
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${user.name}</td>
                            <td>${user.email}</td>
                            <td><span class="badge ${roleBadge}">${userRole}</span></td>
                            <td><span class="badge badge-open">Aktif</span></td>
                            <td>
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 text-sm font-medium btn-edit" data-id="${user.id}">Edit</button>
                                <button type="button" class="text-red-600 hover:text-red-900 text-sm font-medium btn-delete" data-id="${user.id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                
                if(tbody) {
                    tbody.innerHTML = rows;
                    
                    // Attach events to dynamically created buttons
                    document.querySelectorAll('.btn-edit').forEach(btn => {
                        btn.addEventListener('click', (e) => editUser(e.target.getAttribute('data-id')));
                    });
                    document.querySelectorAll('.btn-delete').forEach(btn => {
                        btn.addEventListener('click', (e) => deleteUser(e.target.getAttribute('data-id')));
                    });
                }
            } catch (error) {
                if(loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data pengguna', 'error');
            }
        };

        // Open Add Modal
        document.getElementById('btn-add-user').addEventListener('click', () => {
            form.reset();
            document.getElementById('user-id').value = '';
            document.getElementById('user-password').required = true;
            modal.classList.remove('hidden');
        });

        // Edit User function
        const editUser = (id) => {
            const user = users.find(u => u.id == id);
            if(!user) return;
            
            form.reset();
            document.getElementById('user-id').value = user.id;
            form.elements['name'].value = user.name;
            form.elements['email'].value = user.email;
            document.getElementById('user-password').required = false; // Optional when editing
            
            if(user.roles && user.roles.length > 0) {
                form.elements['role_id'].value = user.roles[0].id;
            }
            
            modal.classList.remove('hidden');
        };

        // Form Submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = document.getElementById('user-id').value;
            const payload = {
                name: form.elements['name'].value,
                email: form.elements['email'].value,
            };
            
            const password = form.elements['password'].value;
            if (password) {
                payload.password = password;
            }
            
            const roleId = form.elements['role_id'].value;
            if (roleId) {
                payload.role_ids = [parseInt(roleId)];
            }
            
            try {
                if (id) {
                    await window.api.put(`/admin/users/${id}`, payload);
                    window.showToast('Pengguna berhasil diperbarui');
                } else {
                    await window.api.post('/admin/users', payload);
                    window.showToast('Pengguna berhasil ditambahkan');
                }
                
                modal.classList.add('hidden');
                loadUsers(searchInput.value);
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menyimpan pengguna', 'error');
            }
        });

        // Delete User function
        const deleteUser = async (id) => {
            if(!confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) return;
            
            try {
                await window.api.delete(`/admin/users/${id}`);
                window.showToast('Pengguna berhasil dihapus');
                loadUsers(searchInput.value);
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menghapus pengguna', 'error');
            }
        };

        // Search debounce
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadUsers(e.target.value);
            }, 500);
        });

        // Initialize
        loadRoles();
        loadUsers();
    });
</script>
@endsection
