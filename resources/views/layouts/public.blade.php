<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Website Resmi SMKN 1 Katapang — Sekolah Menengah Kejuruan Pusat Keunggulan dengan program keahlian industri terdepan.')">
    <meta name="keywords" content="@yield('meta_keywords', 'SMKN 1 Katapang, sekolah kejuruan, pendidikan vokasi, TeFA, ekstrakurikuler Bandung')">
    <meta property="og:title" content="@yield('title', 'Beranda') — SMKN 1 Katapang">
    <meta property="og:description" content="@yield('meta_description', 'Website Resmi SMKN 1 Katapang')">
    <meta property="og:type" content="website">
    <title>@yield('title', 'Beranda') — SMKN 1 Katapang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&family=inter:400,500,600" rel="stylesheet"/>
    @stack('head')
</head>
<body class="bg-white text-gray-900 antialiased">

{{-- ══════════════════════════════════════════════
     NAVBAR — Professional always-visible design
══════════════════════════════════════════════ --}}
@php
    $navLinks = [
        ['href' => route('home'),    'label' => 'Beranda',          'route' => 'home'],
        ['href' => route('profile'), 'label' => 'Profil',           'route' => 'profile'],
        ['href' => route('hubin'),   'label' => 'Hubin & BKK',      'route' => 'hubin'],
        ['href' => route('tefa'),    'label' => 'Katalog TeFA',     'route' => 'tefa'],
        ['href' => route('eskul'),   'label' => 'Ekstrakurikuler',  'route' => 'eskul'],
        ['href' => route('news'),    'label' => 'Berita',           'route' => 'news'],
        ['href' => route('contact'), 'label' => 'Kontak',           'route' => 'contact'],
    ];
    $currentRouteName = Route::currentRouteName();
@endphp

<header id="navbar" class="fixed top-0 left-0 right-0 z-40">

    {{-- Top accent bar --}}
    <div class="h-1 bg-gradient-to-r from-brand-700 via-brand-500 to-gold-500"></div>

    <nav class="bg-white/97 backdrop-blur-xl shadow-[0_2px_20px_rgba(30,58,95,0.10)] border-b border-gray-100/80">
        <div class="section-container">
            <div class="flex items-center justify-between h-[68px]">

                {{-- ── Logo ──────────────────────────────────── --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 group">
                    <div class="w-10 h-10 bg-brand-gradient rounded-xl flex items-center justify-center
                                flex-shrink-0 shadow-md group-hover:shadow-brand-200 transition-shadow duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <div class="hidden sm:block leading-none">
                        <p class="font-extrabold text-brand-800 text-[15px] tracking-tight">SMKN 1 Katapang</p>
                        <p class="text-[11px] text-gray-400 font-medium mt-0.5 tracking-wide">Sekolah Menengah Kejuruan</p>
                    </div>
                </a>

                {{-- ── Desktop Navigation ─────────────────────── --}}
                <div class="hidden lg:flex items-center gap-1">
                    @foreach($navLinks as $link)
                        @php $isActive = ($currentRouteName === $link['route']); @endphp
                        <a href="{{ $link['href'] }}"
                           class="relative px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 group
                                  {{ $isActive
                                      ? 'text-brand-700 bg-brand-50'
                                      : 'text-gray-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
                            {{ $link['label'] }}
                            {{-- Active underline bar --}}
                            <span class="absolute bottom-0.5 left-4 right-4 h-[2px] rounded-full transition-all duration-200
                                         {{ $isActive ? 'bg-brand-600 opacity-100' : 'bg-brand-400 opacity-0 group-hover:opacity-60' }}"></span>
                        </a>
                    @endforeach
                </div>

                {{-- ── Right actions ──────────────────────────── --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                       class="hidden lg:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                              bg-brand-gradient text-white shadow-sm hover:shadow-md hover:opacity-90
                              transition-all duration-200 active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Login Admin
                    </a>

                    {{-- Hamburger --}}
                    <button id="mobile-menu-toggle"
                            class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl
                                   text-gray-600 hover:text-brand-700 hover:bg-brand-50 transition-colors duration-150"
                            aria-label="Buka menu" aria-expanded="false">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

{{-- Mobile Slide Menu --}}
<nav id="mobile-menu"
     class="fixed top-0 right-0 h-full w-72 bg-white z-50 shadow-2xl transform translate-x-full
            transition-transform duration-300 lg:hidden flex flex-col overflow-y-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-brand-gradient rounded-lg flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-brand-800 text-sm">SMKN 1 Katapang</p>
                <p class="text-[10px] text-gray-400">Sekolah Menengah Kejuruan</p>
            </div>
        </div>
        <button class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 transition-colors"
                aria-label="Tutup menu" onclick="document.getElementById('mobile-menu-toggle').click()">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Nav items --}}
    <div class="flex flex-col px-3 py-4 gap-0.5 flex-1">
        @foreach($navLinks as $link)
            @php $isActive = ($currentRouteName === $link['route']); @endphp
            <a href="{{ $link['href'] }}"
               class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-150
                      {{ $isActive ? 'bg-brand-50 text-brand-700 border border-brand-100' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-700' }}">
                <span>{{ $link['label'] }}</span>
                @if($isActive)
                <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="px-4 pb-6 pt-2 border-t border-gray-100 bg-gray-50/50">
        <a href="{{ route('login') }}"
           class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl
                  bg-brand-gradient text-white text-sm font-bold shadow-sm hover:opacity-90 transition-opacity">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
            Login Admin
        </a>
    </div>
</nav>

{{-- ══════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════ --}}
<main>
    @yield('content')
</main>

{{-- ══════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════ --}}
<footer class="bg-brand-950 text-gray-300">
    <div class="section-container py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-brand-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <p class="font-bold text-white text-lg">SMKN 1 Katapang</p>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Membentuk generasi terampil, berkarakter, dan siap bersaing di era industri 4.0.
                </p>
                <div class="flex items-center gap-3 mt-5">
                    @foreach([
                        ['icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z', 'label' => 'Facebook'],
                        ['icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z', 'label' => 'Instagram'],
                        ['icon' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z', 'label' => 'Twitter'],
                    ] as $social)
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center
                                           hover:bg-brand-600 transition-colors" aria-label="{{ $social['label'] }}">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="{{ $social['icon'] }}"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Links --}}
            @foreach([
                ['title' => 'Halaman', 'links' => [
                    ['href' => route('home'),    'label' => 'Beranda'],
                    ['href' => route('profile'), 'label' => 'Profil Sekolah'],
                    ['href' => route('hubin'),   'label' => 'Hubin & BKK'],
                    ['href' => route('tefa'),    'label' => 'Katalog TeFA'],
                ]],
                ['title' => 'Program', 'links' => [
                    ['href' => route('eskul'),   'label' => 'Ekstrakurikuler'],
                    ['href' => route('news'),    'label' => 'Berita & Agenda'],
                    ['href' => route('contact'), 'label' => 'Kontak'],
                    ['href' => route('login'),   'label' => 'Login Admin'],
                ]],
                ['title' => 'Kontak', 'links' => [
                    ['href' => '#', 'label' => 'Jl. Ceuri Terusan Kopo No. 1, Katapang'],
                    ['href' => 'tel:+62225893777', 'label' => '(022) 589-3777'],
                    ['href' => 'mailto:info@smkn1katapang.sch.id', 'label' => 'info@smkn1katapang.sch.id'],
                    ['href' => '#', 'label' => 'Senin–Jumat 07:00–16:00'],
                ]],
            ] as $col)
                <div>
                    <h3 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">{{ $col['title'] }}</h3>
                    <ul class="space-y-2">
                        @foreach($col['links'] as $link)
                            <li>
                                <a href="{{ $link['href'] }}"
                                   class="text-sm text-gray-400 hover:text-white transition-colors">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="section-container py-4 flex items-center justify-center text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} SMKN 1 Katapang. Semua hak dilindungi.
            </p>
        </div>
    </div>
</footer>

{{-- Toast Container --}}
<div id="toast-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

@stack('scripts')
</body>
</html>
