@extends('layouts.public')

@section('title', 'Hasil Nilai Saya')

@section('content')
<main class="min-h-[70vh] bg-gray-50 py-12">
    <div class="mx-auto w-full max-w-6xl px-4 sm:px-6">
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-semibold text-brand-700">Portal Siswa</p><h1 class="mt-1 text-2xl font-black text-gray-900">Hasil Nilai Mata Pelajaran</h1><p id="student-identity" class="mt-2 text-sm text-gray-500"></p></div><a href="/siswa/nilai" class="btn btn-ghost">Ganti Data</a></div>
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-50 text-left text-gray-500"><tr><th class="px-5 py-4 font-semibold">Mata Pelajaran</th><th class="px-4 py-4 font-semibold">Teori</th><th class="px-4 py-4 font-semibold">Praktik</th><th class="px-4 py-4 font-semibold">UKK</th><th class="px-4 py-4 font-semibold">PKL</th><th class="px-5 py-4 font-semibold">Nilai Akhir</th></tr></thead><tbody id="grade-results" class="divide-y divide-gray-100"></tbody></table></div><div class="border-t border-gray-100 p-5"><button id="download-grades" class="btn btn-primary">Unduh Nilai PDF</button></div></div>
    </div>
</main>
@endsection

@push('scripts')
<script>
const escapeValue = value => String(value ?? '-').replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
document.addEventListener('DOMContentLoaded', () => {
    if (!window.auth.requireRole('Siswa', '/login?redirect=/siswa/nilai')) return;
    const result = JSON.parse(sessionStorage.getItem('student_grade_result') || 'null');
    const identity = JSON.parse(sessionStorage.getItem('student_grade_identity') || 'null');
    if (!result || !identity) { window.location.href = '/siswa/nilai'; return; }
    document.getElementById('student-identity').textContent = `${result.student.name} · NIS ${result.student.nis} · ${result.student.class_name}`;
    document.getElementById('grade-results').innerHTML = result.grades.length ? result.grades.map(grade => `<tr><td class="px-5 py-4 font-semibold text-gray-900">${escapeValue(grade.subject)}</td><td class="px-4 py-4">${escapeValue(grade.theory_score)}</td><td class="px-4 py-4">${escapeValue(grade.practice_score)}</td><td class="px-4 py-4">${escapeValue(grade.ukk_score)}</td><td class="px-4 py-4">${escapeValue(grade.pkl_score)}</td><td class="px-5 py-4 font-bold text-brand-700">${escapeValue(grade.final_score)}</td></tr>`).join('') : '<tr><td colspan="6" class="px-5 py-10 text-center text-gray-500">Belum ada nilai yang diinput oleh guru.</td></tr>';
    document.getElementById('download-grades').addEventListener('click', async () => { try { const pdf = await window.studentApi.downloadGrades(identity); const url = URL.createObjectURL(pdf); const link = document.createElement('a'); link.href = url; link.download = `nilai-${result.student.nis}.pdf`; link.click(); URL.revokeObjectURL(url); } catch (error) { window.toast.apiError(error); } });
});
</script>
@endpush
