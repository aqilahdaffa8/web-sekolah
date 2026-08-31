<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <h1 class="text-xl font-semibold text-gray-800" id="topbar-title">@yield('title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- User Dropdown -->
        <div class="relative">
            <button id="user-menu-btn" class="flex items-center gap-2 focus:outline-none hover:opacity-80 transition">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200" id="user-avatar-initials">
                    U
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-700 leading-none" id="user-name-display">User Name</p>
                    <p class="text-xs text-gray-500 mt-1" id="user-role-display">Role</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Dropdown menu -->
            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200">
                <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                    <p class="text-sm font-medium text-gray-700" id="user-name-mobile">User Name</p>
                    <p class="text-xs text-gray-500" id="user-role-mobile">Role</p>
                </div>
                <button id="btn-logout" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
            </div>
        </div>
    </div>
</header>

<script>
    // Note: The actual logic for #btn-logout will be in resources/js/pages/dashboard/index.js or similar
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('user-menu-btn');
        const menu = document.getElementById('user-menu');
        if(btn && menu) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => {
                menu.classList.add('hidden');
            });
        }
    });
</script>
