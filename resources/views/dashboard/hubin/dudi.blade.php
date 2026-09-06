@extends('layouts.dashboard')
@section('title', 'Mitra DUDI')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Mitra DUDI</h1>
        <p class="text-gray-500 mt-1">Kelola daftar mitra dunia usaha dan industri.</p>
    </div>
    <button onclick="openCreateModal()" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Tambah Mitra
    </button>
</div>

<div class="card mb-5 p-4">
    <div class="relative max-w-sm">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <input type="search" id="dudi-search" class="form-input pl-9 py-2 text-sm" placeholder="Cari mitra...">
    </div>
</div>

<div id="dudi-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @for($i=0;$i<6;$i++)
    <div class="card p-5 flex gap-4">
        <div class="skeleton w-14 h-14 rounded-xl flex-shrink-0"></div>
        <div class="flex-1 space-y-2">
            <div class="skeleton h-4 w-2/3 rounded"></div>
            <div class="skeleton h-3 w-1/2 rounded"></div>
        </div>
    </div>
    @endfor
</div>

{{-- Create/Edit Modal --}}
<div id="dudi-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="dudi-modal-title" class="text-lg font-bold text-gray-900">Tambah Mitra</h3>
            <button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="dudi-id">
            <div><label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label><input type="text" id="d-name" class="form-input" placeholder="PT. Contoh Indonesia"></div>
            <div><label class="form-label">Industri</label><input type="text" id="d-industry" class="form-input" placeholder="Teknologi, Manufaktur, dll."></div>
            <div><label class="form-label">Alamat</label><input type="text" id="d-address" class="form-input" placeholder="Kota, Provinsi"></div>
            <div><label class="form-label">Website</label><input type="url" id="d-website" class="form-input" placeholder="https://"></div>
            <div><label class="form-label">Logo</label><input type="file" id="d-logo" class="form-input" accept="image/*"></div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-ghost">Batal</button>
            <button id="btn-save-dudi" onclick="saveDudi()" class="btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;
let allPartners = [];

async function loadDudi() {
    try {
        const res = await window.hubinApi.dudiPartners();
        allPartners = res.data || res || [];
        renderGrid(allPartners);
    } catch (err) { window.toast.apiError(err); }
}

function renderGrid(partners) {
    document.getElementById('dudi-grid').innerHTML = partners.length ? partners.map(p => `
        <div class="card p-5 flex gap-4 items-start">
            <div class="w-14 h-14 flex-shrink-0 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
                ${p.logo ? `<img src="${window.utils.storageUrl(p.logo)}" alt="${p.name}" class="w-full h-full object-contain">` :
                `<span class="text-2xl font-black text-gray-400">${p.name[0]}</span>`}
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 truncate">${p.name}</h3>
                <p class="text-sm text-gray-500">${p.industry || ''}</p>
                ${p.website ? `<a href="${p.website}" target="_blank" class="text-xs text-brand-600 hover:underline">Website →</a>` : ''}
            </div>
            <div class="flex gap-1">
                <button onclick="editDudi(${JSON.stringify(p).replace(/"/g,'&quot;')})" class="btn-icon w-8 h-8 text-gray-400 hover:text-brand-700 hover:bg-brand-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                </button>
                <button onclick="deleteDudi(${p.id})" class="btn-icon w-8 h-8 text-gray-400 hover:text-danger hover:bg-danger-light">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </div>
        </div>
    `).join('') : '<p class="col-span-3 text-center text-gray-400 py-12">Belum ada mitra DUDI.</p>';
}

window.openCreateModal = () => { editingId=null; document.getElementById('dudi-modal-title').textContent='Tambah Mitra'; ['dudi-id','d-name','d-industry','d-address','d-website'].forEach(id=>document.getElementById(id).value=''); window.openModal(document.getElementById('dudi-modal')); };
window.editDudi = (p) => { if(typeof p==='string') p=JSON.parse(p); editingId=p.id; document.getElementById('dudi-modal-title').textContent='Edit Mitra'; document.getElementById('dudi-id').value=p.id; document.getElementById('d-name').value=p.name; document.getElementById('d-industry').value=p.industry||''; document.getElementById('d-address').value=p.address||''; document.getElementById('d-website').value=p.website||''; window.openModal(document.getElementById('dudi-modal')); };

window.saveDudi = async () => {
    const btn = document.getElementById('btn-save-dudi');
    const fd = new FormData();
    fd.append('name', document.getElementById('d-name').value);
    fd.append('industry', document.getElementById('d-industry').value);
    fd.append('address', document.getElementById('d-address').value);
    fd.append('website', document.getElementById('d-website').value);
    const logoFile = document.getElementById('d-logo').files[0];
    if (logoFile) fd.append('logo', logoFile);
    try {
        window.utils.setButtonLoading(btn, true);
        editingId ? await window.hubinApi.updateDudi(editingId, fd) : await window.hubinApi.createDudi(fd);
        window.toast.success(editingId ? 'Mitra diperbarui.' : 'Mitra ditambahkan.');
        window.closeModal(document.getElementById('dudi-modal'));
        loadDudi();
    } catch (err) { window.toast.apiError(err); } finally { window.utils.setButtonLoading(btn, false, 'Simpan'); }
};

window.deleteDudi = async (id) => {
    if (!confirm('Hapus mitra ini?')) return;
    try { await window.hubinApi.deleteDudi(id); window.toast.success('Mitra dihapus.'); loadDudi(); } catch(err) { window.toast.apiError(err); }
};

document.getElementById('dudi-search').addEventListener('input', window.utils.debounce(e => {
    const q = e.target.value.toLowerCase();
    renderGrid(allPartners.filter(p => p.name.toLowerCase().includes(q)));
}, 300));

loadDudi();
</script>
@endpush
