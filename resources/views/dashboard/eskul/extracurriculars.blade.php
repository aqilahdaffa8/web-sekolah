@extends('layouts.dashboard')

@section('title', 'Daftar Eskul')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Ekstrakurikuler</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data ekstrakurikuler sekolah dan pembina (coach).</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Eskul
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="eskul-table" :headers="['Nama Eskul', 'Jadwal', 'Pembina', 'Jml Pendaftar', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
</div>

<x-ui.modal id="eskul-modal" title="Form Ekstrakurikuler">
    <form id="eskul-form">
        <input type="hidden" id="eskul_id" name="eskul_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Eskul</label>
                <input type="text" id="name" name="name" class="form-input w-full" required maxlength="255">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal</label>
                <input type="text" id="schedule" name="schedule" class="form-input w-full" placeholder="Misal: Setiap Jumat 14.00 - 16.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pembina (Coach)</label>
                <select id="coach_id" name="coach_id" class="form-input w-full">
                    <option value="">-- Pilih Pembina --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="form-input w-full" rows="3"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="eskul-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-eskul">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('eskul-table');
        const loading = document.getElementById('eskul-table-loading');
        const empty = document.getElementById('eskul-table-empty');
        const modal = document.getElementById('eskul-modal');
        const form = document.getElementById('eskul-form');
        
        let records = [];
        let masterData = { users: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/eskul/master-data');
                const coachSelect = document.getElementById('coach_id');
                masterData.users.forEach(u => {
                    coachSelect.add(new Option(u.name, u.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('eskul_id').value = '';
            
            if (id) {
                const item = records.find(x => x.id === id);
                if (item) {
                    document.getElementById('eskul_id').value = item.id;
                    document.getElementById('name').value = item.name;
                    document.getElementById('schedule').value = item.schedule || '';
                    document.getElementById('coach_id').value = item.coach_id || '';
                    document.getElementById('description').value = item.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteEskul = async (id) => {
            if (!confirm('Hapus eskul ini?')) return;
            try {
                await window.api.delete(`/eskul/extracurriculars/${id}`);
                window.showToast('Eskul dihapus', 'success');
                loadRecords();
            } catch (error) {
                window.showToast('Gagal menghapus eskul', 'error');
            }
        };

        const loadRecords = async () => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get('/eskul/extracurriculars');
                // The endpoint currently returns all records (not paginated) based on Controller index()
                records = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!records || records.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                records.forEach(item => {
                    const coachName = item.coach ? item.coach.name : '-';
                    const count = item.registrations_count || 0;
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${item.name}</td>
                            <td class="text-gray-600">${item.schedule || '-'}</td>
                            <td class="text-gray-600">${coachName}</td>
                            <td class="text-gray-600">${count} Pendaftar</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${item.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteEskul(${item.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data eskul', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-eskul');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('eskul_id').value;
            const payload = {
                name: document.getElementById('name').value,
                schedule: document.getElementById('schedule').value || null,
                coach_id: document.getElementById('coach_id').value || null,
                description: document.getElementById('description').value || null,
            };

            try {
                if (id) {
                    await window.api.put(`/eskul/extracurriculars/${id}`, payload);
                } else {
                    await window.api.post('/eskul/extracurriculars', payload);
                }
                window.showToast('Eskul berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadRecords();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan eskul', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        loadMasterData();
        loadRecords();
    });
</script>
@endsection
