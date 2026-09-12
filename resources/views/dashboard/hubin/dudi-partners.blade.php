@extends('layouts.dashboard')

@section('title', 'DUDI & Mitra')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">DUDI & Mitra</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data mitra Dunia Usaha dan Dunia Industri (DUDI).</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Mitra
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <input type="text" id="search-input" class="form-input w-full max-w-sm" placeholder="Cari nama perusahaan...">
    </div>

    <x-ui.table id="dudis-table" :headers="['Perusahaan', 'Bidang Industri', 'Logo', 'Dokumen MoU', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="dudi-modal" title="Form Mitra DUDI">
    <form id="dudi-form">
        <input type="hidden" id="dudi_id" name="dudi_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                <input type="text" id="company_name" name="company_name" class="form-input w-full" required maxlength="255">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Industri</label>
                <input type="text" id="industry_field" name="industry_field" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Logo</label>
                <input type="text" id="logo_url" name="logo_url" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen MoU (URL)</label>
                <input type="text" id="mou_document" name="mou_document" class="form-input w-full">
            </div>
        </div>

        <div class="crud-modal-actions">
            <button type="button" class="btn btn-secondary" data-modal-close="dudi-modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-save" id="btn-save-dudi">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('dudis-table');
        const loading = document.getElementById('dudis-table-loading');
        const empty = document.getElementById('dudis-table-empty');
        const modal = document.getElementById('dudi-modal');
        const form = document.getElementById('dudi-form');
        const searchInput = document.getElementById('search-input');
        
        let dudis = [];

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('dudi_id').value = '';
            
            if (id) {
                const d = dudis.find(x => x.id === id);
                if (d) {
                    document.getElementById('dudi_id').value = d.id;
                    document.getElementById('company_name').value = d.company_name;
                    document.getElementById('industry_field').value = d.industry_field || '';
                    document.getElementById('logo_url').value = d.logo_url || '';
                    document.getElementById('mou_document').value = d.mou_document || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteDudi = async (id) => {
            if (!confirm('Hapus mitra ini?')) return;
            try {
                await window.hubinApi.deleteDudi(id);
                window.showToast('Mitra dihapus', 'success');
                loadDudis();
            } catch (error) {
                window.showToast('Gagal menghapus mitra', 'error');
            }
        };

        const loadDudis = async (search = '') => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.hubinApi.dudiPartners({ search });
                dudis = response.data ?? response ?? [];

                if (loading) loading.classList.add('hidden');

                if (!dudis || dudis.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                dudis.forEach(d => {
                    const logoHtml = d.logo_url ? `<img src="${d.logo_url}" class="h-8 object-contain">` : '-';
                    const mouHtml = d.mou_document ? `<a href="${d.mou_document}" target="_blank" class="text-blue-600 underline">Lihat MoU</a>` : '-';
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${d.company_name ?? '-'}</td>
                            <td class="text-gray-600">${d.industry_field ?? '-'}</td>
                            <td>${logoHtml}</td>
                            <td>${mouHtml}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${d.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteDudi(${d.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat mitra', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-dudi');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('dudi_id').value;
            const payload = {
                company_name: document.getElementById('company_name').value,
                industry_field: document.getElementById('industry_field').value || null,
                logo_url: document.getElementById('logo_url').value || null,
                mou_document: document.getElementById('mou_document').value || null,
            };

            try {
                if (id) {
                    await window.hubinApi.updateDudi(id, payload);
                } else {
                    await window.hubinApi.createDudi(payload);
                }
                window.showToast('Mitra berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadDudis();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan mitra', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadDudis(e.target.value);
            }, 500);
        });

        loadDudis();
    });
</script>
@endsection
