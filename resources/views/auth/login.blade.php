<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet"/>
</head>
<body class="min-h-screen bg-hero-gradient flex items-center justify-center p-4">

{{-- Background pattern --}}
<div class="absolute inset-0 bg-[url('/images/pattern-dots.svg')] bg-[length:28px] opacity-20"></div>

<div class="relative z-10 w-full max-w-md">

    {{-- Card --}}
    <div class="card shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-brand-gradient p-8 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white">Portal Admin SMKN 1 Katapang</h1>
            <p class="text-white/70 text-sm mt-1">Masuk ke dashboard manajemen sekolah</p>
        </div>

        {{-- Form --}}
        <div class="p-8">

            {{-- Alert --}}
            <div id="login-alert" class="hidden mb-5 p-4 bg-danger-light border border-danger/20 rounded-xl text-sm text-danger flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span id="login-alert-text"></span>
            </div>

            <form id="login-form" class="space-y-5" novalidate>

                <div>
                    <label class="form-label" for="email">Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <input type="email" id="email" name="email" class="form-input pl-10" placeholder="admin@smk.sch.id" autocomplete="email" required>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <input type="password" id="password" name="password" class="form-input pl-10 pr-10" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" id="toggle-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                aria-label="Tampilkan sandi">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btn-login" class="btn btn-primary w-full btn-lg">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-brand-700 transition-colors">
                    ← Kembali ke Website
                </a>
            </div>
        </div>
    </div>

    <p class="text-center text-white/40 text-xs mt-6">
        &copy; {{ date('Y') }} SMKN 1 Katapang. Akses terbatas untuk personel sekolah.
    </p>
</div>

{{-- Toast --}}
<div id="toast-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Helper to determine destination route from roles
    function getDestination(roles = []) {
        if (roles.includes('Super Admin')) return '/dashboard';
        if (roles.includes('Hubin'))      return '/dashboard/hubin/mitra';
        if (roles.includes('Koperasi'))   return '/dashboard/koperasi/produk';
        if (roles.includes('Guru'))       return '/dashboard/guru/nilai';
        if (roles.includes('Eskul'))      return '/dashboard/eskul/kelola';
        return '/dashboard';
    }

    // Redirect if already logged in
    const existingToken = localStorage.getItem('smk_token');
    if (existingToken) {
        try {
            const roles = JSON.parse(localStorage.getItem('smk_roles') || '[]');
            window.location.href = getDestination(roles);
            return;
        } catch (_) {
            window.location.href = '/dashboard';
            return;
        }
    }

    // Password toggle
    const toggleBtn = document.getElementById('toggle-password');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const input = document.getElementById('password');
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
        });
    }

    // Login form
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn   = document.getElementById('btn-login');
            const alert = document.getElementById('login-alert');
            const alertText = document.getElementById('login-alert-text');
            const email    = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            alert.classList.add('hidden');

            if (!email || !password) {
                alertText.textContent = 'Email dan kata sandi wajib diisi.';
                alert.classList.remove('hidden');
                return;
            }

            // Safe button loading state
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="w-4 h-4 animate-spin inline-block mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Memverifikasi...
            `;

            try {
                // If window.auth.login is available, use it
                if (window.auth && typeof window.auth.login === 'function') {
                    await window.auth.login(email, password);
                    return;
                }

                // Resilient direct fetch fallback
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    const msg = data.message
                        || data.errors?.email?.[0]
                        || data.errors?.password?.[0]
                        || 'Email atau kata sandi salah.';
                    throw new Error(msg);
                }

                // Save session
                const rawRoles = data.roles || data.user?.roles || [];
                const roles = rawRoles.map(r => (typeof r === 'object' ? (r.role_name || r.name) : r));
                const permissions = data.permissions || data.user?.permissions || [];

                localStorage.setItem('smk_token', data.token || data.access_token);
                localStorage.setItem('smk_user', JSON.stringify(data.user));
                localStorage.setItem('smk_roles', JSON.stringify(roles));
                localStorage.setItem('smk_permissions', JSON.stringify(permissions));

                // Redirect to role dashboard
                window.location.href = getDestination(roles);
            } catch (err) {
                alertText.textContent = err.message || 'Email atau kata sandi salah.';
                alert.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = originalText || 'Masuk ke Dashboard';
            }
        });
    }
});
</script>
</body>
</html>
