@extends('layouts.dashboard')
@section('title', 'Input Nilai Siswa')
@section('content')
<div class="mb-6"><h1 class="text-2xl font-black text-gray-900">Input Nilai Siswa</h1><p class="text-gray-500 mt-1">Input dan kelola data penilaian akademik siswa.</p></div>
<div class="card mb-5 p-4 flex flex-wrap gap-3">
    <select id="g-class" class="form-select w-auto text-sm py-2"><option value="">Semua Kelas</option></select>
    <select id="g-subject" class="form-select w-auto text-sm py-2"><option value="">Semua Mata Pelajaran</option></select>
    <input type="search" id="g-search" class="form-input py-2 text-sm flex-1 min-w-[200px]" placeholder="Cari siswa...">
    <button onclick="openGradeModal()" class="btn-primary btn-sm">Input Nilai</button>
</div>
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>Nama Siswa</th><th>Kelas</th><th>Mata Pelajaran</th><th>Nilai Tugas</th><th>Nilai UTS</th><th>Nilai UAS</th><th>Nilai Akhir</th><th>Aksi</th></tr></thead>
            <tbody id="grades-tbody">
                @for($i=0;$i<5;$i++)<tr>@for($j=0;$j<8;$j++)<td><div class="skeleton h-4 w-16 rounded"></div></td>@endfor</tr>@endfor
            </tbody>
        </table>
    </div>
    <div id="grades-pagination" class="p-4 border-t border-gray-100"></div>
</div>
<div id="grade-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header"><h3 class="text-lg font-bold">Input Nilai</h3><button data-modal-close class="btn-icon text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg></button></div>
        <div class="modal-body space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div><label class="form-label">Nama Siswa *</label><input type="text" id="g-student" class="form-input"></div>
                <div><label class="form-label">Kelas *</label><input type="text" id="g-class-input" class="form-input" placeholder="X TKJ 1"></div>
                <div class="col-span-2"><label class="form-label">Mata Pelajaran *</label><input type="text" id="g-subject-input" class="form-input"></div>
                <div><label class="form-label">Nilai Tugas</label><input type="number" id="g-task" class="form-input" min="0" max="100" placeholder="0-100"></div>
                <div><label class="form-label">Nilai UTS</label><input type="number" id="g-uts" class="form-input" min="0" max="100" placeholder="0-100"></div>
                <div><label class="form-label">Nilai UAS</label><input type="number" id="g-uas" class="form-input" min="0" max="100" placeholder="0-100"></div>
                <div><label class="form-label">Semester</label><select id="g-semester" class="form-select"><option value="1">Ganjil</option><option value="2">Genap</option></select></div>
            </div>
        </div>
        <div class="modal-footer"><button data-modal-close class="btn-ghost">Batal</button><button id="btn-save-grade" onclick="saveGrade()" class="btn-primary">Simpan</button></div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

async function loadGrades(page=1) {
    try {
        const res = await window.guruApi.grades({ page, per_page: 15, search: document.getElementById('g-search').value });
        const grades = res.data || [];
        document.getElementById('grades-tbody').innerHTML = grades.length ? grades.map(g => `
            <tr><td class="font-medium">${g.student_name}</td><td>${g.class}</td><td>${g.subject}</td>
            <td>${g.task_score ?? '-'}</td><td>${g.uts_score ?? '-'}</td><td>${g.uas_score ?? '-'}</td>
            <td class="font-bold text-brand-700">${g.final_score ?? '-'}</td>
            <td><button onclick="editGrade(${JSON.stringify(g).replace(/"/g,'&quot;')})" class="btn-secondary btn-sm">Edit</button></td></tr>
        `).join('') : '<tr><td colspan="8" class="text-center py-12 text-gray-400">Belum ada data nilai.</td></tr>';
        window.utils.renderPagination('grades-pagination', res.meta, loadGrades);
    } catch(err) { window.toast.apiError(err); }
}

window.openGradeModal = () => { ['g-student','g-class-input','g-subject-input','g-task','g-uts','g-uas'].forEach(id=>document.getElementById(id).value=''); window.openModal(document.getElementById('grade-modal')); };
window.editGrade = (g) => { document.getElementById('g-student').value=g.student_name; document.getElementById('g-class-input').value=g.class; document.getElementById('g-subject-input').value=g.subject; document.getElementById('g-task').value=g.task_score||''; document.getElementById('g-uts').value=g.uts_score||''; document.getElementById('g-uas').value=g.uas_score||''; window.openModal(document.getElementById('grade-modal')); };
window.saveGrade = async () => {
    const btn = document.getElementById('btn-save-grade');
    const payload = { student_name: document.getElementById('g-student').value, class: document.getElementById('g-class-input').value, subject: document.getElementById('g-subject-input').value, task_score: +document.getElementById('g-task').value||null, uts_score: +document.getElementById('g-uts').value||null, uas_score: +document.getElementById('g-uas').value||null, semester: document.getElementById('g-semester').value };
    try { window.utils.setButtonLoading(btn,true); await window.guruApi.upsertGrades(payload); window.toast.success('Nilai disimpan.'); window.closeModal(document.getElementById('grade-modal')); loadGrades(); } catch(err){window.toast.apiError(err);}finally{window.utils.setButtonLoading(btn,false,'Simpan');}
};
loadGrades();

});
</script>
@endpush
