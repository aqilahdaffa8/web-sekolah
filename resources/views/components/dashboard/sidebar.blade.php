<aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-screen sticky top-0 transition-all duration-300 z-30" id="sidebar">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200 shrink-0">
        <a href="/dashboard" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-700 rounded flex items-center justify-center text-white font-bold text-lg">
                S
            </div>
            <span class="font-bold text-gray-900 text-lg tracking-wide hidden sm:block sidebar-text">Dashboard</span>
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1" id="sidebar-nav">
        <!-- Dashboard Home -->
        <a href="/dashboard" class="sidebar-nav-item" data-menu="dashboard">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- Menus will be injected here via JS based on user role/permissions -->
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 text-xs text-center text-gray-500 sidebar-text">
        &copy; {{ date('Y') }} SMK Nusantara
    </div>
</aside>
