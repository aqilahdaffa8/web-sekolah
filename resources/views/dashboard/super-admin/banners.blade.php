@extends('layouts.dashboard')

@section('title', 'Manajemen Banner')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Banner</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola gambar banner yang tampil di halaman utama web.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-banner">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Banner
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="banners-table" :headers="['Preview', 'URL Gambar', 'URL Link', 'Urutan', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

{{-- Modal Tambah/Edit Banner --}}
<x-ui.modal id="banner-modal" title="Data Banner" maxWidth="md">
    <form id="banner-form">
        <input type="hidden" id="banner-id">
        <x-ui.form-input type="text" name="image_url" label="URL Gambar" required="true" placeholder="https://example.com/gambar.jpg" />
        <x-ui.form-input type="text" name="link_url" label="URL Link (Opsional)" placeholder="https://example.com/halaman-tujuan" />
        <x-ui.form-input type="number" name="sort_order" label="Urutan Tampil" placeholder="1" />
        <div class="crud-modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close="banner-modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-save">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody   = document.getElementById('banners-table');
        const loading = document.getElementById('banners-table-loading');
        const empty   = document.getElementById('banners-table-empty');
        const form    = document.getElementById('banner-form');
        const modal   = document.getElementById('banner-modal');

        let banners = [];

        const loadBanners = async () => {
            if (tbody)   tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty)   empty.classList.add('hidden');

            try {
                const data = await window.api.get('/admin/banners');
                banners = data;

                if (loading) loading.classList.add('hidden');

                if (!data || data.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                data.forEach(banner => {
                    const linkHtml = banner.link_url
                        ? `<a href="${banner.link_url}" target="_blank" class="text-blue-600 text-sm hover:underline truncate max-w-xs block">${banner.link_url}</a>`
                        : '<span class="text-gray-400 text-sm">-</span>';

                    rows += `
                        <tr>
                            <td>
                                <img src="${banner.image_url}" alt="Banner" class="w-28 h-14 object-cover rounded border border-gray-200"
                                    onerror="this.src=''; this.alt='Gambar tidak ditemukan'; this.classList.add('opacity-40')">
                            </td>
                            <td>
                                <a href="${banner.image_url}" target="_blank" class="text-blue-600 text-sm hover:underline truncate max-w-xs block">${banner.image_url}</a>
                            </td>
                            <td>${linkHtml}</td>
                            <td><span class="badge badge-open">${banner.sort_order ?? 0}</span></td>
                            <td>
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 text-sm font-medium btn-edit" data-id="${banner.id}">Edit</button>
                                <button type="button" class="text-red-600 hover:text-red-900 text-sm font-medium btn-delete" data-id="${banner.id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });

                if (tbody) {
                    tbody.innerHTML = rows;
                    tbody.querySelectorAll('.btn-edit').forEach(btn =>
                        btn.addEventListener('click', e => editBanner(e.target.getAttribute('data-id')))
                    );
                    tbody.querySelectorAll('.btn-delete').forEach(btn =>
                        btn.addEventListener('click', e => deleteBanner(e.target.getAttribute('data-id')))
                    );
                }
            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data banner', 'error');
            }
        };

        document.getElementById('btn-add-banner').addEventListener('click', () => {
            form.reset();
            document.getElementById('banner-id').value = '';
            modal.classList.remove('hidden');
        });

        const editBanner = (id) => {
            const banner = banners.find(b => b.id == id);
            if (!banner) return;
            form.reset();
            document.getElementById('banner-id').value = banner.id;
            form.elements['image_url'].value  = banner.image_url  ?? '';
            form.elements['link_url'].value   = banner.link_url   ?? '';
            form.elements['sort_order'].value = banner.sort_order ?? '';
            modal.classList.remove('hidden');
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('banner-id').value;
            const payload = {
                image_url:  form.elements['image_url'].value,
                link_url:   form.elements['link_url'].value   || null,
                sort_order: parseInt(form.elements['sort_order'].value) || 0,
            };

            try {
                if (id) {
                    await window.api.put(`/admin/banners/${id}`, payload);
                    window.showToast('Banner berhasil diperbarui');
                } else {
                    await window.api.post('/admin/banners', payload);
                    window.showToast('Banner berhasil ditambahkan');
                }
                modal.classList.add('hidden');
                loadBanners();
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menyimpan banner', 'error');
            }
        });

        const deleteBanner = async (id) => {
            if (!confirm('Yakin ingin menghapus banner ini?')) return;
            try {
                await window.api.delete(`/admin/banners/${id}`);
                window.showToast('Banner berhasil dihapus');
                loadBanners();
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menghapus banner', 'error');
            }
        };

        loadBanners();
    });
</script>
@endsection
