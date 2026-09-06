<!DOCTYPE html>
<html lang="id">
<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet"/>
</head>
<body class="min-h-screen bg-hero-gradient flex items-center justify-center p-4">

{{-- Background pattern --}}
<div class="absolute inset-0 opacity-10"
     style="background-image: url('/images/pattern-dots.svg'); background-size: 28px;"></div>

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

                <button type="submit" id="btn-login" class="btn-primary w-full btn-lg">
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
    // Redirect if already logged in
    if (localStorage.getItem('smk_token')) {
        window.location.href = window.auth.getDashboardRoute();
    }

    // Password toggle
    document.getElementById('toggle-password').addEventListener('click', () => {
        const input = document.getElementById('password');
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
    });

    // Login form
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn   = document.getElementById('btn-login');
        const alert = document.getElementById('login-alert');
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        alert.classList.add('hidden');

        if (!email || !password) {
            document.getElementById('login-alert-text').textContent = 'Email dan kata sandi wajib diisi.';
            alert.classList.remove('hidden');
            return;
        }

        try {
            window.utils.setButtonLoading(btn, true, 'Memverifikasi...');
            // login() stores session & redirects; execution won't reach below on success
            await window.auth.login(email, password);
        } catch (err) {
            const msg = err.message
                || err.errors?.email?.[0]
                || err.errors?.password?.[0]
                || 'Email atau kata sandi salah.';
            document.getElementById('login-alert-text').textContent = msg;
            alert.classList.remove('hidden');
            window.utils.setButtonLoading(btn, false, 'Masuk ke Dashboard');
        }
    });
});
</script>
=======
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - SMKN 1 KATAPANG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center font-sans">

    <div class="max-w-md w-full px-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-700 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4 shadow-lg">
                S
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Sistem Informasi SMKN 1 KATAPANG</h1>
            <p class="text-gray-500 mt-2">Login ke Dashboard Admin</p>
        </div>

        <div class="card p-8 shadow-xl border-t-4 border-blue-700">
            <form id="login-form">
                <x-ui.form-input 
                    type="email" 
                    name="email" 
                    label="Email Address" 
                    required="true" 
                    placeholder="admin@smknusantara.sch.id" 
                />
                
                <div class="mb-6">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="form-input" 
                        placeholder="••••••••"
                    />
                    <div id="password-error" class="form-error hidden"></div>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full py-2.5 text-base" id="btn-login">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>
        
        <p class="text-center text-sm text-gray-500 mt-6">
            &copy; {{ date('Y') }} SMKN 1 KATAPANG.
        </p>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col items-end"></div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('login-form');
            const btn = document.getElementById('btn-login');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = form.email.value;
                const password = form.password.value;

                // Reset errors
                document.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));
                document.querySelectorAll('.form-error').forEach(el => el.classList.add('hidden'));

                btn.disabled = true;
                btn.innerHTML = 'Loading...';

                try {
                    // Call the auth module we created
                    const response = await window.auth.login(email, password);
                    
                    window.showToast('Login berhasil! Mengalihkan...', 'success');
                    
                    // Redirect based on role (or just to dashboard router)
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);

                } catch (error) {
                    btn.disabled = false;
                    btn.innerHTML = 'Masuk ke Dashboard';
                    
                    if (error.status === 422 && error.errors) {
                        // Validation errors
                        Object.keys(error.errors).forEach(key => {
                            const input = document.getElementById(key);
                            const errDiv = document.getElementById(`${key}-error`);
                            if (input && errDiv) {
                                input.classList.add('error');
                                errDiv.textContent = error.errors[key][0];
                                errDiv.classList.remove('hidden');
                            }
                        });
                    } else if (error.status === 429) {
                        window.showToast('Terlalu banyak percobaan. Coba lagi nanti.', 'error');
                    } else {
                        window.showToast(error.message || 'Login gagal. Periksa kredensial Anda.', 'error');
                    }
                }
            });
        });
    </script>
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
</body>
</html>
