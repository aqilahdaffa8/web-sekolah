@extends('layouts.public')

@section('title', 'Lihat Nilai Saya')

@section('content')
<main class="min-h-[70vh] bg-gray-50 py-16">
    <div class="mx-auto w-full max-w-lg px-4 sm:px-6">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold text-brand-700">Portal Siswa</p>
            <h1 class="mt-2 text-2xl font-black text-gray-900">Lihat Nilai Mata Pelajaran</h1>
            <p class="mt-2 text-sm leading-6 text-gray-500">Masukkan nama lengkap dan NIS yang terdaftar untuk melihat nilai Anda.</p>

            <form id="grade-verify-form" class="mt-6 space-y-4">
                <div><label for="student-name" class="form-label">Nama Lengkap</label><input id="student-name" class="form-input" required autocomplete="name"></div>
                <div><label for="student-nis" class="form-label">NIS</label><input id="student-nis" class="form-input" required autocomplete="off"></div>
                <button class="btn btn-primary w-full" type="submit">Verifikasi dan Lihat Nilai</button>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.auth.requireRole('Siswa', '/login?redirect=/siswa/nilai')) return;

    document.getElementById('grade-verify-form').addEventListener('submit', async event => {
        event.preventDefault();
        const payload = { name: document.getElementById('student-name').value.trim(), nis: document.getElementById('student-nis').value.trim() };
        try {
            const result = await window.studentApi.grades(payload);
            sessionStorage.setItem('student_grade_result', JSON.stringify(result));
            sessionStorage.setItem('student_grade_identity', JSON.stringify(payload));
            window.location.href = '/siswa/nilai/hasil';
        } catch (error) { window.toast.apiError(error); }
    });
});
</script>
@endpush
