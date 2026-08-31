@extends('layouts.dashboard')

@section('title', 'Prestasi Eskul')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Prestasi Ekstrakurikuler</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data prestasi dan penghargaan tiap ekstrakurikuler.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Prestasi
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex gap-4 bg-gray-50">
        <select id="eskul-filter" class="form-input w-48">
            <option value="">Semua Eskul</option>
        </select>
    </div>

    <x-ui.table id="achievements-table" :headers="['Prestasi / Penghargaan', 'Ekstrakurikuler', 'Gambar', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="achievement-modal" title="Form Prestasi">
    <form id="achievement-form">
        <input type="hidden" id="achievement_id" name="achievement_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Prestasi</label>
                <input type="text" id="title" name="title" class="form-input w-full" required maxlength="255">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ekstrakurikuler</label>
                <select id="extracurricular_id" name="extracurricular_id" class="form-input w-full">
                    <option value="">-- Pilih Eskul (Opsional) --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                <input type="text" id="image_url" name="image_url" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi & Detail</label>
                <textarea id="description" name="description" class="form-input w-full" rows="4"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="achievement-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-achievement">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('achievements-table');
        const loading = document.getElementById('achievements-table-loading');
        const empty = document.getElementById('achievements-table-empty');
        const modal = document.getElementById('achievement-modal');
        const form = document.getElementById('achievement-form');
        const eskulFilter = document.getElementById('eskul-filter');
        
        let achievements = [];
        let masterData = { extracurriculars: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/eskul/master-data');
                const formSelect = document.getElementById('extracurricular_id');
                masterData.extracurriculars.forEach(e => {
                    eskulFilter.add(new Option(e.name, e.id));
                    formSelect.add(new Option(e.name, e.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('achievement_id').value = '';
            
            if (id) {
                const a = achievements.find(x => x.id === id);
                if (a) {
                    document.getElementById('achievement_id').value = a.id;
                    document.getElementById('title').value = a.title;
                    document.getElementById('extracurricular_id').value = a.extracurricular_id || '';
                    document.getElementById('image_url').value = a.image_url || '';
                    document.getElementById('description').value = a.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteAchievement = async (id) => {
            if (!confirm('Hapus data prestasi ini?')) return;
            try {
                await window.api.delete(`/eskul/achievements/${id}`);
                window.showToast('Prestasi dihapus', 'success');
                loadAchievements();
            } catch (error) {
                window.showToast('Gagal menghapus prestasi', 'error');
            }
        };

        const loadAchievements = async () => {
            const eskul_id = eskulFilter.value;

            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                let url = `/eskul/achievements?`;
                if (eskul_id) url += `extracurricular_id=${encodeURIComponent(eskul_id)}`;

                const response = await window.api.get(url);
                achievements = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!achievements || achievements.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                achievements.forEach(a => {
                    const eskulName = a.extracurricular ? a.extracurricular.name : '-';
                    const imgHtml = a.image_url ? `<img src="${a.image_url}" class="h-10 object-contain rounded">` : '-';
                    
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">
                                ${a.title}
                                <p class="text-xs text-gray-500 font-normal mt-1 truncate max-w-sm">${a.description || ''}</p>
                            </td>
                            <td class="text-gray-600">${eskulName}</td>
                            <td>${imgHtml}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${a.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteAchievement(${a.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat prestasi', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-achievement');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('achievement_id').value;
            const payload = {
                title: document.getElementById('title').value,
                extracurricular_id: document.getElementById('extracurricular_id').value || null,
                image_url: document.getElementById('image_url').value || null,
                description: document.getElementById('description').value || null,
            };

            try {
                if (id) {
                    await window.api.put(`/eskul/achievements/${id}`, payload);
                } else {
                    await window.api.post('/eskul/achievements', payload);
                }
                window.showToast('Prestasi berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadAchievements();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan prestasi', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        eskulFilter.addEventListener('change', loadAchievements);

        loadMasterData();
        loadAchievements();
    });
</script>
@endsection
