<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Masuk — SMKN 1 Katapang</title>
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
            <h1 class="text-2xl font-black text-white">Portal SMKN 1 Katapang</h1>
            <p class="text-white/70 text-sm mt-1">Masuk untuk siswa aktif atau staf sekolah</p>
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

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500 mb-2">Siswa aktif belum punya akun?</p>
                <button type="button" id="btn-open-activation" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl border border-brand-200 bg-brand-50/60 hover:bg-brand-50 text-brand-800 text-sm font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Aktivasi Akun Siswa Baru
                </button>
            </div>

            <div class="mt-4 text-center">
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

{{-- ═══════════════════════════════════
     MODAL AKTIVASI AKUN SISWA
═══════════════════════════════════ --}}
<div id="activation-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-md">
        <div class="modal-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center font-bold text-sm">🎓</div>
                <div>
                    <h3 class="text-lg font-bold text-brand-900">Aktivasi Akun Siswa</h3>
                    <p class="text-xs text-brand-600">Khusus siswa aktif dengan NIS resmi</p>
                </div>
            </div>
            <button type="button" data-modal-close class="btn-icon text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="modal-body space-y-4">
            {{-- Alert --}}
            <div id="act-alert" class="hidden p-3 bg-danger-light border border-danger/20 rounded-xl text-xs text-danger flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span id="act-alert-text"></span>
            </div>

            {{-- Step 1: Input NIS --}}
            <div>
                <label class="form-label" for="act-nis">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                <div class="flex gap-2">
                    <input type="text" id="act-nis" class="form-input" placeholder="Contoh: 20240010" required>
                    <button type="button" id="btn-check-nis" class="btn btn-secondary px-4 text-xs font-bold shrink-0">
                        Cek NIS
                    </button>
                </div>
            </div>

            {{-- Verified Student Info Card --}}
            <div id="act-student-info" class="hidden p-3.5 bg-brand-50/70 border border-brand-200 rounded-xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-gradient text-white flex items-center justify-center font-bold text-sm shrink-0" id="act-initials">DS</div>
                <div class="leading-tight">
                    <p class="text-[11px] text-brand-600 font-semibold uppercase tracking-wider">Data Siswa Ditemukan:</p>
                    <p class="text-sm font-bold text-brand-900" id="act-student-name">Nama Siswa</p>
                    <p class="text-xs text-brand-700" id="act-student-class">Kelas: -</p>
                </div>
            </div>

            {{-- Step 2: Credential Fields --}}
            <div id="act-credential-fields" class="hidden space-y-4 pt-2 border-t border-gray-100">
                <div>
                    <label class="form-label" for="act-email">Alamat Email Baru <span class="text-danger">*</span></label>
                    <input type="email" id="act-email" class="form-input" placeholder="nama@email.com atau nama@siswa.smk.sch.id" required>
                    <p class="text-[11px] text-gray-500 mt-1">Email ini akan digunakan saat masuk ke web sekolah.</p>
                </div>

                <div>
                    <label class="form-label" for="act-password">Kata Sandi Baru <span class="text-danger">*</span></label>
                    <input type="password" id="act-password" class="form-input" placeholder="Minimal 8 karakter" required>
                </div>

                <div>
                    <label class="form-label" for="act-password-conf">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                    <input type="password" id="act-password-conf" class="form-input" placeholder="Ulangi kata sandi baru" required>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-modal-close class="btn btn-ghost">Batal</button>
            <button type="button" id="btn-submit-activation" class="btn btn-primary" disabled>
                Aktifkan Akun
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Helper to determine destination route from roles
    function getDestination(roles = []) {
        const params = new URLSearchParams(window.location.search);
        const redirect = params.get('redirect');
        if (redirect && redirect.startsWith('/') && !redirect.startsWith('//')) {
            return redirect;
        }
        if (roles.includes('Siswa') && !roles.includes('Super Admin')) return '/eskul';
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

    // ── Aktivasi Akun Siswa ──────────────────────────────────────────
    const activationModal = document.getElementById('activation-modal');
    const btnOpenActivation = document.getElementById('btn-open-activation');
    const actNis = document.getElementById('act-nis');
    const btnCheckNis = document.getElementById('btn-check-nis');
    const actAlert = document.getElementById('act-alert');
    const actAlertText = document.getElementById('act-alert-text');
    const actStudentInfo = document.getElementById('act-student-info');
    const actInitials = document.getElementById('act-initials');
    const actStudentName = document.getElementById('act-student-name');
    const actStudentClass = document.getElementById('act-student-class');
    const actCredentialFields = document.getElementById('act-credential-fields');
    const actEmail = document.getElementById('act-email');
    const actPassword = document.getElementById('act-password');
    const actPasswordConf = document.getElementById('act-password-conf');
    const btnSubmitActivation = document.getElementById('btn-submit-activation');

    function resetActivationModal() {
        actNis.value = '';
        actEmail.value = '';
        actPassword.value = '';
        actPasswordConf.value = '';
        actAlert.classList.add('hidden');
        actAlertText.textContent = '';
        actStudentInfo.classList.add('hidden');
        actCredentialFields.classList.add('hidden');
        btnSubmitActivation.disabled = true;
    }

    if (btnOpenActivation && activationModal) {
        btnOpenActivation.addEventListener('click', () => {
            resetActivationModal();
            if (typeof window.openModal === 'function') {
                window.openModal(activationModal);
            } else {
                activationModal.classList.remove('hidden');
                activationModal.classList.add('flex');
            }
        });
    }

    if (btnCheckNis) {
        btnCheckNis.addEventListener('click', async () => {
            const nis = actNis.value.trim();
            actAlert.classList.add('hidden');
            actStudentInfo.classList.add('hidden');
            actCredentialFields.classList.add('hidden');
            btnSubmitActivation.disabled = true;

            if (!nis) {
                actAlertText.textContent = 'Silakan masukkan NIS Anda terlebih dahulu.';
                actAlert.classList.remove('hidden');
                return;
            }

            const originalBtn = btnCheckNis.innerHTML;
            btnCheckNis.disabled = true;
            btnCheckNis.textContent = 'Memeriksa...';

            try {
                const res = await fetch('/api/auth/student/check-nis', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ nis })
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || 'NIS tidak valid atau belum terdaftar.');
                }

                // Show student identity
                const student = data.student;
                actStudentName.textContent = student.name;
                actStudentClass.textContent = 'Kelas: ' + (student.class_name || '-');
                const initials = student.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
                actInitials.textContent = initials;
                actStudentInfo.classList.remove('hidden');

                // Suggest default school email if desired or let student type
                actEmail.value = `${student.nis}@siswa.smk.sch.id`;

                // Show credential fields & enable submit
                actCredentialFields.classList.remove('hidden');
                btnSubmitActivation.disabled = false;
            } catch (err) {
                actAlertText.textContent = err.message || 'Terjadi kesalahan saat memeriksa NIS.';
                actAlert.classList.remove('hidden');
            } finally {
                btnCheckNis.disabled = false;
                btnCheckNis.innerHTML = originalBtn;
            }
        });
    }

    if (btnSubmitActivation) {
        btnSubmitActivation.addEventListener('click', async () => {
            const nis = actNis.value.trim();
            const email = actEmail.value.trim();
            const password = actPassword.value;
            const passwordConfirmation = actPasswordConf.value;

            actAlert.classList.add('hidden');

            if (!email || !password || !passwordConfirmation) {
                actAlertText.textContent = 'Semua field wajib diisi.';
                actAlert.classList.remove('hidden');
                return;
            }

            if (password.length < 8) {
                actAlertText.textContent = 'Kata sandi minimal 8 karakter.';
                actAlert.classList.remove('hidden');
                return;
            }

            if (password !== passwordConfirmation) {
                actAlertText.textContent = 'Konfirmasi kata sandi tidak cocok.';
                actAlert.classList.remove('hidden');
                return;
            }

            const originalBtn = btnSubmitActivation.innerHTML;
            btnSubmitActivation.disabled = true;
            btnSubmitActivation.textContent = 'Mengaktifkan Akun...';

            try {
                const res = await fetch('/api/auth/student/activate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nis,
                        email,
                        password,
                        password_confirmation: passwordConfirmation,
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    const msg = data.message
                        || data.errors?.email?.[0]
                        || data.errors?.password?.[0]
                        || 'Gagal mengaktifkan akun.';
                    throw new Error(msg);
                }

                // Save session
                const rawRoles = data.roles || data.user?.roles || ['Siswa'];
                const roles = rawRoles.map(r => (typeof r === 'object' ? (r.role_name || r.name) : r));
                const permissions = data.permissions || data.user?.permissions || [];

                localStorage.setItem('smk_token', data.token || data.access_token);
                localStorage.setItem('smk_user', JSON.stringify(data.user));
                localStorage.setItem('smk_roles', JSON.stringify(roles));
                localStorage.setItem('smk_permissions', JSON.stringify(permissions));

                if (window.toast && typeof window.toast.success === 'function') {
                    window.toast.success('Aktivasi berhasil! Selamat datang.');
                }

                if (typeof window.closeModal === 'function') {
                    window.closeModal(activationModal);
                }

                // Redirect to eskul or destination
                window.location.href = getDestination(roles);
            } catch (err) {
                actAlertText.textContent = err.message || 'Terjadi kesalahan saat aktivasi akun.';
                actAlert.classList.remove('hidden');
                btnSubmitActivation.disabled = false;
                btnSubmitActivation.innerHTML = originalBtn;
            }
        });
    }
});
</script>
</body>
</html>
