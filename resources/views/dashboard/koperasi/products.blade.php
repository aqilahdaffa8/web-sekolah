@extends('layouts.dashboard')

@section('title', 'Katalog Produk TeFA')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Produk & Jasa TeFA</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola katalog Teaching Factory.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('product-modal').classList.remove('hidden')">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Item
    </button>
</div>

<!-- Stat cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-dashboard.stat-card title="Total Item" value="24" color="blue" />
    <x-dashboard.stat-card title="Pesanan Pending" value="12" color="yellow" />
    <x-dashboard.stat-card title="Pendapatan Bulan Ini" value="Rp 4.5M" color="green" />
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div class="w-1/3">
            <x-ui.form-input type="text" name="search" placeholder="Cari nama produk..." class="w-full" />
        </div>
        <div>
            <select class="form-input" id="filter-type">
                <option value="">Semua Tipe</option>
                <option value="product">Produk Fisik</option>
                <option value="service">Jasa</option>
            </select>
        </div>
    </div>
    
    <x-ui.table id="products-table" :headers="['Nama Item', 'Kategori', 'Tipe', 'Harga', 'Stok', 'Status', 'Aksi']">
        <!-- Will be populated via JS -->
    </x-ui.table>
</div>

<!-- Modal Create/Edit Product -->
<x-ui.modal id="product-modal" title="Data Produk/Jasa" maxWidth="lg">
    <form id="product-form">
        <input type="hidden" name="id" id="product-id">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <x-ui.form-input type="text" name="name" label="Nama Produk/Jasa" required="true" />
            </div>
            
            <div>
                <x-ui.form-input type="select" name="type" label="Tipe" required="true" :options="[
                    'product' => 'Produk Fisik',
                    'service' => 'Jasa'
                ]" />
            </div>
            <div>
                <x-ui.form-input type="text" name="category" label="Kategori/Jurusan" required="true" />
            </div>
            
            <div>
                <x-ui.form-input type="number" name="price" label="Harga (Rp)" required="true" />
            </div>
            <div>
                <x-ui.form-input type="number" name="stock" label="Stok Tersedia" required="true" />
            </div>
            
            <div class="md:col-span-2">
                <x-ui.form-input type="textarea" name="description" label="Deskripsi" rows="4" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="product-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-product">Simpan Item</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        // Init logic for products
    });
</script>
@endsection
