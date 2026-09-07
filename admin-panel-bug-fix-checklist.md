# Admin Panel --- Bug Fix & API Stabilization

**Project:** Website Sekolah\
**Scope:** Admin Panel / Super Admin\
**Frontend:** React + Vite + Tailwind CSS\
**Backend:** Laravel API\
**Target:** Stabilitas Admin Panel dari ±85% menuju 100%.

------------------------------------------------------------------------

## 1. Ringkasan Bug

Berdasarkan hasil testing/screenshot, ditemukan tiga kelompok masalah
utama:

1.  **Data tidak muncul di Admin Panel**, walaupun data yang sama sudah
    terlihat pada halaman user.
2.  **Fungsi CRUD tidak berjalan**, terutama hapus, tambah, simpan,
    update, dan perubahan status.
3.  **Error API muncul sebagai notifikasi merah**, contohnya:
    -   `window.guruApi.learningModules is not a function`
    -   `window.eskulApi.updateRegistrationStatus is not a function`
    -   `window.hubinApi.vacancies is not a function`
    -   `Cannot read properties of undefined (reading '0')`

Selain itu beberapa halaman terlihat berhenti pada **skeleton/loading
state**.

------------------------------------------------------------------------

# 2. Prioritas Debugging

  Prioritas   Masalah                                             Level
  ----------- --------------------------------------------------- ----------
  P0          API function tidak tersedia / `is not a function`   Critical
  P0          Data Admin tidak dapat dimuat                       Critical
  P0          Response API tidak sesuai dengan frontend           Critical
  P0          CRUD gagal                                          Critical
  P1          Loading skeleton tidak selesai                      High
  P1          Delete/update/status tidak bekerja                  High
  P1          Filter/search tidak bekerja                         High
  P2          Toast/error notification tidak konsisten            Medium
  P3          Cleanup console warning/error                       Low

------------------------------------------------------------------------

# 3. Alur Debugging Wajib

Setiap halaman yang bermasalah diperiksa dari ujung ke ujung:

``` text
React Component
      ↓
API Service
      ↓
HTTP Request
      ↓
Laravel Route
      ↓
Controller
      ↓
Model / Database
      ↓
JSON Response
      ↓
API Service
      ↓
React State
      ↓
UI
```

**Jangan hanya memperbaiki UI.** Jika data tidak muncul, cari titik
pertama di mana alurnya gagal.

------------------------------------------------------------------------

# 4. BUG: Data Tidak Muncul

## Gejala

Beberapa halaman Admin hanya menampilkan skeleton atau kosong, sedangkan
halaman user sudah memiliki data.

Halaman yang perlu diperiksa:

-   [ ] Dashboard
-   [ ] Modul Ajar
-   [ ] Pendaftaran Eskul
-   [ ] Fasilitas & Sarpras
-   [ ] Lowongan Kerja Alumni
-   [ ] Mitra DUDI / Hubin
-   [ ] Produk & Jasa
-   [ ] Modul admin lain yang menggunakan API

## Checklist

-   [ ] Endpoint GET benar.
-   [ ] HTTP method benar.
-   [ ] Function API tersedia.
-   [ ] Function API sudah di-export.
-   [ ] Import di React benar.
-   [ ] Route Laravel tersedia.
-   [ ] Controller benar.
-   [ ] Middleware/auth tidak memblokir request.
-   [ ] Response JSON benar.
-   [ ] Frontend membaca property response yang benar.
-   [ ] State React di-update setelah request.
-   [ ] Loading state berhenti setelah request selesai.
-   [ ] Empty state muncul jika memang tidak ada data.
-   [ ] Error state muncul jika API gagal.

### Catatan penting

Jika data user sudah muncul, **jangan membuat dummy data baru untuk
Admin**. Bandingkan endpoint, response, service API, authorization, dan
state management antara user dan admin.

------------------------------------------------------------------------

# 5. BUG: `window.guruApi.learningModules is not a function`

**Halaman:** Modul Ajar & Materi\
**Status:** CRITICAL

Error berarti kode mencoba memanggil:

``` js
window.guruApi.learningModules()
```

tetapi `learningModules` bukan function yang tersedia pada object
tersebut.

## Periksa

-   [ ] File `guruApi` ditemukan.
-   [ ] Function `learningModules` benar-benar ada.
-   [ ] Function di-export.
-   [ ] Function dimasukkan ke object `guruApi` jika menggunakan
    `window.guruApi`.
-   [ ] Tidak ada typo nama function.
-   [ ] Tidak ada perbedaan seperti `learningModules()` vs
    `getLearningModules()`.
-   [ ] Component menggunakan API service versi terbaru.
-   [ ] Tidak ada circular dependency.
-   [ ] Vite tidak menggunakan module/cache lama.

## Rekomendasi

Untuk React + Vite, lebih baik gunakan module import langsung:

``` js
import {
    getModules,
    createModule,
    updateModule,
    deleteModule,
} from "@/api/guruApi";
```

Daripada terlalu bergantung pada:

``` js
window.guruApi
```

kecuali arsitektur project memang menggunakan global API registry.

------------------------------------------------------------------------

# 6. MODUL AJAR --- CRUD TEST

### Read

-   [ ] Data modul muncul.
-   [ ] Skeleton hanya tampil selama request.
-   [ ] Empty state benar.
-   [ ] Error state benar.

### Create

-   [ ] `Tambah Modul` membuka form.
-   [ ] Input dapat diisi.
-   [ ] Validasi berjalan.
-   [ ] `Simpan` memanggil API.
-   [ ] Data tersimpan di database.
-   [ ] Data baru muncul tanpa refresh manual.
-   [ ] Modal tertutup setelah sukses.

### Update

-   [ ] Data lama masuk form.
-   [ ] Update berhasil.
-   [ ] Data UI berubah.
-   [ ] Database berubah.

### Delete

-   [ ] Konfirmasi muncul jika digunakan.
-   [ ] ID benar.
-   [ ] DELETE request berhasil.
-   [ ] Data hilang dari UI.
-   [ ] Data benar-benar hilang dari database.

------------------------------------------------------------------------

# 7. BUG: `window.eskulApi.updateRegistrationStatus is not a function`

**Halaman:** Pendaftaran Eskul\
**Status:** CRITICAL

Tombol `Terima` dan `Tolak` memerlukan function API untuk mengubah
status pendaftaran.

## Checklist

-   [ ] Cari `eskulApi`.
-   [ ] Pastikan `updateRegistrationStatus` tersedia.
-   [ ] Pastikan function di-export.
-   [ ] Pastikan function masuk object `eskulApi`.
-   [ ] Nama function di component dan service sama.
-   [ ] Route Laravel tersedia.
-   [ ] HTTP method benar.
-   [ ] ID registration benar.
-   [ ] Payload status sesuai backend.
-   [ ] Database berubah.
-   [ ] UI memperbarui status.

## Flow yang harus berhasil

``` text
Siswa daftar Eskul
      ↓
Database
      ↓
Admin melihat pendaftaran
      ↓
Klik Terima / Tolak
      ↓
API update status
      ↓
Database berubah
      ↓
React update state
      ↓
Status berubah di UI
```

### Testing status

-   [ ] Menunggu → Terima
-   [ ] Menunggu → Tolak
-   [ ] Filter Semua
-   [ ] Filter Menunggu
-   [ ] Filter Diterima
-   [ ] Filter Ditolak
-   [ ] Status tetap benar setelah reload

------------------------------------------------------------------------

# 8. BUG: `window.hubinApi.vacancies is not a function`

**Halaman:** Lowongan Kerja Alumni\
**Status:** CRITICAL

Halaman terlihat berhenti pada skeleton/loading.

## Checklist

-   [ ] Function `vacancies` tersedia.
-   [ ] Jika nama sebenarnya `getVacancies`, samakan pemanggilannya.
-   [ ] Function di-export.
-   [ ] Object `hubinApi` benar.
-   [ ] Endpoint Laravel benar.
-   [ ] Response API benar.
-   [ ] State diisi.
-   [ ] Skeleton berhenti setelah request.
-   [ ] Empty state tersedia.
-   [ ] Search posisi/perusahaan bekerja.
-   [ ] Filter status bekerja.
-   [ ] Tambah loker bekerja.
-   [ ] Edit bekerja.
-   [ ] Delete bekerja.

------------------------------------------------------------------------

# 9. BUG: `Cannot read properties of undefined (reading '0')`

Error ini biasanya terjadi ketika frontend melakukan akses seperti:

``` js
data.images[0]
```

padahal `data.images` bernilai `undefined`.

## Perbaikan

Gunakan defensive rendering:

``` js
data.images?.[0]
```

atau fallback:

``` js
const image = data.images?.[0] ?? fallbackImage;
```

Namun **jangan hanya menambahkan optional chaining untuk menutupi bug**.
Periksa mengapa API tidak mengirim property tersebut.

## Checklist

-   [ ] Property response diperiksa.
-   [ ] Array selalu memiliki default `[]`.
-   [ ] Image kosong memiliki fallback.
-   [ ] Nested object aman.
-   [ ] Tidak ada akses `[0]` pada undefined.
-   [ ] Tidak ada error setelah data kosong.

------------------------------------------------------------------------

# 10. STANDARDISASI RESPONSE API

Pastikan frontend dan backend memiliki kontrak response yang sama.

Contoh:

``` json
{
    "success": true,
    "data": [],
    "message": "Data berhasil diambil"
}
```

Jika response sebenarnya:

``` json
{
    "data": {
        "items": []
    }
}
```

maka frontend harus membaca:

``` js
response.data.items
```

bukan:

``` js
response.data
```

## Checklist

-   [ ] Struktur response setiap endpoint didokumentasikan.
-   [ ] Frontend membaca property yang benar.
-   [ ] Pagination ditangani jika ada.
-   [ ] Error response memiliki format konsisten.
-   [ ] Tidak ada asumsi bentuk response tanpa mengecek Network tab.

------------------------------------------------------------------------

# 11. BUG FUNGSI HAPUS

Beberapa tombol `Hapus` dilaporkan tidak dapat digunakan.

Pada screenshot Produk & Jasa terlihat notifikasi:

> `Produk berhasil dihapus.`

Ini menunjukkan setidaknya satu kondisi delete sudah berhasil, tetapi
tetap lakukan **regression test** pada seluruh modul.

## Delete flow

``` text
Klik Hapus
    ↓
Konfirmasi
    ↓
Handler
    ↓
DELETE API
    ↓
Laravel Controller
    ↓
Database
    ↓
Response
    ↓
Update React State
    ↓
Item hilang
```

## Checklist universal

-   [ ] Tombol dapat diklik.
-   [ ] ID benar.
-   [ ] Handler benar.
-   [ ] Endpoint benar.
-   [ ] HTTP method DELETE benar.
-   [ ] Authorization benar.
-   [ ] Database menghapus data.
-   [ ] Toast success muncul satu kali.
-   [ ] Item hilang dari UI.
-   [ ] Reload tidak mengembalikan item yang sudah dihapus.
-   [ ] Error ditampilkan jika delete gagal.

### Pola React yang disarankan

``` js
const handleDelete = async (id) => {
    try {
        setDeletingId(id);

        await api.deleteProduct(id);

        setProducts((prev) =>
            prev.filter((item) => item.id !== id)
        );

        showSuccess("Produk berhasil dihapus.");
    } catch (error) {
        console.error("Delete error:", error);

        showError(
            error?.response?.data?.message ||
            "Produk gagal dihapus."
        );
    } finally {
        setDeletingId(null);
    }
};
```

------------------------------------------------------------------------

# 12. NOTIFIKASI MERAH API

Notifikasi teknis seperti:

``` text
window.guruApi.learningModules is not a function
```

``` text
window.eskulApi.updateRegistrationStatus is not a function
```

``` text
window.hubinApi.vacancies is not a function
```

**tidak boleh muncul kepada user pada kondisi normal.**

## Root cause yang harus dicari

### A. Function tidak ada

``` js
api.someFunction()
```

tetapi function tidak dibuat.

### B. Tidak di-export

``` js
export const someFunction = ...
```

belum tersedia.

### C. Tidak masuk object API

Jika memakai:

``` js
window.guruApi
```

pastikan function masuk:

``` js
window.guruApi = {
    someFunction,
};
```

### D. Typo

Contoh:

``` text
updateRegistrationStatus
```

vs

``` text
updateRegistrationStatuses
```

### E. Service/component tidak sinkron

Component menggunakan API versi lama sementara service sudah berubah.

### F. Endpoint Laravel berbeda

Function ada, tetapi endpoint yang dipanggil salah.

------------------------------------------------------------------------

# 13. ERROR HANDLING YANG BENAR

Jangan hanya menyembunyikan error:

``` js
try {
    await api.someFunction();
} catch {
    // abaikan
}
```

Gunakan:

``` js
try {
    const response = await api.someFunction();
    // success
} catch (error) {
    console.error("API Error:", error);

    showError("Data gagal dimuat. Silakan coba lagi.");
}
```

User melihat pesan sederhana, developer tetap memiliki detail error di
console.

------------------------------------------------------------------------

# 14. NOTIFIKASI TIDAK BOLEH GANDA

Setiap aksi hanya boleh menghasilkan satu toast.

Periksa:

-   [ ] Tidak ada dua handler.
-   [ ] Tidak ada API call ganda.
-   [ ] Service tidak menampilkan toast sekaligus component.
-   [ ] `useEffect` tidak memanggil toast berulang.
-   [ ] Axios interceptor tidak menampilkan error tambahan jika
    component sudah menanganinya.

Contoh hasil yang benar:

``` text
Produk berhasil dihapus.
```

Bukan:

``` text
Produk berhasil dihapus.
Produk berhasil dihapus.
```

------------------------------------------------------------------------

# 15. DASHBOARD ADMIN

Statistik harus berasal dari API/database:

-   Total Siswa
-   Pesanan Hari Ini
-   Loker Aktif
-   Pendaftar Eskul

## Checklist

-   [ ] Data dinamis berhasil dimuat.
-   [ ] Angka sesuai database.
-   [ ] Tidak menggunakan dummy data pada production.
-   [ ] Loading benar.
-   [ ] Error state benar.
-   [ ] Pesanan Terbaru muncul.
-   [ ] `Lihat Semua` menuju route benar.
-   [ ] Data terbaru benar-benar terbaru.

------------------------------------------------------------------------

# 16. FASILITAS & SARPRAS

Screenshot menunjukkan card fasilitas masih berada pada skeleton.

## Checklist

-   [ ] API fasilitas tersedia.
-   [ ] GET berhasil.
-   [ ] Data card muncul.
-   [ ] Image URL benar.
-   [ ] Fallback image tersedia.
-   [ ] Tambah fasilitas.
-   [ ] Edit fasilitas.
-   [ ] Hapus fasilitas.
-   [ ] Upload image.
-   [ ] Preview image.
-   [ ] Simpan berhasil.
-   [ ] Data baru muncul setelah create.

------------------------------------------------------------------------

# 17. MITRA DUDI / HUBIN

Screenshot menunjukkan Mitra DUDI masih loading.

## Checklist

-   [ ] API mitra tersedia.
-   [ ] GET berhasil.
-   [ ] Data muncul.
-   [ ] Logo/image aman jika kosong.
-   [ ] Tidak ada akses `[0]` pada undefined.
-   [ ] Tambah mitra.
-   [ ] Edit mitra.
-   [ ] Delete mitra.
-   [ ] Search mitra.
-   [ ] Data konsisten setelah reload.

------------------------------------------------------------------------

# 18. PRODUK & JASA

## CRUD

-   [ ] Load products.
-   [ ] Tambah produk.
-   [ ] Edit produk.
-   [ ] Hapus produk.
-   [ ] Upload image.
-   [ ] Preview image.
-   [ ] Harga.
-   [ ] Stock.
-   [ ] Deskripsi.
-   [ ] Status produk.

## Delete regression

Setelah muncul:

``` text
Produk berhasil dihapus.
```

pastikan:

``` text
Database → terhapus
Frontend → hilang
Reload → tetap hilang
```

------------------------------------------------------------------------

# 19. STRUKTUR API SERVICE YANG DISARANKAN

``` text
src/
├── api/
│   ├── client.js
│   ├── guruApi.js
│   ├── eskulApi.js
│   ├── hubinApi.js
│   ├── fasilitasApi.js
│   ├── produkApi.js
│   └── dashboardApi.js
│
├── pages/
│   └── admin/
│
├── components/
│   └── admin/
│
└── hooks/
```

Gunakan naming konsisten:

``` text
GET
getModules()
getVacancies()
getFacilities()
getProducts()

CREATE
createModule()
createVacancy()
createFacility()
createProduct()

UPDATE
updateModule()
updateVacancy()
updateFacility()
updateProduct()

DELETE
deleteModule()
deleteVacancy()
deleteFacility()
deleteProduct()

STATUS
updateRegistrationStatus()
```

Hindari nama yang tidak konsisten untuk fungsi yang sama seperti:

``` text
modules()
learningModules()
getModules()
fetchModules()
```

------------------------------------------------------------------------

# 20. CEK ROUTE LARAVEL

Jalankan:

``` bash
php artisan route:list
```

Cari endpoint terkait:

``` text
modules
eskul
registrations
vacancies
facilities
products
dashboard
```

Periksa:

-   [ ] Route tersedia.
-   [ ] HTTP method benar.
-   [ ] Controller benar.
-   [ ] Middleware benar.
-   [ ] Parameter benar.
-   [ ] Authorization sesuai role.

------------------------------------------------------------------------

# 21. TEST API DENGAN DEVTOOLS

Buka:

``` text
F12
→ Network
→ Fetch/XHR
```

Reload halaman.

Periksa:

``` text
Request URL
Request Method
Status Code
Request Payload
Response JSON
```

Status umum:

``` text
200 = berhasil
201 = create berhasil
204 = delete berhasil tanpa response body
400 = request salah
401 = authentication
403 = authorization
404 = endpoint/data tidak ditemukan
422 = validation error
500 = server error
```

------------------------------------------------------------------------

# 22. PERBANDINGAN USER VS ADMIN

Jika user bisa melihat data tetapi admin tidak:

``` text
USER
 ↓
Endpoint A
 ↓
Response A

ADMIN
 ↓
Endpoint B
 ↓
Response B
```

Bandingkan:

-   [ ] URL endpoint.
-   [ ] HTTP method.
-   [ ] Authorization.
-   [ ] Query parameter.
-   [ ] Response JSON.
-   [ ] Mapping data.
-   [ ] State React.

Jika datanya sama, gunakan service/API yang konsisten jika arsitektur
project memungkinkan.

------------------------------------------------------------------------

# 23. FINAL CRUD TEST

Setiap modul wajib melewati:

``` text
CREATE
  ↓
READ
  ↓
UPDATE
  ↓
DELETE
  ↓
READ ulang
```

Checklist:

-   [ ] Create berhasil.
-   [ ] Data muncul.
-   [ ] Read berhasil.
-   [ ] Update berhasil.
-   [ ] Data berubah.
-   [ ] Delete berhasil.
-   [ ] Data hilang.
-   [ ] Reload.
-   [ ] Database tetap benar.

------------------------------------------------------------------------

# 24. FINAL ADMIN REGRESSION TEST

## Dashboard

-   [ ] Statistik
-   [ ] Pesanan terbaru
-   [ ] API error

## User Management

-   [ ] List
-   [ ] Tambah
-   [ ] Edit
-   [ ] Hapus
-   [ ] Cancel
-   [ ] Exit

## Guru

-   [ ] Data guru
-   [ ] Input nilai
-   [ ] Simpan nilai

## Kelas & Mapel

-   [ ] Data kelas
-   [ ] Data mapel
-   [ ] CRUD

## Modul Ajar

-   [ ] Load
-   [ ] Tambah
-   [ ] Edit
-   [ ] Hapus
-   [ ] Search

## Eskul

-   [ ] Kelola eskul
-   [ ] Pendaftaran
-   [ ] Terima
-   [ ] Tolak
-   [ ] Filter

## Hubin

-   [ ] Mitra DUDI
-   [ ] Lowongan
-   [ ] Tambah
-   [ ] Edit
-   [ ] Hapus
-   [ ] Search/filter

## Koperasi TEFA / Produk

-   [ ] Produk
-   [ ] Pesanan
-   [ ] Filter
-   [ ] CRUD
-   [ ] Delete

## Fasilitas

-   [ ] List
-   [ ] Tambah
-   [ ] Edit
-   [ ] Hapus
-   [ ] Image

## Galeri Prestasi

-   [ ] Tambah
-   [ ] Simpan
-   [ ] Edit
-   [ ] Hapus
-   [ ] Image

------------------------------------------------------------------------

# 25. DEFINITION OF DONE

Bug dianggap **FIXED** jika:

-   [ ] API function tersedia.
-   [ ] Export/import benar.
-   [ ] Endpoint Laravel benar.
-   [ ] HTTP method benar.
-   [ ] Payload benar.
-   [ ] Response sesuai contract.
-   [ ] State React benar.
-   [ ] Loading selesai.
-   [ ] Empty state benar.
-   [ ] Error state benar.
-   [ ] Create berhasil.
-   [ ] Read berhasil.
-   [ ] Update berhasil.
-   [ ] Delete berhasil.
-   [ ] Database sesuai.
-   [ ] Filter/search bekerja.
-   [ ] Status update bekerja.
-   [ ] Toast tidak ganda.
-   [ ] Error teknis tidak bocor ke UI.
-   [ ] Console tidak memiliki error kritis.
-   [ ] Laravel log tidak memiliki error kritis.
-   [ ] `npm run build` berhasil.
-   [ ] Regression testing selesai.

------------------------------------------------------------------------

# 26. URUTAN PENGERJAAN

## Phase 1 --- API Service

Perbaiki terlebih dahulu:

``` text
guruApi
eskulApi
hubinApi
fasilitasApi
produkApi
dashboardApi
```

Target:

> Tidak ada lagi error `is not a function`.

## Phase 2 --- Data Fetching

Perbaiki:

``` text
GET
↓
Response
↓
State
↓
UI
```

Target:

> Tidak ada halaman stuck pada skeleton ketika API berhasil.

## Phase 3 --- CRUD

Perbaiki:

``` text
CREATE
UPDATE
DELETE
```

Target:

> Semua button menjalankan aksi yang benar.

## Phase 4 --- Response & Defensive Rendering

Perbaiki:

``` text
undefined
null
[]
image
nested object
```

Target:

> Tidak ada `Cannot read properties of undefined`.

## Phase 5 --- Notification

Standarkan:

``` text
Success
Error
Loading
Empty
```

Target:

> Tidak ada error teknis tampil sebagai notifikasi user.

## Phase 6 --- Regression

Test seluruh Admin Panel.

------------------------------------------------------------------------

# 27. MASTER CHECKLIST

``` text
[ ] API service diperiksa
[ ] Function API tersedia
[ ] Export/import benar
[ ] Endpoint Laravel benar
[ ] HTTP method benar
[ ] Payload benar
[ ] Response JSON benar
[ ] React state benar
[ ] Loading benar
[ ] Empty state benar
[ ] Error state benar
[ ] Create berhasil
[ ] Read berhasil
[ ] Update berhasil
[ ] Delete berhasil
[ ] Filter berhasil
[ ] Search berhasil
[ ] Upload image berhasil
[ ] Status update berhasil
[ ] Toast tidak ganda
[ ] Error teknis tidak muncul di UI
[ ] Console bersih
[ ] Laravel log bersih
[ ] npm run build berhasil
[ ] Regression test selesai
```

------------------------------------------------------------------------

# 28. TARGET AKHIR

``` text
React Component
      ↓
API Service
      ↓
Laravel Route
      ↓
Controller
      ↓
Database
      ↓
JSON Response
      ↓
React State
      ↓
UI
```

Target akhir:

``` text
┌─────────────────────────────┐
│       ADMIN PANEL           │
├─────────────────────────────┤
│ ✓ Data tampil               │
│ ✓ CRUD bekerja              │
│ ✓ Delete bekerja            │
│ ✓ Status update bekerja     │
│ ✓ API stabil                │
│ ✓ Loading normal            │
│ ✓ Error handling benar      │
│ ✓ Toast tidak ganda         │
│ ✓ Console bersih            │
│ ✓ Database konsisten        │
└─────────────────────────────┘
```

> **Prinsip debugging:** jangan memperbaiki gejala hanya dari UI. Cari
> sumber masalah dari **Component → API Service → Route Laravel →
> Controller → Database → Response → React State**.

**Status:** `READY FOR ADMIN PANEL DEBUGGING`\
**Target:** `85% → 100%`
