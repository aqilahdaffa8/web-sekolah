@extends('layouts.public')
@section('title', 'Ekstrakurikuler')
@section('meta_description', 'Kegiatan ekstrakurikuler SMK — daftar anggota, jadwal latihan, dan galeri prestasi.')

@section('content')

<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <span class="badge-brand mb-4 inline-block">Pengembangan Diri</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Ekstrakurikuler</h1>
        <p class="text-white/70 max-w-xl mx-auto">Temukan passion Anda, kembangkan bakat, dan raih prestasi bersama.</p>
    </div>
</div>

{{-- Eskul Grid --}}
<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="text-center mb-12">
            <h2 class="section-title">Pilih Ekstrakurikuler</h2>
            <p class="section-subtitle mx-auto text-center">Lebih dari 30 pilihan kegiatan sesuai minat dan bakat Anda.</p>
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
                <label class="form-label" for="reg-name">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="reg-name" class="form-input" placeholder="Nama sesuai rapor" required>
            </div>
            <div>
                <label class="form-label" for="reg-class">Kelas <span class="text-danger">*</span></label>
                <input type="text" id="reg-class" class="form-input" placeholder="Contoh: X TKJ 1" required>
            </div>
            <div>
                <label class="form-label" for="reg-phone">No. HP <span class="text-danger">*</span></label>
                <input type="tel" id="reg-phone" class="form-input" placeholder="08XXXXXXXXXX" required>
            </div>
            <div>
                <label class="form-label" for="reg-reason">Motivasi Bergabung</label>
                <textarea id="reg-reason" class="form-input h-20 resize-none" placeholder="Mengapa Anda tertarik bergabung?"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button data-modal-close class="btn-ghost">Batal</button>
            <button id="btn-register" onclick="submitRegistration()" class="btn-primary">Daftar Sekarang</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
import { publicApi } from '/resources/js/api.js';
import { api }       from '/resources/js/api.js';
import { storageUrl, formatDate, setButtonLoading } from '/resources/js/utils.js';
import { toast }     from '/resources/js/toast.js';

const ESKUL_ICONS = ['⚽','🏀','🎭','🎨','💻','🎵','📷','🏊','🥊','📚','♟️','🏐','🤸','🎤','🎬'];

let allAchievements = [];
let currentEskulId  = null;

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
    }
}

function renderEskul(eskul) {
    document.getElementById('eskul-grid').innerHTML = eskul.map((e, i) => `
        <div class="card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                    ${ESKUL_ICONS[i % ESKUL_ICONS.length]}
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-lg">${e.name}</h3>
                    <p class="text-sm text-gray-500">${e.coach || e.advisor || 'Pembina TBA'}</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">${e.description || ''}</p>
            <div class="flex flex-wrap gap-2 mb-5">
                ${e.schedule ? `<span class="badge-info">📅 ${e.schedule}</span>` : ''}
                ${e.location ? `<span class="badge-gray">📍 ${e.location}</span>` : ''}
                ${e.member_count !== undefined ? `<span class="badge-brand">👥 ${e.member_count} anggota</span>` : ''}
            </div>
            <button onclick="openRegister(${JSON.stringify(e).replace(/"/g, '&quot;')})"
                    class="btn-primary w-full btn-sm"
                    ${e.status === 'closed' ? 'disabled' : ''}>
                ${e.status === 'closed' ? 'Pendaftaran Ditutup' : 'Daftar Sekarang'}
            </button>
        </div>
    `).join('') || '<p class="col-span-3 text-center text-gray-500 py-12">Belum ada data ekstrakurikuler.</p>';
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
                    <h3 class="font-bold text-gray-900 line-clamp-2">${a.title}</h3>
                    <span class="badge-warning flex-shrink-0">${a.level || 'Nasional'}</span>
                </div>
                <p class="text-sm text-gray-500">${a.extracurricular?.name || ''} · ${a.year || new Date().getFullYear()}</p>
            </div>
        </div>
    `).join('') || '<p class="col-span-3 text-center text-gray-500 py-12">Belum ada data prestasi.</p>';
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

window.openRegister = (eskul) => {
    if (typeof eskul === 'string') eskul = JSON.parse(eskul);
    currentEskulId = eskul.id;
    document.getElementById('reg-eskul-id').value = eskul.id;
    document.getElementById('reg-eskul-name').textContent = eskul.name;
    document.getElementById('reg-eskul-schedule').textContent = eskul.schedule || '';
    openModal(document.getElementById('register-modal'));
};

window.submitRegistration = async () => {
    const btn = document.getElementById('btn-register');
    const payload = {
        extracurricular_id: document.getElementById('reg-eskul-id').value,
        name:               document.getElementById('reg-name').value,
        class:              document.getElementById('reg-class').value,
        phone:              document.getElementById('reg-phone').value,
        motivation:         document.getElementById('reg-reason').value,
    };

    if (!payload.name || !payload.class || !payload.phone) {
        toast.warning('Harap isi semua field yang wajib.');
        return;
    }

    try {
        setButtonLoading(btn, true);
        // Use public registration endpoint (mapped to EskulPublicController if exists, otherwise custom)
        await api.post('/public/extracurriculars/register', payload);
        closeModal(document.getElementById('register-modal'));
        toast.success('Pendaftaran berhasil! Tim kami akan menghubungi Anda.');
    } catch (err) {
        toast.apiError(err);
    } finally {
        setButtonLoading(btn, false, 'Daftar Sekarang');
    }
};

loadEskul();
</script>
@endpush

