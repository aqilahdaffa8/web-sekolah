@extends('layouts.dashboard')

@section('title', 'Lowongan Kerja')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Lowongan Kerja</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data lowongan kerja (BKK) dari mitra DUDI.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Lowongan
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex gap-4 bg-gray-50">
        <input type="text" id="search-input" class="form-input w-full max-w-sm" placeholder="Cari posisi...">
        <select id="status-filter" class="form-input w-48">
            <option value="">Semua Status</option>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>
    </div>

    <x-ui.table id="jobs-table" :headers="['Posisi / Pekerjaan', 'Perusahaan (DUDI)', 'Status', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="job-modal" title="Form Lowongan Kerja">
    <form id="job-form">
        <input type="hidden" id="job_id" name="job_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Pekerjaan</label>
                <input type="text" id="job_title" name="job_title" class="form-input w-full" required maxlength="255">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan (Mitra DUDI)</label>
                <select id="dudi_id" name="dudi_id" class="form-input w-full" required>
                    <option value="">-- Pilih Perusahaan --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="form-input w-full" required>
                    <option value="open">Open (Dibuka)</option>
                    <option value="closed">Closed (Ditutup)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi & Syarat</label>
                <textarea id="description" name="description" class="form-input w-full" rows="5"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="job-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-job">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('jobs-table');
        const loading = document.getElementById('jobs-table-loading');
        const empty = document.getElementById('jobs-table-empty');
        const modal = document.getElementById('job-modal');
        const form = document.getElementById('job-form');
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        
        let jobs = [];
        let masterData = { dudis: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/hubin/master-data');
                const dudiSelect = document.getElementById('dudi_id');
                masterData.dudis.forEach(d => {
                    dudiSelect.add(new Option(d.company_name, d.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('job_id').value = '';
            document.getElementById('status').value = 'open'; // default
            
            if (id) {
                const j = jobs.find(x => x.id === id);
                if (j) {
                    document.getElementById('job_id').value = j.id;
                    document.getElementById('job_title').value = j.job_title;
                    document.getElementById('dudi_id').value = j.dudi_id;
                    document.getElementById('status').value = j.status || 'open';
                    document.getElementById('description').value = j.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteJob = async (id) => {
            if (!confirm('Hapus lowongan ini?')) return;
            try {
                await window.api.delete(`/hubin/job-vacancies/${id}`);
                window.showToast('Lowongan dihapus', 'success');
                loadJobs();
            } catch (error) {
                window.showToast('Gagal menghapus lowongan', 'error');
            }
        };

        const loadJobs = async () => {
            const search = searchInput.value;
            const status = statusFilter.value;

            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                // Adjust parameter query based on your controller setup
                let url = `/hubin/job-vacancies?search=${encodeURIComponent(search)}`;
                if (status) url += `&status=${status}`;

                const response = await window.api.get(url);
                jobs = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!jobs || jobs.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                jobs.forEach(j => {
                    const company = j.dudi_partner ? j.dudi_partner.company_name : '-';
                    const badgeClass = j.status === 'open' ? 'badge-active' : 'badge-cancelled';
                    const badgeText = j.status === 'open' ? 'Open' : 'Closed';
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${j.job_title}</td>
                            <td class="text-gray-600">${company}</td>
                            <td><span class="badge ${badgeClass}">${badgeText}</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${j.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteJob(${j.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat lowongan', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-job');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('job_id').value;
            const payload = {
                job_title: document.getElementById('job_title').value,
                dudi_id: document.getElementById('dudi_id').value,
                status: document.getElementById('status').value,
                description: document.getElementById('description').value || null,
            };

            try {
                if (id) {
                    await window.api.put(`/hubin/job-vacancies/${id}`, payload);
                } else {
                    await window.api.post('/hubin/job-vacancies', payload);
                }
                window.showToast('Lowongan berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadJobs();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan lowongan', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadJobs, 500);
        });
        statusFilter.addEventListener('change', loadJobs);

        loadMasterData();
        loadJobs();
    });
</script>
@endsection
