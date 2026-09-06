@extends('layouts.public')
<<<<<<< HEAD
@section('title', 'Kontak')
@section('meta_description', 'Hubungi SMKN 1 Katapang — alamat, telepon, dan formulir kontak online.')

@section('content')

<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Hubungi Kami</h1>
        <p class="text-white/70 max-w-xl mx-auto">Kami siap membantu. Kirim pesan atau kunjungi kami langsung.</p>
    </div>
</div>

<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

            {{-- Contact Form --}}
            <div class="lg:col-span-3">
                <div class="card p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                    <form id="contact-form" class="space-y-5" novalidate>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="c-name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="c-name" name="name" class="form-input" placeholder="Nama Anda" required>
                            </div>
                            <div>
                                <label class="form-label" for="c-email">Email <span class="text-danger">*</span></label>
                                <input type="email" id="c-email" name="email" class="form-input" placeholder="email@contoh.com" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="c-phone">No. HP</label>
                            <input type="tel" id="c-phone" name="phone" class="form-input" placeholder="08XXXXXXXXXX">
                        </div>
                        <div>
                            <label class="form-label" for="c-subject">Subjek <span class="text-danger">*</span></label>
                            <select id="c-subject" name="subject" class="form-select" required>
                                <option value="">Pilih subjek...</option>
                                <option>Informasi Pendaftaran</option>
                                <option>Kerjasama / PKL</option>
                                <option>Produk TeFA</option>
                                <option>Ekstrakurikuler</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="c-message">Pesan <span class="text-danger">*</span></label>
                            <textarea id="c-message" name="message" class="form-input h-32 resize-none" placeholder="Tulis pesan Anda..." required></textarea>
                        </div>
                        <button type="submit" id="btn-send" class="btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="lg:col-span-2 space-y-6">

                @foreach([
                    ['Alamat', '📍', 'Jl. Ceuri Terusan Kopo No. 1, Katapang, Kab. Bandung, Jawa Barat 40921', '#'],
                    ['Telepon', '📞', '(022) 589-3777', 'tel:+62225893777'],
                    ['Email', '📧', 'info@smkn1katapang.sch.id', 'mailto:info@smkn1katapang.sch.id'],
                    ['Jam Operasional', '🕐', 'Senin – Jumat: 07.00 – 16.00 WIB', '#'],
                ] as [$label, $icon, $value, $href])
                <div class="card p-5 flex gap-4 items-start">
                    <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">{{ $icon }}</div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $label }}</p>
                        <a href="{{ $href }}" class="text-gray-800 hover:text-brand-700 transition-colors font-medium">{{ $value }}</a>
                    </div>
                </div>
                @endforeach

                {{-- Social Media --}}
                <div class="card p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Ikuti Kami</p>
                    <div class="flex gap-3">
                        @foreach(['Facebook', 'Instagram', 'Twitter', 'YouTube'] as $social)
                        <a href="#" class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center hover:bg-brand-600 hover:text-white transition-colors text-brand-700 text-sm font-bold"
                           aria-label="{{ $social }}">
                            {{ substr($social, 0, 2) }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script type="module">
import { api }    from '/resources/js/api.js';
import { toast }  from '/resources/js/toast.js';
import { setButtonLoading } from '/resources/js/utils.js';

document.getElementById('contact-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btn-send');
    const payload = {
        name:    document.getElementById('c-name').value,
        email:   document.getElementById('c-email').value,
        phone:   document.getElementById('c-phone').value,
        subject: document.getElementById('c-subject').value,
        message: document.getElementById('c-message').value,
    };

    if (!payload.name || !payload.email || !payload.subject || !payload.message) {
        toast.warning('Harap lengkapi semua field wajib.');
        return;
    }

    try {
        setButtonLoading(btn, true, 'Mengirim...');
        await api.post('/public/contact', payload);
        toast.success('Pesan berhasil terkirim! Kami akan membalas secepatnya.');
        e.target.reset();
    } catch (err) {
        toast.apiError(err);
    } finally {
        setButtonLoading(btn, false, 'Kirim Pesan');
    }
});
</script>
@endpush
=======

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
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
