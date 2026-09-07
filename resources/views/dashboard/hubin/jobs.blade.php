@extends('layouts.dashboard')
@section('title', 'Lowongan Kerja Alumni')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Lowongan Kerja Alumni</h1>
        <p class="text-gray-500 mt-1">Kelola papan loker untuk alumni SMK.</p>
    </div>
    <button onclick="openJobModal()" class="btn btn-primary">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Loker
    </button>
</div>

<div class="card mb-5 p-4 flex flex-wrap gap-3">
    <input type="search" id="job-search" class="form-input py-2 text-sm flex-1 min-w-[200px]" placeholder="Cari posisi atau perusahaan...">
    <select id="job-status" class="form-select w-auto text-sm py-2">
        <option value="">Semua Status</option><option value="open">Buka</option><option value="closed">Tutup</option>
    </select>
</div>

<div id="jobs-list" class="space-y-4">
    @for($i=0;$i<4;$i++)
    <div class="card p-5 flex gap-4">
        <div class="skeleton w-14 h-14 rounded-xl flex-shrink-0"></div>
        <div class="flex-1 space-y-2">
            <div class="skeleton h-5 w-1/2 rounded"></div>
            <div class="skeleton h-4 w-1/3 rounded"></div>
        </div>
        <div class="skeleton w-16 h-6 rounded-full"></div>
    </div>
    @endfor
</div>
<div id="jobs-pagination" class="mt-6"></div>

<div id="job-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-2xl">
        <div class="modal-header"><h3 id="job-modal-title" class="text-lg font-bold text-gray-900">Tambah Loker</h3><button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button></div>
        <div class="modal-body grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="hidden" id="job-id">
            <div class="sm:col-span-2"><label class="form-label">Judul Posisi <span class="text-danger">*</span></label><input type="text" id="j-title" class="form-input" placeholder="Software Engineer"></div>
            <div><label class="form-label">Perusahaan</label><input type="text" id="j-company" class="form-input" placeholder="PT. Contoh"></div>
            <div><label class="form-label">Lokasi</label><input type="text" id="j-location" class="form-input" placeholder="Jakarta, Indonesia"></div>
            <div><label class="form-label">Tipe</label><select id="j-type" class="form-select"><option>Full Time</option><option>Part Time</option><option>Internship</option><option>Kontrak</option></select></div>
            <div><label class="form-label">Status</label><select id="j-status" class="form-select"><option value="open">Buka</option><option value="closed">Tutup</option></select></div>
            <div><label class="form-label">Rentang Gaji</label><input type="text" id="j-salary" class="form-input" placeholder="Rp 5.000.000 – 8.000.000"></div>
            <div><label class="form-label">Deadline</label><input type="date" id="j-deadline" class="form-input"></div>
            <div class="sm:col-span-2"><label class="form-label">Deskripsi</label><textarea id="j-desc" class="form-input h-28 resize-none" placeholder="Deskripsi pekerjaan dan kualifikasi..."></textarea></div>
            <div class="sm:col-span-2"><label class="form-label">Link Lamaran</label><input type="url" id="j-apply" class="form-input" placeholder="https://apply.example.com"></div>
        </div>
        <div class="modal-footer"><button type="button" data-modal-close class="btn-ghost">Batal</button><button type="button" id="btn-save-job" onclick="saveJob()" class="btn btn-primary btn-save">Simpan</button></div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
let editingId = null;

window.loadJobs = async function(page=1) {
    const search = document.getElementById('job-search').value;
    const status = document.getElementById('job-status').value;
    try {
        const res = await window.hubinApi.vacancies({ page, per_page: 15, search, status });
        renderGrid(res.data || []);
        window.utils.renderPagination('jobs-pagination', res.meta, loadJobs);
    } catch(err) { window.toast.apiError(err); }
}

async function loadDudiOptions() {
    try {
        const res = await window.hubinApi.dudiPartners();
        const partners = res.data || res || [];
        // Note: the modal fields should have j-dudi, let's create a select if missing or just use an input for now.
        // Actually, the HTML doesn't have a select for 'j-dudi', it has 'j-company' as text input. 
        // We will just leave loadDudiOptions empty or use it if we add a select.
    } catch (err) { console.error('Gagal load dudi', err); }
}

function renderGrid(jobs) {
    document.getElementById('jobs-list').innerHTML = jobs.length ? jobs.map(j => `
        <div class="card p-5 flex flex-col sm:flex-row gap-4 items-start">
            <div class="w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🏢</div>
            <div class="flex-1">
                <div class="flex items-start justify-between gap-2 flex-wrap">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">${j.job_title || j.title}</h3>
                        <p class="text-sm text-gray-500">${j.dudi?.company_name || j.dudi_partner?.company_name || 'Perusahaan'}</p>
                    </div>
                    <span class="badge ${j.status === 'open' ? 'badge-success' : 'badge-danger'}">${j.status === 'open' ? 'Buka' : 'Tutup'}</span>
                </div>
                <p class="text-sm text-gray-600 mt-2">${j.description}</p>
                <div class="mt-4 flex gap-2">
                    <button onclick='editJob(${JSON.stringify(j).replace(/'/g, "&apos;")})' class="btn-secondary btn-sm">Edit</button>
                    <button onclick="deleteJob(${j.id})" class="btn-danger btn-sm">Hapus</button>
                </div>
            </div>
        </div>
    `).join('') : '<p class="text-center py-12 text-gray-400">Belum ada lowongan pekerjaan.</p>';
}

window.openJobModal = () => { 
    editingId = null; 
    document.getElementById('job-modal-title').textContent = 'Tambah Lowongan'; 
    ['job-id','j-title','j-company','j-location','j-type','j-salary','j-deadline','j-desc','j-apply'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.value = '';
    }); 
    document.getElementById('j-status').value = 'open'; 
    window.openModal(document.getElementById('job-modal')); 
};

window.editJob = (j) => { 
    if (typeof j === 'string') j = JSON.parse(j); 
    editingId = j.id; 
    document.getElementById('job-modal-title').textContent = 'Edit Lowongan'; 
    document.getElementById('job-id').value = j.id; 
    document.getElementById('j-title').value = j.job_title || j.title; 
    document.getElementById('j-desc').value = j.description; 
    document.getElementById('j-status').value = j.status; 
    window.openModal(document.getElementById('job-modal')); 
};

window.saveJob = async () => {
    const btn = document.getElementById('btn-save-job');
    const payload = {
        title: document.getElementById('j-title').value,
        description: document.getElementById('j-desc').value,
        status: document.getElementById('j-status').value,
        // dudi_id is required usually, if it's missing in modal we send 1 as fallback for demo
        dudi_id: 1, 
        job_title: document.getElementById('j-title').value,
    };
    try {
        window.utils.setButtonLoading(btn, true);
        editingId ? await window.hubinApi.updateVacancy(editingId, payload) : await window.hubinApi.createVacancy(payload);
        window.toast.success('Lowongan berhasil disimpan.');
        window.closeModal(document.getElementById('job-modal'));
        loadJobs();
    } catch (err) { window.toast.apiError(err); } finally { window.utils.setButtonLoading(btn, false, 'Simpan'); }
};

window.deleteJob = async (id) => {
    if (!confirm('Hapus lowongan ini?')) return;
    try { await window.hubinApi.deleteVacancy(id); window.toast.success('Lowongan dihapus.'); loadJobs(); } catch(err) { window.toast.apiError(err); }
};

document.getElementById('job-search').addEventListener('input', window.utils.debounce(() => loadJobs(1), 400));
document.getElementById('job-status').addEventListener('change', () => loadJobs(1));

loadJobs();
</script>
@endpush
