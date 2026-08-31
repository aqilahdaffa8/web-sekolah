@extends('layouts.public')

@section('title', 'Profil & Jurusan - SMK Nusantara')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Profil & Jurusan</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Mengenal lebih dekat SMK Nusantara dan pilihan kompetensi keahlian unggulan kami.</p>
        </div>
    </div>

    <!-- Profil Singkat -->
    <section class="section bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2">
                    <img src="https://via.placeholder.com/600x400?text=Gedung+Sekolah" alt="Gedung SMK Nusantara" class="rounded-xl shadow-lg w-full">
                </div>
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Sejarah & Visi Misi</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Berdiri sejak tahun 1998, SMK Nusantara telah meluluskan ribuan alumni yang kini sukses berkarir di berbagai industri nasional dan multinasional. Kami berkomitmen untuk terus beradaptasi dengan perkembangan teknologi.
                    </p>
                    <div class="mt-6">
                        <h3 class="font-bold text-xl text-blue-700 mb-2">Visi</h3>
                        <p class="text-gray-600 italic border-l-4 border-blue-500 pl-4 py-2 bg-blue-50 rounded-r-lg">
                            "Menjadi lembaga pendidikan dan pelatihan vokasi yang unggul, berkarakter, dan berdaya saing global."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jurusan -->
    <section class="section bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Kompetensi Keahlian</h2>
                <p class="section-subtitle">Program studi pilihan yang dirancang sesuai kebutuhan industri saat ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- TKJ -->
                <div class="card p-6 border-t-4 border-blue-600">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center text-blue-700 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Teknik Komputer & Jaringan</h3>
                    <p class="text-gray-600 mb-4">Mempelajari infrastruktur jaringan, administrasi server, keamanan siber, dan troubleshooting perangkat keras.</p>
                </div>

                <!-- RPL -->
                <div class="card p-6 border-t-4 border-yellow-400">
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rekayasa Perangkat Lunak</h3>
                    <p class="text-gray-600 mb-4">Fokus pada pengembangan aplikasi web, mobile, desktop, serta perancangan sistem basis data modern.</p>
                </div>

                <!-- MM -->
                <div class="card p-6 border-t-4 border-purple-500">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Multimedia / DKV</h3>
                    <p class="text-gray-600 mb-4">Mendalami desain grafis, animasi, editing video, dan produksi konten digital kreatif yang siap pakai.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
