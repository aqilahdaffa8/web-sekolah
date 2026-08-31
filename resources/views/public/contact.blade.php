@extends('layouts.public')

@section('title', 'Kontak Kami - SMKN 1 KATAPANG')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Kontak Kami</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Punya pertanyaan atau ingin tahu lebih lanjut? Jangan ragu untuk menghubungi kami.</p>
        </div>
    </div>

    <section class="section bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Info Kontak -->
                <div class="w-full lg:w-1/3">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Kontak</h2>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Alamat Sekolah</h3>
                                <p class="text-gray-600 mt-1">Jl. Pendidikan No. 123, Kota Cerdas, Provinsi Maju 12345</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Telepon & WhatsApp</h3>
                                <p class="text-gray-600 mt-1">(021) 555-1234<br>0812-3456-7890 (WA Only)</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Email</h3>
                                <p class="text-gray-600 mt-1">info@smknusantara.sch.id<br>hubin@smknusantara.sch.id</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Kontak -->
                <div class="w-full lg:w-2/3">
                    <div class="card p-8 border-t-4 border-blue-700 shadow-xl">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                        <form id="contact-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-ui.form-input type="text" name="name" label="Nama Lengkap" required="true" />
                                <x-ui.form-input type="email" name="email" label="Email" required="true" />
                            </div>
                            
                            <x-ui.form-input type="text" name="subject" label="Subjek Pesan" required="true" />
                            <x-ui.form-input type="textarea" name="message" label="Isi Pesan" rows="5" required="true" />
                            
                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary w-full md:w-auto px-8" id="btn-submit-contact">
                                    Kirim Pesan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Maps -->
    <div class="w-full h-96 bg-gray-200">
        <!-- Embed Google Maps iframe here -->
        <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-500 font-medium">
            [Peta Lokasi Google Maps]
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('contact-form');
            if(form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const btn = document.getElementById('btn-submit-contact');
                    btn.disabled = true;
                    btn.innerHTML = 'Mengirim...';
                    
                    // Simulate API Call
                    try {
                        const formData = {
                            name: form.name.value,
                            email: form.email.value,
                            subject: form.subject.value,
                            message: form.message.value
                        };
                        
                        await window.api.post('/public/contact', formData);
                        
                        window.utils.showToast('Pesan Anda berhasil dikirim! Kami akan membalas secepatnya.', 'success');
                        form.reset();
                    } catch (error) {
                        window.utils.showToast('Gagal mengirim pesan. Silakan coba lagi.', 'error');
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = 'Kirim Pesan Sekarang';
                    }
                });
            }
        });
    </script>
@endsection
