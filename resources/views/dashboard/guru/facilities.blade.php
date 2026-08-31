@extends('layouts.dashboard')

@section('title', 'Fasilitas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Fasilitas</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data fasilitas sekolah dan laboratorium.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Fasilitas
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="facilities-table" :headers="['Fasilitas', 'Program Keahlian', 'Deskripsi', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

<x-ui.modal id="facility-modal" title="Form Fasilitas">
    <form id="facility-form">
        <input type="hidden" id="facility_id" name="facility_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                <input type="text" id="name" name="name" class="form-input w-full" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Keahlian</label>
                <select id="program_id" name="program_id" class="form-input w-full" required>
                    <option value="">-- Pilih Program --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="form-input w-full" rows="3"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="facility-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-facility">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('facilities-table');
        const loading = document.getElementById('facilities-table-loading');
        const empty = document.getElementById('facilities-table-empty');
        const modal = document.getElementById('facility-modal');
        const form = document.getElementById('facility-form');
        
        let facilities = [];
        let masterData = { programs: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/guru/master-data');
                const progSelect = document.getElementById('program_id');
                masterData.programs.forEach(p => {
                    progSelect.add(new Option(p.name, p.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('facility_id').value = '';
            
            if (id) {
                const fac = facilities.find(f => f.id === id);
                if (fac) {
                    document.getElementById('facility_id').value = fac.id;
                    document.getElementById('name').value = fac.name;
                    document.getElementById('program_id').value = fac.program_id || '';
                    document.getElementById('description').value = fac.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteFacility = async (id) => {
            if (!confirm('Hapus fasilitas ini?')) return;
            try {
                await window.api.delete(`/guru/facilities/${id}`);
                window.showToast('Fasilitas dihapus', 'success');
                loadFacilities();
            } catch (error) {
                window.showToast('Gagal menghapus fasilitas', 'error');
            }
        };

        const loadFacilities = async () => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get('/guru/facilities');
                facilities = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!facilities || facilities.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                facilities.forEach(fac => {
                    const progName = fac.program ? fac.program.name : (fac.program_id ? `Program #${fac.program_id}` : '-');
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${fac.name}</td>
                            <td class="text-gray-600">${progName}</td>
                            <td class="text-gray-600 text-sm truncate max-w-xs">${fac.description || '-'}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${fac.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteFacility(${fac.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat fasilitas', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-facility');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('facility_id').value;
            const payload = {
                name: document.getElementById('name').value,
                program_id: document.getElementById('program_id').value,
                description: document.getElementById('description').value,
            };

            try {
                if (id) {
                    await window.api.put(`/guru/facilities/${id}`, payload);
                } else {
                    await window.api.post('/guru/facilities', payload);
                }
                window.showToast('Fasilitas berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadFacilities();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan fasilitas', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        loadMasterData();
        loadFacilities();
    });
</script>
@endsection
