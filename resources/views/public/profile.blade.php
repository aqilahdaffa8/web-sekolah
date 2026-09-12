@extends('layouts.public')

@section('title', 'Profil & Jurusan')
@section('meta_description', 'Profil SMKN 1 Katapang — Visi-misi, sejarah, struktur organisasi, kompetensi keahlian, dan fasilitas praktik terlengkap.')

@section('content')

{{-- ══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════ --}}
<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Profil & Jurusan</h1>
        <p class="text-white/70 max-w-xl mx-auto">
            Kenali kami lebih dekat — sejarah, visi-misi, dan program keahlian unggulan kami.
        </p>
    </div>
</div>

{{-- ══════════════════════════════════════
     TABS NAV
══════════════════════════════════════ --}}
<div class="bg-white border-b border-gray-200 sticky top-16 z-30">
    <div class="section-container">
        <div class="flex gap-0 overflow-x-auto scrollbar-thin -mb-px" data-tab-group="profile">
            @foreach([
                ['id' => 'visi-misi',     'label' => 'Visi & Misi'],
                ['id' => 'sejarah',       'label' => 'Sejarah'],
                ['id' => 'struktur',      'label' => 'Struktur Organisasi'],
                ['id' => 'kompetensi',    'label' => 'Kompetensi Keahlian'],
                ['id' => 'fasilitas',     'label' => 'Fasilitas & Lab'],
            ] as $tab)
            <button data-tab="{{ $tab['id'] }}"
                    class="tab-btn shrink-0 rounded-none border-b-2 border-transparent px-5 py-4 text-sm
                           hover:text-brand-700 hover:border-brand-300 transition-colors
                           {{ $loop->first ? 'active text-brand-700 !border-brand-700' : 'text-gray-600' }}">
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     TAB CONTENT
══════════════════════════════════════ --}}
<div class="section-padding">
    <div class="section-container">

        {{-- Visi & Misi --}}
        <div data-tab-panel="visi-misi" data-group="profile" class="active animate-fade-in">
            <div id="visi-misi-content">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    <div class="skeleton-block">
                        {{-- Skeleton --}}
                        <div class="space-y-4">
                            <div class="skeleton h-7 w-1/3 rounded"></div>
                            <div class="skeleton h-4 w-full rounded"></div>
                            <div class="skeleton h-4 w-4/5 rounded"></div>
                            <div class="skeleton h-4 w-3/4 rounded"></div>
                        </div>
                    </div>
                    <div class="skeleton-block">
                        <div class="space-y-3">
                            <div class="skeleton h-7 w-1/3 rounded"></div>
                            @for($i=0;$i<5;$i++)<div class="skeleton h-4 w-full rounded"></div>@endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sejarah --}}
        <div data-tab-panel="sejarah" data-group="profile" class="hidden animate-fade-in">
            <div id="sejarah-content">
                <div class="max-w-3xl mx-auto">
                    <div class="space-y-3">
                        @for($i=0;$i<8;$i++)<div class="skeleton h-4 w-full rounded"></div>@endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- Struktur Organisasi --}}
        <div data-tab-panel="struktur" data-group="profile" class="hidden animate-fade-in">
            <div id="struktur-content">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Struktur Organisasi</h2>
                </div>
                <div class="flex flex-col items-center gap-4" id="org-chart">
                    <div class="skeleton h-20 w-48 rounded-xl"></div>
                    <div class="skeleton h-px w-px"></div>
                    <div class="flex gap-8 justify-center flex-wrap">
                        @for($i=0;$i<4;$i++)
                        <div class="skeleton h-20 w-40 rounded-xl"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- Kompetensi Keahlian --}}
        <div data-tab-panel="kompetensi" data-group="profile" class="hidden animate-fade-in">
            <div class="text-center mb-12">
                <h2 class="section-title">Kompetensi Keahlian</h2>
                <p class="section-subtitle mx-auto text-center">Program keahlian kami dirancang bersama mitra industri terkemuka.</p>
            </div>
            <div id="programs-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i=0;$i<6;$i++)
                <div class="card p-6">
                    <div class="skeleton w-14 h-14 rounded-xl mb-4"></div>
                    <div class="skeleton h-5 w-2/3 rounded mb-2"></div>
                    <div class="skeleton h-3 w-full rounded"></div>
                    <div class="skeleton h-3 w-4/5 rounded mt-1"></div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Fasilitas & Lab --}}
        <div data-tab-panel="fasilitas" data-group="profile" class="hidden animate-fade-in">
            <div class="text-center mb-12">
                <h2 class="section-title">Fasilitas & Laboratorium</h2>
                <p class="section-subtitle mx-auto text-center">Fasilitas berstandar industri untuk mendukung pembelajaran praktik.</p>
            </div>
            <div id="facilities-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i=0;$i<6;$i++)
                <div class="card">
                    <div class="skeleton h-52 w-full"></div>
                    <div class="p-4 space-y-2">
                        <div class="skeleton h-4 w-3/4 rounded"></div>
                        <div class="skeleton h-3 w-full rounded"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script type="module">
import { publicApi } from '/resources/js/api.js';
import { storageUrl } from '/resources/js/utils.js';

async function loadProfile() {
    try {
        const data = await publicApi.profile();
        const school = data.school || {};

        renderVisiMisi({ ...data, ...school });
        renderSejarah({ ...data, ...school });
        renderStruktur({ ...data, ...school });
        renderPrograms(data.programs || []);
        renderFacilities(data.facilities || []);
    } catch (err) {
        console.error('Profile load failed', err);
    }
}

function renderVisiMisi(data) {
    document.getElementById('visi-misi-content').innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Visi</h2>
                <p class="text-gray-600 leading-relaxed text-lg">${data.vision || 'Menjadi sekolah kejuruan unggulan yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.'}</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-gold-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-gold-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Misi</h2>
                <ul class="space-y-3">
                    ${(data.missions || ['Menyelenggarakan pendidikan bermutu berbasis kompetensi industri.','Mengembangkan karakter dan jiwa wirausaha siswa.','Membangun kemitraan strategis dengan DUDI.']).map(m => `
                        <li class="flex gap-3 items-start">
                            <div class="w-6 h-6 bg-success-light rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-success" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <span class="text-gray-700">${m}</span>
                        </li>
                    `).join('')}
                </ul>
            </div>
        </div>
    `;
}

function renderSejarah(data) {
    document.getElementById('sejarah-content').innerHTML = `
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sejarah Sekolah</h2>
            <div class="prose prose-lg text-gray-600 leading-relaxed">
                ${data.history || '<p>SMKN 1 Katapang berdiri sejak tahun 1999 dengan komitmen mencetak generasi vokasi unggul dan berkarakter di bidang teknologi dan industri. Selama lebih dari 2 dekade, kami telah meluluskan ribuan alumni kompeten yang berkarier di berbagai industri teknologi nasional maupun internasional.</p>'}
            </div>
        </div>
    `;
}

function renderStruktur(data) {
    const members = data.organization_members || [];
    document.getElementById('org-chart').innerHTML = members.length ? `
        <div class="w-full overflow-x-auto">
            <div class="flex flex-wrap justify-center gap-6">
                ${members.map(m => `
                    <div class="flex flex-col items-center text-center card p-5 w-44">
                        <div class="w-16 h-16 rounded-full bg-brand-100 mb-3 overflow-hidden flex-shrink-0">
                            ${m.photo ? `<img src="${storageUrl(m.photo)}" alt="${m.name}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full flex items-center justify-center text-2xl font-bold text-brand-700">${m.name[0]}</div>`}
                        </div>
                        <p class="font-bold text-sm text-gray-900">${m.name}</p>
                        <p class="text-xs text-gray-500 mt-1">${m.position}</p>
                    </div>
                `).join('')}
            </div>
        </div>
    ` : '<p class="text-center text-gray-500 py-12">Data struktur organisasi belum tersedia.</p>';
}

function getProgramIcon(name) {
    const n = (name || '').toLowerCase();
    if (n.includes('perangkat') || n.includes('rpl')) return '💻';
    if (n.includes('jaringan') || n.includes('tkj')) return '🌐';
    if (n.includes('broadcasting') || n.includes('perfilman') || n.includes('bp')) return '🎬';
    if (n.includes('mesin')) return '⚙️';
    if (n.includes('otomotif')) return '🚗';
    if (n.includes('tekstil') || n.includes('tpt')) return '🧵';
    if (n.includes('elektronika') || n.includes('elektro')) return '⚡';
    return '🎓';
}

function renderPrograms(programs) {
    document.getElementById('programs-grid').innerHTML = programs.map((p) => {
        const progName = p.name || p.program_name || 'Program Keahlian';
        const icon = getProgramIcon(progName);
        return `
        <div class="card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-2xl bg-brand-50 flex items-center justify-center text-3xl shadow-xs border border-brand-100/60">
                    ${icon}
                </div>
                <span class="badge-brand">${p.level || 'SMK 3 Tahun'}</span>
            </div>
            <h3 class="font-bold text-gray-900 text-lg mb-2">${progName}</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">${p.description || ''}</p>
            <div class="flex items-center justify-between pt-3 border-t border-gray-50 mt-auto">
                <span class="badge-success text-xs font-semibold">Akreditasi ${p.accreditation || 'A'}</span>
                <span class="text-xs text-brand-600 font-semibold">${p.classes?.length || 2} Kelas</span>
            </div>
        </div>
    `;
    }).join('') || '<p class="text-gray-500 col-span-3 text-center py-12">Data program belum tersedia.</p>';
}

function renderFacilities(facilities) {
    document.getElementById('facilities-grid').innerHTML = facilities.map(f => {
        const facName = f.name || f.facility_name || 'Fasilitas';
        const rawImg = f.image || f.image_url || '';
        const imgSrc = rawImg.startsWith('http') ? rawImg : storageUrl(rawImg);
        return `
        <div class="card group overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300">
            <div class="overflow-hidden h-52 bg-slate-100 relative">
                <img src="${imgSrc}" alt="${facName}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1562774053-701939374585?w=600&auto=format&fit=crop&q=80'">
                ${f.location ? `<span class="absolute bottom-3 left-3 bg-black/60 text-white text-xs px-2.5 py-1 rounded-lg backdrop-blur-xs font-medium">${f.location}</span>` : ''}
            </div>
            <div class="p-5">
                <h3 class="font-bold text-gray-900 text-base mb-1.5 group-hover:text-brand-700 transition-colors">${facName}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">${f.description || 'Fasilitas praktikum modern untuk menunjang kegiatan pembelajaran siswa.'}</p>
            </div>
        </div>
    `;
    }).join('') || '<p class="text-gray-500 col-span-3 text-center py-12">Data fasilitas belum tersedia.</p>';
}

// Tab system — custom for profile (border-bottom style)
document.querySelectorAll('[data-tab-group="profile"] [data-tab]').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('[data-tab-group="profile"] [data-tab]').forEach(b => {
            b.classList.remove('active', 'text-brand-700', '!border-brand-700');
            b.classList.add('text-gray-600', 'border-transparent');
        });
        btn.classList.add('active', 'text-brand-700', '!border-brand-700');
        btn.classList.remove('text-gray-600', 'border-transparent');

        document.querySelectorAll('[data-tab-panel][data-group="profile"]').forEach(panel => {
            panel.classList.toggle('hidden', panel.dataset.tabPanel !== tab);
            panel.classList.toggle('active', panel.dataset.tabPanel === tab);
        });
    });
});

loadProfile();
</script>
@endpush

