@extends('layouts.public')
@section('title', 'Katalog TeFA & Koperasi')
@section('meta_description', 'Produk dan jasa karya siswa SMK — beli langsung, dukung belajar industri.')

@section('content')

{{-- Hero --}}
<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container">
        <div class="max-w-2xl">
            <span class="badge-brand mb-4 inline-block">TeFA & Koperasi</span>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Katalog Produk & Jasa</h1>
            <p class="text-white/70 mb-6">Temukan produk berkualitas karya siswa. Setiap pembelian mendukung pembelajaran vokasi nyata.</p>
            <div class="relative max-w-md">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input id="product-search" type="search" placeholder="Cari produk..."
                       class="form-input pl-10 bg-white/10 border-white/20 text-white placeholder-white/50 focus:bg-white focus:text-gray-900">
            </div>
        </div>
    </div>
</div>

{{-- Filter & Grid --}}
<section class="section-padding bg-gray-50">
    <div class="section-container">

        {{-- Category Filter --}}
        <div id="category-filters" class="flex gap-2 mb-8 overflow-x-auto scrollbar-thin pb-2">
            <button class="btn btn-sm btn-primary flex-shrink-0 category-btn active" data-cat="all">Semua</button>
            {{-- Dynamic categories via JS --}}
        </div>

        <div id="products-container">
            {{-- Skeleton --}}
            <div id="products-skeleton" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
                @for($i=0;$i<8;$i++)
                <div class="card">
                    <div class="skeleton aspect-square w-full"></div>
                    <div class="p-4 space-y-2">
                        <div class="skeleton h-4 w-3/4 rounded"></div>
                        <div class="skeleton h-3 w-1/2 rounded"></div>
                        <div class="skeleton h-5 w-2/3 rounded"></div>
                    </div>
                </div>
                @endfor
            </div>

            <div id="products-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6 hidden"></div>

            <div id="products-empty" class="hidden text-center py-20">
                <p class="text-5xl mb-4">🛒</p>
                <p class="text-gray-500">Tidak ada produk ditemukan.</p>
            </div>
        </div>

        {{-- Pagination --}}
        <div id="products-pagination" class="mt-10"></div>
    </div>
</section>

{{-- ═══════════════════════════════════
     PRODUCT DETAIL MODAL
═══════════════════════════════════ --}}
<div id="product-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-2xl">
        <div class="modal-header">
            <h3 id="modal-product-name" class="text-lg font-bold text-gray-900"></h3>
            <button data-modal-close class="btn-icon text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <img id="modal-product-img" src="" alt="" class="w-full aspect-square object-cover rounded-xl">
                <div>
                    <div id="modal-product-details"></div>

                    {{-- Order form --}}
                    <form id="order-form" class="mt-5 space-y-3">
                        <input type="hidden" id="order-product-id">
                        <div>
                            <label class="form-label" for="order-name">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="order-name" name="customer_name" class="form-input" placeholder="Nama Anda" required>
                        </div>
                        <div>
                            <label class="form-label" for="order-phone">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" id="order-phone" name="customer_phone" class="form-input" placeholder="08XXXXXXXXXX" required>
                        </div>
                        <div>
                            <label class="form-label" for="order-qty">Jumlah <span class="text-danger">*</span></label>
                            <div class="flex gap-2 items-center">
                                <button type="button" onclick="changeQty(-1)" class="btn btn-secondary btn-sm w-10 h-10 p-0 justify-center text-lg">−</button>
                                <input type="number" id="order-qty" name="quantity" value="1" min="1"
                                       class="form-input text-center w-20" readonly>
                                <button type="button" onclick="changeQty(1)" class="btn btn-secondary btn-sm w-10 h-10 p-0 justify-center text-lg">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="order-address">Alamat Pengiriman <span class="text-danger">*</span></label>
                            <textarea id="order-address" name="delivery_address" class="form-input h-20 resize-none" placeholder="Alamat lengkap..." required></textarea>
                        </div>
                        <div>
                            <label class="form-label" for="order-note">Catatan (opsional)</label>
                            <input type="text" id="order-note" name="notes" class="form-input" placeholder="Warna, ukuran, atau permintaan khusus">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn btn-ghost">Batal</button>
            <button id="btn-submit-order" onclick="submitOrder()" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                Pesan Sekarang
            </button>
        </div>
    </div>
</div>

{{-- Order Success Modal --}}
<div id="success-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-sm text-center">
        <div class="p-8">
            <div class="w-20 h-20 bg-success-light rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Pesanan Diterima!</h3>
            <p class="text-gray-500 text-sm mb-2">Pesanan Anda sedang diproses dengan status <span class="badge-warning">Pending</span></p>
            <p class="text-gray-500 text-sm mb-6">Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi dan pembayaran.</p>
            <p class="text-xs text-gray-400 mb-6">Kode pesanan: <span id="order-code" class="font-mono font-bold text-brand-700"></span></p>
            <button data-modal-close class="btn btn-primary w-full">Selesai</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
import { publicApi } from '/resources/js/api.js';
import { storageUrl, formatCurrency, formatNumber, debounce, setFormErrors, setButtonLoading } from '/resources/js/utils.js';
import { toast }     from '/resources/js/toast.js';

let allProducts = [];
let categories  = [];
let currentCat  = 'all';
let currentProduct = null;

async function loadProducts() {
    try {
        const data = await publicApi.tefa();
        allProducts = data.products || data || [];
        categories  = [...new Set(allProducts.map(p => p.category?.name).filter(Boolean))];

        renderCategories();
        renderProducts(allProducts);
    } catch (err) {
        console.error(err);
        toast.error('Gagal memuat produk.');
    }
}

function renderCategories() {
    const container = document.getElementById('category-filters');
    const extras = categories.map(cat =>
        `<button class="btn btn-sm btn-secondary flex-shrink-0 category-btn" data-cat="${cat}" onclick="setCategory('${cat}')">${cat}</button>`
    ).join('');
    container.innerHTML = `<button class="btn btn-sm btn-primary flex-shrink-0 category-btn active" data-cat="all" onclick="setCategory('all')">Semua</button>${extras}`;
}

window.setCategory = (cat) => {
    currentCat = cat;
    document.querySelectorAll('.category-btn').forEach(btn => {
        const isActive = btn.dataset.cat === cat;
        btn.className = `btn btn-sm ${isActive ? 'btn-primary' : 'btn-secondary'} flex-shrink-0 category-btn`;
    });
    const filtered = cat === 'all' ? allProducts : allProducts.filter(p => p.category?.name === cat);
    renderProducts(filtered);
};

function renderProducts(products) {
    document.getElementById('products-skeleton').classList.add('hidden');
    const grid  = document.getElementById('products-grid');
    const empty = document.getElementById('products-empty');

    if (!products.length) {
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
        return;
    }
    empty.classList.add('hidden');
    grid.classList.remove('hidden');

    grid.innerHTML = products.map(p => `
        <div class="product-card group cursor-pointer" onclick="openProduct(${JSON.stringify(p).replace(/"/g, '&quot;')})">
            <div class="overflow-hidden relative">
                <img src="${storageUrl(p.image_url || p.image)}" alt="${p.product_name || p.name}"
                     class="w-full aspect-square object-cover transition-transform duration-500 group-hover:scale-110"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80'">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all flex items-center justify-center">
                    <span class="btn btn-primary opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">
                        Pesan
                    </span>
                </div>
                ${p.stock !== undefined ? `
                <span class="absolute top-2 left-2 ${p.stock > 0 ? 'badge-success' : 'badge-danger'}">
                    ${p.stock > 0 ? `Stok: ${p.stock}` : 'Habis'}
                </span>` : ''}
            </div>
            <div class="product-card-body">
                <p class="text-xs text-brand-600 mb-1">${p.category?.name ?? 'Produk Kejuruan'}</p>
                <h3 class="font-bold text-gray-900 line-clamp-2 text-sm">${p.product_name || p.name}</h3>
                <p class="text-base font-black text-brand-700 mt-2">${formatCurrency(p.price)}</p>
            </div>
        </div>
    `).join('');
}

// ── Open product detail ───────────────────────────────────────
window.openProduct = (product) => {
    if (typeof product === 'string') product = JSON.parse(product);
    currentProduct = product;

    const pName = product.product_name || product.name || 'Detail Produk';
    document.getElementById('modal-product-name').textContent = pName;
    document.getElementById('modal-product-img').src = storageUrl(product.image_url || product.image);
    document.getElementById('modal-product-img').alt = pName;
    document.getElementById('order-product-id').value = product.id;
    document.getElementById('order-qty').value = 1;

    document.getElementById('modal-product-details').innerHTML = `
        <p class="text-xs text-brand-600 mb-1">${product.category?.name ?? ''}</p>
        <p class="text-2xl font-black text-brand-700 mb-2">${formatCurrency(product.price)}</p>
        <p class="text-sm text-gray-500 mb-3">${product.description || ''}</p>
        <div class="flex gap-2 flex-wrap">
            <span class="${(product.stock ?? 1) > 0 ? 'badge-success' : 'badge-danger'}">
                ${(product.stock ?? 1) > 0 ? `Stok: ${product.stock ?? '✓'}` : 'Stok habis'}
            </span>
            ${product.unit ? `<span class="badge-gray">Per ${product.unit}</span>` : ''}
        </div>
    `;

    openModal(document.getElementById('product-modal'));
};

// ── Qty buttons ───────────────────────────────────────────────
window.changeQty = (delta) => {
    const input = document.getElementById('order-qty');
    const max   = currentProduct?.stock ?? 999;
    const val   = Math.max(1, Math.min(parseInt(input.value) + delta, max));
    input.value = val;
};

// ── Submit order ──────────────────────────────────────────────
window.submitOrder = async () => {
    const btn = document.getElementById('btn-submit-order');
    const form = document.getElementById('order-form');

    const payload = {
        product_id:       document.getElementById('order-product-id').value,
        customer_name:    document.getElementById('order-name').value,
        customer_phone:   document.getElementById('order-phone').value,
        quantity:         parseInt(document.getElementById('order-qty').value),
        delivery_address: document.getElementById('order-address').value,
        notes:            document.getElementById('order-note').value,
    };

    if (!payload.customer_name || !payload.customer_phone || !payload.delivery_address) {
        toast.warning('Harap lengkapi semua field yang wajib diisi.');
        return;
    }

    try {
        setButtonLoading(btn, true, 'Memproses...');
        const data = await publicApi.placeOrder(payload);
        closeModal(document.getElementById('product-modal'));
        document.getElementById('order-code').textContent = data.order?.code || data.code || '-';
        form.reset();
        openModal(document.getElementById('success-modal'));
    } catch (err) {
        if (err.status === 422) setFormErrors(err.errors);
        else toast.apiError(err);
    } finally {
        setButtonLoading(btn, false, 'Pesan Sekarang');
    }
};

// ── Search ────────────────────────────────────────────────────
document.getElementById('product-search').addEventListener('input', debounce(e => {
    const q = e.target.value.toLowerCase();
    const filtered = allProducts.filter(p =>
        p.name.toLowerCase().includes(q) ||
        (p.category?.name || '').toLowerCase().includes(q)
    );
    renderProducts(filtered);
}, 300));

loadProducts();
</script>
@endpush
