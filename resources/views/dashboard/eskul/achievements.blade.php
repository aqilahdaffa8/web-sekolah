@extends('layouts.dashboard')
<<<<<<< HEAD
@section('title', 'Galeri Prestasi')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Galeri Prestasi</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Galeri Prestasi</h1>
        <p class="text-gray-500 mt-1">Kelola daftar penghargaan dan prestasi kejuaraan siswa.</p>
    </div>
    <button onclick="openCreateModal()" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
=======

@section('title', 'Prestasi Eskul')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Prestasi Ekstrakurikuler</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data prestasi dan penghargaan tiap ekstrakurikuler.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
        Tambah Prestasi
    </button>
</div>

<<<<<<< HEAD
<div id="achievements-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @for($i=0;$i<6;$i++)
    <div class="card p-5 space-y-3 animate-pulse">
        <div class="skeleton h-5 w-3/4 rounded"></div>
        <div class="skeleton h-4 w-1/2 rounded"></div>
        <div class="skeleton h-16 w-full rounded-xl"></div>
    </div>
    @endfor
</div>

<div id="achieve-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="achieve-modal-title" class="text-lg font-bold text-gray-900">Tambah Prestasi</h3>
            <button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="a-id">
            <div>
                <label class="form-label">Nama Kejuaraan / Prestasi *</label>
                <input type="text" id="a-title" class="form-input" placeholder="Contoh: Juara 1 LKS Tingkat Provinsi">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Tingkat</label>
                    <select id="a-level" class="form-select">
                        <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" id="a-year" class="form-input" value="{{ date('Y') }}">
                </div>
            </div>
            <div>
                <label class="form-label">Deskripsi / Peraih</label>
                <textarea id="a-desc" class="form-input h-20 resize-none" placeholder="Nama peraih atau detail lomba..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-secondary">Batal</button>
            <button id="btn-save-achieve" onclick="saveAchieve()" class="btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;

async function loadAchievements() {
    try {
        const res = await window.eskulApi.achievements();
        const list = res.data || res || [];
        renderList(list);
    } catch(err) {
        window.toast.apiError(err);
    }
}

function renderList(list) {
    const grid = document.getElementById('achievements-grid');
    if (!list.length) {
        grid.innerHTML = `<div class="col-span-3 card p-12 text-center text-gray-400">Belum ada data prestasi. Klik tombol Tambah Prestasi untuk menambahkan.</div>`;
        return;
    }

    grid.innerHTML = list.map(a => `
        <div class="card p-5 flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <div class="flex items-start justify-between gap-2 mb-2">
                    <span class="badge-brand text-xs font-bold">🏆 ${a.competition_level || a.level || 'Tingkat Nasional'}</span>
                    <span class="text-xs text-gray-400 font-semibold">${a.year || new Date(a.created_at).getFullYear()}</span>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">${a.title || a.achievement_name}</h3>
                <p class="text-sm text-gray-600 mb-4">${a.description || 'Prestasi membanggakan siswa SMKN 1 Katapang.'}</p>
            </div>
            <div class="pt-4 border-t border-gray-100 flex gap-2">
                <button onclick='editAchieve(${JSON.stringify(a).replace(/'/g, "&apos;")})' class="btn-table-edit flex-1">Edit</button>
                <button onclick="deleteAchieve(${a.id})" class="btn-table-delete">Hapus</button>
            </div>
        </div>
    `).join('');
}

window.openCreateModal = () => {
    editingId = null;
    document.getElementById('achieve-modal-title').textContent = 'Tambah Prestasi';
    ['a-id', 'a-title', 'a-desc'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('a-year').value = new Date().getFullYear();
    window.openModal(document.getElementById('achieve-modal'));
};

window.editAchieve = (a) => {
    if(typeof a === 'string') a = JSON.parse(a);
    editingId = a.id;
    document.getElementById('achieve-modal-title').textContent = 'Edit Prestasi';
    document.getElementById('a-id').value = a.id;
    document.getElementById('a-title').value = a.title || a.achievement_name;
    document.getElementById('a-level').value = a.competition_level || a.level || 'Nasional';
    document.getElementById('a-year').value = a.year || new Date(a.created_at).getFullYear();
    document.getElementById('a-desc').value = a.description || '';
    window.openModal(document.getElementById('achieve-modal'));
};

window.saveAchieve = async () => {
    const btn = document.getElementById('btn-save-achieve');
    const title = document.getElementById('a-title').value.trim();
    if (!title) {
        window.toast.error('Nama prestasi wajib diisi.');
        return;
    }
    const payload = {
        title: title,
        achievement_name: title,
        competition_level: document.getElementById('a-level').value,
        year: document.getElementById('a-year').value,
        description: document.getElementById('a-desc').value.trim(),
    };
    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.eskulApi.updateAchievement(editingId, payload);
            window.toast.success('Prestasi berhasil diperbarui.');
        } else {
            await window.eskulApi.createAchievement(payload);
            window.toast.success('Prestasi berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('achieve-modal'));
        loadAchievements();
    } catch(err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.deleteAchieve = async (id) => {
    if (!confirm('Hapus data prestasi ini?')) return;
    try {
        await window.eskulApi.deleteAchievement(id);
        window.toast.success('Data prestasi dihapus.');
        loadAchievements();
    } catch(err) {
        window.toast.apiError(err);
    }
};

loadAchievements();
</script>
@endpush
=======
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
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
