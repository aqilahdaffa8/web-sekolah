@extends('layouts.dashboard')
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
    <button onclick="openProductModal()" class="btn btn-primary">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </button>
</div>

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
            <button type="button" data-modal-close class="btn btn-secondary">Batal</button>
            <button type="button" id="btn-save-prod" onclick="saveProduct()" class="btn btn-primary btn-save">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
let editingId = null;
let allProducts = [];

const fallbackImgs = [
    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80',
    'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
];

async function load() {
    const loading = document.getElementById('prod-count');
    loading.classList.remove('hidden');
    loading.textContent = 'Memuat data...';
    try {
        const res = await window.koperasiApi.products();
        allProducts = res.data || res || [];
        render(allProducts);
    } catch(err) {
        loading.classList.add('hidden');
        document.getElementById('products-grid').innerHTML = `
            <div class="col-span-full card p-12 text-center text-danger">
                <p class="font-semibold">Data produk gagal dimuat.</p>
                <p class="mt-1 text-sm text-gray-500">Periksa sesi login atau koneksi API, lalu muat ulang halaman.</p>
            </div>`;
        window.toast.apiError(err);
    }
}

function render(products) {
    const query = document.getElementById('prod-search').value.toLowerCase();
    const filtered = products.filter(p => (p.product_name || p.name || '').toLowerCase().includes(query));
    document.getElementById('prod-count').classList.add('hidden');

    if (!filtered.length) {
        document.getElementById('products-grid').innerHTML = `
            <div class="col-span-4 card p-12 text-center text-gray-400">
                <p class="font-semibold text-base">Belum ada produk yang cocok.</p>
                <p class="text-xs mt-1">Klik Tambah Produk untuk memasukkan produk baru.</p>
            </div>`;
        return;
    }

    document.getElementById('products-grid').innerHTML = filtered.map((p, idx) => {
        const name = p.product_name ?? p.name ?? 'Produk Vokasi';
        const img = p.image_url ?? p.image ?? fallbackImgs[idx % fallbackImgs.length];
        const price = p.price ?? 0;
        const stock = p.stock ?? 0;
        const description = p.description ?? 'Karya terbaik siswa SMKN 1 Katapang.';

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
                    <p class="text-brand-700 font-extrabold text-base">${window.utils.formatCurrency(price)}</p>
                    <p class="text-xs text-gray-500 line-clamp-2 mt-1">${description}</p>
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

