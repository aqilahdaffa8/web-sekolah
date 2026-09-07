@extends('layouts.dashboard')

@section('title', 'Tracer Study')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tracer Study</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data penelusuran alumni (Kerja, Kuliah, Wirausaha).</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Data
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex gap-4 bg-gray-50">
        <select id="status-filter" class="form-input w-48 border border-gray-300 rounded-lg">
            <option value="">Semua Status</option>
            <option value="Kerja">Bekerja</option>
            <option value="Kuliah">Kuliah</option>
            <option value="Wirausaha">Wirausaha</option>
        </select>
    </div>

    <x-ui.table id="tracer-table" :headers="['Nama Alumni', 'Tahun Lulus', 'Status', 'Tempat (Perusahaan/Kampus)', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="tracer-modal" title="Form Tracer Study">
    <form id="tracer-form">
        <input type="hidden" id="tracer_id" name="tracer_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Siswa (Alumni)</label>
                <select id="student_id" name="student_id" class="form-input w-full" required>
                    <option value="">-- Pilih Siswa --</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                    <input type="number" id="graduation_year" name="graduation_year" class="form-input w-full" min="2000" max="2099" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Saat Ini</label>
                    <select id="current_status" name="current_status" class="form-input w-full" required>
                        <option value="Kerja">Bekerja</option>
                        <option value="Kuliah">Kuliah</option>
                        <option value="Wirausaha">Wirausaha</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan / Kampus / Usaha</label>
                <input type="text" id="company_or_campus_name" name="company_or_campus_name" class="form-input w-full" maxlength="255">
            </div>
        </div>

        <div class="crud-modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close="tracer-modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-save" id="btn-save-tracer">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('tracer-table');
        const loading = document.getElementById('tracer-table-loading');
        const empty = document.getElementById('tracer-table-empty');
        const modal = document.getElementById('tracer-modal');
        const form = document.getElementById('tracer-form');
        const statusFilter = document.getElementById('status-filter');
        
        let records = [];
        let masterData = { students: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/hubin/master-data');
                const studentSelect = document.getElementById('student_id');
                masterData.students.forEach(s => {
                    studentSelect.add(new Option(s.name, s.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('tracer_id').value = '';
            document.getElementById('current_status').value = 'Kerja'; // default
            document.getElementById('graduation_year').value = new Date().getFullYear();
            
            if (id) {
                const t = records.find(x => x.id === id);
                if (t) {
                    document.getElementById('tracer_id').value = t.id;
                    document.getElementById('student_id').value = t.student_id;
                    document.getElementById('graduation_year').value = t.graduation_year;
                    document.getElementById('current_status').value = t.current_status;
                    document.getElementById('company_or_campus_name').value = t.company_or_campus_name || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteTracer = async (id) => {
            if (!confirm('Hapus data tracer study ini?')) return;
            try {
                await window.api.delete(`/hubin/tracer-studies/${id}`);
                window.showToast('Data dihapus', 'success');
                loadRecords();
            } catch (error) {
                window.showToast('Gagal menghapus data', 'error');
            }
        };

        const loadRecords = async () => {
            const status = statusFilter.value;

            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                let url = `/hubin/tracer-studies`;
                if (status) url += `?status=${encodeURIComponent(status)}`; // Adjust to controller filter if any

                const response = await window.api.get(url);
                records = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!records || records.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                records.forEach(t => {
                    const student = t.student ? t.student.name : '-';
                    const badgeClass = t.current_status === 'Kerja' ? 'badge-active' : 
                                       t.current_status === 'Kuliah' ? 'badge-completed' : 'badge-open';
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${student}</td>
                            <td class="text-gray-600">${t.graduation_year}</td>
                            <td><span class="badge ${badgeClass}">${t.current_status}</span></td>
                            <td class="text-gray-600">${t.company_or_campus_name || '-'}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${t.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteTracer(${t.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat tracer study', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-tracer');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('tracer_id').value;
            const payload = {
                student_id: document.getElementById('student_id').value,
                graduation_year: document.getElementById('graduation_year').value,
                current_status: document.getElementById('current_status').value,
                company_or_campus_name: document.getElementById('company_or_campus_name').value || null,
            };

            try {
                if (id) {
                    await window.api.put(`/hubin/tracer-studies/${id}`, payload);
                } else {
                    await window.api.post('/hubin/tracer-studies', payload);
                }
                window.showToast('Data berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadRecords();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan data', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        statusFilter.addEventListener('change', loadRecords);

        loadMasterData();
        loadRecords();
    });
</script>
@endsection
