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
        <!-- Dynamic menus injected by JS -->
        <div id="role-nav-items"></div>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 text-xs text-center text-gray-500 sidebar-text">
        &copy; {{ date('Y') }} SMKN 1 KATAPANG
    </div>
</aside>

<script>
(function () {
    const currentPath = window.location.pathname;

    const isActive = (href) => currentPath.startsWith(href) ? 'active' : '';

    const navIcon = {
        users:        '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
        students:     '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14.25a4.125 4.125 0 100-8.25 4.125 4.125 0 000 8.25zM4.5 20.25a7.5 7.5 0 0115 0"></path></svg>',
        roles:        '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
        banners:      '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
        menus:        '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>',
        posts:        '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>',
        activityLogs: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
        dudiPartners: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
        jobVacancies: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
        tracerStudy:  '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
        products:     '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
        orders:       '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
        grades:       '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>',
        modules:      '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
        facilities:   '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
        extracurriculars: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>',
        achievements: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>',
        registrations: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>',
    };

    const navConfig = {
        'Super Admin': [
            { label: 'Super Admin', type: 'group' },
            { label: 'Pengguna',     href: '/dashboard/super-admin/users',         icon: 'users' },
            { label: 'Data Siswa',   href: '/dashboard/super-admin/students',      icon: 'students' },
            { label: 'Role & Akses', href: '/dashboard/super-admin/roles',         icon: 'roles' },
            { label: 'Banner',       href: '/dashboard/super-admin/banners',       icon: 'banners' },
            { label: 'Menu Navigasi',href: '/dashboard/super-admin/menus',         icon: 'menus' },
            { label: 'Berita',       href: '/dashboard/super-admin/posts',         icon: 'posts' },
            { label: 'Log Aktivitas',href: '/dashboard/super-admin/activity-logs',icon: 'activityLogs' },
        ],
        'Hubin': [
            { label: 'Hubungan Industri', type: 'group' },
            { label: 'DUDI & Mitra',  href: '/dashboard/hubin/dudi-partners',  icon: 'dudiPartners' },
            { label: 'Lowongan Kerja',href: '/dashboard/hubin/job-vacancies',  icon: 'jobVacancies' },
            { label: 'Tracer Study',  href: '/dashboard/hubin/tracer-studies', icon: 'tracerStudy' },
        ],
        'Koperasi': [
            { label: 'Koperasi & TeFA', type: 'group' },
            { label: 'Produk',  href: '/dashboard/koperasi/products', icon: 'products' },
            { label: 'Pesanan', href: '/dashboard/koperasi/orders',   icon: 'orders' },
        ],
        'Guru': [
            { label: 'Akademik', type: 'group' },
            { label: 'Nilai Siswa',    href: '/dashboard/guru/grades',            icon: 'grades' },
            { label: 'Modul Belajar',  href: '/dashboard/guru/learning-modules',  icon: 'modules' },
            { label: 'Fasilitas',      href: '/dashboard/guru/facilities',         icon: 'facilities' },
        ],
        'Eskul': [
            { label: 'Ekstrakurikuler', type: 'group' },
            { label: 'Eskul',          href: '/dashboard/eskul/extracurriculars', icon: 'extracurriculars' },
            { label: 'Pendaftaran',    href: '/dashboard/eskul/registrations',    icon: 'registrations' },
            { label: 'Prestasi',       href: '/dashboard/eskul/achievements',     icon: 'achievements' },
        ],
        'Siswa': [
            { label: 'Siswa', type: 'group' },
            { label: 'Lihat Nilai',    href: '/siswa/nilai',    icon: 'grades' },
        ],
    };

    function buildNav() {
        const container = document.getElementById('role-nav-items');
        if (!container) return;

        let userRoles = [];
        try {
            const stored = localStorage.getItem('smk_user');
            if (stored) {
                const user = JSON.parse(stored);
                userRoles = (user.roles || []).map(r => r.role_name || r);
            }
        } catch (e) { /* no-op */ }

        let html = '';
        userRoles.forEach(role => {
            const items = navConfig[role];
            if (!items) return;

            items.forEach(item => {
                if (item.type === 'group') {
                    html += `<p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider sidebar-text">${item.label}</p>`;
                } else {
                    const active = currentPath.startsWith(item.href) ? 'active' : '';
                    html += `
                        <a href="${item.href}" class="sidebar-nav-item ${active}">
                            ${navIcon[item.icon] || ''}
                            <span class="sidebar-text">${item.label}</span>
                        </a>`;
                }
            });
        });

        container.innerHTML = html;
    }

    // Run immediately so sidebar is populated before paint
    document.addEventListener('DOMContentLoaded', buildNav);
})();
</script>
