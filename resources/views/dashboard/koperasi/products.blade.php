@extends('layouts.dashboard')

@section('title', 'Produk TeFA / Koperasi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Produk TeFA & Koperasi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data produk yang dijual di Teaching Factory dan Koperasi.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Produk
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <input type="text" id="search-input" class="form-input w-full max-w-sm" placeholder="Cari nama produk...">
    </div>

    <x-ui.table id="products-table" :headers="['Produk', 'Kategori/Program', 'Harga', 'Stok', 'Gambar', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="product-modal" title="Form Produk">
    <form id="product-form">
        <input type="hidden" id="product_id" name="product_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" id="product_name" name="product_name" class="form-input w-full" required maxlength="255">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Keahlian (Kategori)</label>
                <select id="program_id" name="program_id" class="form-input w-full" required>
                    <option value="">-- Pilih Program Keahlian --</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" step="0.01" min="0" id="price" name="price" class="form-input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" min="0" id="stock" name="stock" class="form-input w-full" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                <input type="text" id="image_url" name="image_url" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="form-input w-full" rows="3"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="product-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-product">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('products-table');
        const loading = document.getElementById('products-table-loading');
        const empty = document.getElementById('products-table-empty');
        const modal = document.getElementById('product-modal');
        const form = document.getElementById('product-form');
        const searchInput = document.getElementById('search-input');
        
        let products = [];
        let masterData = { programs: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/koperasi/master-data');
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
            document.getElementById('product_id').value = '';
            
            if (id) {
                const p = products.find(x => x.id === id);
                if (p) {
                    document.getElementById('product_id').value = p.id;
                    document.getElementById('product_name').value = p.product_name;
                    document.getElementById('program_id').value = p.program_id;
                    document.getElementById('price').value = p.price;
                    document.getElementById('stock').value = p.stock;
                    document.getElementById('image_url').value = p.image_url || '';
                    document.getElementById('description').value = p.description || '';
                }
            }
            modal.classList.remove('hidden');
        };

        window.deleteProduct = async (id) => {
            if (!confirm('Hapus produk ini?')) return;
            try {
                await window.api.delete(`/koperasi/products/${id}`);
                window.showToast('Produk dihapus', 'success');
                loadProducts();
            } catch (error) {
                window.showToast('Gagal menghapus produk', 'error');
            }
        };

        const loadProducts = async (search = '') => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get(`/koperasi/products?search=${encodeURIComponent(search)}`);
                products = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!products || products.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                products.forEach(p => {
                    const progName = p.program ? p.program.name : '-';
                    const imgHtml = p.image_url ? `<img src="${p.image_url}" class="h-10 w-10 object-cover rounded">` : '-';
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${p.product_name}</td>
                            <td class="text-gray-600">${progName}</td>
                            <td class="text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(p.price)}</td>
                            <td class="text-gray-600">${p.stock}</td>
                            <td>${imgHtml}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="openFormModal(${p.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button onclick="deleteProduct(${p.id})" class="text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat produk', 'error');
            }
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-product');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('product_id').value;
            const payload = {
                product_name: document.getElementById('product_name').value,
                program_id: document.getElementById('program_id').value,
                price: document.getElementById('price').value,
                stock: document.getElementById('stock').value,
                image_url: document.getElementById('image_url').value || null,
                description: document.getElementById('description').value || null,
            };

            try {
                if (id) {
                    await window.api.put(`/koperasi/products/${id}`, payload);
                } else {
                    await window.api.post('/koperasi/products', payload);
                }
                window.showToast('Produk berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadProducts();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan produk', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadProducts(e.target.value);
            }, 500);
        });

        loadMasterData();
        loadProducts();
    });
</script>
@endsection
