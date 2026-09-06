@extends('layouts.dashboard')
<<<<<<< HEAD
@section('title', 'Produk & Jasa TeFA')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Produk & Jasa</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Produk & Jasa TeFA</h1>
        <p class="text-gray-500 mt-1">Kelola etalase karya siswa dan unit produksi sekolah.</p>
    </div>
    <button onclick="openProductModal()" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
=======

@section('title', 'Produk TeFA / Koperasi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Produk TeFA & Koperasi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data produk yang dijual di Teaching Factory dan Koperasi.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
        Tambah Produk
    </button>
</div>

<<<<<<< HEAD
<div class="card mb-6 p-4">
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="relative w-full sm:w-80">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="search" id="prod-search" class="form-input pl-9 text-sm" placeholder="Cari nama produk...">
        </div>
        <div class="text-xs text-gray-500 font-medium" id="prod-count">Memuat data...</div>
    </div>
</div>

<div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
    @for($i=0;$i<8;$i++)
    <div class="card p-3 space-y-3 animate-pulse">
        <div class="skeleton aspect-square w-full rounded-xl"></div>
        <div class="skeleton h-4 w-3/4 rounded"></div>
        <div class="skeleton h-5 w-1/2 rounded"></div>
    </div>
    @endfor
</div>

<div id="prod-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="prod-modal-title" class="text-lg font-bold text-gray-900">Tambah Produk</h3>
            <button data-modal-close class="btn-icon text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="prod-id">
            <div>
                <label class="form-label">Nama Produk *</label>
                <input type="text" id="p-name" class="form-input" placeholder="Contoh: Tas Kulit Premium">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Harga (Rp) *</label>
                    <input type="number" id="p-price" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Stok Tersedia</label>
                    <input type="number" id="p-stock" class="form-input" placeholder="10">
                </div>
            </div>
            <div>
                <label class="form-label">Deskripsi Produk</label>
                <textarea id="p-desc" class="form-input h-24 resize-none" placeholder="Deskripsi spesifikasi produk karya siswa..."></textarea>
            </div>
            <div>
                <label class="form-label">URL Foto Produk</label>
                <input type="url" id="p-image-url" class="form-input" placeholder="https://images.unsplash.com/...">
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-secondary">Batal</button>
            <button id="btn-save-prod" onclick="saveProduct()" class="btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;
let allProducts = [];

const fallbackImgs = [
    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
];

async function load() {
    try {
        const res = await window.koperasiApi.products();
        allProducts = res.data || res || [];
        render(allProducts);
    } catch(err) {
        window.toast.apiError(err);
    }
}

function render(products) {
    const query = document.getElementById('prod-search').value.toLowerCase();
    const filtered = products.filter(p => (p.product_name || p.name || '').toLowerCase().includes(query));
    document.getElementById('prod-count').textContent = `Total: ${filtered.length} Produk`;

    if (!filtered.length) {
        document.getElementById('products-grid').innerHTML = `
            <div class="col-span-4 card p-12 text-center text-gray-400">
                <p class="font-semibold text-base">Belum ada produk yang cocok.</p>
                <p class="text-xs mt-1">Klik Tambah Produk untuk memasukkan produk baru.</p>
            </div>`;
        return;
    }

    document.getElementById('products-grid').innerHTML = filtered.map((p, idx) => {
        const name = p.product_name || p.name || 'Produk Vokasi';
        const img = p.image_url || p.image || fallbackImgs[idx % fallbackImgs.length];
        const stock = p.stock ?? 10;

        return `
        <div class="card overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all">
            <div>
                <div class="overflow-hidden relative aspect-square bg-gray-100">
                    <img src="${img}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='${fallbackImgs[idx % fallbackImgs.length]}'">
                    <span class="absolute top-2.5 left-2.5 badge ${stock > 0 ? 'badge-success' : 'badge-danger'} shadow-sm">
                        ${stock > 0 ? 'Stok ' + stock : 'Habis'}
                    </span>
                </div>
                <div class="p-4">
                    <p class="font-bold text-sm text-gray-900 line-clamp-1 mb-1">${name}</p>
                    <p class="text-brand-700 font-extrabold text-base">${window.utils.formatCurrency(p.price)}</p>
                    <p class="text-xs text-gray-500 line-clamp-2 mt-1">${p.description || 'Karya terbaik siswa SMKN 1 Katapang.'}</p>
                </div>
            </div>
            <div class="p-4 pt-0 flex gap-2">
                <button onclick='editProduct(${JSON.stringify(p).replace(/'/g, "&apos;")})' class="btn-table-edit flex-1">
                    Edit
                </button>
                <button onclick="deleteProduct(${p.id})" class="btn-table-delete">
                    Hapus
                </button>
            </div>
        </div>`;
    }).join('');
}

window.openProductModal = () => {
    editingId = null;
    document.getElementById('prod-modal-title').textContent = 'Tambah Produk';
    ['prod-id', 'p-name', 'p-price', 'p-stock', 'p-desc', 'p-image-url'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('p-stock').value = 10;
    window.openModal(document.getElementById('prod-modal'));
};

window.editProduct = (p) => {
    if(typeof p === 'string') p = JSON.parse(p);
    editingId = p.id;
    document.getElementById('prod-modal-title').textContent = 'Edit Produk';
    document.getElementById('prod-id').value = p.id;
    document.getElementById('p-name').value = p.product_name || p.name;
    document.getElementById('p-price').value = p.price;
    document.getElementById('p-stock').value = p.stock || 0;
    document.getElementById('p-desc').value = p.description || '';
    document.getElementById('p-image-url').value = p.image_url || '';
    window.openModal(document.getElementById('prod-modal'));
};

window.saveProduct = async () => {
    const btn = document.getElementById('btn-save-prod');
    const name = document.getElementById('p-name').value.trim();
    if (!name) {
        window.toast.error('Nama produk wajib diisi.');
        return;
    }
    const payload = {
        product_name: name,
        name: name,
        price: document.getElementById('p-price').value || 0,
        stock: document.getElementById('p-stock').value || 0,
        description: document.getElementById('p-desc').value.trim(),
        image_url: document.getElementById('p-image-url').value.trim(),
        program_id: 1,
    };
    try {
        window.utils.setButtonLoading(btn, true);
        if (editingId) {
            await window.koperasiApi.updateProduct(editingId, payload);
            window.toast.success('Produk berhasil diperbarui.');
        } else {
            await window.koperasiApi.createProduct(payload);
            window.toast.success('Produk berhasil ditambahkan.');
        }
        window.closeModal(document.getElementById('prod-modal'));
        load();
    } catch(err) {
        window.toast.apiError(err);
    } finally {
        window.utils.setButtonLoading(btn, false, 'Simpan');
    }
};

window.deleteProduct = async (id) => {
    if (!confirm('Hapus produk ini dari katalog?')) return;
    try {
        await window.koperasiApi.deleteProduct(id);
        window.toast.success('Produk berhasil dihapus.');
        load();
    } catch(err) {
        window.toast.apiError(err);
    }
};

document.getElementById('prod-search').addEventListener('input', () => render(allProducts));

load();
</script>
@endpush
=======
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
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
