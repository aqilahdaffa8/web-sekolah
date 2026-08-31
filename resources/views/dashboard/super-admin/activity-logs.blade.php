@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas</h1>
        <p class="text-sm text-gray-500 mt-1">Rekam jejak semua perubahan data yang dilakukan pengguna di sistem.</p>
    </div>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex gap-3 items-center bg-gray-50 flex-wrap">
        <div class="flex-1 min-w-48">
            <select id="filter-action" class="form-input w-full">
                <option value="">Semua Aksi</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
                <option value="assigned_role">Assign Role</option>
                <option value="revoked_role">Revoke Role</option>
            </select>
        </div>
        <div class="flex-1 min-w-48">
            <select id="filter-table" class="form-input w-full">
                <option value="">Semua Tabel</option>
                <option value="users">Users</option>
                <option value="roles">Roles</option>
                <option value="posts">Posts</option>
                <option value="banners">Banners</option>
                <option value="menus">Menus</option>
            </select>
        </div>
        <button id="btn-reset-filter" class="btn btn-secondary text-sm">Reset Filter</button>
    </div>

    <x-ui.table id="logs-table" :headers="['Waktu', 'Pengguna', 'Aksi', 'Tabel', 'ID Data', 'Keterangan']">
        {{-- Will be populated via JS --}}
    </x-ui.table>

    {{-- Pagination --}}
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody         = document.getElementById('logs-table');
        const loading       = document.getElementById('logs-table-loading');
        const empty         = document.getElementById('logs-table-empty');
        const filterAction  = document.getElementById('filter-action');
        const filterTable   = document.getElementById('filter-table');
        const paginationInfo    = document.getElementById('pagination-info');
        const paginationButtons = document.getElementById('pagination-buttons');

        let currentPage = 1;

        const actionLabel = {
            'created':       '<span class="badge badge-active">Created</span>',
            'updated':       '<span class="badge badge-completed">Updated</span>',
            'deleted':       '<span class="badge badge-cancelled">Deleted</span>',
            'assigned_role': '<span class="badge badge-open">Assign Role</span>',
            'revoked_role':  '<span class="badge badge-open">Revoke Role</span>',
        };

        const loadLogs = async (page = 1) => {
            if (tbody)   tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty)   empty.classList.add('hidden');

            const action = filterAction.value;
            const table  = filterTable.value;

            try {
                const response = await window.api.get(`/admin/activity-logs?page=${page}&action=${action}&table=${table}`);
                const data = response.data;

                if (loading) loading.classList.add('hidden');

                if (!data || data.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    paginationInfo.textContent = '';
                    paginationButtons.innerHTML = '';
                    return;
                }

                // Pagination info
                paginationInfo.textContent = `Menampilkan ${response.from}–${response.to} dari ${response.total} log`;

                // Pagination buttons
                let buttons = '';
                if (response.prev_page_url) {
                    buttons += `<button class="btn btn-secondary text-xs btn-page" data-page="${response.current_page - 1}">‹ Prev</button>`;
                }
                buttons += `<span class="px-2 py-1 text-gray-700 font-medium">Hal. ${response.current_page} / ${response.last_page}</span>`;
                if (response.next_page_url) {
                    buttons += `<button class="btn btn-secondary text-xs btn-page" data-page="${response.current_page + 1}">Next ›</button>`;
                }
                paginationButtons.innerHTML = buttons;
                paginationButtons.querySelectorAll('.btn-page').forEach(btn =>
                    btn.addEventListener('click', e => {
                        currentPage = parseInt(e.target.getAttribute('data-page'));
                        loadLogs(currentPage);
                    })
                );

                let rows = '';
                data.forEach(log => {
                    const user    = log.user ? log.user.name : `(User #${log.user_id})`;
                    const date    = window.formatDate ? window.formatDate(log.created_at) : log.created_at;
                    const badge   = actionLabel[log.action] ?? `<span class="badge badge-open">${log.action}</span>`;
                    const notes   = log.notes ? `<span class="text-xs text-gray-500">${log.notes}</span>` : '-';

                    rows += `
                        <tr>
                            <td class="text-gray-500 text-sm whitespace-nowrap">${date}</td>
                            <td class="font-medium text-gray-900">${user}</td>
                            <td>${badge}</td>
                            <td class="text-gray-600 text-sm font-mono">${log.table_affected}</td>
                            <td class="text-gray-400 text-sm">${log.record_id ?? '-'}</td>
                            <td>${notes}</td>
                        </tr>
                    `;
                });

                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat log aktivitas', 'error');
            }
        };

        filterAction.addEventListener('change', () => loadLogs(1));
        filterTable.addEventListener('change', () => loadLogs(1));
        document.getElementById('btn-reset-filter').addEventListener('click', () => {
            filterAction.value = '';
            filterTable.value  = '';
            loadLogs(1);
        });

        loadLogs();
    });
</script>
@endsection
