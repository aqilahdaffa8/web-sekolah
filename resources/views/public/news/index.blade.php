@extends('layouts.public')

@section('title', 'Berita & Agenda - SMK Nusantara')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Berita & Agenda</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Informasi terbaru seputar kegiatan sekolah, prestasi, dan pengumuman akademik.</p>
        </div>
    </div>

    <section class="section bg-white">
        <div class="container mx-auto px-4">
            
            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- Berita List -->
                <div class="w-full lg:w-2/3">
                    <div class="mb-8 border-b border-gray-200 pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Semua Berita</h2>
                    </div>

                    <div id="news-container" class="space-y-8">
                        <!-- Loaded via JS -->
                        @for($i=0; $i<4; $i++)
                            <div class="flex flex-col md:flex-row gap-6 bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="w-full md:w-1/3">
                                    <x-ui.skeleton class="w-full h-40 rounded-lg" />
                                </div>
                                <div class="w-full md:w-2/3">
                                    <x-ui.skeleton lines="3" />
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Sidebar (Kategori & Agenda) -->
                <div class="w-full lg:w-1/3 space-y-8">
                    
                    <!-- Kategori -->
                    <div class="card p-6 bg-gray-50 shadow-none">
                        <h3 class="font-bold text-lg text-gray-900 mb-4 pb-2 border-b border-gray-200">Kategori</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li><a href="#" class="hover:text-blue-600 flex justify-between"><span>Akademik</span> <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">12</span></a></li>
                            <li><a href="#" class="hover:text-blue-600 flex justify-between"><span>Prestasi</span> <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">8</span></a></li>
                            <li><a href="#" class="hover:text-blue-600 flex justify-between"><span>Kegiatan</span> <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">24</span></a></li>
                            <li><a href="#" class="hover:text-blue-600 flex justify-between"><span>Pengumuman</span> <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">5</span></a></li>
                        </ul>
                    </div>

                    <!-- Agenda -->
                    <div class="card p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-4 pb-2 border-b border-gray-200">Agenda Terdekat</h3>
                        <div class="space-y-4" id="agenda-sidebar-container">
                            <x-ui.skeleton lines="2" />
                            <hr class="border-gray-100">
                            <x-ui.skeleton lines="2" />
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
