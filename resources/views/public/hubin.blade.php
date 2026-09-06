@extends('layouts.public')

<<<<<<< HEAD
@section('title', 'Hubungan Industri & BKK')
@section('meta_description', 'Mitra DUDI, lowongan kerja alumni, dan informasi PKL/Magang SMKN 1 Katapang.')

@section('content')

{{-- Hero --}}
<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <span class="badge-brand mb-4 inline-block">Hubungan Industri</span>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Hubin & BKK</h1>
        <p class="text-white/70 max-w-xl mx-auto">
            Jembatan antara sekolah dan dunia industri untuk PKL, karier, dan kemitraan.
        </p>
    </div>
</div>

{{-- Stats bar --}}
<div class="bg-brand-700 py-8">
    <div class="section-container">
        <div class="grid grid-cols-3 gap-4 text-center text-white">
            <div>
                <p class="text-2xl font-black" id="stat-dudi">—</p>
                <p class="text-sm text-white/70">Mitra DUDI</p>
            </div>
            <div>
                <p class="text-2xl font-black" id="stat-jobs">—</p>
                <p class="text-sm text-white/70">Lowongan Aktif</p>
            </div>
            <div>
                <p class="text-2xl font-black" id="stat-alumni">—</p>
                <p class="text-sm text-white/70">Alumni Tersalurkan</p>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MITRA DUDI
══════════════════════════════════════ --}}
<section class="section-padding bg-white">
    <div class="section-container">
        <div class="text-center mb-12">
            <p class="text-gold-600 font-semibold text-sm uppercase tracking-wider mb-2">Rekanan</p>
            <h2 class="section-title">Mitra DUDI</h2>
            <p class="section-subtitle mx-auto text-center">
                Perusahaan dan instansi mitra yang terpercaya untuk PKL dan serapan lulusan.
            </p>
        </div>

        <div id="dudi-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @for($i=0;$i<10;$i++)
            <div class="card p-5 text-center">
                <div class="skeleton w-16 h-16 rounded-xl mx-auto mb-3"></div>
                <div class="skeleton h-3 w-3/4 mx-auto rounded"></div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     JOB VACANCIES
══════════════════════════════════════ --}}
<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
            <div>
                <p class="text-gold-600 font-semibold text-sm uppercase tracking-wider mb-2">Karier</p>
                <h2 class="section-title mb-0">Lowongan Kerja Alumni</h2>
            </div>
            {{-- Filter --}}
            <div class="flex gap-2">
                <button id="filter-all"    class="btn btn-sm btn-primary" onclick="filterJobs('all')">Semua</button>
                <button id="filter-open"   class="btn btn-sm btn-secondary" onclick="filterJobs('open')">Buka</button>
                <button id="filter-closed" class="btn btn-sm btn-secondary" onclick="filterJobs('closed')">Tutup</button>
            </div>
        </div>

        {{-- Search --}}
        <div class="mb-6">
            <div class="relative max-w-md">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="search" id="job-search" placeholder="Cari lowongan..."
                       class="form-input pl-10">
            </div>
        </div>

        <div id="jobs-list" class="space-y-4">
            @for($i=0;$i<4;$i++)
            <div class="card p-5 flex gap-4">
                <div class="skeleton w-14 h-14 rounded-xl flex-shrink-0"></div>
                <div class="flex-1 space-y-2">
                    <div class="skeleton h-5 w-1/2 rounded"></div>
                    <div class="skeleton h-4 w-1/3 rounded"></div>
                    <div class="skeleton h-3 w-full rounded"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     PKL / MAGANG FLOW
══════════════════════════════════════ --}}
<section class="section-padding bg-white">
    <div class="section-container">
        <div class="text-center mb-12">
            <p class="text-gold-600 font-semibold text-sm uppercase tracking-wider mb-2">Prakerin</p>
            <h2 class="section-title">Alur PKL & Magang</h2>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="relative">
                {{-- Connector line --}}
                <div class="absolute left-8 top-8 bottom-8 w-0.5 bg-brand-100 hidden md:block"></div>

                @foreach([
                    ['Pendaftaran & Orientasi',  'Siswa mendaftar PKL, mengikuti orientasi persiapan industri dan pembekalan soft skill.',    'bg-brand-100 text-brand-700'],
                    ['Penempatan & Koordinasi',  'Tim Hubin mencocokkan kompetensi siswa dengan kebutuhan mitra DUDI dan menerbitkan surat pengantar.', 'bg-gold-100 text-gold-700'],
                    ['Pelaksanaan PKL',          'Siswa mengikuti kegiatan PKL selama 3–6 bulan di perusahaan mitra dengan bimbingan pembimbing industri.', 'bg-success-light text-success-dark'],
                    ['Pelaporan & Evaluasi',     'Siswa menyusun laporan PKL, presentasi hasil, dan penilaian bersama pembimbing sekolah dan industri.', 'bg-info-light text-info-dark'],
                    ['Sertifikasi',              'Siswa mendapatkan sertifikat kompetensi dari mitra industri dan Lembaga Sertifikasi Profesi (LSP).', 'bg-purple-100 text-purple-700'],
                ] as $i => $step)
                <div class="flex gap-6 mb-8 relative items-start">
                    <div class="step-circle {{ $step[2] }} w-16 h-16 flex-shrink-0 z-10 ring-4 ring-white">
                        {{ $i + 1 }}
                    </div>
                    <div class="card flex-1 p-5 mt-3">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $step[0] }}</h3>
                        <p class="text-gray-500 text-sm">{{ $step[1] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('contact') }}" class="btn-primary btn-lg">
                    Hubungi Tim Hubin
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script type="module">
import { publicApi } from '/resources/js/api.js';
import { storageUrl, formatDate, animateCounter, debounce } from '/resources/js/utils.js';

let allJobs = [];
let currentFilter = 'all';

async function loadHubin() {
    try {
        const data = await publicApi.hubin();
        renderDudi(data.partners || []);
        allJobs = data.job_vacancies || [];
        renderJobs(allJobs);

        // Stats
        animateCounter(document.getElementById('stat-dudi'), data.stats?.partners ?? data.partners?.length ?? 0);
        animateCounter(document.getElementById('stat-jobs'), data.stats?.open_jobs ?? allJobs.filter(j => j.status === 'open').length);
        animateCounter(document.getElementById('stat-alumni'), data.stats?.alumni_placed ?? 0);
    } catch (err) {
        console.error('Hubin load failed', err);
    }
}

function renderDudi(partners) {
    document.getElementById('dudi-grid').innerHTML = partners.map(p => `
        <div class="card p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
            <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                ${p.logo
                    ? `<img src="${storageUrl(p.logo)}" alt="${p.name}" class="w-full h-full object-contain">`
                    : `<div class="w-full h-full bg-brand-100 rounded-xl flex items-center justify-center text-2xl font-black text-brand-700">${p.name[0]}</div>`}
            </div>
            <p class="text-xs font-semibold text-gray-700 line-clamp-2">${p.name}</p>
            ${p.industry ? `<p class="text-xs text-gray-400 mt-1">${p.industry}</p>` : ''}
        </div>
    `).join('') || '<p class="col-span-5 text-center text-gray-500 py-8">Belum ada data mitra.</p>';
}

function renderJobs(jobs) {
    const filtered = jobs.filter(j => currentFilter === 'all' || j.status === currentFilter);
    document.getElementById('jobs-list').innerHTML = filtered.length ? filtered.map(j => `
        <div class="card p-5 flex flex-col sm:flex-row gap-4 items-start hover:shadow-md transition-shadow job-item"
             data-status="${j.status}">
            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 text-2xl">
                🏢
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="font-bold text-gray-900">${j.title}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">${j.company || j.dudi_partner?.name || 'Perusahaan'} · ${j.location || 'Indonesia'}</p>
                    </div>
                    <span class="${j.status === 'open' ? 'badge-success' : 'badge-danger'}">${j.status === 'open' ? 'Buka' : 'Tutup'}</span>
                </div>
                ${j.description ? `<p class="text-sm text-gray-600 mt-2 line-clamp-2">${j.description}</p>` : ''}
                <div class="flex flex-wrap gap-2 mt-3">
                    ${j.type ? `<span class="badge-info">${j.type}</span>` : ''}
                    ${j.deadline ? `<span class="badge-warning">Deadline: ${formatDate(j.deadline)}</span>` : ''}
                    ${j.salary_range ? `<span class="badge-gray">${j.salary_range}</span>` : ''}
                </div>
            </div>
            ${j.apply_url && j.status === 'open' ? `
                <a href="${j.apply_url}" target="_blank" class="btn-primary btn-sm flex-shrink-0">
                    Lamar
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </a>
            ` : ''}
        </div>
    `).join('') : `<div class="text-center py-16 text-gray-400">
        <p class="text-5xl mb-4">📋</p>
        <p>Tidak ada lowongan ${currentFilter !== 'all' ? (currentFilter === 'open' ? 'aktif' : 'tutup') : ''} saat ini.</p>
    </div>`;
}

// Filter
window.filterJobs = (filter) => {
    currentFilter = filter;
    ['all','open','closed'].forEach(f => {
        const btn = document.getElementById(`filter-${f}`);
        btn.className = `btn btn-sm ${f === filter ? 'btn-primary' : 'btn-secondary'}`;
    });
    renderJobs(allJobs);
};

// Search
const searchInput = document.getElementById('job-search');
if (searchInput) {
    searchInput.addEventListener('input', debounce((e) => {
        const q = e.target.value.toLowerCase();
        const filtered = allJobs.filter(j =>
            j.title.toLowerCase().includes(q) ||
            (j.company || '').toLowerCase().includes(q) ||
            (j.location || '').toLowerCase().includes(q)
        );
        renderJobs(filtered);
    }, 300));
}

loadHubin();
</script>
@endpush
=======
@section('title', 'Hubin & BKK - SMKN 1 KATAPANG')

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
                    <p class="text-gray-600">Peluang karir eksklusif untuk alumni SMKN 1 KATAPANG.</p>
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
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
