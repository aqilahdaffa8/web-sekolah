@extends('layouts.dashboard')
<<<<<<< HEAD
@section('title', 'Kelola Ekstrakurikuler')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Ekstrakurikuler</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Kelola Ekstrakurikuler</h1>
        <p class="text-gray-500 mt-1">Daftar kegiatan ekstrakurikuler SMKN 1 Katapang.</p>
    </div>
    <button onclick="openCreateModal()" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
=======

@section('title', 'Daftar Eskul')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Ekstrakurikuler</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data ekstrakurikuler sekolah dan pembina (coach).</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
        Tambah Eskul
    </button>
</div>

<<<<<<< HEAD
{{-- Search & Filter --}}
<div class="card mb-6 p-4">
    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div class="relative w-full sm:w-80">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="search" id="eskul-search" class="form-input pl-9 text-sm" placeholder="Cari ekstrakurikuler...">
        </div>
        <div class="text-xs text-gray-500 font-medium" id="total-count">
            Memuat data...
        </div>
    </div>
</div>

{{-- Grid of Eskul --}}
<div id="eskul-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @for($i=0; $i<6; $i++)
    <div class="card p-5 space-y-3 animate-pulse">
        <div class="skeleton h-6 w-1/2 rounded"></div>
        <div class="skeleton h-4 w-full rounded"></div>
        <div class="skeleton h-4 w-3/4 rounded"></div>
        <div class="flex gap-2 pt-2">
            <div class="skeleton h-8 w-16 rounded-lg"></div>
            <div class="skeleton h-8 w-16 rounded-lg"></div>
        </div>
    </div>
    @endfor
</div>

{{-- Modal Form --}}
<div id="eskul-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="eskul-modal-title" class="text-lg font-bold text-gray-900">Tambah Ekstrakurikuler</h3>
            <button data-modal-close class="btn-icon text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="e-id">
            <div>
                <label class="form-label">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                <input type="text" id="e-name" class="form-input" placeholder="Contoh: Robotika & IoT">
            </div>
            <div>
                <label class="form-label">Jadwal Latihan</label>
                <input type="text" id="e-schedule" class="form-input" placeholder="Contoh: Sabtu, 08.00 - 11.00 WIB">
            </div>
            <div>
                <label class="form-label">Deskripsi</label>
                <textarea id="e-desc" class="form-input h-24 resize-none" placeholder="Deskripsi program dan kegiatan..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-secondary">Batal</button>
            <button id="btn-save-eskul" onclick="saveEskul()" class="btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;
let allEskul = [];

async function loadEskul() {
    try {
        const res = await window.eskulApi.extracurriculars();
        allEskul = res.data || res || [];
        renderEskul(allEskul);
    } catch(err) {
        window.toast.apiError(err);
    }
}

function renderEskul(list) {
    const query = document.getElementById('eskul-search').value.toLowerCase();
    const filtered = list.filter(e => e.name.toLowerCase().includes(query) || (e.description||'').toLowerCase().includes(query));
    document.getElementById('total-count').textContent = `Total: ${filtered.length} Ekstrakurikuler`;

    if (!filtered.length) {
        document.getElementById('eskul-grid').innerHTML = `
            <div class="col-span-3 card p-12 text-center text-gray-400">
                <p class="text-base font-semibold">Tidak ada ekstrakurikuler yang ditemukan.</p>
                <p class="text-xs mt-1">Klik tombol 'Tambah Eskul' di atas untuk menambah kegiatan baru.</p>
            </div>`;
        return;
    }

    document.getElementById('eskul-grid').innerHTML = filtered.map(e => `
        <div class="card p-5 flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="font-bold text-gray-900 text-lg">${e.name}</h3>
                    <span class="badge-brand text-xs">${e.registrations_count ? e.registrations_count + ' Peserta' : 'Aktif'}</span>
                </div>
                <p class="text-xs text-brand-600 font-semibold mb-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ${e.schedule || 'Jadwal belum ditentukan'}
                </p>
                <p class="text-sm text-gray-600 line-clamp-3 mb-4">${e.description || 'Tidak ada deskripsi.'}</p>
            </div>
            <div class="pt-4 border-t border-gray-100 flex items-center gap-2">
                <button onclick='editEskul(${JSON.stringify(e).replace(/'/g, "&apos;")})' class="btn-table-edit flex-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit
                </button>
                <button onclick="deleteEskul(${e.id})" class="btn-table-delete">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </div>
        </div>
    `).join('');
}

window.openCreateModal = () => {
    editingId = null;
    document.getElementById('eskul-modal-title').textContent = 'Tambah Ekstrakurikuler';
    document.getElementById('e-id').value = '';
    document.getElementById('e-name').value = '';
    document.getElementById('e-schedule').value = '';
    document.getElementById('e-desc').value = '';
    window.openModal(document.getElementById('eskul-modal'));
};

window.editEskul = (e) => {
    if (typeof e === 'string') e = JSON.parse(e);
    editingId = e.id;
    document.getElementById('eskul-modal-title').textContent = 'Edit Ekstrakurikuler';
    document.getElementById('e-id').value = e.id;
    document.getElementById('e-name').value = e.name;
    document.getElementById('e-schedule').value = e.schedule || '';
    document.getElementById('e-desc').value = e.description || '';
    window.openModal(document.getElementById('eskul-modal'));
};

window.saveEskul = async () => {
    const btn = document.getElementById('btn-save-eskul');
    const name = document.getElementById('e-name').value.trim();
    if (!name) {
        window.toast.error('Nama ekstrakurikuler wajib diisi.');
        return;
    }

    const payload = {
        name: name,
        schedule: document.getElementById('e-schedule').value.trim(),
        description: document.getElementById('e-desc').value.trim(),
    };

    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.eskulApi.updateExtracurricular(editingId, payload);
            window.toast.success('Ekstrakurikuler berhasil diperbarui.');
        } else {
            await window.eskulApi.createExtracurricular(payload);
            window.toast.success('Ekstrakurikuler berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('eskul-modal'));
        loadEskul();
    } catch(err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.deleteEskul = async (id) => {
    if (!confirm('Apakah Anda yakin ingin menghapus ekstrakurikuler ini?')) return;
    try {
        await window.eskulApi.deleteExtracurricular(id);
        window.toast.success('Ekstrakurikuler berhasil dihapus.');
        loadEskul();
    } catch(err) {
        window.toast.apiError(err);
    }
};

document.getElementById('eskul-search').addEventListener('input', () => renderEskul(allEskul));

loadEskul();
</script>
@endpush
=======
<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="eskul-table" :headers="['Nama Eskul', 'Jadwal', 'Pembina', 'Jml Pendaftar', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
</div>

<x-ui.modal id="eskul-modal" title="Form Ekstrakurikuler">
    <form id="eskul-form">
        <input type="hidden" id="eskul_id" name="eskul_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Eskul</label>
                <input type="text" id="name" name="name" class="form-input w-full" required maxlength="255">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal</label>
                <input type="text" id="schedule" name="schedule" class="form-input w-full" placeholder="Misal: Setiap Jumat 14.00 - 16.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pembina (Coach)</label>
                <select id="coach_id" name="coach_id" class="form-input w-full">
                    <option value="">-- Pilih Pembina --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="form-input w-full" rows="3"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="eskul-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-eskul">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('eskul-table');
        const loading = document.getElementById('eskul-table-loading');
        const empty = document.getElementById('eskul-table-empty');
        const modal = document.getElementById('eskul-modal');
        const form = document.getElementById('eskul-form');
        
        let records = [];
        let masterData = { users: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/eskul/master-data');
                const coachSelect = document.getElementById('coach_id');
                masterData.users.forEach(u => {
                    coachSelect.add(new Option(u.name, u.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('eskul_id').value = '';
            
            if (id) {
                const item = records.find(x => x.id === id);
                if (item) {
                    document.getElementById('eskul_id').value = item.id;
                    document.getElementById('name').value = item.name;
                    document.getElementById('schedule').value = item.schedule || '';
                    document.getElementById('coach_id').value = item.coach_id || '';
                    document.getElementById('description').value = item.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteEskul = async (id) => {
            if (!confirm('Hapus eskul ini?')) return;
            try {
                await window.api.delete(`/eskul/extracurriculars/${id}`);
                window.showToast('Eskul dihapus', 'success');
                loadRecords();
            } catch (error) {
                window.showToast('Gagal menghapus eskul', 'error');
            }
        };

        const loadRecords = async () => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get('/eskul/extracurriculars');
                // The endpoint currently returns all records (not paginated) based on Controller index()
                records = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!records || records.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                records.forEach(item => {
                    const coachName = item.coach ? item.coach.name : '-';
                    const count = item.registrations_count || 0;
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${item.name}</td>
                            <td class="text-gray-600">${item.schedule || '-'}</td>
                            <td class="text-gray-600">${coachName}</td>
                            <td class="text-gray-600">${count} Pendaftar</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${item.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteEskul(${item.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data eskul', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-eskul');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('eskul_id').value;
            const payload = {
                name: document.getElementById('name').value,
                schedule: document.getElementById('schedule').value || null,
                coach_id: document.getElementById('coach_id').value || null,
                description: document.getElementById('description').value || null,
            };

            try {
                if (id) {
                    await window.api.put(`/eskul/extracurriculars/${id}`, payload);
                } else {
                    await window.api.post('/eskul/extracurriculars', payload);
                }
                window.showToast('Eskul berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadRecords();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan eskul', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        loadMasterData();
        loadRecords();
    });
</script>
@endsection
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
