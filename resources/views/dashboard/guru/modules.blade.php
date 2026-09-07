@extends('layouts.dashboard')
@section('title', 'Modul Ajar & Materi')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Modul Ajar</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Modul Ajar & Materi</h1>
        <p class="text-gray-500 mt-1">Kelola modul pembelajaran, materi ajar, dan silabus.</p>
    </div>
    <button onclick="openCreateModal()" class="btn btn-primary">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Modul
    </button>
</div>

<div class="card mb-6 p-4">
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <input type="search" id="module-search" class="form-input text-sm w-full sm:w-80" placeholder="Cari judul modul atau mata pelajaran...">
        <div class="text-xs text-gray-500 font-medium" id="module-count">Memuat data...</div>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Judul Modul</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas / Tingkat</th>
                    <th>Tanggal Unggah</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="module-tbody">
                @for($i=0; $i<5; $i++)
                <tr>
                    <td colspan="5" class="p-4"><div class="skeleton h-5 w-full rounded"></div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<div id="module-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="module-modal-title" class="text-lg font-bold text-gray-900">Tambah Modul Ajar</h3>
            <button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="m-id">
            <div>
                <label class="form-label">Judul Modul <span class="text-rose-500">*</span></label>
                <input type="text" id="m-title" class="form-input" placeholder="Contoh: Modul Pemrograman Web Modern">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Mata Pelajaran</label>
                    <select id="m-subject" class="form-select">
                        <option value="">Pilih mata pelajaran...</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tingkat Kelas</label>
                    <select id="m-grade" class="form-select">
                        <option value="">Pilih kelas...</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Deskripsi / Capaian Pembelajaran</label>
                <textarea id="m-desc" class="form-input h-24 resize-none" placeholder="Ringkasan materi pokok modul..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-secondary">Batal</button>
            <button type="button" id="btn-save-module" onclick="saveModule()" class="btn btn-primary btn-save">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
let editingId = null;
let allModules = [];
let masterData = { subjects: [], classes: [] };

async function loadMasterData() {
    const data = await window.api.get('/guru/master-data');
    masterData = data;

    document.getElementById('m-subject').innerHTML = '<option value="">Pilih mata pelajaran...</option>' +
        (masterData.subjects || []).map(subject => `<option value="${subject.id}">${subject.name}</option>`).join('');
    document.getElementById('m-grade').innerHTML = '<option value="">Pilih kelas...</option>' +
        (masterData.classes || []).map(classRoom => `<option value="${classRoom.id}">${classRoom.name}</option>`).join('');
}

async function loadModules() {
    const loading = document.getElementById('module-count');
    loading.classList.remove('hidden');
    loading.textContent = 'Memuat data...';
    try {
        const res = await window.guruApi.learningModules();
        allModules = res.data || res || [];
        renderTable(allModules);
    } catch(err) {
        loading.classList.add('hidden');
        window.toast.apiError(err);
    }
}

function renderTable(list) {
    const query = document.getElementById('module-search').value.toLowerCase();
    const filtered = list.filter(m => (m.title||m.module_name||'').toLowerCase().includes(query) || (m.subject_name||'').toLowerCase().includes(query));
    document.getElementById('module-count').classList.add('hidden');

    const tbody = document.getElementById('module-tbody');
    if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12 text-gray-400">Belum ada modul ajar. Klik tombol Tambah Modul untuk membuat materi baru.</td></tr>`;
        return;
    }

    tbody.innerHTML = filtered.map(m => `
        <tr>
            <td>
                <p class="font-bold text-gray-900">${m.title || m.module_name}</p>
                <p class="text-xs text-gray-400 line-clamp-1">${m.description || 'Modul kurikulum merdeka'}</p>
            </td>
            <td><span class="badge-brand">${m.subject?.subject_name || m.subject_name || 'Produktif'}</span></td>
            <td><span class="font-medium text-gray-700">${m.target_grade || m.grade || 'XI SMK'}</span></td>
            <td><span class="text-xs text-gray-500">${window.utils.formatDate(m.created_at)}</span></td>
            <td class="text-right">
                <div class="inline-flex gap-1.5 justify-end">
                    <button onclick='editModule(${JSON.stringify(m).replace(/'/g, "&apos;")})' class="btn-table-edit">Edit</button>
                    <button onclick="deleteModule(${m.id})" class="btn-table-delete">Hapus</button>
                </div>
            </td>
        </tr>
    `).join('');
}

window.openCreateModal = () => {
    editingId = null;
    document.getElementById('module-modal-title').textContent = 'Tambah Modul Ajar';
    ['m-id', 'm-title', 'm-subject', 'm-desc'].forEach(id => document.getElementById(id).value = '');
    window.openModal(document.getElementById('module-modal'));
};

window.editModule = (m) => {
    if(typeof m === 'string') m = JSON.parse(m);
    editingId = m.id;
    document.getElementById('module-modal-title').textContent = 'Edit Modul Ajar';
    document.getElementById('m-id').value = m.id;
    document.getElementById('m-title').value = m.title || m.module_name;
    document.getElementById('m-subject').value = m.subject_id || m.subject?.id || '';
    document.getElementById('m-grade').value = m.class_id || m.class_room?.id || '';
    document.getElementById('m-desc').value = m.description || '';
    window.openModal(document.getElementById('module-modal'));
};

window.saveModule = async () => {
    const btn = document.getElementById('btn-save-module');
    const title = document.getElementById('m-title').value.trim();
    if (!title) {
        window.toast.error('Judul modul wajib diisi.');
        return;
    }
    const payload = {
        title: title,
        module_name: title,
        subject_id: document.getElementById('m-subject').value,
        class_id: document.getElementById('m-grade').value,
        description: document.getElementById('m-desc').value.trim(),
    };

    if (!payload.subject_id || !payload.class_id) {
        window.toast.error('Mata pelajaran dan kelas wajib dipilih.');
        return;
    }
    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.guruApi.updateLearningModule(editingId, payload);
            window.toast.success('Modul berhasil diperbarui.');
        } else {
            await window.guruApi.createLearningModule(payload);
            window.toast.success('Modul ajar berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('module-modal'));
        loadModules();
    } catch(err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.deleteModule = async (id) => {
    if (!confirm('Hapus modul ajar ini?')) return;
    try {
        await window.guruApi.deleteLearningModule(id);
        window.toast.success('Modul ajar dihapus.');
        loadModules();
    } catch(err) {
        window.toast.apiError(err);
    }
};

document.getElementById('module-search').addEventListener('input', () => renderTable(allModules));

Promise.all([loadMasterData(), loadModules()]).catch(err => window.toast.apiError(err));
</script>
@endpush
