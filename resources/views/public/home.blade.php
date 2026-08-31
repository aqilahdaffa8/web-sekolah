@extends('layouts.public')

@section('title', 'Beranda - SMK Nusantara')

@section('content')
    <!-- Hero Slider -->
    <x-public.hero-slider />

    <!-- Fitur & Statistik -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                    <h3 class="text-3xl font-bold text-blue-700" id="stat-students">0</h3>
                    <p class="text-gray-600 mt-2 font-medium">Siswa Aktif</p>
                </div>
                <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                    <h3 class="text-3xl font-bold text-blue-700" id="stat-teachers">0</h3>
                    <p class="text-gray-600 mt-2 font-medium">Guru Profesional</p>
                </div>
                <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                    <h3 class="text-3xl font-bold text-blue-700" id="stat-programs">0</h3>
                    <p class="text-gray-600 mt-2 font-medium">Kompetensi Keahlian</p>
                </div>
                <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                    <h3 class="text-3xl font-bold text-blue-700" id="stat-alumni">0</h3>
                    <p class="text-gray-600 mt-2 font-medium">Alumni Sukses</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru & Agenda -->
    <section class="section bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Berita -->
                <div class="w-full md:w-2/3">
                    <div class="flex justify-between items-end mb-6">
                        <h2 class="section-title mb-0">Berita Terbaru</h2>
                        <a href="/berita" class="text-blue-600 font-medium hover:underline">Lihat Semua</a>
                    </div>
                    
                    <div id="latest-news" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.skeleton type="card" />
                        <x-ui.skeleton type="card" />
                    </div>
                </div>

                <!-- Agenda -->
                <div class="w-full md:w-1/3">
                    <h2 class="section-title mb-6">Agenda Terdekat</h2>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-0 overflow-hidden">
                        <ul id="upcoming-events" class="divide-y divide-gray-100">
                            <li class="p-4"><x-ui.skeleton lines="2" /></li>
                            <li class="p-4"><x-ui.skeleton lines="2" /></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Katalog TeFA Unggulan -->
    <section class="section bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="section-title">Produk TeFA Unggulan</h2>
                <p class="section-subtitle mb-0">Hasil karya terbaik dari Teaching Factory siswa kami</p>
            </div>

            <div id="featured-products" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <x-ui.skeleton type="card" />
                <x-ui.skeleton type="card" />
                <x-ui.skeleton type="card" />
                <x-ui.skeleton type="card" />
            </div>
            
            <div class="text-center mt-10">
                <a href="/tefa" class="btn btn-primary btn-lg">Lihat Katalog Lengkap</a>
            </div>
        </div>
    </section>
@endsection

<!-- Ini akan dieksekusi oleh Vite/app.js jika kita meletakkan script khusus halaman -->
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        // Panggil script khusus home.js yang nanti dibuat
        if (window.initHomePage) window.initHomePage();
    });
</script>
