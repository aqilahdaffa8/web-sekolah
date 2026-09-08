@extends('layouts.dashboard')
@section('title', 'Pendaftaran Eskul')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Pendaftaran Eskul</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Pendaftaran Eskul</h1>
        <p class="text-gray-500 mt-1">Verifikasi dan kelola permohonan pendaftaran anggota baru.</p>
    </div>
</div>

{{-- Stat & Filter Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 cursor-pointer border-2 transition-all filter-card border-brand-500 bg-brand-50/20" data-status="" onclick="setFilterStatus('')">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-gray-600">Semua Pendaftar</span>
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
        </div>
        <p class="text-2xl font-black text-gray-900 mt-2" id="count-all">—</p>
    </div>
    <div class="card p-4 cursor-pointer border-2 border-transparent hover:border-amber-400 transition-all filter-card" data-status="pending" onclick="setFilterStatus('pending')">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-amber-600">Menunggu Jawaban</span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
        </div>
        <p class="text-2xl font-black text-gray-900 mt-2" id="count-pending">—</p>
    </div>
    <div class="card p-4 cursor-pointer border-2 border-transparent hover:border-emerald-400 transition-all filter-card" data-status="approved" onclick="setFilterStatus('approved')">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-emerald-600">Siswa Diterima</span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
        </div>
        <p class="text-2xl font-black text-gray-900 mt-2" id="count-approved">—</p>
    </div>
    <div class="card p-4 cursor-pointer border-2 border-transparent hover:border-rose-400 transition-all filter-card" data-status="rejected" onclick="setFilterStatus('rejected')">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-rose-600">Siswa Ditolak</span>
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
        </div>
        <p class="text-2xl font-black text-gray-900 mt-2" id="count-rejected">—</p>
    </div>
</div>

<div class="card mb-6 p-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
    <div class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
        <div class="relative w-full sm:w-72">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="search" id="reg-search" class="form-input pl-9 text-sm w-full" placeholder="Cari siswa, NIS, atau eskul...">
        </div>
        <select id="filter-status" class="form-select text-sm py-2 w-full sm:w-auto">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Jawaban (Pending)</option>
            <option value="approved">Diterima (Approved)</option>
            <option value="rejected">Ditolak (Rejected)</option>
        </select>
    </div>
    <div class="text-xs text-gray-500 font-medium whitespace-nowrap" id="reg-count">Memuat data...</div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Ekstrakurikuler</th>
                    <th>Motivasi / Catatan</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="reg-tbody">
                @for($i=0; $i<5; $i++)
                <tr>
                    <td colspan="7" class="p-4"><div class="skeleton h-5 w-full rounded"></div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="reg-pagination" class="p-4 border-t border-gray-100"></div>
</div>
@endsection

@push('scripts')
<script type="module">
let currentPage = 1;

window.setFilterStatus = (status) => {
    document.getElementById('filter-status').value = status;
    window.loadRegistrations(1);
};

window.loadRegistrations = async (page = 1) => {
    currentPage = page;
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('reg-search').value.trim();
    const loading = document.getElementById('reg-count');

    // Update active filter card UI
    document.querySelectorAll('.filter-card').forEach(card => {
        const cardStatus = card.dataset.status;
        if (cardStatus === status) {
            card.classList.add('border-brand-500', 'bg-brand-50/20');
            card.classList.remove('border-transparent');
        } else {
            card.classList.remove('border-brand-500', 'bg-brand-50/20');
            card.classList.add('border-transparent');
        }
    });

    loading.classList.remove('hidden');
    loading.textContent = 'Memuat data...';

    try {
        const res = await window.eskulApi.registrations({ page, status, search });
        const list = res.data || res || [];
        loading.classList.add('hidden');

        // Update counts
        if (res.counts) {
            document.getElementById('count-all').textContent = res.counts.all ?? 0;
            document.getElementById('count-pending').textContent = res.counts.pending ?? 0;
            document.getElementById('count-approved').textContent = res.counts.approved ?? 0;
            document.getElementById('count-rejected').textContent = res.counts.rejected ?? 0;
        }

        renderTable(list);

        // Handle pagination
        const meta = res.meta || (res.last_page ? res : null);
        if (meta) {
            window.utils.renderPagination('reg-pagination', meta, window.loadRegistrations);
        } else {
            document.getElementById('reg-pagination').innerHTML = '';
        }
    } catch(err) {
        loading.classList.add('hidden');
        window.toast.apiError(err);
    }
};

function renderTable(list) {
    const tbody = document.getElementById('reg-tbody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-12 text-gray-400">Tidak ada data pendaftaran yang sesuai dengan filter.</td></tr>`;
        return;
    }

    tbody.innerHTML = list.map(r => {
        let badgeClass = 'badge-warning';
        let statusLabel = 'Menunggu';
        if (r.status === 'approved') { badgeClass = 'badge-success'; statusLabel = 'Diterima'; }
        if (r.status === 'rejected') { badgeClass = 'badge-danger'; statusLabel = 'Ditolak'; }

        const noteText = r.notes ? r.notes.replace(/"/g, '&quot;') : '-';

        return `
        <tr>
            <td>
                <p class="font-semibold text-gray-900">${r.student?.name || 'Siswa'}</p>
                <p class="text-xs text-gray-400">NIS: ${r.student?.nis || '-'}</p>
            </td>
            <td><span class="badge-brand text-xs">${r.student?.class_room?.class_name || r.student?.class_name || 'X RPL'}</span></td>
            <td><span class="font-medium text-brand-700">${r.extracurricular?.name || '-'}</span></td>
            <td>
                <p class="text-xs text-gray-600 max-w-xs line-clamp-2" title="${noteText}">${r.notes || '<span class="text-gray-400 italic">Tidak ada catatan</span>'}</p>
            </td>
            <td><span class="text-xs text-gray-500">${window.utils.formatDate(r.created_at)}</span></td>
            <td><span class="badge ${badgeClass}">${statusLabel}</span></td>
            <td class="text-right">
                <div class="inline-flex gap-1.5 justify-end">
                    ${r.status !== 'approved' ? `
                    <button onclick="updateStatus(${r.id}, 'approved')" class="btn-table-view text-xs" title="Terima Siswa">
                        ✓ Terima
                    </button>` : ''}
                    ${r.status !== 'rejected' ? `
                    <button onclick="updateStatus(${r.id}, 'rejected')" class="btn-table-delete text-xs" title="Tolak Siswa">
                        ✕ Tolak
                    </button>` : ''}
                    ${r.status !== 'pending' ? `
                    <button onclick="updateStatus(${r.id}, 'pending')" class="btn-table-edit text-xs" title="Reset ke Menunggu">
                        ↺ Reset
                    </button>` : ''}
                </div>
            </td>
        </tr>`;
    }).join('');
}

window.updateStatus = async (id, status) => {
    const label = status === 'approved' ? 'Diterima' : (status === 'rejected' ? 'Ditolak' : 'Menunggu Jawaban');
    try {
        await window.eskulApi.updateRegistrationStatus(id, status);
        window.toast.success(`Status pendaftaran berhasil diubah menjadi "${label}".`);
        window.loadRegistrations(currentPage);
    } catch(err) {
        window.toast.apiError(err);
    }
};

document.getElementById('filter-status').addEventListener('change', () => window.loadRegistrations(1));
document.getElementById('reg-search').addEventListener('input', window.utils.debounce(() => window.loadRegistrations(1), 350));

window.loadRegistrations(1);
</script>
@endpush

