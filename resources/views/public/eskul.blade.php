@extends('layouts.public')

@section('title', 'Ekstrakurikuler - SMKN 1 KATAPANG')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Kegiatan Ekstrakurikuler</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Wadah pengembangan minat, bakat, dan karakter siswa di luar jam akademik.</p>
        </div>
    </div>

    <section class="section bg-white">
        <div class="container mx-auto px-4">
            <!-- Filter -->
            <div class="flex justify-center gap-4 mb-12">
                <button class="btn btn-primary px-6 rounded-full">Semua</button>
                <button class="btn btn-secondary px-6 rounded-full text-gray-600 hover:text-blue-600 hover:border-blue-600">Olahraga</button>
                <button class="btn btn-secondary px-6 rounded-full text-gray-600 hover:text-blue-600 hover:border-blue-600">Seni</button>
                <button class="btn btn-secondary px-6 rounded-full text-gray-600 hover:text-blue-600 hover:border-blue-600">Akademik</button>
            </div>

            <!-- List Eskul -->
            <div id="eskul-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <!-- Data loaded via JS -->
                @for($i=0; $i<6; $i++)
                    <div class="card overflow-hidden group">
                        <div class="aspect-video bg-gray-200 relative overflow-hidden">
                            <x-ui.skeleton class="w-full h-full rounded-none" />
                        </div>
                        <div class="p-6">
                            <x-ui.skeleton lines="2" />
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Pendaftaran Eskul (CTA) -->
    <section class="py-16 bg-blue-50 border-t border-blue-100">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Tertarik Mengembangkan Bakatmu?</h2>
            <p class="text-gray-600 mb-8 max-w-xl mx-auto">Siswa dapat mengikuti maksimal 2 kegiatan ekstrakurikuler. Pendaftaran dibuka setiap awal semester.</p>
            <button class="btn btn-lg btn-primary shadow-lg" onclick="document.getElementById('register-modal').classList.remove('hidden')">
                Daftar Ekstrakurikuler
            </button>
        </div>
    </section>

    <!-- Modal Daftar -->
    <x-ui.modal id="register-modal" title="Pendaftaran Ekstrakurikuler">
        <form id="register-eskul-form">
            <x-ui.form-input type="text" name="student_nis" label="NIS Siswa" required="true" placeholder="Masukkan Nomor Induk Siswa" />
            <x-ui.form-input type="text" name="student_name" label="Nama Lengkap" required="true" />
            <x-ui.form-input type="select" name="extracurricular_id" label="Pilih Ekstrakurikuler" required="true" :options="[
                '1' => 'Pramuka (Wajib)',
                '2' => 'Futsal',
                '3' => 'Paskibra',
                '4' => 'Rohis'
            ]" />
            
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" data-modal-close="register-modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-register">Kirim Pendaftaran</button>
            </div>
        </form>
    </x-ui.modal>
@endsection
