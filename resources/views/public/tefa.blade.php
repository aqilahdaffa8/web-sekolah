@extends('layouts.public')

@section('title', 'Katalog TeFA - SMKN 1 KATAPANG')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Katalog Teaching Factory (TeFA)</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Jelajahi produk karya inovatif siswa dan jasa profesional dari berbagai program keahlian kami.</p>
        </div>
    </div>

    <section class="section bg-gray-50">
        <div class="container mx-auto px-4">
            <!-- Filter & Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div class="w-full md:w-1/3">
                    <x-ui.form-input type="text" name="search" placeholder="Cari produk atau jasa..." class="w-full" />
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-primary">Semua</button>
                    <button class="btn btn-secondary">Produk</button>
                    <button class="btn btn-secondary">Jasa</button>
                </div>
            </div>

            <!-- Product Grid -->
            <div id="tefa-products" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Data will be loaded via JS -->
                @for($i=0; $i<8; $i++)
                    <x-ui.skeleton type="card" />
                @endfor
            </div>
            
            <div class="text-center mt-12 hidden" id="tefa-load-more">
                <button class="btn btn-secondary btn-lg">Muat Lebih Banyak</button>
            </div>
        </div>
    </section>

    <!-- Modal Order -->
    <x-ui.modal id="order-modal" title="Pesan Produk/Jasa">
        <form id="order-form">
            <input type="hidden" name="tefa_product_id" id="order-product-id">
            
            <div class="mb-4">
                <h4 class="font-bold text-lg text-gray-900" id="order-product-name">Nama Produk</h4>
                <p class="text-blue-700 font-semibold" id="order-product-price">Rp 0</p>
            </div>
            
            <x-ui.form-input type="text" name="customer_name" label="Nama Lengkap" required="true" placeholder="Masukkan nama Anda" />
            <x-ui.form-input type="text" name="customer_phone" label="No. WhatsApp" required="true" placeholder="08xxxxxxxxx" />
            <x-ui.form-input type="number" name="quantity" label="Jumlah Pesanan" required="true" value="1" />
            <x-ui.form-input type="textarea" name="notes" label="Catatan Tambahan" placeholder="Opsional, berikan catatan khusus untuk pesanan Anda" />
            
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" data-modal-close="order-modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-order">Kirim Pesanan</button>
            </div>
        </form>
    </x-ui.modal>
@endsection
