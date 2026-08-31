@extends('layouts.dashboard')

@section('title', 'Modul Belajar')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Modul Belajar</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola materi pelajaran dan modul untuk siswa.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Modul
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <x-ui.table id="modules-table" :headers="['Judul', 'Kelas', 'Mata Pelajaran', 'File', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>
</div>

<x-ui.modal id="module-modal" title="Form Modul Belajar">
    <form id="module-form">
        <input type="hidden" id="module_id" name="module_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Modul</label>
                <input type="text" id="title" name="title" class="form-input w-full" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="form-input w-full" rows="3"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL / Path File</label>
                <input type="text" id="file_path" name="file_path" class="form-input w-full">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select id="class_id" name="class_id" class="form-input w-full" required>
                        <option value="">-- Pilih Kelas --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select id="subject_id" name="subject_id" class="form-input w-full" required>
                        <option value="">-- Pilih Mapel --</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="module-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-module">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('modules-table');
        const loading = document.getElementById('modules-table-loading');
        const empty = document.getElementById('modules-table-empty');
        const modal = document.getElementById('module-modal');
        const form = document.getElementById('module-form');
        
        let modules = [];
        let masterData = { classes: [], subjects: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/guru/master-data');
                const classSelect = document.getElementById('class_id');
                masterData.classes.forEach(c => {
                    classSelect.add(new Option(c.name, c.id));
                });
                const subjectSelect = document.getElementById('subject_id');
                masterData.subjects.forEach(s => {
                    subjectSelect.add(new Option(s.name, s.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('module_id').value = '';
            
            if (id) {
                const mod = modules.find(m => m.id === id);
                if (mod) {
                    document.getElementById('module_id').value = mod.id;
                    document.getElementById('title').value = mod.title;
                    document.getElementById('description').value = mod.description || '';
                    document.getElementById('file_path').value = mod.file_path || '';
                    document.getElementById('class_id').value = mod.class_id || '';
                    document.getElementById('subject_id').value = mod.subject_id || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteModule = async (id) => {
            if (!confirm('Hapus modul ini?')) return;
            try {
                await window.api.delete(`/guru/learning-modules/${id}`);
                window.showToast('Modul dihapus', 'success');
                loadModules();
            } catch (error) {
                window.showToast('Gagal menghapus modul', 'error');
            }
        };

        const loadModules = async () => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get('/guru/learning-modules');
                modules = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!modules || modules.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                modules.forEach(mod => {
                    const className = mod.class_room ? mod.class_room.name : `Kelas #${mod.class_id}`;
                    const subjectName = mod.subject ? mod.subject.name : `Mapel #${mod.subject_id}`;
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${mod.title}</td>
                            <td class="text-gray-600">${className}</td>
                            <td class="text-gray-600">${subjectName}</td>
                            <td class="text-blue-600"><a href="${mod.file_path}" target="_blank">Lihat File</a></td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${mod.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteModule(${mod.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat modul', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-module');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('module_id').value;
            const payload = {
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                file_path: document.getElementById('file_path').value,
            };
            if (!id) {
                payload.class_id = document.getElementById('class_id').value;
                payload.subject_id = document.getElementById('subject_id').value;
            }

            try {
                if (id) {
                    await window.api.put(`/guru/learning-modules/${id}`, payload);
                } else {
                    await window.api.post('/guru/learning-modules', payload);
                }
                window.showToast('Modul berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadModules();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan modul', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        loadMasterData();
        loadModules();
    });
</script>
@endsection
