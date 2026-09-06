@extends('layouts.dashboard')
<<<<<<< HEAD
@section('title', 'Fasilitas & Sarpras')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Fasilitas</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Fasilitas & Sarpras</h1>
        <p class="text-gray-500 mt-1">Daftar laboratorium, bengkel praktik, dan fasilitas sekolah.</p>
    </div>
    <button onclick="openCreateModal()" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
=======

@section('title', 'Fasilitas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Fasilitas</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data fasilitas sekolah dan laboratorium.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
        Tambah Fasilitas
    </button>
</div>

<<<<<<< HEAD
<div id="facilities-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @for($i=0;$i<6;$i++)
    <div class="card p-5 space-y-3 animate-pulse">
        <div class="skeleton h-44 w-full rounded-xl"></div>
        <div class="skeleton h-5 w-3/4 rounded"></div>
        <div class="skeleton h-4 w-1/2 rounded"></div>
    </div>
    @endfor
</div>

<div id="facility-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="facility-modal-title" class="text-lg font-bold text-gray-900">Tambah Fasilitas</h3>
            <button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="f-id">
            <div>
                <label class="form-label">Nama Fasilitas / Ruang *</label>
                <input type="text" id="f-name" class="form-input" placeholder="Contoh: Lab Komputer RPL 1">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Lokasi Gedung</label>
                    <input type="text" id="f-location" class="form-input" placeholder="Gedung B, Lantai 2">
                </div>
                <div>
                    <label class="form-label">Kapasitas (Orang)</label>
                    <input type="number" id="f-capacity" class="form-input" placeholder="36">
                </div>
            </div>
            <div>
                <label class="form-label">Deskripsi & Kelengkapan</label>
                <textarea id="f-desc" class="form-input h-24 resize-none" placeholder="36 Unit PC Core i7, Proyektor, AC..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-secondary">Batal</button>
            <button id="btn-save-facility" onclick="saveFacility()" class="btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;

async function loadFacilities() {
    try {
        const res = await window.guruApi.facilities();
        const list = res.data || res || [];
        renderList(list);
    } catch(err) {
        window.toast.apiError(err);
    }
}

function renderList(list) {
    const grid = document.getElementById('facilities-grid');
    if (!list.length) {
        grid.innerHTML = `<div class="col-span-3 card p-12 text-center text-gray-400">Belum ada data fasilitas. Klik Tambah Fasilitas untuk mendaftarkan ruang baru.</div>`;
        return;
    }

    const defaultImgs = [
        'https://images.unsplash.com/photo-1562774053-701939374585?w=600&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=600&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80',
    ];

    grid.innerHTML = list.map((f, idx) => `
        <div class="card overflow-hidden flex flex-col justify-between hover:shadow-md transition-all group">
            <div class="h-44 overflow-hidden relative bg-gray-100">
                <img src="${f.image_url || defaultImgs[idx % defaultImgs.length]}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <span class="absolute top-3 right-3 badge-brand font-semibold text-xs shadow-sm">${f.capacity ? f.capacity + ' Siswa' : 'Fasilitas'}</span>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">${f.facility_name || f.name}</h3>
                    <p class="text-xs text-brand-600 font-semibold mb-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        ${f.location || 'Kampus Utama'}
                    </p>
                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">${f.description || 'Fasilitas berstandar industri siap pakai.'}</p>
                </div>
                <div class="pt-3 border-t border-gray-100 flex gap-2">
                    <button onclick='editFacility(${JSON.stringify(f).replace(/'/g, "&apos;")})' class="btn-table-edit flex-1">Edit</button>
                    <button onclick="deleteFacility(${f.id})" class="btn-table-delete">Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
}

window.openCreateModal = () => {
    editingId = null;
    document.getElementById('facility-modal-title').textContent = 'Tambah Fasilitas';
    ['f-id', 'f-name', 'f-location', 'f-capacity', 'f-desc'].forEach(id => document.getElementById(id).value = '');
    window.openModal(document.getElementById('facility-modal'));
};

window.editFacility = (f) => {
    if(typeof f === 'string') f = JSON.parse(f);
    editingId = f.id;
    document.getElementById('facility-modal-title').textContent = 'Edit Fasilitas';
    document.getElementById('f-id').value = f.id;
    document.getElementById('f-name').value = f.facility_name || f.name;
    document.getElementById('f-location').value = f.location || '';
    document.getElementById('f-capacity').value = f.capacity || '';
    document.getElementById('f-desc').value = f.description || '';
    window.openModal(document.getElementById('facility-modal'));
};

window.saveFacility = async () => {
    const btn = document.getElementById('btn-save-facility');
    const name = document.getElementById('f-name').value.trim();
    if (!name) {
        window.toast.error('Nama fasilitas wajib diisi.');
        return;
    }
    const payload = {
        name: name,
        facility_name: name,
        location: document.getElementById('f-location').value.trim(),
        capacity: document.getElementById('f-capacity').value || 30,
        description: document.getElementById('f-desc').value.trim(),
    };
    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.guruApi.updateFacility(editingId, payload);
            window.toast.success('Fasilitas berhasil diperbarui.');
        } else {
            await window.guruApi.createFacility(payload);
            window.toast.success('Fasilitas berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('facility-modal'));
        loadFacilities();
    } catch(err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.deleteFacility = async (id) => {
    if (!confirm('Hapus fasilitas ini?')) return;
    try {
        await window.guruApi.deleteFacility(id);
        window.toast.success('Fasilitas dihapus.');
        loadFacilities();
    } catch(err) {
        window.toast.apiError(err);
    }
};

loadFacilities();
</script>
@endpush
=======
<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="facilities-table" :headers="['Fasilitas', 'Program Keahlian', 'Deskripsi', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

<x-ui.modal id="facility-modal" title="Form Fasilitas">
    <form id="facility-form">
        <input type="hidden" id="facility_id" name="facility_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                <input type="text" id="name" name="name" class="form-input w-full" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Keahlian</label>
                <select id="program_id" name="program_id" class="form-input w-full" required>
                    <option value="">-- Pilih Program --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="form-input w-full" rows="3"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="facility-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-facility">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('facilities-table');
        const loading = document.getElementById('facilities-table-loading');
        const empty = document.getElementById('facilities-table-empty');
        const modal = document.getElementById('facility-modal');
        const form = document.getElementById('facility-form');
        
        let facilities = [];
        let masterData = { programs: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/guru/master-data');
                const progSelect = document.getElementById('program_id');
                masterData.programs.forEach(p => {
                    progSelect.add(new Option(p.name, p.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('facility_id').value = '';
            
            if (id) {
                const fac = facilities.find(f => f.id === id);
                if (fac) {
                    document.getElementById('facility_id').value = fac.id;
                    document.getElementById('name').value = fac.name;
                    document.getElementById('program_id').value = fac.program_id || '';
                    document.getElementById('description').value = fac.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteFacility = async (id) => {
            if (!confirm('Hapus fasilitas ini?')) return;
            try {
                await window.api.delete(`/guru/facilities/${id}`);
                window.showToast('Fasilitas dihapus', 'success');
                loadFacilities();
            } catch (error) {
                window.showToast('Gagal menghapus fasilitas', 'error');
            }
        };

        const loadFacilities = async () => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get('/guru/facilities');
                facilities = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!facilities || facilities.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                facilities.forEach(fac => {
                    const progName = fac.program ? fac.program.name : (fac.program_id ? `Program #${fac.program_id}` : '-');
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${fac.name}</td>
                            <td class="text-gray-600">${progName}</td>
                            <td class="text-gray-600 text-sm truncate max-w-xs">${fac.description || '-'}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${fac.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteFacility(${fac.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat fasilitas', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-facility');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('facility_id').value;
            const payload = {
                name: document.getElementById('name').value,
                program_id: document.getElementById('program_id').value,
                description: document.getElementById('description').value,
            };

            try {
                if (id) {
                    await window.api.put(`/guru/facilities/${id}`, payload);
                } else {
                    await window.api.post('/guru/facilities', payload);
                }
                window.showToast('Fasilitas berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadFacilities();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan fasilitas', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        loadMasterData();
        loadFacilities();
    });
</script>
@endsection
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
