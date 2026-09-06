@extends('layouts.public')

<<<<<<< HEAD
@section('title', 'Profil & Jurusan')
@section('meta_description', 'Profil SMKN 1 Katapang — Visi-misi, sejarah, struktur organisasi, kompetensi keahlian, dan fasilitas praktik terlengkap.')

@section('content')

{{-- ══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════ --}}
<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <span class="badge-brand mb-4 inline-block">Tentang Kami</span>
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
=======
@section('title', 'Profil & Jurusan - SMKN 1 KATAPANG')

@section('content')
    <div class="bg-blue-800 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Profil & Jurusan</h1>
            <p class="text-blue-200 max-w-2xl mx-auto text-lg">Mengenal lebih dekat SMKN 1 KATAPANG dan pilihan kompetensi keahlian unggulan kami.</p>
        </div>
    </div>

    <!-- Profil Singkat -->
    <section class="section bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2">
                    <img src="https://via.placeholder.com/600x400?text=Gedung+Sekolah" alt="Gedung SMKN 1 KATAPANG" class="rounded-xl shadow-lg w-full">
                </div>
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Sejarah & Visi Misi</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Berdiri sejak tahun 1998, SMKN 1 KATAPANG telah meluluskan ribuan alumni yang kini sukses berkarir di berbagai industri nasional dan multinasional. Kami berkomitmen untuk terus beradaptasi dengan perkembangan teknologi.
                    </p>
                    <div class="mt-6">
                        <h3 class="font-bold text-xl text-blue-700 mb-2">Visi</h3>
                        <p class="text-gray-600 italic border-l-4 border-blue-500 pl-4 py-2 bg-blue-50 rounded-r-lg">
                            "Menjadi lembaga pendidikan dan pelatihan vokasi yang unggul, berkarakter, dan berdaya saing global."
                        </p>
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD

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

        renderVisiMisi(data);
        renderSejarah(data);
        renderStruktur(data);
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

function renderPrograms(programs) {
    const icons = ['💻','⚙️','🎨','🍳','🏥','⚡'];
    document.getElementById('programs-grid').innerHTML = programs.map((p, i) => `
        <div class="card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-4">${icons[i % icons.length]}</div>
            <h3 class="font-bold text-gray-900 text-lg mb-2">${p.name}</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-4">${p.description || ''}</p>
            <div class="flex items-center gap-2 mt-auto">
                <span class="badge-brand">${p.level || 'SMK'}</span>
                ${p.accreditation ? `<span class="badge-success">Akreditasi ${p.accreditation}</span>` : ''}
            </div>
        </div>
    `).join('') || '<p class="text-gray-500 col-span-3 text-center py-12">Data program belum tersedia.</p>';
}

function renderFacilities(facilities) {
    document.getElementById('facilities-grid').innerHTML = facilities.map(f => `
        <div class="card group overflow-hidden">
            <div class="overflow-hidden h-52">
                <img src="${storageUrl(f.image)}" alt="${f.name}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1562774053-701939374585?w=600&auto=format&fit=crop&q=80'">
            </div>
            <div class="p-4">
                <h3 class="font-bold text-gray-900 mb-1">${f.name}</h3>
                <p class="text-sm text-gray-500">${f.description || ''}</p>
            </div>
        </div>
    `).join('') || '<p class="text-gray-500 col-span-3 text-center py-12">Data fasilitas belum tersedia.</p>';
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
=======
    </section>

    <!-- Jurusan -->
    <section class="section bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Kompetensi Keahlian</h2>
                <p class="section-subtitle">Program studi pilihan yang dirancang sesuai kebutuhan industri saat ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- TKJ -->
                <div class="card p-6 border-t-4 border-blue-600">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center text-blue-700 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Teknik Komputer & Jaringan</h3>
                    <p class="text-gray-600 mb-4">Mempelajari infrastruktur jaringan, administrasi server, keamanan siber, dan troubleshooting perangkat keras.</p>
                </div>

                <!-- RPL -->
                <div class="card p-6 border-t-4 border-yellow-400">
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rekayasa Perangkat Lunak</h3>
                    <p class="text-gray-600 mb-4">Fokus pada pengembangan aplikasi web, mobile, desktop, serta perancangan sistem basis data modern.</p>
                </div>

                <!-- MM -->
                <div class="card p-6 border-t-4 border-purple-500">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Multimedia / DKV</h3>
                    <p class="text-gray-600 mb-4">Mendalami desain grafis, animasi, editing video, dan produksi konten digital kreatif yang siap pakai.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
