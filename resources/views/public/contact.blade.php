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
                        <button type="submit" id="btn-send" class="btn btn-primary w-full">
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

