@extends('layouts.public')

@section('title', 'Beranda')
@section('meta_description', 'Website resmi SMK Unggulan — Pendidikan vokasi terbaik dengan program keahlian industri 4.0, TeFA, dan ekstrakurikuler unggulan.')

@section('content')

{{-- ══════════════════════════════════════════════
     HERO BANNER SLIDER
══════════════════════════════════════════════ --}}
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden" aria-label="Banner Utama">

    {{-- Slider Track --}}
    <div id="slider-track" class="absolute inset-0 flex transition-transform duration-700 ease-in-out">

        {{-- Slide 1: Primary Hero Banner --}}
        <div class="slide relative w-full flex-shrink-0 bg-hero-gradient">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: url('/images/pattern-dots.svg'); background-size: 30px;"></div>
            <div class="relative z-10 section-container h-full flex flex-col justify-center pt-20 pb-16">
                <div class="max-w-3xl">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6 text-balance">
                        Cetak <span class="text-gold-400">Generasi</span> Unggul<br>Siap Industri 4.0
                    </h1>
                    <p class="text-lg text-white/80 mb-8 max-w-2xl leading-relaxed">
                        Program keahlian terdepan, fasilitas industri mutakhir, dan ekosistem vokasi yang mendorong setiap siswa menjadi talenta berdaya saing global.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('profile') }}" class="btn btn-gold btn-lg">
                            Kenali Sekolah Kami
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="{{ route('tefa') }}" class="glass btn btn-lg text-white hover:bg-white/20">
                            Lihat Katalog TeFA
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dynamic slides inserted by JS --}}
    </div>

    {{-- Dots --}}
    <div id="slider-dots" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2"></div>

</section>

{{-- ══════════════════════════════════════════════
     STATISTICS
══════════════════════════════════════════════ --}}
<section class="bg-brand-700 py-12 shadow-inner border-y border-brand-800">
    <div class="section-container">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-0 lg:divide-x lg:divide-white/20" id="stats-row">
            @foreach([
                ['id' => 'stat-students', 'label' => 'Siswa Aktif',         'default' => '1.250+', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                ['id' => 'stat-teachers', 'label' => 'Tenaga Pengajar',     'default' => '85+',    'icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5'],
                ['id' => 'stat-programs', 'label' => 'Kompetensi Keahlian', 'default' => '7',      'icon' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25'],
                ['id' => 'stat-partners', 'label' => 'Mitra DUDI',          'default' => '50+',    'icon' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21'],
            ] as $stat)
            <div class="lg:px-10 flex flex-col items-center text-center text-white stat-counter-item">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gold-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-3xl lg:text-4xl font-black text-white" id="{{ $stat['id'] }}">{{ $stat['default'] }}</p>
                <p class="text-sm text-white/70 mt-1">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     LATEST NEWS
══════════════════════════════════════════════ --}}
<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="flex items-end justify-between mb-10 flex-wrap gap-4">
            <div>
                <p class="text-gold-600 font-semibold text-sm uppercase tracking-wider mb-2">Terkini</p>
                <h2 class="section-title mb-0">Berita & Pengumuman</h2>
            </div>
            <a href="{{ route('news') }}" class="btn btn-secondary btn-sm group">
                Lihat Semua
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        {{-- Skeleton --}}
        <div id="news-skeleton" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i = 0; $i < 3; $i++)
            <div class="card">
                <div class="skeleton h-48 w-full"></div>
                <div class="p-5 space-y-3">
                    <div class="skeleton h-3 w-20 rounded-full"></div>
                    <div class="skeleton h-5 w-4/5 rounded"></div>
                    <div class="skeleton h-4 w-full rounded"></div>
                    <div class="skeleton h-4 w-3/4 rounded"></div>
                </div>
            </div>
            @endfor
        </div>

        {{-- News grid --}}
        <div id="news-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden"></div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     UPCOMING EVENTS
══════════════════════════════════════════════ --}}
<section class="section-padding bg-white">
    <div class="section-container">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">

            <div class="lg:col-span-2">
                <p class="text-gold-600 font-semibold text-sm uppercase tracking-wider mb-2">Kalender</p>
                <h2 class="section-title mb-4">Agenda Terdekat</h2>
                <p class="section-subtitle text-base mb-6">
                    Jadwal kegiatan akademik, penerimaan siswa baru, dan event sekolah terbaru.
                </p>
                <a href="{{ route('news') }}#agenda" class="btn btn-primary">
                    Lihat Semua Agenda
                </a>
            </div>

            <div class="lg:col-span-3" id="events-list">
                {{-- Skeleton --}}
                @for($i = 0; $i < 4; $i++)
                <div class="flex gap-4 p-4 mb-3 card">
                    <div class="skeleton w-14 h-14 rounded-xl flex-shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="skeleton h-4 w-3/4 rounded"></div>
                        <div class="skeleton h-3 w-1/2 rounded"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     TEFA CATALOG HIGHLIGHT
══════════════════════════════════════════════ --}}
<section class="section-padding bg-hero-gradient">
    <div class="section-container">
        <div class="text-center mb-12">
            <p class="text-gold-400 font-semibold text-sm uppercase tracking-wider mb-2">Produk Unggulan</p>
            <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Katalog TeFA & Koperasi</h2>
            <p class="text-white/60 max-w-xl mx-auto">
                Produk berkualitas karya siswa dan jasa jurusan — hasil nyata pembelajaran berbasis industri.
            </p>
        </div>

        {{-- Skeleton --}}
        <div id="products-skeleton" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
            @for($i = 0; $i < 4; $i++)
            <div class="bg-white/5 rounded-2xl overflow-hidden">
                <div class="skeleton h-52 w-full bg-white/10"></div>
                <div class="p-4 space-y-2">
                    <div class="skeleton h-4 w-3/4 rounded bg-white/10"></div>
                    <div class="skeleton h-3 w-1/2 rounded bg-white/10"></div>
                </div>
            </div>
            @endfor
        </div>

        <div id="products-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6 hidden"></div>

        <div class="text-center mt-10">
            <a href="{{ route('tefa') }}" class="btn btn-gold btn-lg">
                Lihat Semua Produk
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     EXTRACURRICULAR PREVIEW
══════════════════════════════════════════════ --}}
<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="text-center mb-12">
            <h2 class="section-title">Ekstrakurikuler</h2>
            <p class="section-subtitle mx-auto text-center">
                50+ kegiatan pengembangan bakat, kepemimpinan, dan prestasi nasional.
            </p>
        </div>

        <div id="eskul-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            @for($i = 0; $i < 6; $i++)
            <div class="card p-5 text-center">
                <div class="skeleton w-12 h-12 rounded-xl mx-auto mb-3"></div>
                <div class="skeleton h-3 w-3/4 mx-auto rounded"></div>
            </div>
            @endfor
        </div>

        <div class="text-center">
            <a href="{{ route('eskul') }}" class="btn btn-secondary">
                Lihat Semua Ekstrakurikuler
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script type="module">
import { publicApi as apiModule } from '/resources/js/api.js';
import * as utilsModule from '/resources/js/utils.js';

const publicApi = window.publicApi || apiModule;
const utils     = window.utils || utilsModule;
const {
    formatDate = (d) => String(d || ''),
    formatRelativeTime = (d) => String(d || ''),
    formatNumber = (n) => String(n || 0),
    animateCounter = (el, val) => { if (el) el.textContent = val; },
    observeElements = (sel, cb) => { document.querySelectorAll(sel).forEach(cb); },
    storageUrl = (p) => {
        if (!p) return '';
        if (p.startsWith('http://') || p.startsWith('https://')) return p;
        return `/storage/${p.replace(/^\//, '')}`;
    },
    truncate = (s, len = 100) => (s && s.length > len) ? s.substring(0, len) + '...' : (s || '')
} = utils;

// ── Hero Slider ───────────────────────────────────────────────
let currentSlide  = 0;
let totalSlides   = 1;
let sliderTimer   = null;

function goToSlide(n) {
    const track = document.getElementById('slider-track');
    const dots  = document.querySelectorAll('.slider-dot');
    if (!track) return;
    currentSlide = (n + totalSlides) % totalSlides;
    track.style.transform = `translateX(-${currentSlide * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === currentSlide));
}

function startAutoPlay() {
    if (sliderTimer) clearInterval(sliderTimer);
    sliderTimer = setInterval(() => goToSlide(currentSlide + 1), 5000);
}

function initSlider(banners) {
    const track = document.getElementById('slider-track');
    const dotsEl = document.getElementById('slider-dots');
    if (!track || !dotsEl) return;

    // Add dynamic slides
    if (banners && banners.length) {
        banners.forEach(b => {
            const slide = document.createElement('div');
            slide.className = 'slide relative w-full flex-shrink-0 bg-hero-gradient';
            const bannerImg = b.image ? storageUrl(b.image) : '';
            slide.innerHTML = `
                ${bannerImg ? `<img src="${bannerImg}" alt="${b.title || 'Banner'}"
                    class="absolute inset-0 w-full h-full object-cover opacity-40">` : ''}
                <div class="absolute inset-0 bg-gradient-to-r from-brand-950/90 to-transparent"></div>
                <div class="relative z-10 section-container h-full flex flex-col justify-center pt-20 pb-16">
                    <div class="max-w-3xl">
                        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6 text-balance">
                            ${b.title || 'SMKN 1 Katapang'}
                        </h2>
                        ${b.subtitle ? `<p class="text-lg text-white/80 mb-8 max-w-2xl">${b.subtitle}</p>` : ''}
                        ${b.link ? `<a href="${b.link}" class="btn btn-gold btn-lg">Selengkapnya</a>` : ''}
                    </div>
                </div>
            `;
            track.appendChild(slide);
        });
    }

    totalSlides = document.querySelectorAll('.slide').length || 1;

    // Build dots
    dotsEl.innerHTML = Array(totalSlides).fill(0).map((_, i) =>
        `<button class="slider-dot ${i === 0 ? 'active' : ''}" data-slide="${i}" aria-label="Slide ${i+1}"></button>`
    ).join('');

    dotsEl.querySelectorAll('.slider-dot').forEach(dot => {
        dot.addEventListener('click', () => goToSlide(+dot.dataset.slide));
    });

    if (totalSlides > 1) startAutoPlay();
}

// ── Fetch Home Data ───────────────────────────────────────────
async function loadHome() {
    try {
        const data = await publicApi.home();

        // 1. Banners
        if (data.banners?.length) {
            initSlider(data.banners);
        } else {
            initSlider([]);
        }

        // 2. Stats
        if (data.stats) {
            const stats = data.stats;
            const statMap = {
                'stat-students': stats.students ?? 1250,
                'stat-teachers': stats.teachers ?? 85,
                'stat-programs': stats.programs ?? 7,
                'stat-partners': stats.partners ?? 50,
            };
            Object.entries(statMap).forEach(([id, val]) => {
                const el = document.getElementById(id);
                if (el) animateCounter(el, val);
            });
        }

        // 3. News (Berita & Pengumuman)
        const newsItems = data.latest_news || data.news || [];
        if (newsItems.length) {
            renderNews(newsItems);
        } else {
            document.getElementById('news-skeleton')?.classList.add('hidden');
            const grid = document.getElementById('news-grid');
            if (grid) {
                grid.classList.remove('hidden');
                grid.innerHTML = '<p class="col-span-3 text-center text-gray-400 py-10">Belum ada berita & pengumuman tersedia.</p>';
            }
        }

        // 4. Events (Agenda Terdekat)
        const eventItems = data.upcoming_events || data.events || [];
        if (eventItems.length) {
            renderEvents(eventItems);
        } else {
            const list = document.getElementById('events-list');
            if (list) list.innerHTML = '<p class="text-gray-400 py-8 text-center">Belum ada agenda kegiatan terdekat.</p>';
        }

        // 5. Products (Katalog TeFA & Koperasi)
        const productItems = data.featured_products || data.products || [];
        if (productItems.length) {
            renderProducts(productItems);
        } else {
            document.getElementById('products-skeleton')?.classList.add('hidden');
            const grid = document.getElementById('products-grid');
            if (grid) {
                grid.classList.remove('hidden');
                grid.innerHTML = '<p class="col-span-4 text-center text-white/60 py-10">Belum ada produk TeFA & Koperasi tersedia.</p>';
            }
        }

        // 6. Extracurriculars (Ekstrakurikuler)
        const eskulItems = data.extracurriculars || [];
        if (eskulItems.length) {
            renderEskul(eskulItems);
        } else {
            const grid = document.getElementById('eskul-grid');
            if (grid) grid.innerHTML = '<p class="col-span-6 text-center text-gray-400 py-8">Belum ada data ekstrakurikuler.</p>';
        }

    } catch (err) {
        console.warn('Home data load failed, using fallbacks', err);
        loadFallbackStats();
    }
}

function loadFallbackStats() {
    const elStudents = document.getElementById('stat-students');
    const elTeachers = document.getElementById('stat-teachers');
    const elPrograms = document.getElementById('stat-programs');
    const elPartners = document.getElementById('stat-partners');
    if (elStudents) animateCounter(elStudents, 1250);
    if (elTeachers) animateCounter(elTeachers, 85);
    if (elPrograms) animateCounter(elPrograms, 7);
    if (elPartners) animateCounter(elPartners, 50);
}

// ── Render Functions ──────────────────────────────────────────
function renderNews(items) {
    document.getElementById('news-skeleton')?.classList.add('hidden');
    const grid = document.getElementById('news-grid');
    if (!grid) return;
    grid.classList.remove('hidden');

    const fallbackNewsImgs = [
        'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
    ];

    grid.innerHTML = items.slice(0, 3).map((post, idx) => {
        const rawImg = post.image_url || post.thumbnail || '';
        const imgSrc = rawImg.startsWith('http') ? rawImg : (rawImg ? storageUrl(rawImg) : fallbackNewsImgs[idx % fallbackNewsImgs.length]);
        const catName = post.category?.name || post.category?.category_name || 'Berita';
        const dateFormatted = post.published_at ? formatRelativeTime(post.published_at) : 'Baru saja';
        const postTitle = post.title || 'Informasi Sekolah';
        const postExcerpt = post.excerpt || post.content || '';

        return `
        <article class="news-card group flex flex-col h-full card hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden">
            <div class="overflow-hidden bg-gray-100 relative h-48">
                <img src="${imgSrc}" alt="${postTitle}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy"
                     onerror="this.src='${fallbackNewsImgs[idx % fallbackNewsImgs.length]}'">
            </div>
            <div class="news-card-body p-5 flex flex-col flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge-info text-xs font-semibold">${catName}</span>
                    <span class="text-xs text-gray-400">${dateFormatted}</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-brand-700 transition-colors text-base">
                    ${postTitle}
                </h3>
                <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-1">${truncate(postExcerpt, 120)}</p>
                <a href="/berita/${post.slug || post.id}" class="text-sm font-semibold text-brand-700 hover:text-brand-900 flex items-center gap-1 mt-auto group-inner">
                    Baca Selengkapnya
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </article>
    `}).join('');
}

function renderEvents(events) {
    const list = document.getElementById('events-list');
    if (!list) return;
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

    list.innerHTML = events.slice(0, 5).map(ev => {
        const rawDate = ev.event_date || ev.start_date || '';
        const d = rawDate ? new Date(rawDate) : new Date();
        const day = isNaN(d.getDate()) ? '01' : String(d.getDate()).padStart(2, '0');
        const month = isNaN(d.getMonth()) ? 'Sep' : months[d.getMonth()];
        const evTitle = ev.title || 'Agenda Kegiatan';
        const evLoc   = ev.location || 'Kampus SMKN 1 Katapang';
        const evType  = ev.type || 'Kegiatan Sekolah';

        return `
        <a href="/berita#agenda" class="flex items-center gap-4 p-4 mb-3 card hover:border-brand-200 hover:shadow-md transition-all border border-gray-100">
            <div class="w-14 h-14 bg-brand-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0 border border-brand-100 shadow-xs">
                <span class="text-xl font-black text-brand-700 leading-none">${day}</span>
                <span class="text-xs text-brand-500 font-bold uppercase mt-0.5">${month}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-900 line-clamp-1 text-sm md:text-base">${evTitle}</p>
                <p class="text-xs md:text-sm text-gray-500 mt-0.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    ${evLoc}
                </p>
            </div>
            <span class="badge-info text-xs font-semibold flex-shrink-0 self-center hidden sm:inline-block">${evType}</span>
        </a>`;
    }).join('');
}

function renderProducts(products) {
    document.getElementById('products-skeleton')?.classList.add('hidden');
    const grid = document.getElementById('products-grid');
    if (!grid) return;
    grid.classList.remove('hidden');

    const fallbackProdImgs = [
        'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80',
        'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
    ];

    grid.innerHTML = products.slice(0, 4).map((p, idx) => {
        const prodName = p.name || p.product_name || 'Produk Unggulan';
        const rawImg   = p.image || p.image_url || '';
        const imgSrc   = rawImg.startsWith('http') ? rawImg : (rawImg ? storageUrl(rawImg) : fallbackProdImgs[idx % fallbackProdImgs.length]);
        const catName  = p.category?.name || p.category?.category_name || p.program?.program_name || 'TeFA & Koperasi';
        const price    = Number(p.price || 0);

        return `
        <a href="/katalog" class="group block h-full">
            <div class="bg-white/10 rounded-2xl overflow-hidden border border-white/10 hover:border-gold-400/50 transition-all hover:-translate-y-1 duration-300 flex flex-col h-full">
                <div class="relative overflow-hidden aspect-square bg-white/5">
                    <img src="${imgSrc}" alt="${prodName}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         loading="lazy"
                         onerror="this.src='${fallbackProdImgs[idx % fallbackProdImgs.length]}'">
                    ${p.stock !== undefined && p.stock <= 0
                        ? '<span class="absolute top-2 right-2 badge-danger">Habis</span>'
                        : '<span class="absolute top-2 right-2 badge-success">Tersedia</span>'}
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <p class="text-xs text-gold-400 mb-1 font-medium">${catName}</p>
                    <h3 class="font-bold text-white line-clamp-1 text-sm md:text-base">${prodName}</h3>
                    <p class="text-lg font-black text-gold-400 mt-auto pt-2">Rp ${price.toLocaleString('id-ID')}</p>
                </div>
            </div>
        </a>
    `}).join('');
}

function renderEskul(eskul) {
    const emojis = ['🤖','⛺','⚽','🚑','🗣️','💃','🎶','🎨','💂','📖'];
    const grid = document.getElementById('eskul-grid');
    if (!grid) return;

    grid.innerHTML = eskul.slice(0, 6).map((e, i) => `
        <a href="/ekstrakurikuler" class="card p-5 text-center hover:border-brand-200 hover:shadow-md transition-all group flex flex-col items-center justify-center border border-gray-100">
            <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-3 text-2xl group-hover:bg-brand-100 transition-colors shadow-2xs">
                ${emojis[i % emojis.length]}
            </div>
            <p class="text-sm font-bold text-gray-800 line-clamp-2">${e.name}</p>
        </a>
    `).join('');
}

// ── Bootstrap ─────────────────────────────────────────────────
loadHome();
</script>
@endpush

