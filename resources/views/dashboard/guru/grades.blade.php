@extends('layouts.dashboard')

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
