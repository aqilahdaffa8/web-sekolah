@extends('layouts.public')

@section('title', 'Hubin & BKK - SMK Nusantara')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Hubungan Industri & BKK</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Menjembatani siswa dan alumni dengan dunia usaha dan dunia industri (DUDI).</p>
        </div>
    </div>

    <!-- Mitra DUDI -->
    <section class="section bg-white">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-center mb-10">Mitra Industri Kami</h2>
            <div id="dudi-partners" class="flex flex-wrap justify-center gap-8 items-center opacity-70">
                <!-- Data loaded via JS -->
                <x-ui.skeleton class="w-32 h-16" />
                <x-ui.skeleton class="w-32 h-16" />
                <x-ui.skeleton class="w-32 h-16" />
                <x-ui.skeleton class="w-32 h-16" />
            </div>
        </div>
    </section>

    <!-- Lowongan Kerja (BKK) -->
    <section class="section bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="section-title mb-2">Lowongan Kerja (BKK)</h2>
                    <p class="text-gray-600">Peluang karir eksklusif untuk alumni SMK Nusantara.</p>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-sm btn-primary">Semua</button>
                    <button class="btn btn-sm btn-secondary">Buka</button>
                </div>
            </div>

            <div id="job-vacancies" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                 <!-- Data loaded via JS -->
                 @for($i=0; $i<6; $i++)
                    <div class="card p-6">
                        <x-ui.skeleton lines="4" />
                    </div>
                 @endfor
            </div>
        </div>
    </section>
@endsection
