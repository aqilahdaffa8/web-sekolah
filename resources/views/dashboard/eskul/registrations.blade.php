@extends('layouts.dashboard')

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
