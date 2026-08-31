@extends('layouts.dashboard')

@section('title', 'Memuat Dashboard...')

@section('content')
<div class="flex items-center justify-center h-full min-h-[400px]">
    <div class="text-center">
        <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-500 font-medium">Memuat profil dan mengatur ruang kerja Anda...</p>
    </div>
</div>

<script type="module">
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            // Ensure we are logged in and have user data
            const user = window.auth.getUser();
            if (!user) {
                // Try fetching from API if not in local storage
                await window.api.get('/auth/me');
            }
            
            const roles = window.auth.getRoles();
            
            // Redirect based on role hierarchy
            if (roles.includes('Super Admin')) {
                window.location.replace('/dashboard/super-admin/users');
            } else if (roles.includes('Hubin')) {
                window.location.replace('/dashboard/hubin/dudi-partners');
            } else if (roles.includes('Koperasi')) {
                window.location.replace('/dashboard/koperasi/products');
            } else if (roles.includes('Guru')) {
                window.location.replace('/dashboard/guru/grades');
            } else if (roles.includes('Eskul')) {
                window.location.replace('/dashboard/eskul/extracurriculars');
            } else {
                window.utils.showToast('Role tidak dikenali. Hubungi administrator.', 'error');
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
            window.location.href = '/login';
        }
    });
</script>
@endsection
