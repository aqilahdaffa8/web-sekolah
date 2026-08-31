@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola akun, role, dan akses sistem.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('user-modal').classList.remove('hidden')">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pengguna
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div class="w-1/3">
            <x-ui.form-input type="text" name="search" placeholder="Cari nama atau email..." class="w-full" />
        </div>
        <div>
            <select class="form-input" id="filter-role">
                <option value="">Semua Role</option>
                <option value="Super Admin">Super Admin</option>
                <option value="Guru">Guru</option>
                <option value="Hubin">Hubin & BKK</option>
                <option value="Koperasi">Koperasi</option>
                <option value="Eskul">Eskul</option>
            </select>
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
        
        <x-ui.form-input type="select" name="role" label="Role Utama" required="true" :options="[
            'Super Admin' => 'Super Admin',
            'Guru' => 'Guru & Akademik',
            'Hubin' => 'Hubin & BKK',
            'Koperasi' => 'Koperasi & TeFA',
            'Eskul' => 'Eskul'
        ]" />

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="user-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-user">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        // Mock data loading
        const tbody = document.getElementById('users-table-body');
        const loading = document.getElementById('users-table-body-loading');
        const empty = document.getElementById('users-table-body-empty');
        
        const loadUsers = async () => {
            if(tbody) tbody.innerHTML = '';
            if(loading) loading.classList.remove('hidden');
            if(empty) empty.classList.add('hidden');
            
            try {
                // Simulasikan request API
                const data = await window.api.get('/admin/users');
                
                if(loading) loading.classList.add('hidden');
                
                if(!data || data.length === 0) {
                    if(empty) empty.classList.remove('hidden');
                    return;
                }
                
                // Render rows
                let rows = '';
                data.forEach(user => {
                    const roleBadge = user.role === 'Super Admin' ? 'badge-active' : 'badge-completed';
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${user.name}</td>
                            <td>${user.email}</td>
                            <td><span class="badge ${roleBadge}">${user.role}</span></td>
                            <td><span class="badge badge-open">Aktif</span></td>
                            <td>
                                <button class="text-blue-600 hover:text-blue-900 mr-3 text-sm font-medium">Edit</button>
                                <button class="text-red-600 hover:text-red-900 text-sm font-medium">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
                if(tbody) tbody.innerHTML = rows;
            } catch (error) {
                if(loading) loading.classList.add('hidden');
                console.error(error);
                window.utils.showToast('Gagal memuat data pengguna', 'error');
            }
        };

        // Initialize
        loadUsers();
    });
</script>
@endsection
