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

<div class="card mb-6 p-4 flex flex-wrap gap-4 items-center justify-between">
    <div class="flex gap-2">
        <select id="filter-status" class="form-select text-sm py-2" onchange="loadRegistrations()">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Konfirmasi</option>
            <option value="approved">Diterima</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>
    <div class="text-xs text-gray-500 font-medium" id="reg-count">Memuat data...</div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Ekstrakurikuler</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="reg-tbody">
                @for($i=0; $i<5; $i++)
                <tr>
                    <td colspan="6" class="p-4"><div class="skeleton h-5 w-full rounded"></div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="reg-pagination" class="p-4 border-t border-gray-100"></div>
</div>
@endsection

@push('scripts')
<script>
async function loadRegistrations(page=1) {
    const status = document.getElementById('filter-status').value;
    try {
        const res = await window.eskulApi.registrations({ page, status });
        const list = res.data || res || [];
        document.getElementById('reg-count').textContent = `Total: ${list.length} Pendaftar`;
        renderTable(list);
        if (res.meta) {
            window.utils.renderPagination('reg-pagination', res.meta, loadRegistrations);
        }
    } catch(err) {
        window.toast.apiError(err);
    }
}

function renderTable(list) {
    const tbody = document.getElementById('reg-tbody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-12 text-gray-400">Belum ada data pendaftaran eskul.</td></tr>`;
        return;
    }

    tbody.innerHTML = list.map(r => {
        let badgeClass = 'badge-warning';
        let statusLabel = 'Menunggu';
        if (r.status === 'approved') { badgeClass = 'badge-success'; statusLabel = 'Diterima'; }
        if (r.status === 'rejected') { badgeClass = 'badge-danger'; statusLabel = 'Ditolak'; }

        return `
        <tr>
            <td>
                <p class="font-semibold text-gray-900">${r.student?.name || 'Siswa'}</p>
                <p class="text-xs text-gray-400">NIS: ${r.student?.nis || '-'}</p>
            </td>
            <td>${r.student?.class_room?.class_name || r.student?.class_name || 'X RPL'}</td>
            <td><span class="font-medium text-brand-700">${r.extracurricular?.name || '-'}</span></td>
            <td><span class="text-xs text-gray-500">${window.utils.formatDate(r.created_at)}</span></td>
            <td><span class="badge ${badgeClass}">${statusLabel}</span></td>
            <td class="text-right">
                <div class="inline-flex gap-1.5 justify-end">
                    ${r.status !== 'approved' ? `
                    <button onclick="updateStatus(${r.id}, 'approved')" class="btn-table-view text-xs" title="Terima Pendaftaran">
                        ✓ Terima
                    </button>` : ''}
                    ${r.status !== 'rejected' ? `
                    <button onclick="updateStatus(${r.id}, 'rejected')" class="btn-table-delete text-xs" title="Tolak Pendaftaran">
                        ✕ Tolak
                    </button>` : ''}
                </div>
            </td>
        </tr>`;
    }).join('');
}

window.updateStatus = async (id, status) => {
    try {
        await window.eskulApi.updateRegistrationStatus(id, status);
        window.toast.success(`Status pendaftaran berhasil diubah menjadi ${status === 'approved' ? 'Diterima' : 'Ditolak'}.`);
        loadRegistrations();
    } catch(err) {
        window.toast.apiError(err);
    }
};

loadRegistrations();
</script>
@endpush

