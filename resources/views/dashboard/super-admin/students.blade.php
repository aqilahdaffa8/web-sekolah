@extends('layouts.dashboard')

@section('title', 'Data Siswa')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Data Siswa</h1>
        <p class="mt-1 text-gray-500">Kelola data siswa dan akun login dari satu tempat.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="/api/admin/students/template" class="btn btn-ghost">Unduh Template CSV</a>
        <button type="button" class="btn btn-ghost" onclick="openImportModal()">Import CSV</button>
        <button type="button" class="btn btn-primary" onclick="openStudentModal()">Tambah Siswa</button>
    </div>
</div>

<div class="card mb-5"><div class="p-4 grid gap-3 md:grid-cols-4">
    <input id="student-search" type="search" class="form-input" placeholder="Cari NIS atau nama">
    <select id="student-class-filter" class="form-select"><option value="">Semua Kelas</option></select>
    <select id="student-status-filter" class="form-select"><option value="">Semua Status</option><option value="aktif">Aktif</option><option value="lulus">Lulus</option></select>
    <select id="student-account-filter" class="form-select"><option value="">Semua Akun</option><option value="active">Sudah Aktif</option><option value="inactive">Belum Aktif</option></select>
</div></div>

<div class="card"><div class="overflow-x-auto"><table class="data-table"><thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Status</th><th>Akun</th><th class="text-right">Aksi</th></tr></thead><tbody id="students-tbody"></tbody></table></div><div id="students-pagination" class="p-4 border-t border-gray-100"></div></div>

<div id="student-modal" data-modal class="modal-overlay hidden"><div class="modal-box"><div class="modal-header"><h3 id="student-modal-title" class="text-lg font-bold">Tambah Siswa</h3><button data-modal-close class="btn-icon">×</button></div><div class="modal-body space-y-4">
    <input id="student-id" type="hidden"><div><label class="form-label">NIS</label><input id="student-nis" class="form-input"></div><div><label class="form-label">Nama</label><input id="student-name" class="form-input"></div><div><label class="form-label">Kelas</label><select id="student-class" class="form-select"></select></div><div><label class="form-label">Status</label><select id="student-status" class="form-select"><option value="aktif">Aktif</option><option value="lulus">Lulus</option></select></div>
    <label id="create-account-wrap" class="flex items-center gap-2 text-sm"><input id="create-account" type="checkbox"> Buatkan akun login siswa</label><div id="account-fields" class="hidden space-y-3"><input id="student-email" type="email" class="form-input" placeholder="Email siswa"><input id="student-password" type="password" class="form-input" placeholder="Password minimal 8 karakter"></div>
</div><div class="modal-footer"><button data-modal-close class="btn btn-ghost">Batal</button><button type="button" class="btn btn-primary" onclick="saveStudent()">Simpan</button></div></div></div>

<div id="import-modal" data-modal class="modal-overlay hidden"><div class="modal-box"><div class="modal-header"><h3 class="text-lg font-bold">Import Data Siswa</h3><button data-modal-close class="btn-icon">×</button></div><div class="modal-body space-y-4"><p class="text-sm text-gray-500">Gunakan kolom: <code>nis,nama,kelas,status</code>.</p><input id="student-csv" type="file" accept=".csv,text/csv" class="form-input"><label class="flex items-center gap-2 text-sm"><input id="import-create-accounts" type="checkbox"> Otomatis buat akun dengan email NIS dan password awal NIS</label><div id="import-result" class="hidden text-sm"></div></div><div class="modal-footer"><button data-modal-close class="btn btn-ghost">Batal</button><button type="button" class="btn btn-primary" onclick="importStudents()">Import</button></div></div></div>
@endsection

@push('scripts')
<script>
let studentClasses = [], currentStudentPage = 1;
const studentModal = () => document.getElementById('student-modal');
const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[char]));
const studentSearch = document.getElementById('student-search');
const studentClassFilter = document.getElementById('student-class-filter');
const studentStatusFilter = document.getElementById('student-status-filter');
const studentAccountFilter = document.getElementById('student-account-filter');
const studentsTbody = document.getElementById('students-tbody');
const studentId = document.getElementById('student-id');
const studentNis = document.getElementById('student-nis');
const studentName = document.getElementById('student-name');
const studentClass = document.getElementById('student-class');
const studentStatus = document.getElementById('student-status');
const createAccount = document.getElementById('create-account');
const createAccountWrap = document.getElementById('create-account-wrap');
const accountFields = document.getElementById('account-fields');
const studentEmail = document.getElementById('student-email');
const studentPassword = document.getElementById('student-password');
const studentModalTitle = document.getElementById('student-modal-title');
const studentCsv = document.getElementById('student-csv');
const importCreateAccounts = document.getElementById('import-create-accounts');
const importResult = document.getElementById('import-result');

async function loadStudents(page = 1) {
    currentStudentPage = page;
    try {
        const response = await window.adminApi.students({ page, per_page: 15, search: studentSearch.value, class_id: studentClassFilter.value, status: studentStatusFilter.value, account_status: studentAccountFilter.value });
        studentsTbody.innerHTML = (response.data || []).map(student => `<tr><td>${escapeHtml(student.nis)}</td><td>${escapeHtml(student.name)}</td><td>${escapeHtml(student.class_room?.class_name || '-')}</td><td><span class="badge ${student.status === 'aktif' ? 'badge-success' : 'badge-gray'}">${escapeHtml(student.status)}</span></td><td><span class="badge ${student.user_id ? 'badge-success' : 'badge-gray'}">${student.user_id ? 'Sudah Aktif' : 'Belum Aktif'}</span></td><td class="text-right"><button class="btn-icon" onclick='editStudent(${JSON.stringify(student).replace(/'/g, '&#039;')})'>Edit</button><button class="btn-icon text-danger" onclick="deleteStudent(${student.id})">Hapus</button></td></tr>`).join('') || '<tr><td colspan="6" class="text-center text-gray-500 py-8">Belum ada data siswa.</td></tr>';
        window.utils.renderPagination('students-pagination', response.meta, loadStudents);
    } catch (error) { window.toast.apiError(error); }
}

async function loadStudentClasses() {
    studentClasses = await window.adminApi.studentClasses();
    const options = '<option value="">Pilih kelas</option>' + studentClasses.map(item => `<option value="${item.id}">${escapeHtml(item.class_name)}</option>`).join('');
    studentClass.innerHTML = options; studentClassFilter.innerHTML = '<option value="">Semua Kelas</option>' + studentClasses.map(item => `<option value="${item.id}">${escapeHtml(item.class_name)}</option>`).join('');
}

function openStudentModal(student = null) { studentId.value = student?.id || ''; studentNis.value = student?.nis || ''; studentName.value = student?.name || ''; studentClass.value = student?.class_id || ''; studentStatus.value = student?.status || 'aktif'; createAccount.checked = false; createAccountWrap.classList.toggle('hidden', Boolean(student)); accountFields.classList.add('hidden'); studentModalTitle.textContent = student ? 'Edit Siswa' : 'Tambah Siswa'; studentModal().classList.remove('hidden'); }
function editStudent(student) { openStudentModal(student); }
function openImportModal() { importResult.classList.add('hidden'); document.getElementById('import-modal').classList.remove('hidden'); }
function closeModals() { document.querySelectorAll('[data-modal]').forEach(item => item.classList.add('hidden')); }

async function saveStudent() { const payload = { nis: studentNis.value, name: studentName.value, class_id: studentClass.value, status: studentStatus.value }; if (!studentId.value && createAccount.checked) Object.assign(payload, { create_account: true, email: studentEmail.value, password: studentPassword.value }); try { studentId.value ? await window.adminApi.updateStudent(studentId.value, payload) : await window.adminApi.createStudent(payload); closeModals(); window.toast.success('Data siswa berhasil disimpan.'); loadStudents(currentStudentPage); } catch (error) { window.toast.apiError(error); } }
async function deleteStudent(id) { if (!confirm('Hapus data siswa ini?')) return; try { await window.adminApi.deleteStudent(id); window.toast.success('Data siswa berhasil dihapus.'); loadStudents(currentStudentPage); } catch (error) { window.toast.apiError(error); } }
async function importStudents() { const file = studentCsv.files[0]; if (!file) { window.toast.error('Pilih file CSV terlebih dahulu.'); return; } const formData = new FormData(); formData.append('file', file); formData.append('create_accounts', importCreateAccounts.checked ? '1' : '0'); try { const response = await window.adminApi.importStudents(formData); importResult.textContent = `Selesai: ${response.result.created} baru, ${response.result.updated} diperbarui, ${response.result.accounts_created} akun dibuat, ${response.result.skipped} dilewati.`; importResult.classList.remove('hidden'); loadStudentClasses(); loadStudents(1); } catch (error) { window.toast.apiError(error); } }

document.addEventListener('DOMContentLoaded', async () => { await loadStudentClasses(); await loadStudents(); ['studentSearch', 'studentClassFilter', 'studentStatusFilter', 'studentAccountFilter'].forEach(id => document.getElementById(id).addEventListener(id === 'studentSearch' ? 'input' : 'change', () => loadStudents(1))); createAccount.addEventListener('change', () => accountFields.classList.toggle('hidden', !createAccount.checked)); document.querySelectorAll('[data-modal-close]').forEach(button => button.addEventListener('click', closeModals)); });
</script>
@endpush
