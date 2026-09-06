@extends('layouts.dashboard')
<<<<<<< HEAD
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
=======

@section('title', 'Pendaftaran Eskul')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pendaftaran Ekstrakurikuler</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola permohonan pendaftaran siswa untuk masuk ke eskul.</p>
    </div>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex gap-4 bg-gray-50">
        <select id="eskul-filter" class="form-input w-48">
            <option value="">Semua Eskul</option>
        </select>
        <select id="status-filter" class="form-input w-48">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <x-ui.table id="regs-table" :headers="['Siswa', 'Ekstrakurikuler', 'Catatan / Alasan', 'Status', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('regs-table');
        const loading = document.getElementById('regs-table-loading');
        const empty = document.getElementById('regs-table-empty');
        const eskulFilter = document.getElementById('eskul-filter');
        const statusFilter = document.getElementById('status-filter');
        
        let records = [];
        let masterData = { extracurriculars: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/eskul/master-data');
                masterData.extracurriculars.forEach(e => {
                    eskulFilter.add(new Option(e.name, e.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.updateStatus = async (id, newStatus) => {
            if (!confirm(`Tandai pendaftaran ini sebagai ${newStatus}?`)) return;
            try {
                await window.api.patch(`/eskul/registrations/${id}/status`, { status: newStatus });
                window.showToast('Status berhasil diupdate', 'success');
                loadRecords();
            } catch (error) {
                window.showToast('Gagal update status', 'error');
            }
        };

        const loadRecords = async () => {
            const status = statusFilter.value;
            const eskul_id = eskulFilter.value;

            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                let url = `/eskul/registrations?`;
                if (status) url += `status=${encodeURIComponent(status)}&`;
                if (eskul_id) url += `extracurricular_id=${encodeURIComponent(eskul_id)}&`;

                const response = await window.api.get(url);
                records = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!records || records.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                records.forEach(item => {
                    const studentName = item.student ? item.student.name : '-';
                    const eskulName = item.extracurricular ? item.extracurricular.name : '-';
                    const badgeClass = item.status === 'approved' ? 'badge-active' : 
                                       item.status === 'rejected' ? 'badge-cancelled' : 'badge-open';
                    
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${studentName}</td>
                            <td class="text-gray-600">${eskulName}</td>
                            <td class="text-gray-600 text-sm max-w-xs truncate">${item.notes || '-'}</td>
                            <td><span class="badge ${badgeClass} uppercase text-xs">${item.status}</span></td>
                            <td>
                                ${item.status === 'pending' ? `
                                    <div class="flex gap-2">
                                        <button onclick="updateStatus(${item.id}, 'approved')" class="text-green-600 hover:text-green-800">Terima</button>
                                        <button onclick="updateStatus(${item.id}, 'rejected')" class="text-red-600 hover:text-red-800">Tolak</button>
                                    </div>
                                ` : '-'}
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat pendaftaran', 'error');
            }
        };

        eskulFilter.addEventListener('change', loadRecords);
        statusFilter.addEventListener('change', loadRecords);

        loadMasterData();
        loadRecords();
    });
</script>
@endsection
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
