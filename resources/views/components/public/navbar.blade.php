<nav class="bg-blue-800 text-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-800 font-bold text-xl">
                    S
                </div>
                <div>
                    <h1 class="font-bold text-xl tracking-wide">SMKN 1 KATAPANG</h1>
                    <p class="text-xs text-blue-200">Bisa & Hebat</p>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="/" class="hover:text-yellow-400 transition">Beranda</a>
                <a href="/profil" class="hover:text-yellow-400 transition">Profil & Jurusan</a>
                <a href="/hubin" class="hover:text-yellow-400 transition">Hubin & BKK</a>
                <a href="/tefa" class="hover:text-yellow-400 transition">Katalog TeFA</a>
                <a href="/eskul" class="hover:text-yellow-400 transition">Ekstrakurikuler</a>
                <a href="/berita" class="hover:text-yellow-400 transition">Berita & Agenda</a>
                <a href="/kontak" class="hover:text-yellow-400 transition">Kontak</a>
                <a href="/siswa/nilai" class="hover:text-yellow-400 transition">Cek Nilai</a>
                
                <div class="border-l border-blue-600 pl-6 ml-2">
                    <a href="/login" class="btn bg-yellow-400 text-blue-900 hover:bg-yellow-300 border-none font-bold">Login Admin</a>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-blue-700 pb-4">
        <a href="/" class="block px-4 py-2 hover:bg-blue-600">Beranda</a>
        <a href="/profil" class="block px-4 py-2 hover:bg-blue-600">Profil & Jurusan</a>
        <a href="/hubin" class="block px-4 py-2 hover:bg-blue-600">Hubin & BKK</a>
        <a href="/tefa" class="block px-4 py-2 hover:bg-blue-600">Katalog TeFA</a>
        <a href="/eskul" class="block px-4 py-2 hover:bg-blue-600">Ekstrakurikuler</a>
        <a href="/berita" class="block px-4 py-2 hover:bg-blue-600">Berita & Agenda</a>
        <a href="/kontak" class="block px-4 py-2 hover:bg-blue-600">Kontak</a>
        <a href="/siswa/nilai" class="block px-4 py-2 hover:bg-blue-600">Cek Nilai</a>
        <a href="/login" class="block px-4 py-2 text-yellow-400 font-bold mt-2 border-t border-blue-600">Login Admin</a>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if(btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>
