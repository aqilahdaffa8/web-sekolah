<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - SMK Nusantara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center font-sans">

    <div class="max-w-md w-full px-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-700 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4 shadow-lg">
                S
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Sistem Informasi SMK</h1>
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
            &copy; {{ date('Y') }} SMK Nusantara.
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
</body>
</html>
