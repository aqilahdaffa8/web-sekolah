@extends('layouts.dashboard')
<<<<<<< HEAD
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
=======

@section('title', 'Nilai Siswa')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Nilai Siswa</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data nilai siswa berdasarkan mata pelajaran.</p>
    </div>
    <button class="btn btn-primary" onclick="openFormModal()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Input Nilai
    </button>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <input type="text" id="search-input" class="form-input w-full max-w-sm" placeholder="Cari nama siswa...">
    </div>

    <x-ui.table id="grades-table" :headers="['Siswa', 'Mata Pelajaran', 'Teori', 'Praktik', 'UKK', 'PKL', 'Aksi']">
        {{-- Will be populated via JS --}}
    </x-ui.table>

    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="grade-modal" title="Input Nilai Siswa">
    <form id="grade-form">
        <input type="hidden" id="grade_id" name="grade_id">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Siswa</label>
                <select id="student_id" name="student_id" class="form-input w-full" required>
                    <option value="">-- Pilih Siswa --</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                <select id="subject_id" name="subject_id" class="form-input w-full" required>
                    <option value="">-- Pilih Mapel --</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Teori</label>
                    <input type="number" step="0.01" max="100" min="0" id="theory_score" name="theory_score" class="form-input w-full" value="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Praktik</label>
                    <input type="number" step="0.01" max="100" min="0" id="practice_score" name="practice_score" class="form-input w-full" value="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai UKK</label>
                    <input type="number" step="0.01" max="100" min="0" id="ukk_score" name="ukk_score" class="form-input w-full" value="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai PKL</label>
                    <input type="number" step="0.01" max="100" min="0" id="pkl_score" name="pkl_score" class="form-input w-full" value="0">
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="btn btn-secondary" data-modal-close="grade-modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btn-save-grade">Simpan</button>
        </div>
    </form>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('grades-table');
        const loading = document.getElementById('grades-table-loading');
        const empty = document.getElementById('grades-table-empty');
        const modal = document.getElementById('grade-modal');
        const form = document.getElementById('grade-form');
        const searchInput = document.getElementById('search-input');
        
        let grades = [];
        let masterData = { students: [], subjects: [] };

        const loadMasterData = async () => {
            try {
                masterData = await window.api.get('/guru/master-data');
                const studentSelect = document.getElementById('student_id');
                masterData.students.forEach(s => {
                    studentSelect.add(new Option(s.name, s.id));
                });
                const subjectSelect = document.getElementById('subject_id');
                masterData.subjects.forEach(s => {
                    subjectSelect.add(new Option(s.name, s.id));
                });
            } catch (e) {
                console.error('Gagal memuat master data', e);
            }
        };

        window.openFormModal = (id = null) => {
            form.reset();
            document.getElementById('grade_id').value = '';
            
            if (id) {
                const grade = grades.find(g => g.id === id);
                if (grade) {
                    document.getElementById('grade_id').value = grade.id;
                    document.getElementById('student_id').value = grade.student_id;
                    document.getElementById('subject_id').value = grade.subject_id;
                    document.getElementById('theory_score').value = grade.theory_score;
                    document.getElementById('practice_score').value = grade.practice_score;
                    document.getElementById('ukk_score').value = grade.ukk_score;
                    document.getElementById('pkl_score').value = grade.pkl_score;
                }
            }
            modal.classList.remove('hidden');
        };

        const loadGrades = async () => {
            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await window.api.get('/guru/grades');
                grades = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!grades || grades.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderTable(grades);
            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat data nilai', 'error');
            }
        };

        const renderTable = (data) => {
            let rows = '';
            data.forEach(grade => {
                const studentName = grade.student ? grade.student.name : `Siswa #${grade.student_id}`;
                const subjectName = grade.subject ? grade.subject.name : `Mapel #${grade.subject_id}`;
                rows += `
                    <tr>
                        <td class="font-medium text-gray-900">${studentName}</td>
                        <td class="text-gray-600">${subjectName}</td>
                        <td class="text-gray-600">${grade.theory_score ?? '-'}</td>
                        <td class="text-gray-600">${grade.practice_score ?? '-'}</td>
                        <td class="text-gray-600">${grade.ukk_score ?? '-'}</td>
                        <td class="text-gray-600">${grade.pkl_score ?? '-'}</td>
                        <td>
                            <div class="flex gap-2">
                                <button onclick="openFormModal(${grade.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            if (tbody) tbody.innerHTML = rows;
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-grade');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const payload = {
                student_id: document.getElementById('student_id').value,
                subject_id: document.getElementById('subject_id').value,
                theory_score: document.getElementById('theory_score').value || null,
                practice_score: document.getElementById('practice_score').value || null,
                ukk_score: document.getElementById('ukk_score').value || null,
                pkl_score: document.getElementById('pkl_score').value || null,
            };

            try {
                await window.api.post('/guru/grades', payload);
                window.showToast('Data nilai berhasil disimpan', 'success');
                modal.classList.add('hidden');
                loadGrades();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal menyimpan nilai', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        });

        loadMasterData();
        loadGrades();
    });
</script>
@endsection
>>>>>>> dbce877d0f289c11f00a4851f99f3a28482b2d43
