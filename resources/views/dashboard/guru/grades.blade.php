@extends('layouts.dashboard')
@section('title', 'Input Nilai Siswa')

@section('breadcrumb')
<li class="breadcrumb-separator">/</li>
<li><span class="text-gray-900 font-medium">Input Nilai Siswa</span></li>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Input Nilai Siswa</h1>
        <p class="text-gray-500 mt-1">Kelola dan input nilai akademik siswa secara berjenjang per kelas dan mata pelajaran.</p>
    </div>
    <div class="flex gap-2">
        <button onclick="openGradeModal()" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Input Perorangan
        </button>
    </div>
</div>

{{-- Hierarchical Filter Bar --}}
<div class="card mb-6 p-5 bg-white shadow-sm border border-gray-100">
    <div class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        Filter Penilaian Berjenjang
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="form-label text-xs font-semibold text-gray-600" for="g-class">1. Pilih Kelas <span class="text-danger">*</span></label>
            <select id="g-class" class="form-select text-sm w-full">
                <option value="">-- Semua / Pilih Kelas --</option>
            </select>
        </div>
        <div>
            <label class="form-label text-xs font-semibold text-gray-600" for="g-subject">2. Pilih Mata Pelajaran <span class="text-danger">*</span></label>
            <select id="g-subject" class="form-select text-sm w-full">
                <option value="">-- Semua / Pilih Mapel --</option>
            </select>
        </div>
        <div>
            <label class="form-label text-xs font-semibold text-gray-600" for="g-search">Cari Nama / NIS</label>
            <input type="search" id="g-search" class="form-input text-sm w-full" placeholder="Ketik nama atau NIS siswa...">
        </div>
        <div class="flex items-end gap-2">
            <button type="button" id="btn-load-class" onclick="onFilterChange()" class="btn btn-primary w-full text-sm">
                Terapkan Filter
            </button>
            <button type="button" onclick="resetFilters()" class="btn btn-ghost text-sm px-3" title="Reset Filter">
                ↺
            </button>
        </div>
    </div>
    <div id="filter-hint" class="mt-3 text-xs text-brand-700 bg-brand-50/70 p-2.5 rounded-lg border border-brand-100 hidden">
        💡 Mode Penilaian Kelas aktif: Anda dapat menginput nilai semua siswa di kelas ini dan menyimpannya secara batch.
    </div>
</div>

{{-- Main Table Section --}}
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h2 id="table-heading" class="font-bold text-gray-900 text-base">Daftar Nilai Siswa</h2>
        <div class="flex items-center gap-2">
            <span id="grades-count" class="text-xs text-gray-500 font-medium">Memuat data...</span>
            <button type="button" id="btn-batch-save" onclick="saveBatchGrades()" class="btn btn-primary btn-sm hidden">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Semua Nilai (Batch)
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead id="grades-thead">
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Nilai Tugas</th>
                    <th>Nilai UTS</th>
                    <th>Nilai UAS</th>
                    <th>Nilai Akhir</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="grades-tbody">
                @for($i=0;$i<5;$i++)
                <tr>
                    <td colspan="9" class="p-4"><div class="skeleton h-5 w-full rounded"></div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="grades-pagination" class="p-4 border-t border-gray-100"></div>
</div>

{{-- Modal Input / Edit Perorangan --}}
<div id="grade-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <h3 id="grade-modal-title" class="text-lg font-bold text-gray-900">Input Nilai Siswa</h3>
            <button data-modal-close class="btn-icon text-gray-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body space-y-4">
            <input type="hidden" id="modal-grade-id">
            <div>
                <label class="form-label" for="m-student">Siswa <span class="text-danger">*</span></label>
                <select id="m-student" class="form-select">
                    <option value="">-- Pilih Siswa --</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="m-subject">Mata Pelajaran <span class="text-danger">*</span></label>
                <select id="m-subject" class="form-select">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                </select>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="form-label text-xs" for="m-practice">Tugas / Praktik</label>
                    <input type="number" id="m-practice" class="form-input" min="0" max="100" placeholder="0-100" oninput="calculateModalFinal()">
                </div>
                <div>
                    <label class="form-label text-xs" for="m-theory">UTS / Teori</label>
                    <input type="number" id="m-theory" class="form-input" min="0" max="100" placeholder="0-100" oninput="calculateModalFinal()">
                </div>
                <div>
                    <label class="form-label text-xs" for="m-ukk">UAS / UKK</label>
                    <input type="number" id="m-ukk" class="form-input" min="0" max="100" placeholder="0-100" oninput="calculateModalFinal()">
                </div>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Perkiraan Nilai Akhir:</span>
                <span id="modal-final-score" class="text-base font-black text-brand-700">-</span>
            </div>
        </div>
<<<<<<< Updated upstream
        <div class="modal-footer">
            <button type="button" data-modal-close class="btn btn-ghost">Batal</button>
            <button type="button" id="btn-save-grade" onclick="saveGradeModal()" class="btn btn-primary btn-save">Simpan Nilai</button>
        </div>
=======
<<<<<<< HEAD
        <div class="modal-footer"><button type="button" data-modal-close class="btn-ghost">Batal</button><button type="button" id="btn-save-grade" onclick="saveGrade()" class="btn btn-primary btn-save">Simpan</button></div>
>>>>>>> Stashed changes
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
document.addEventListener('DOMContentLoaded', async () => {
    let masterData = { classes: [], subjects: [], students: [] };
    let isClassMode = false;
    let currentClassStudents = [];

    // ── Load Master Data ──────────────────────────────────────────────────────
    async function loadMasterData() {
        try {
            const res = await window.guruApi.masterData();
            masterData = res || { classes: [], subjects: [], students: [] };

            const classSelect = document.getElementById('g-class');
            const subjectSelect = document.getElementById('g-subject');
            const modalStudent = document.getElementById('m-student');
            const modalSubject = document.getElementById('m-subject');

            // Populate Classes
            if (masterData.classes) {
                classSelect.innerHTML = '<option value="">-- Semua / Pilih Kelas --</option>' +
                    masterData.classes.map(c => `<option value="${c.id}">${c.name || c.class_name}</option>`).join('');
            }

            // Populate Subjects
            if (masterData.subjects) {
                const subOpts = masterData.subjects.map(s => `<option value="${s.id}">${s.name || s.subject_name}</option>`).join('');
                subjectSelect.innerHTML = '<option value="">-- Semua / Pilih Mapel --</option>' + subOpts;
                modalSubject.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>' + subOpts;
            }

            // Populate Students for Modal
            if (masterData.students) {
                modalStudent.innerHTML = '<option value="">-- Pilih Siswa --</option>' +
                    masterData.students.map(s => `<option value="${s.id}">${s.name} (NIS: ${s.nis || '-'})</option>`).join('');
            }
        } catch(err) {
            console.error('Failed to load guru master data', err);
        }
    }

    // ── Load Default Paginated Grades ─────────────────────────────────────────
    async function loadGrades(page = 1) {
        isClassMode = false;
        document.getElementById('filter-hint').classList.add('hidden');
        document.getElementById('btn-batch-save').classList.add('hidden');
        document.getElementById('table-heading').textContent = 'Daftar Nilai Siswa';

        const classId = document.getElementById('g-class').value;
        const subjectId = document.getElementById('g-subject').value;
        const search = document.getElementById('g-search').value;
        const countEl = document.getElementById('grades-count');
        countEl.textContent = 'Memuat data...';

        try {
            const res = await window.guruApi.grades({
                page,
                per_page: 15,
                class_id: classId || undefined,
                subject_id: subjectId || undefined,
                search: search || undefined
            });

            const list = res.data || [];
            countEl.textContent = `Total: ${res.total ?? list.length} data`;

            // Reset table header
            document.getElementById('grades-thead').innerHTML = `
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Nilai Tugas</th>
                    <th>Nilai UTS</th>
                    <th>Nilai UAS</th>
                    <th>Nilai Akhir</th>
                    <th class="text-right">Aksi</th>
                </tr>
            `;

            const tbody = document.getElementById('grades-tbody');
            if (!list.length) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-12 text-gray-400">Belum ada data nilai akademik. Silakan pilih kelas & mapel atau klik Input Perorangan.</td></tr>';
                document.getElementById('grades-pagination').innerHTML = '';
                return;
            }

            tbody.innerHTML = list.map(g => `
                <tr>
                    <td class="text-xs text-gray-500">${g.nis ?? '-'}</td>
                    <td class="font-semibold text-gray-900">${g.student_name ?? '-'}</td>
                    <td><span class="badge badge-gray">${g.class ?? '-'}</span></td>
                    <td><span class="font-medium text-brand-800">${g.subject ?? '-'}</span></td>
                    <td>${g.practice_score ?? g.task_score ?? '-'}</td>
                    <td>${g.theory_score ?? g.uts_score ?? '-'}</td>
                    <td>${g.ukk_score ?? g.uas_score ?? '-'}</td>
                    <td><span class="font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-lg">${g.final_score ?? '-'}</span></td>
                    <td class="text-right">
                        <button type="button" onclick="window.editGradeModal(${JSON.stringify(g).replace(/"/g, '&quot;')})" class="btn btn-secondary btn-sm">
                            Edit
                        </button>
                    </td>
                </tr>
            `).join('');

            if (res.meta) {
                window.utils.renderPagination('grades-pagination', res.meta, loadGrades);
            } else {
                document.getElementById('grades-pagination').innerHTML = '';
            }
        } catch(err) {
            countEl.textContent = 'Gagal memuat';
            window.toast.apiError(err);
        }
    }

    // ── Load Students By Class for Interactive Batch Grading ──────────────────
    async function loadClassGrading(classId, subjectId) {
        isClassMode = true;
        document.getElementById('filter-hint').classList.remove('hidden');
        document.getElementById('btn-batch-save').classList.remove('hidden');

        const className = document.getElementById('g-class').selectedOptions[0]?.text || '';
        const subjectName = document.getElementById('g-subject').selectedOptions[0]?.text || '';
        document.getElementById('table-heading').textContent = `Input Nilai: ${className} — ${subjectName}`;

        const countEl = document.getElementById('grades-count');
        countEl.textContent = 'Memuat siswa kelas...';

        try {
            const res = await window.guruApi.studentsByClass({ class_id: classId, subject_id: subjectId });
            currentClassStudents = res.students || [];
            countEl.textContent = `Total: ${currentClassStudents.length} siswa`;
            document.getElementById('grades-pagination').innerHTML = '';

            document.getElementById('grades-thead').innerHTML = `
                <tr>
                    <th class="w-24">NIS</th>
                    <th>Nama Siswa</th>
                    <th class="w-28">Tugas/Praktik (0-100)</th>
                    <th class="w-28">UTS/Teori (0-100)</th>
                    <th class="w-28">UAS/UKK (0-100)</th>
                    <th class="w-24">Nilai Akhir</th>
                    <th class="text-right w-24">Aksi</th>
                </tr>
            `;

            const tbody = document.getElementById('grades-tbody');
            if (!currentClassStudents.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400">Tidak ada siswa terdaftar di kelas ini.</td></tr>';
                return;
            }

            tbody.innerHTML = currentClassStudents.map((s, idx) => {
                const final = s.final_score ?? '-';
                return `
                <tr id="row-student-${s.student_id}">
                    <td class="text-xs text-gray-500">${s.nis || '-'}</td>
                    <td class="font-semibold text-gray-900">${s.student_name}</td>
                    <td>
                        <input type="number" min="0" max="100" class="form-input text-sm py-1 px-2 w-24 text-center score-practice"
                               id="p-${s.student_id}" value="${s.practice_score ?? ''}" placeholder="0-100"
                               oninput="window.calcRowFinal(${s.student_id})">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" class="form-input text-sm py-1 px-2 w-24 text-center score-theory"
                               id="t-${s.student_id}" value="${s.theory_score ?? ''}" placeholder="0-100"
                               oninput="window.calcRowFinal(${s.student_id})">
                    </td>
                    <td>
                        <input type="number" min="0" max="100" class="form-input text-sm py-1 px-2 w-24 text-center score-ukk"
                               id="u-${s.student_id}" value="${s.ukk_score ?? ''}" placeholder="0-100"
                               oninput="window.calcRowFinal(${s.student_id})">
                    </td>
                    <td>
                        <span id="final-${s.student_id}" class="font-black text-brand-700 bg-brand-50 px-2.5 py-1 rounded-lg text-sm">
                            ${final}
                        </span>
                    </td>
                    <td class="text-right">
                        <button type="button" onclick="window.saveSingleStudentGrade(${s.student_id})" id="btn-save-${s.student_id}" class="btn btn-secondary btn-sm">
                            Simpan
                        </button>
                    </td>
                </tr>
                `;
            }).join('');
        } catch(err) {
            countEl.textContent = 'Gagal memuat';
            window.toast.apiError(err);
        }
    }

    // ── Row Live Calculation ──────────────────────────────────────────────────
    window.calcRowFinal = (studentId) => {
        const p = parseFloat(document.getElementById(`p-${studentId}`)?.value);
        const t = parseFloat(document.getElementById(`t-${studentId}`)?.value);
        const u = parseFloat(document.getElementById(`u-${studentId}`)?.value);

        const scores = [p, t, u].filter(v => !isNaN(v) && v >= 0 && v <= 100);
        const finalEl = document.getElementById(`final-${studentId}`);
        if (!finalEl) return;

        if (scores.length > 0) {
            const avg = (scores.reduce((a, b) => a + b, 0) / scores.length).toFixed(1);
            finalEl.textContent = avg;
        } else {
            finalEl.textContent = '-';
        }
    };

    // ── Filter Change Handler ─────────────────────────────────────────────────
    window.onFilterChange = () => {
        const classId = document.getElementById('g-class').value;
        const subjectId = document.getElementById('g-subject').value;

        if (classId && subjectId) {
            loadClassGrading(classId, subjectId);
        } else {
            loadGrades(1);
        }
    };

    window.resetFilters = () => {
        document.getElementById('g-class').value = '';
        document.getElementById('g-subject').value = '';
        document.getElementById('g-search').value = '';
        loadGrades(1);
    };

    // ── Save Single Student Grade ─────────────────────────────────────────────
    window.saveSingleStudentGrade = async (studentId) => {
        const classId = document.getElementById('g-class').value;
        const subjectId = document.getElementById('g-subject').value;

        if (!subjectId) {
            window.toast.error('Pilih mata pelajaran terlebih dahulu.');
            return;
        }

        const p = document.getElementById(`p-${studentId}`)?.value;
        const t = document.getElementById(`t-${studentId}`)?.value;
        const u = document.getElementById(`u-${studentId}`)?.value;

        const payload = {
            student_id: studentId,
            subject_id: parseInt(subjectId),
            practice_score: p !== '' ? parseFloat(p) : null,
            theory_score: t !== '' ? parseFloat(t) : null,
            ukk_score: u !== '' ? parseFloat(u) : null,
        };

        const btn = document.getElementById(`btn-save-${studentId}`);
        try {
            window.utils.setButtonLoading(btn, true);
            await window.guruApi.upsertGrades(payload);
            window.toast.success('Nilai siswa berhasil disimpan.');
        } catch(err) {
            window.toast.apiError(err);
        } finally {
            window.utils.setButtonLoading(btn, false, 'Simpan');
        }
    };

    // ── Save Batch Grades ─────────────────────────────────────────────────────
    window.saveBatchGrades = async () => {
        const classId = document.getElementById('g-class').value;
        const subjectId = document.getElementById('g-subject').value;

        if (!classId || !subjectId) {
            window.toast.error('Pilih kelas dan mata pelajaran.');
            return;
        }

        const grades = [];
        for (const s of currentClassStudents) {
            const p = document.getElementById(`p-${s.student_id}`)?.value;
            const t = document.getElementById(`t-${s.student_id}`)?.value;
            const u = document.getElementById(`u-${s.student_id}`)?.value;

            // Only send if at least one score is filled
            if (p !== '' || t !== '' || u !== '') {
                grades.push({
                    student_id: s.student_id,
                    practice_score: p !== '' ? parseFloat(p) : null,
                    theory_score: t !== '' ? parseFloat(t) : null,
                    ukk_score: u !== '' ? parseFloat(u) : null,
                });
            }
        }

        if (!grades.length) {
            window.toast.warning('Belum ada nilai yang diinputkan.');
            return;
        }

        const btn = document.getElementById('btn-batch-save');
        try {
            window.utils.setButtonLoading(btn, true);
            const res = await window.guruApi.batchUpsertGrades({
                class_id: parseInt(classId),
                subject_id: parseInt(subjectId),
                grades
            });
            window.toast.success(res.message || 'Semua nilai berhasil disimpan.');
        } catch(err) {
            window.toast.apiError(err);
        } finally {
            window.utils.setButtonLoading(btn, false, 'Simpan Semua Nilai (Batch)');
        }
    };

    // ── Modal Handlers ────────────────────────────────────────────────────────
    window.calculateModalFinal = () => {
        const p = parseFloat(document.getElementById('m-practice').value);
        const t = parseFloat(document.getElementById('m-theory').value);
        const u = parseFloat(document.getElementById('m-ukk').value);
        const scores = [p, t, u].filter(v => !isNaN(v) && v >= 0 && v <= 100);
        document.getElementById('modal-final-score').textContent = scores.length > 0
            ? (scores.reduce((a, b) => a + b, 0) / scores.length).toFixed(1)
            : '-';
    };

    window.openGradeModal = () => {
        document.getElementById('grade-modal-title').textContent = 'Input Nilai Siswa';
        document.getElementById('modal-grade-id').value = '';
        document.getElementById('m-student').value = '';
        document.getElementById('m-student').disabled = false;
        document.getElementById('m-subject').value = document.getElementById('g-subject').value || '';
        document.getElementById('m-practice').value = '';
        document.getElementById('m-theory').value = '';
        document.getElementById('m-ukk').value = '';
        document.getElementById('modal-final-score').textContent = '-';
        window.openModal(document.getElementById('grade-modal'));
    };

    window.editGradeModal = (g) => {
        document.getElementById('grade-modal-title').textContent = 'Edit Nilai Siswa';
        document.getElementById('modal-grade-id').value = g.id || '';
        document.getElementById('m-student').value = g.student_id || '';
        document.getElementById('m-student').disabled = true;
        document.getElementById('m-subject').value = g.subject_id || '';
        document.getElementById('m-practice').value = g.practice_score ?? g.task_score ?? '';
        document.getElementById('m-theory').value = g.theory_score ?? g.uts_score ?? '';
        document.getElementById('m-ukk').value = g.ukk_score ?? g.uas_score ?? '';
        window.calculateModalFinal();
        window.openModal(document.getElementById('grade-modal'));
    };

    window.saveGradeModal = async () => {
        const studentId = document.getElementById('m-student').value;
        const subjectId = document.getElementById('m-subject').value;

        if (!studentId || !subjectId) {
            window.toast.error('Harap pilih siswa dan mata pelajaran.');
            return;
        }

        const p = document.getElementById('m-practice').value;
        const t = document.getElementById('m-theory').value;
        const u = document.getElementById('m-ukk').value;

        const payload = {
            student_id: parseInt(studentId),
            subject_id: parseInt(subjectId),
            practice_score: p !== '' ? parseFloat(p) : null,
            theory_score: t !== '' ? parseFloat(t) : null,
            ukk_score: u !== '' ? parseFloat(u) : null,
        };

        const btn = document.getElementById('btn-save-grade');
        try {
            window.utils.setButtonLoading(btn, true);
            await window.guruApi.upsertGrades(payload);
            window.toast.success('Nilai siswa berhasil disimpan.');
            window.closeModal(document.getElementById('grade-modal'));

            if (isClassMode) {
                const classId = document.getElementById('g-class').value;
                loadClassGrading(classId, subjectId);
            } else {
                loadGrades(1);
            }
        } catch(err) {
            window.toast.apiError(err);
        } finally {
            window.utils.setButtonLoading(btn, false, 'Simpan Nilai');
        }
    };

    // Auto-listen to dropdown change
    document.getElementById('g-class').addEventListener('change', window.onFilterChange);
    document.getElementById('g-subject').addEventListener('change', window.onFilterChange);
    document.getElementById('g-search').addEventListener('keyup', (e) => {
        if (e.key === 'Enter') loadGrades(1);
    });

    // Initialize
    await loadMasterData();
    loadGrades(1);
});
</script>
@endpush
