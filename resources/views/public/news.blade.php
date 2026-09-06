@extends('layouts.public')
@section('title', 'Berita & Agenda')
@section('meta_description', 'Berita terbaru, pengumuman, dan agenda kegiatan SMKN 1 Katapang.')

@section('content')

<div class="bg-hero-gradient pt-28 pb-16">
    <div class="section-container text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Berita & Agenda</h1>
        <p class="text-white/70 max-w-xl mx-auto">Update terbaru kegiatan, prestasi, dan agenda sekolah.</p>
    </div>
</div>

{{-- Tabs --}}
<div class="bg-white border-b border-gray-200 sticky top-16 z-30">
    <div class="section-container">
        <div class="flex gap-0" data-tab-group="news">
            <button data-tab="berita"
                    class="tab-btn rounded-none border-b-2 border-brand-700 text-brand-700 px-6 py-4 text-sm active">
                Berita
            </button>
            <button data-tab="agenda"
                    class="tab-btn rounded-none border-b-2 border-transparent text-gray-600 hover:text-brand-700 px-6 py-4 text-sm">
                Agenda
            </button>
        </div>
    </div>
</div>

<section class="section-padding bg-gray-50">
    <div class="section-container">

        {{-- Berita tab --}}
        <div data-tab-panel="berita" data-group="news" class="active">
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <input id="news-search" type="search" placeholder="Cari berita..." class="form-input pl-10">
                </div>
                <select id="category-filter" class="form-select w-auto min-w-[180px]">
                    <option value="">Semua Kategori</option>
                </select>
            </div>

            <div id="news-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
            <div id="news-pagination" class="mt-10"></div>
        </div>

        {{-- Agenda tab --}}
        <div data-tab-panel="agenda" data-group="news" class="hidden" id="agenda">
            <div id="events-list" class="space-y-4"></div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script type="module">
import { publicApi } from '/resources/js/api.js';
import { formatDate, formatRelativeTime, storageUrl, debounce, paginate, truncate } from '/resources/js/utils.js';

let allNews   = [];
let allEvents = [];
let page      = 1;
const PER_PAGE = 9;

async function loadNews() {
    try {
        const data = await publicApi.news();
        allNews = (data.data || data || []).filter(n => n.status !== 'draft');
        const categories = [...new Set(allNews.map(n => n.category?.name).filter(Boolean))];

        // Populate category filter
        const sel = document.getElementById('category-filter');
        categories.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c; opt.textContent = c;
            sel.appendChild(opt);
        });

        renderNews(allNews, 1);
    } catch (e) { console.error(e); }
}

async function loadEvents() {
    try {
        const data = await publicApi.agenda();
        allEvents = data.data || data || [];
        renderEvents(allEvents);
    } catch (e) { console.error(e); }
}

function renderNews(items, p) {
    const start = (p - 1) * PER_PAGE;
    const slice = items.slice(start, start + PER_PAGE);
    const grid  = document.getElementById('news-grid');

    grid.innerHTML = slice.map(post => `
        <article class="news-card group">
            <div class="overflow-hidden">
                <img src="${storageUrl(post.thumbnail)}" alt="${post.title}"
                     class="news-card-img transition-transform duration-500 group-hover:scale-105"
                     loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&auto=format&fit=crop&q=80'">
            </div>
            <div class="news-card-body">
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge-info">${post.category?.name ?? 'Berita'}</span>
                    <span class="text-xs text-gray-400">${formatRelativeTime(post.published_at)}</span>
                </div>
                <h2 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-brand-700 transition-colors">
                    ${post.title}
                </h2>
                <p class="text-sm text-gray-500 line-clamp-3 mb-4 flex-1">${truncate(post.excerpt || '', 120)}</p>
                <a href="/berita/${post.slug}" class="text-sm font-semibold text-brand-700 hover:text-brand-900 flex items-center gap-1">
                    Baca Selengkapnya
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </article>
    `).join('') || '<p class="col-span-3 text-center text-gray-500 py-12">Belum ada berita.</p>';

    paginate(document.getElementById('news-pagination'), items.length, PER_PAGE, p, (newPage) => {
        renderNews(items, newPage);
        window.scrollTo({ top: 400, behavior: 'smooth' });
    });
}

function renderEvents(events) {
    document.getElementById('events-list').innerHTML = events.map(ev => {
        const d = new Date(ev.event_date || ev.start_date);
        return `
        <div class="card p-5 flex flex-col sm:flex-row gap-5 items-start">
            <div class="w-16 h-16 bg-brand-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0 border border-brand-100">
                <span class="text-2xl font-black text-brand-700 leading-none">${d.getDate()}</span>
                <span class="text-xs text-brand-500 font-medium">${['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'][d.getMonth()]}</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900">${ev.title}</h3>
                <p class="text-sm text-gray-500 mt-1">${ev.description || ''}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    ${ev.location ? `<span class="badge-gray">📍 ${ev.location}</span>` : ''}
                    ${ev.type ? `<span class="badge-info">${ev.type}</span>` : ''}
                </div>
            </div>
        </div>`;
    }).join('') || '<p class="text-center text-gray-500 py-12">Belum ada agenda mendatang.</p>';
}

// Filters
document.getElementById('news-search').addEventListener('input', debounce(e => {
    const q = e.target.value.toLowerCase();
    const cat = document.getElementById('category-filter').value;
    const filtered = allNews.filter(n =>
        (!q || n.title.toLowerCase().includes(q)) &&
        (!cat || n.category?.name === cat)
    );
    renderNews(filtered, 1);
}, 300));

document.getElementById('category-filter').addEventListener('change', e => {
    const q = document.getElementById('news-search').value.toLowerCase();
    const cat = e.target.value;
    const filtered = allNews.filter(n =>
        (!q || n.title.toLowerCase().includes(q)) &&
        (!cat || n.category?.name === cat)
    );
    renderNews(filtered, 1);
});

// Tab custom
document.querySelectorAll('[data-tab-group="news"] [data-tab]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-tab-group="news"] [data-tab]').forEach(b => {
            b.classList.remove('active', 'text-brand-700', 'border-brand-700');
            b.classList.add('text-gray-600', 'border-transparent');
        });
        btn.classList.add('active', 'text-brand-700', 'border-brand-700');
        document.querySelectorAll('[data-tab-panel][data-group="news"]').forEach(p => {
            p.classList.toggle('hidden', p.dataset.tabPanel !== btn.dataset.tab);
        });
    });
});

loadNews();
loadEvents();
</script>
@endpush
