@extends('layouts.dashboard')

@section('title', 'Manajemen Berita')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Berita & Postingan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola artikel dan berita yang tampil di web sekolah.</p>
    </div>
    <button class="btn btn-primary" id="btn-add-post">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tulis Berita
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div class="w-1/3">
            <input type="text" id="search-input" placeholder="Cari judul berita..." class="form-input w-full" />
        </div>
        <div>
            <select id="filter-status" class="form-input border border-gray-300 rounded-lg">
                <option value="">Semua Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>
    </div>
    <x-ui.table id="posts-table" :headers="['Judul', 'Penulis', 'Status', 'Tanggal', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

{{-- Modal Tambah/Edit Post --}}
<x-ui.modal id="post-modal" title="Tulis Berita" maxWidth="2xl">
    <form id="post-form">
        <input type="hidden" id="post-id">
        <x-ui.form-input type="text" name="title" label="Judul Berita" required="true" placeholder="Masukkan judul berita..." />
        <x-ui.form-input type="text" name="image_url" label="URL Gambar Sampul (Opsional)" placeholder="https://example.com/gambar.jpg" />

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="form-input w-full">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Berita <span class="text-red-500">*</span></label>
            <textarea name="content" rows="8" class="form-input w-full" placeholder="Tulis isi berita di sini..." required></textarea>
        </div>

        <div class="crud-modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close="post-modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-save">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody       = document.getElementById('posts-table');
        const loading     = document.getElementById('posts-table-loading');
        const empty       = document.getElementById('posts-table-empty');
        const form        = document.getElementById('post-form');
        const modal       = document.getElementById('post-modal');
        const searchInput = document.getElementById('search-input');
        const filterStatus = document.getElementById('filter-status');

        let posts = [];

        const loadPosts = async (search = '', status = '') => {
            if (tbody)   tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty)   empty.classList.add('hidden');

            try {
                const response = await window.api.get(`/admin/posts?search=${search}&status=${status}`);
                const data = response.data;
                posts = data;

                if (loading) loading.classList.add('hidden');

                if (!data || data.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                data.forEach(post => {
                    const statusBadge = post.status === 'published' ? 'badge-active' : 'badge-open';
                    const statusLabel = post.status === 'published' ? 'Published' : 'Draft';
                    const author = post.author ? post.author.name : '-';
                    const date   = window.formatDate ? window.formatDate(post.created_at) : post.created_at;

                    rows += `
                        <tr>
                            <td>
                                <div class="font-medium text-gray-900 max-w-xs truncate">${post.title}</div>
                            </td>
                            <td class="text-gray-600 text-sm">${author}</td>
                            <td><span class="badge ${statusBadge}">${statusLabel}</span></td>
                            <td class="text-gray-500 text-sm">${date}</td>
                            <td>
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 text-sm font-medium btn-edit" data-id="${post.id}">Edit</button>
                                <button type="button" class="text-red-600 hover:text-red-900 text-sm font-medium btn-delete" data-id="${post.id}">Hapus</button>
                            </td>
                        </tr>
                    `;
                });

                if (tbody) {
                    tbody.innerHTML = rows;
                    tbody.querySelectorAll('.btn-edit').forEach(btn =>
                        btn.addEventListener('click', e => editPost(e.target.getAttribute('data-id')))
                    );
                    tbody.querySelectorAll('.btn-delete').forEach(btn =>
                        btn.addEventListener('click', e => deletePost(e.target.getAttribute('data-id')))
                    );
                }
            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data berita', 'error');
            }
        };

        document.getElementById('btn-add-post').addEventListener('click', () => {
            form.reset();
            document.getElementById('post-id').value = '';
            modal.classList.remove('hidden');
        });

        const editPost = (id) => {
            const post = posts.find(p => p.id == id);
            if (!post) return;
            form.reset();
            document.getElementById('post-id').value = post.id;
            form.elements['title'].value     = post.title     ?? '';
            form.elements['content'].value   = post.content   ?? '';
            form.elements['image_url'].value = post.image_url ?? '';
            form.elements['status'].value    = post.status    ?? 'draft';
            modal.classList.remove('hidden');
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('post-id').value;
            const payload = {
                title:     form.elements['title'].value,
                content:   form.elements['content'].value,
                image_url: form.elements['image_url'].value || null,
                status:    form.elements['status'].value,
            };

            try {
                if (id) {
                    await window.api.put(`/admin/posts/${id}`, payload);
                    window.showToast('Berita berhasil diperbarui');
                } else {
                    await window.api.post('/admin/posts', payload);
                    window.showToast('Berita berhasil ditambahkan');
                }
                modal.classList.add('hidden');
                loadPosts(searchInput.value, filterStatus.value);
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menyimpan berita', 'error');
            }
        });

        const deletePost = async (id) => {
            if (!confirm('Yakin ingin menghapus berita ini?')) return;
            try {
                await window.api.delete(`/admin/posts/${id}`);
                window.showToast('Berita berhasil dihapus');
                loadPosts(searchInput.value, filterStatus.value);
            } catch (error) {
                console.error(error);
                window.showToast(error.message || 'Gagal menghapus berita', 'error');
            }
        };

        let searchTimeout;
        searchInput.addEventListener('input', e => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadPosts(e.target.value, filterStatus.value), 500);
        });
        filterStatus.addEventListener('change', () => loadPosts(searchInput.value, filterStatus.value));

        loadPosts();
    });
</script>
@endsection
