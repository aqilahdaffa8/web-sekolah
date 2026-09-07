@extends('layouts.dashboard')
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
    <button onclick="openCreateModal()" class="btn btn-primary">
<<<<<<< HEAD
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
=======
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
>>>>>>> b4131732b82dedec8bb907a60fd0e1b683b999ff
        </svg>
        Tambah Eskul
    </button>
</div>

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
<<<<<<< HEAD
            <button data-modal-close class="btn-secondary">Batal</button>
            <button type="button" id="btn-save-eskul" onclick="saveEskul()" class="btn btn-primary btn-save">Simpan</button>
=======
            <button data-modal-close class="btn btn-secondary">Batal</button>
            <button id="btn-save-eskul" onclick="saveEskul()" class="btn btn-primary">Simpan</button>
>>>>>>> b4131732b82dedec8bb907a60fd0e1b683b999ff
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
let editingId = null;
let allEskul = [];

async function loadEskul() {
    const loading = document.getElementById('total-count');
    loading.classList.remove('hidden');
    loading.textContent = 'Memuat data...';
    try {
        const res = await window.eskulApi.extracurriculars();
        allEskul = res.data || res || [];
        renderEskul(allEskul);
    } catch(err) {
        loading.classList.add('hidden');
        window.toast.apiError(err);
    }
}

function renderEskul(list) {
    const query = document.getElementById('eskul-search').value.toLowerCase();
    const filtered = list.filter(e => e.name.toLowerCase().includes(query) || (e.description||'').toLowerCase().includes(query));
    document.getElementById('total-count').classList.add('hidden');

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
