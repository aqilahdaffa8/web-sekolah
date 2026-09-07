@extends('layouts.public')
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
<<<<<<< HEAD
                        <button type="submit" id="btn-send" class="btn-primary inline-flex w-full items-center justify-center gap-2">
                            <span class="inline-flex items-center gap-2">
                                <span>Kirim Pesan</span>
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                </svg>
                            </span>
=======
                        <button type="submit" id="btn-send" class="btn btn-primary w-full">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            Kirim Pesan
>>>>>>> b4131732b82dedec8bb907a60fd0e1b683b999ff
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
                        @foreach([
                            ['label' => 'Facebook', 'path' => 'M14 8h3V4h-3c-3.314 0-6 2.686-6 6v2H5v4h3v8h4v-8h3l1-4h-4v-2c0-1.105.895-2 2-2z'],
                            ['label' => 'Instagram', 'path' => 'M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A4.5 4.5 0 1112 16.5 4.5 4.5 0 0112 7.5zm0 2A2.5 2.5 0 1014.5 12 2.5 2.5 0 0012 9.5zm5.25-3a1.25 1.25 0 110 2.5 1.25 1.25 0 010-2.5z'],
                            ['label' => 'Twitter', 'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.657l-5.214-6.817-5.963 6.817H1.684l7.73-8.835L1.254 2.25h6.826l4.713 6.231 5.451-6.231zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z'],
                            ['label' => 'YouTube', 'path' => 'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.376.55A3.016 3.016 0 00.502 6.186 31.247 31.247 0 000 12a31.247 31.247 0 00.502 5.814 3.016 3.016 0 002.122 2.136c1.871.55 9.376.55 9.376.55s7.505 0 9.376-.55a3.016 3.016 0 002.122-2.136A31.247 31.247 0 0024 12a31.247 31.247 0 00-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
                        ] as $social)
                        <a href="#" class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700 transition-colors hover:bg-brand-600 hover:text-white"
                           aria-label="{{ $social['label'] }}">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="{{ $social['path'] }}"/>
                            </svg>
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

