@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')

{{-- Welcome header --}}
<div class="mb-8">
    <h1 class="text-2xl font-black text-gray-900" id="dash-welcome">Selamat Datang, Admin!</h1>
    <p class="text-gray-500 mt-1">Ringkasan aktivitas dan data sekolah hari ini.</p>
</div>

{{-- Stats cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    @foreach([
        ['id' => 'ds-students', 'label' => 'Total Siswa',    'color' => 'bg-brand-500',  'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
        ['id' => 'ds-orders',  'label' => 'Pesanan Hari Ini','color' => 'bg-gold-500',   'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z'],
        ['id' => 'ds-jobs',    'label' => 'Loker Aktif',     'color' => 'bg-success',     'icon' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z'],
        ['id' => 'ds-eskul',   'label' => 'Pendaftar Eskul', 'color' => 'bg-purple-500', 'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z'],
    ] as $card)
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 {{ $card['color'] }} rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-gray-900" id="{{ $card['id'] }}">—</p>
        <p class="text-sm text-gray-500 mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Main grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Latest Orders --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Pesanan Terbaru</h2>
                <a href="/dashboard/koperasi/pesanan" class="text-sm text-brand-700 hover:text-brand-900 font-medium">Lihat Semua →</a>
            </div>
            <div id="recent-orders" class="divide-y divide-gray-50">
                @for($i=0;$i<4;$i++)
                <div class="px-5 py-4 flex items-center gap-3">
                    <div class="skeleton w-9 h-9 rounded-full flex-shrink-0"></div>
                    <div class="flex-1 space-y-1.5">
                        <div class="skeleton h-4 w-1/2 rounded"></div>
                        <div class="skeleton h-3 w-1/3 rounded"></div>
                    </div>
                    <div class="skeleton h-6 w-16 rounded-full"></div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="space-y-4">
        <div class="card p-5">
            <h2 class="font-bold text-gray-900 mb-4">Akses Cepat</h2>
            <div class="space-y-2" id="quick-links">
                {{-- Filled by JS based on role --}}
            </div>
        </div>

        {{-- School info --}}
        <div class="card p-5">
            <h2 class="font-bold text-gray-900 mb-3 text-sm">Info Server</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Versi PHP</span>
                    <span class="font-medium text-gray-900">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Laravel</span>
                    <span class="font-medium text-gray-900">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Tanggal</span>
                    <span class="font-medium text-gray-900">{{ now()->translatedFormat('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const user  = window.auth.getUser();
const roles = window.auth.getRoles();

// Welcome message
if (user) {
    const el = document.getElementById('dash-welcome');
    if (el) el.textContent = `Selamat Datang, ${user.name}!`;
}

async function loadStats() {
    try {
        const res = await window.api.get('/dashboard/stats');
        const stats = res.data?.data || res.data || {};
        const elStudents = document.getElementById('ds-students');
        const elOrders   = document.getElementById('ds-orders');
        const elJobs     = document.getElementById('ds-jobs');
        const elEskul    = document.getElementById('ds-eskul');

        if (elStudents) elStudents.textContent = stats.students_count || '1.250';
        if (elOrders)   elOrders.textContent   = stats.orders_count   || '0';
        if (elJobs)     elJobs.textContent     = stats.jobs_count     || '0';
        if (elEskul)    elEskul.textContent    = stats.eskul_count    || '0';
    } catch (err) {
        console.error('Failed to load stats', err);
    }
}
loadStats();

// Recent orders
async function loadRecentOrders() {
    try {
        const res = await window.api.get('/admin/koperasi/orders?per_page=5');
        const orders = res.data?.data || res.data || [];
        document.getElementById('recent-orders').innerHTML = orders.length ? orders.map(o => `
            <div class="px-5 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-brand-100 rounded-full flex items-center justify-center flex-shrink-0 text-brand-700 font-bold text-sm">
                    ${(o.customer_name || '?')[0].toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">${o.customer_name}</p>
                    <p class="text-xs text-gray-400">${o.product?.product_name ?? o.product?.name ?? 'Produk'} · ${window.utils.formatCurrency(o.total_price)}</p>
                </div>
                <span class="badge-${o.status === 'pending' ? 'warning' : o.status === 'done' ? 'success' : 'info'} flex-shrink-0">
                    ${o.status}
                </span>
            </div>
        `).join('') : '<p class="text-center text-gray-400 py-8 text-sm">Belum ada pesanan.</p>';
    } catch (_) {}
}

// Quick links based on role
function renderQuickLinks() {
    const links = [
        { href: '/dashboard', label: 'Dashboard', icon: '🏠', always: true },
    ];

    if (window.auth.hasRole('Super Admin')) {
        links.push(
            { href: '/dashboard/admin/users',  label: 'Manajemen User' },
            { href: '/dashboard/admin/konten', label: 'Kelola Konten' },
        );
    }
    if (window.auth.hasRole('Hubin') || window.auth.hasRole('Super Admin')) {
        links.push({ href: '/dashboard/hubin/loker', label: 'Kelola Loker' });
    }
    if (window.auth.hasRole('Koperasi') || window.auth.hasRole('Super Admin')) {
        links.push({ href: '/dashboard/koperasi/pesanan', label: 'Pesanan Masuk' });
    }
    if (window.auth.hasRole('Guru') || window.auth.hasRole('Super Admin')) {
        links.push({ href: '/dashboard/guru/nilai', label: 'Input Nilai' });
    }

    document.getElementById('quick-links').innerHTML = links.map(l => `
        <a href="${l.href}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                  hover:bg-brand-50 hover:text-brand-700 text-gray-700 transition-colors group">
            <span class="text-base">${l.icon ?? '→'}</span>
            <span class="font-medium">${l.label}</span>
            <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </a>
    `).join('');
}

loadStats();
loadRecentOrders();
renderQuickLinks();
</script>
@endpush
