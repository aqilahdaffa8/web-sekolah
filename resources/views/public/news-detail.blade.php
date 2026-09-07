@extends('layouts.public')
@section('title', 'Detail Berita')

@section('content')
<div class="bg-hero-gradient pt-28 pb-12">
    <div class="section-container">
        <div class="max-w-3xl mx-auto">
            <a href="/berita" class="inline-flex items-center gap-2 text-white/80 hover:text-white mb-6 text-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Semua Berita
            </a>
            <div id="article-header">
                <span class="badge-gold mb-3 inline-block" id="article-category">Berita</span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 leading-tight" id="article-title">
                    Memuat berita...
                </h1>
                <div class="flex items-center gap-4 text-white/70 text-sm">
                    <span id="article-date">—</span>
                    <span>•</span>
                    <span id="article-author">Admin SMKN 1 Katapang</span>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-padding bg-gray-50">
    <div class="section-container">
        <div class="max-w-3xl mx-auto">
            <div class="card overflow-hidden p-6 md:p-10 mb-10">
                <div id="article-thumbnail-wrapper" class="mb-8 rounded-2xl overflow-hidden shadow-sm">
                    <img id="article-thumbnail" src="" alt="Thumbnail" class="w-full h-[360px] object-cover" onerror="this.style.display='none'">
                </div>
                <div id="article-content" class="prose max-w-none text-gray-700 leading-relaxed space-y-4 text-base md:text-lg">
                    <p class="text-gray-400 animate-pulse">Memuat konten berita...</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script type="module">
import { publicApi } from '/resources/js/api.js';
import { formatDate, storageUrl } from '/resources/js/utils.js';

const slug = "{{ $slug ?? '' }}";

async function loadDetail() {
    try {
        const res = await publicApi.newsDetail(slug);
        const post = res.data || res;

        document.title = `${post.title || 'Berita'} - SMKN 1 Katapang`;
        document.getElementById('article-title').textContent = post.title || 'Berita';
        document.getElementById('article-category').textContent = post.category?.name || 'Informasi';
        document.getElementById('article-date').textContent = formatDate(post.published_at || post.created_at);
        document.getElementById('article-author').textContent = post.author?.name || 'Admin Sekolah';

        if (post.thumbnail) {
            document.getElementById('article-thumbnail').src = storageUrl(post.thumbnail);
        } else {
            document.getElementById('article-thumbnail-wrapper').style.display = 'none';
        }

        document.getElementById('article-content').innerHTML = post.content || post.excerpt || '<p>Tidak ada konten tambahan.</p>';
    } catch (e) {
        console.error(e);
        document.getElementById('article-title').textContent = 'Berita Tidak Ditemukan';
        document.getElementById('article-content').innerHTML = `
            <div class="text-center py-10">
                <p class="text-gray-500 mb-6">Maaf, berita yang Anda cari tidak ditemukan atau telah dihapus.</p>
                <a href="/berita" class="btn btn-primary inline-flex">Kembali ke Daftar Berita</a>
            </div>
        `;
    }
}

loadDetail();
</script>
@endpush
@endsection
