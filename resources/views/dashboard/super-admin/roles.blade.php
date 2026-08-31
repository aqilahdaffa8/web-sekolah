@extends('layouts.dashboard')

@section('title', 'Manajemen Role')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Role & Hak Akses</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar role dan perizinan sistem.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-role">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Role
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="roles-table" :headers="['ID', 'Nama Role', 'Jumlah Izin', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

{{-- Modal Tambah/Edit Role --}}
<x-ui.modal id="role-modal" title="Data Role" maxWidth="sm">
    <form id="role-form">
        <input type="hidden" id="role-id">
        <x-ui.form-input type="text" name="role_name" label="Nama Role" required="true" placeholder="mis. Admin Keuangan" />
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="role-modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody   = document.getElementById('roles-table');
        const loading = document.getElementById('roles-table-loading');
        const empty   = document.getElementById('roles-table-empty');
        const form    = document.getElementById('role-form');
        const modal   = document.getElementById('role-modal');

        let roles = [];

        const loadRoles = async () => {
            if (tbody)   tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty)   empty.classList.add('hidden');

            try {
                const data = await window.api.get('/admin/roles');
                roles = data;

                if (loading) loading.classList.add('hidden');

                if (!data || data.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                data.forEach(role => {
                    const permCount = role.permissions ? role.permissions.length : 0;
                    rows += `
                        <tr>
                            <td class="text-gray-400 text-sm">#${role.id}</td>
                            <td class="font-medium text-gray-900">${role.role_name}</td>
                            <td><span class="badge badge-completed">${permCount} Izin</span></td>
                            <td>
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 text-sm font-medium btn-edit" data-id="${role.id}">Edit</button>
                                <button type="button" class="text-red-600 hover:text-red-900 text-sm font-medium btn-delete" data-id="${role.id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });

                if (tbody) {
                    tbody.innerHTML = rows;
                    tbody.querySelectorAll('.btn-edit').forEach(btn =>
                        btn.addEventListener('click', e => editRole(e.target.getAttribute('data-id')))
                    );
                    tbody.querySelectorAll('.btn-delete').forEach(btn =>
                        btn.addEventListener('click', e => deleteRole(e.target.getAttribute('data-id')))
                    );
                }
            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data role', 'error');
            }
        };

        document.getElementById('btn-add-role').addEventListener('click', () => {
            form.reset();
            document.getElementById('role-id').value = '';
            modal.classList.remove('hidden');
        });

        const editRole = (id) => {
            const role = roles.find(r => r.id == id);
            if (!role) return;
            form.reset();
            document.getElementById('role-id').value = role.id;
            form.elements['role_name'].value = role.role_name;
            modal.classList.remove('hidden');
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('role-id').value;
            const payload = { role_name: form.elements['role_name'].value };

            try {
                if (id) {
                    await window.api.put(`/admin/roles/${id}`, payload);
                    window.showToast('Role berhasil diperbarui');
                } else {
                    await window.api.post('/admin/roles', payload);
                    window.showToast('Role berhasil ditambahkan');
                }
                modal.classList.add('hidden');
                loadRoles();
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menyimpan role', 'error');
            }
        });

        const deleteRole = async (id) => {
            if (!confirm('Yakin ingin menghapus role ini?')) return;
            try {
                await window.api.delete(`/admin/roles/${id}`);
                window.showToast('Role berhasil dihapus');
                loadRoles();
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menghapus role', 'error');
            }
        };

        loadRoles();
    });
</script>
@endsection
