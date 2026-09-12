@extends('layouts.public')
@section('title', 'Ekstrakurikuler')
@section('meta_description', 'Kegiatan ekstrakurikuler SMK — daftar anggota, jadwal latihan, dan galeri prestasi.')

@section('content')

<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Ekstrakurikuler</h1>
        <p class="text-white/70 max-w-xl mx-auto">Temukan passion Anda, kembangkan bakat, dan raih prestasi bersama.</p>
    </div>
</div>

{{-- Eskul Grid --}}
<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="text-center mb-12">
            <h2 class="section-title">Pilih Ekstrakurikuler</h2>
            <p id="eskul-summary" class="section-subtitle mx-auto text-center">Memuat daftar ekstrakurikuler...</p>
        </div>

        <div id="eskul-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i=0;$i<6;$i++)
            <div class="card p-6">
                <div class="skeleton w-14 h-14 rounded-xl mb-4"></div>
                <div class="skeleton h-5 w-2/3 rounded mb-2"></div>
                <div class="skeleton h-4 w-full rounded"></div>
                <div class="skeleton h-4 w-3/4 rounded mt-1"></div>
                <div class="flex gap-2 mt-4">
                    <div class="skeleton h-6 w-20 rounded-full"></div>
                    <div class="skeleton h-6 w-16 rounded-full"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- Achievements Gallery --}}
<section class="section-padding bg-white">
    <div class="section-container">
        <div class="text-center mb-12">
            <p class="text-gold-600 font-semibold text-sm uppercase tracking-wider mb-2">Kebanggaan Kami</p>
            <h2 class="section-title">Galeri Prestasi</h2>
        </div>

        {{-- Filter --}}
        <div id="achievement-filters" class="flex gap-2 mb-8 flex-wrap">
            <button class="btn btn-sm btn-primary ach-btn active" data-year="all">Semua</button>
        </div>

        <div id="achievements-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i=0;$i<6;$i++)
            <div class="card">
                <div class="skeleton h-48 w-full"></div>
                <div class="p-4 space-y-2">
                    <div class="skeleton h-4 w-3/4 rounded"></div>
                    <div class="skeleton h-3 w-1/2 rounded"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- Registration Modal --}}
<div id="register-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-gray-900">Daftar Ekstrakurikuler</h3>
            <button data-modal-close class="btn-icon text-gray-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body space-y-4">
            <div class="bg-brand-50 border border-brand-100 rounded-xl p-4 flex gap-3 items-center">
                <div id="reg-eskul-icon" class="text-3xl">🏆</div>
                <div>
                    <p id="reg-eskul-name" class="font-bold text-brand-900"></p>
                    <p id="reg-eskul-schedule" class="text-sm text-brand-600"></p>
                </div>
            </div>
            <input type="hidden" id="reg-eskul-id">
            <div>
                <label class="form-label" for="reg-name">Nama Lengkap</label>
                <input type="text" id="reg-name" class="form-input bg-brand-50/70 text-brand-900 font-semibold" readonly tabindex="-1">
            </div>
            <div>
                <label class="form-label" for="reg-nis">NIS</label>
                <input type="text" id="reg-nis" class="form-input bg-brand-50/70 text-brand-900 font-semibold" readonly tabindex="-1">
            </div>
            <div>
                <label class="form-label" for="reg-reason">Motivasi / Catatan</label>
                <textarea id="reg-reason" class="form-input h-20 resize-none" placeholder="Mengapa Anda tertarik bergabung?"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn btn-ghost">Batal</button>
            <button id="btn-register" onclick="submitRegistration()" class="btn btn-primary">Kirim Pendaftaran</button>
        </div>
    </div>
</div>

{{-- Guest / non-student notice --}}
<div id="login-required-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-brand-900">Pendaftaran khusus siswa aktif</h3>
            <button data-modal-close class="btn-icon text-brand-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body space-y-3">
            <p id="login-required-copy" class="text-sm text-brand-700">Pengunjung umum hanya dapat melihat katalog eskul dan prestasi. Pendaftaran hanya untuk siswa dengan NIS aktif.</p>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn btn-ghost">Tutup</button>
            <a id="login-required-cta" href="{{ url('/login?redirect=/ekstrakurikuler') }}" class="btn btn-primary">Masuk sebagai Siswa</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
const publicApi = window.publicApi;
const toast = window.toast;
const { storageUrl, setButtonLoading } = window.utils;

const ESKUL_ICONS = {
    robot: '🤖',
    iot: '🤖',
    pramuka: '⛺',
    futsal: '⚽',
    sepak: '⚽',
    pmr: '🩺',
    palang: '🩺',
    english: '🌐',
    debat: '🎙️',
    tari: '💃',
    suara: '🎤',
    musik: '🎵',
    desain: '🎨',
    grafis: '🎨',
    multimedia: '📷',
    paskibra: '🇮🇩',
    rohis: '📖',
    tahfidz: '📖',
    default: '⭐',
};

function getEskulIcon(name = '') {
    const normalizedName = name.toLowerCase();
    const matchedKeyword = Object.keys(ESKUL_ICONS).find(keyword =>
        keyword !== 'default' && normalizedName.includes(keyword)
    );

    return ESKUL_ICONS[matchedKeyword || 'default'];
}

let allAchievements = [];

async function loadEskul() {
    try {
        const [eskulData, achData] = await Promise.all([
            publicApi.extracurriculars(),
            publicApi.achievements(),
        ]);

        renderEskul(eskulData.data || eskulData || []);
        allAchievements = achData.data || achData || [];
        renderAchievements(allAchievements);
        buildAchievementFilters(allAchievements);
    } catch (err) {
        console.error(err);
        document.getElementById('eskul-summary').textContent = 'Gagal memuat daftar ekstrakurikuler.';
    }
}

function registrationButton(eskul) {
    const payload = JSON.stringify(eskul).replace(/"/g, '&quot;');
    if (eskul.status === 'closed') {
        return `<button class="btn btn-primary w-full btn-sm" disabled>Pendaftaran Ditutup</button>`;
    }

    if (window.auth?.isActiveStudent?.()) {
        return `<button onclick="openRegister(${payload})" class="btn btn-primary w-full btn-sm">Daftar Sekarang</button>`;
    }

    if (window.auth?.isLoggedIn?.()) {
        return `<button onclick="openLoginRequired('staff')" class="btn btn-secondary w-full btn-sm">Khusus Siswa Aktif</button>`;
    }

    return `<button onclick="openLoginRequired('guest')" class="btn btn-primary w-full btn-sm">Login untuk Mendaftar</button>`;
}

function renderEskul(eskul) {
    document.getElementById('eskul-summary').textContent = eskul.length
        ? `${eskul.length} pilihan kegiatan sesuai minat dan bakat Anda.`
        : 'Belum ada data ekstrakurikuler.';

    document.getElementById('eskul-grid').innerHTML = eskul.map(e => `
        <div class="card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                    ${getEskulIcon(e.name)}
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-brand-900 text-lg">${e.name}</h3>
                    <p class="text-sm text-brand-600">${e.coach?.name || e.advisor || 'Pembina TBA'}</p>
                </div>
            </div>
            <p class="text-sm text-brand-700 mb-4 line-clamp-2">${e.description || ''}</p>
            <div class="flex flex-wrap gap-2 mb-5">
                ${e.schedule ? `<span class="badge-info">📅 ${e.schedule}</span>` : ''}
                ${e.location ? `<span class="badge-brand">📍 ${e.location}</span>` : ''}
                ${e.member_count !== undefined ? `<span class="badge-brand">👥 ${e.member_count} anggota</span>` : ''}
            </div>
            ${registrationButton(e)}
        </div>
    `).join('') || '<p class="col-span-3 text-center text-brand-600 py-12">Belum ada data ekstrakurikuler.</p>';
}

function renderAchievements(achievements) {
    document.getElementById('achievements-grid').innerHTML = achievements.map(a => `
        <div class="card group overflow-hidden">
            ${a.image ? `
            <div class="h-48 overflow-hidden">
                <img src="${storageUrl(a.image)}" alt="${a.title}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                     loading="lazy">
            </div>` : `
            <div class="h-48 bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center text-6xl">🏆</div>`}
            <div class="p-4">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="font-bold text-brand-900 line-clamp-2">${a.title}</h3>
                    <span class="badge-warning flex-shrink-0">${a.level || 'Nasional'}</span>
                </div>
                <p class="text-sm text-brand-600">${a.extracurricular?.name || ''} · ${a.year || new Date().getFullYear()}</p>
            </div>
        </div>
    `).join('') || '<p class="col-span-3 text-center text-brand-600 py-12">Belum ada data prestasi.</p>';
}

function buildAchievementFilters(achievements) {
    const years = [...new Set(achievements.map(a => a.year).filter(Boolean))].sort((a,b)=>b-a);
    const container = document.getElementById('achievement-filters');
    const extras = years.map(y =>
        `<button class="btn btn-sm btn-secondary ach-btn" data-year="${y}" onclick="filterAch('${y}')">${y}</button>`
    ).join('');
    container.innerHTML = `<button class="btn btn-sm btn-primary ach-btn active" data-year="all" onclick="filterAch('all')">Semua</button>${extras}`;
}

window.filterAch = (year) => {
    document.querySelectorAll('.ach-btn').forEach(btn => {
        btn.className = `btn btn-sm ${btn.dataset.year == year ? 'btn-primary' : 'btn-secondary'} ach-btn`;
    });
    const filtered = year === 'all' ? allAchievements : allAchievements.filter(a => String(a.year) === String(year));
    renderAchievements(filtered);
};

window.openLoginRequired = (audience = 'guest') => {
    const copy = document.getElementById('login-required-copy');
    const cta = document.getElementById('login-required-cta');
    if (audience === 'staff') {
        copy.textContent = 'Akun staf tidak dapat mendaftar eskul. Masuk dengan akun Siswa yang terhubung ke NIS aktif.';
        cta.textContent = 'Ganti akun';
    } else {
        copy.textContent = 'Pengunjung umum hanya dapat melihat katalog eskul dan prestasi. Pendaftaran hanya untuk siswa dengan NIS aktif.';
        cta.textContent = 'Masuk sebagai Siswa';
    }
    openModal(document.getElementById('login-required-modal'));
};

window.openRegister = (eskul) => {
    if (!window.auth?.isActiveStudent?.()) {
        window.openLoginRequired(window.auth?.isLoggedIn?.() ? 'staff' : 'guest');
        return;
    }

    if (typeof eskul === 'string') eskul = JSON.parse(eskul);
    const student = window.auth.getStudent();
    document.getElementById('reg-eskul-id').value = eskul.id;
    document.getElementById('reg-eskul-name').textContent = eskul.name;
    document.getElementById('reg-eskul-schedule').textContent = eskul.schedule || '';
    document.getElementById('reg-name').value = student.name;
    document.getElementById('reg-nis').value = student.nis;
    document.getElementById('reg-reason').value = '';
    openModal(document.getElementById('register-modal'));
};

window.submitRegistration = async () => {
    if (!window.auth?.isActiveStudent?.()) {
        toast.warning('Pendaftaran hanya untuk siswa aktif.');
        return;
    }

    const btn = document.getElementById('btn-register');
    const payload = {
        extracurricular_id: document.getElementById('reg-eskul-id').value,
        notes: document.getElementById('reg-reason').value,
    };

    try {
        setButtonLoading(btn, true);
        await publicApi.registerExtracurricular(payload);
        closeModal(document.getElementById('register-modal'));
        toast.success('Pendaftaran berhasil dan menunggu validasi admin.');
    } catch (err) {
        toast.apiError(err);
    } finally {
        setButtonLoading(btn, false, 'Kirim Pendaftaran');
    }
};

loadEskul();
});
</script>
@endpush
