@extends('layouts.dashboard')

@section('title', 'Manajemen Menu')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Menu Navigasi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola susunan menu yang tampil di navigasi web sekolah.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-menu">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Menu
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="menus-table" :headers="['Label', 'URL', 'Induk', 'Urutan', 'Status', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

{{-- Modal Tambah/Edit Menu --}}
<x-ui.modal id="menu-modal" title="Data Menu" maxWidth="md">
    <form id="menu-form">
        <input type="hidden" id="menu-id">
        <x-ui.form-input type="text" name="label" label="Label Menu" required="true" placeholder="mis. Profil Sekolah" />
        <x-ui.form-input type="text" name="url" label="URL Tujuan" required="true" placeholder="mis. /profil" />

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Menu Induk (Opsional)</label>
            <select name="parent_id" id="parent-select" class="form-input w-full">
                <option value="">-- Tidak ada (Menu Utama) --</option>
            </select>
        </div>

        <x-ui.form-input type="number" name="order" label="Urutan Tampil" placeholder="1" />

        <div class="mb-4 flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is-active-check" class="h-4 w-4 rounded border-gray-300" checked>
            <label for="is-active-check" class="text-sm font-medium text-gray-700">Aktifkan menu ini</label>
        </div>

        <div class="crud-modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close="menu-modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-save">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody       = document.getElementById('menus-table');
        const loading     = document.getElementById('menus-table-loading');
        const empty       = document.getElementById('menus-table-empty');
        const form        = document.getElementById('menu-form');
        const modal       = document.getElementById('menu-modal');
        const parentSelect = document.getElementById('parent-select');

        let menus = [];
        let flatMenus = [];

        // Flatten nested menus for dropdown
        const flattenMenus = (list, prefix = '') => {
            list.forEach(m => {
                flatMenus.push({ id: m.id, label: prefix + m.label });
                if (m.children && m.children.length) {
                    flattenMenus(m.children, prefix + '— ');
                }
            });
        };

        const loadMenus = async () => {
            if (tbody)   tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty)   empty.classList.add('hidden');
            flatMenus = [];

            try {
                // API returns top-level menus with children nested
                const data = await window.api.get('/admin/menus');
                menus = data;
                flattenMenus(data);

                // Populate parent dropdown
                let options = '<option value="">-- Tidak ada (Menu Utama) --</option>';
                flatMenus.forEach(m => {
                    options += `<option value="${m.id}">${m.label}</option>`;
                });
                parentSelect.innerHTML = options;

                if (loading) loading.classList.add('hidden');

                if (!data || data.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                // Render rows (including children indented)
                const renderRows = (list, depth = 0) => {
                    let rows = '';
                    const indent = depth > 0 ? `<span class="ml-${depth * 4} text-gray-400">└ </span>` : '';
                    list.forEach(menu => {
                        const statusBadge = menu.is_active ? 'badge-active' : 'badge-open';
                        const statusLabel = menu.is_active ? 'Aktif' : 'Nonaktif';
                        rows += `
                            <tr>
                                <td class="font-medium text-gray-900">${indent}${menu.label}</td>
                                <td class="text-gray-500 text-sm">${menu.url}</td>
                                <td class="text-gray-400 text-sm">${depth > 0 ? 'Sub-menu' : 'Utama'}</td>
                                <td class="text-gray-600">${menu.order ?? 0}</td>
                                <td><span class="badge ${statusBadge}">${statusLabel}</span></td>
                                <td>
                                    <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 text-sm font-medium btn-edit" data-id="${menu.id}">Edit</button>
                                    <button type="button" class="text-red-600 hover:text-red-900 text-sm font-medium btn-delete" data-id="${menu.id}">Hapus</button>
                                </td>
                            </tr>
                        `;
                        if (menu.children && menu.children.length) {
                            rows += renderRows(menu.children, depth + 1);
                        }
                    });
                    return rows;
                };

                if (tbody) {
                    tbody.innerHTML = renderRows(data);
                    tbody.querySelectorAll('.btn-edit').forEach(btn =>
                        btn.addEventListener('click', e => editMenu(e.target.getAttribute('data-id')))
                    );
                    tbody.querySelectorAll('.btn-delete').forEach(btn =>
                        btn.addEventListener('click', e => deleteMenu(e.target.getAttribute('data-id')))
                    );
                }
            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data menu', 'error');
            }
        };

        document.getElementById('btn-add-menu').addEventListener('click', () => {
            form.reset();
            document.getElementById('menu-id').value = '';
            document.getElementById('is-active-check').checked = true;
            modal.classList.remove('hidden');
        });

        const findMenuById = (list, id) => {
            for (const m of list) {
                if (m.id == id) return m;
                if (m.children) {
                    const found = findMenuById(m.children, id);
                    if (found) return found;
                }
            }
            return null;
        };

        const editMenu = (id) => {
            const menu = findMenuById(menus, id);
            if (!menu) return;
            form.reset();
            document.getElementById('menu-id').value     = menu.id;
            form.elements['label'].value                 = menu.label    ?? '';
            form.elements['url'].value                   = menu.url      ?? '';
            form.elements['order'].value                 = menu.order    ?? '';
            form.elements['parent_id'].value             = menu.parent_id ?? '';
            document.getElementById('is-active-check').checked = menu.is_active ? true : false;
            modal.classList.remove('hidden');
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('menu-id').value;
            const payload = {
                label:     form.elements['label'].value,
                url:       form.elements['url'].value,
                order:     parseInt(form.elements['order'].value) || 0,
                parent_id: form.elements['parent_id'].value || null,
                is_active: document.getElementById('is-active-check').checked,
            };

            try {
                if (id) {
                    await window.api.put(`/admin/menus/${id}`, payload);
                    window.showToast('Menu berhasil diperbarui');
                } else {
                    await window.api.post('/admin/menus', payload);
                    window.showToast('Menu berhasil ditambahkan');
                }
                modal.classList.add('hidden');
                loadMenus();
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menyimpan menu', 'error');
            }
        });

        const deleteMenu = async (id) => {
            if (!confirm('Yakin ingin menghapus menu ini? Sub-menu di bawahnya juga akan terhapus.')) return;
            try {
                await window.api.delete(`/admin/menus/${id}`);
                window.showToast('Menu berhasil dihapus');
                loadMenus();
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menghapus menu', 'error');
            }
        };

        loadMenus();
    });
</script>
@endsection
