@extends('layouts.dashboard')
@section('title', 'Tracer Study Alumni')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Tracer Study</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Tracer Study Alumni</h1>
        <p class="text-gray-500 mt-1">Pantau keterserapan alumni di DUDI, Perguruan Tinggi, dan Wirausaha.</p>
    </div>
    <button onclick="openCreateModal()" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Data Alumni
    </button>
</div>

<div class="card mb-6 p-4 flex flex-wrap gap-4 items-center justify-between">
    <div class="flex flex-wrap gap-3">
        <input type="search" id="tracer-search" class="form-input text-sm w-64" placeholder="Cari nama alumni atau instansi...">
        <select id="filter-status" class="form-select text-sm w-auto" onchange="loadTracer()">
            <option value="">Semua Status</option>
            <option value="Kerja">Kerja</option>
            <option value="Kuliah">Kuliah</option>
            <option value="Wirausaha">Wirausaha</option>
            <option value="Mencari Kerja">Mencari Kerja</option>
        </select>
    </div>
    <div class="text-xs text-gray-500 font-medium" id="tracer-count">Memuat data...</div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Alumni</th>
                    <th>Tahun Lulus</th>
                    <th>Status Terkini</th>
                    <th>Nama Perusahaan / Kampus</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="tracer-tbody">
                @for($i=0;$i<5;$i++)
                <tr>
                    <td colspan="5" class="p-4"><div class="skeleton h-5 w-full rounded"></div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="tracer-pagination" class="p-4 border-t border-gray-100"></div>
</div>

<div id="tracer-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="tracer-modal-title" class="text-lg font-bold text-gray-900">Tambah Data Alumni</h3>
            <button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="t-id">
            <div>
                <label class="form-label">Nama Alumni <span class="text-rose-500">*</span></label>
                <input type="text" id="t-name" class="form-input" placeholder="Contoh: Ahmad Fauzi">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Tahun Lulus</label>
                    <input type="number" id="t-year" class="form-input" value="{{ date('Y') - 1 }}">
                </div>
                <div>
                    <label class="form-label">Status Terkini</label>
                    <select id="t-status" class="form-select">
                        <option value="Kerja">Bekerja</option>
                        <option value="Kuliah">Kuliah</option>
                        <option value="Wirausaha">Wirausaha</option>
                        <option value="Mencari Kerja">Mencari Kerja</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Nama Perusahaan / Kampus / Bidang Usaha</label>
                <input type="text" id="t-company" class="form-input" placeholder="Contoh: PT. Astra Honda Motor">
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn btn-secondary">Batal</button>
            <button id="btn-save-tracer" onclick="saveTracer()" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;
let allTracer = [];

async function loadTracer(page=1) {
    const status = document.getElementById('filter-status').value;
    try {
        const res = await window.hubinApi.tracerStudies({ page, status });
        allTracer = res.data || res || [];
        document.getElementById('tracer-count').textContent = `Total: ${allTracer.length} Data`;
        renderTable(allTracer);
        if (res.meta) {
            window.utils.renderPagination('tracer-pagination', res.meta, loadTracer);
        }
    } catch(err) {
        window.toast.apiError(err);
    }
}

function renderTable(list) {
    const query = document.getElementById('tracer-search').value.toLowerCase();
    const filtered = list.filter(t => (t.student?.name||t.name||'').toLowerCase().includes(query) || (t.company_or_campus_name||'').toLowerCase().includes(query));

    const tbody = document.getElementById('tracer-tbody');
    if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12 text-gray-400">Belum ada data tracer study.</td></tr>`;
        return;
    }

    tbody.innerHTML = filtered.map(t => {
        let badge = 'badge-brand';
        if (t.current_status === 'Kerja') badge = 'badge-success';
        if (t.current_status === 'Kuliah') badge = 'badge-info';
        if (t.current_status === 'Wirausaha') badge = 'badge-warning';

        return `
        <tr>
            <td>
                <p class="font-bold text-gray-900">${t.student?.name || t.name || 'Alumni'}</p>
                <p class="text-xs text-gray-400">${t.student?.nis ? 'NIS: ' + t.student.nis : 'Lulusan SMK'}</p>
            </td>
            <td><span class="font-semibold text-gray-700">${t.graduation_year || '-'}</span></td>
            <td><span class="badge ${badge}">${t.current_status || 'Kerja'}</span></td>
            <td><span class="font-medium text-gray-800">${t.company_or_campus_name || '-'}</span></td>
            <td class="text-right">
                <div class="inline-flex gap-1.5 justify-end">
                    <button onclick='editTracer(${JSON.stringify(t).replace(/'/g, "&apos;")})' class="btn-table-edit">Edit</button>
                    <button onclick="deleteTracer(${t.id})" class="btn-table-delete">Hapus</button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

window.openCreateModal = () => {
    editingId = null;
    document.getElementById('tracer-modal-title').textContent = 'Tambah Data Alumni';
    ['t-id', 't-name', 't-company'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('t-year').value = new Date().getFullYear() - 1;
    document.getElementById('t-status').value = 'Kerja';
    window.openModal(document.getElementById('tracer-modal'));
};

window.editTracer = (t) => {
    if(typeof t === 'string') t = JSON.parse(t);
    editingId = t.id;
    document.getElementById('tracer-modal-title').textContent = 'Edit Data Alumni';
    document.getElementById('t-id').value = t.id;
    document.getElementById('t-name').value = t.student?.name || t.name || '';
    document.getElementById('t-year').value = t.graduation_year || 2023;
    document.getElementById('t-status').value = t.current_status || 'Kerja';
    document.getElementById('t-company').value = t.company_or_campus_name || '';
    window.openModal(document.getElementById('tracer-modal'));
};

window.saveTracer = async () => {
    const btn = document.getElementById('btn-save-tracer');
    const name = document.getElementById('t-name').value.trim();
    if (!name) {
        window.toast.error('Nama alumni wajib diisi.');
        return;
    }
    const payload = {
        name: name,
        student_id: 1,
        graduation_year: document.getElementById('t-year').value,
        current_status: document.getElementById('t-status').value,
        company_or_campus_name: document.getElementById('t-company').value.trim(),
    };
    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.hubinApi.updateTracerStudy(editingId, payload);
            window.toast.success('Data tracer study diperbarui.');
        } else {
            await window.hubinApi.createTracerStudy(payload);
            window.toast.success('Data alumni berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('tracer-modal'));
        loadTracer();
    } catch(err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.deleteTracer = async (id) => {
    if (!confirm('Hapus data alumni ini?')) return;
    try {
        await window.hubinApi.deleteTracerStudy(id);
        window.toast.success('Data alumni dihapus.');
        loadTracer();
    } catch(err) {
        window.toast.apiError(err);
    }
};

document.getElementById('tracer-search').addEventListener('input', () => renderTable(allTracer));

loadTracer();
</script>
@endpush
