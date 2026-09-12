# 📘 DOKUMENTASI TEKNIS & TUGAS ROLE BACK-END DEVELOPER
## Proyek Web Sekolah Terpadu (SMKN 1 Katapang)

---

## 🌟 1. Pengenalan: Apa Itu Role Back-End pada Proyek Ini?

Dalam pembangunan aplikasi **Web Sekolah Terpadu**, peran seorang **Back-End Developer** adalah sebagai **arsitek dan perancang mesin di balik layar**. 

Jika Front-End adalah bagian bodi, cat mobil, dan dashboard yang dilihat pengemudi, maka **Back-End adalah mesin, transmisi, bahan bakar, dan sistem kelistrikan** yang membuat mobil tersebut benar-benar dapat menyala, melaju, dan aman dari bahaya.

Secara garis besar, Back-End bertanggung jawab untuk:
1. **Mengelola Basis Data (Database):** Tempat seluruh data sekolah disimpan secara permanen, terstruktur, dan aman.
2. **Menerapkan Aturan Bisnis (Business Logic):** Logika sistem (misal: bagaimana siswa mendaftar eskul, verifikasi pesanan barang di koperasi, penentuan kelulusan/status siswa).
3. **Membangun Layanan API (Application Programming Interface):** Pintu penghubung berupa data terstruktur (JSON) agar halaman depan publik maupun dashboard admin dapat menampilkan data dinamis secara *real-time*.
4. **Menjaga Keamanan Sistem (Security & Access Control):** Melindungi data rahasia, enkripsi kata sandi, dan membatasi hak akses agar setiap role pengguna hanya bisa mengakses menu yang menjadi wewenangnya.

Teknologi utama yang digunakan oleh Back-End pada proyek ini adalah:
* **Bahasa Pemrograman:** PHP 8.4
* **Framework:** Laravel 11
* **Autentikasi:** Laravel Sanctum (Token-Based & Session Security)
* **Database & ORM:** MySQL / MariaDB dengan Eloquent ORM

---

## 🏗️ 2. Arsitektur Back-End Proyek

Arsitektur aplikasi ini dibangun menggunakan kombinasi pola **MVC (Model-View-Controller)** dan **Headless / RESTful API Service**:

```
                 ┌─────────────────────────────────────────┐
                 │       CLIENT / FRONT-END INTERFACE       │
                 │  (Blade Templates + Modern Vanilla JS)  │
                 └───────────────────▲─────────────────────┘
                                     │ HTTP Requests (Fetch / JSON)
                                     ▼
                 ┌─────────────────────────────────────────┐
                 │             ROUTING LAYER               │
                 │   - routes/web.php  (Blade View Routes) │
                 │   - routes/api.php  (REST API Endpoints)│
                 └───────────────────▲─────────────────────┘
                                     │ Middleware (Auth, Throttle, CORS)
                                     ▼
                 ┌─────────────────────────────────────────┐
                 │            CONTROLLER LAYER             │
                 │   Memvalidasi request, memproses alur,  │
                 │   dan menyusun respon data JSON         │
                 └───────────────────▲─────────────────────┘
                                     │ Eloquent ORM Query
                                     ▼
                 ┌─────────────────────────────────────────┐
                 │              MODEL LAYER                │
                 │   Relasi antar tabel, Accessor, Mutator,│
                 │   Fillable fields, Data Casting         │
                 └───────────────────▲─────────────────────┘
                                     │ SQL Queries
                                     ▼
                 ┌─────────────────────────────────────────┐
                 │             DATABASE (SQL)              │
                 │   Tabel User, Siswa, Jurusan, Produk,   │
                 │   Eskul, Berita, Agenda, Kemitraan DUDI │
                 └─────────────────────────────────────────┘
```

---

## 📋 3. Rincian Pekerjaan yang Dikerjakan Back-End Developer

Berikut adalah daftar pekerjaan rinci yang dirancang, dibangun, dan dioptimasi oleh Back-End Developer pada proyek ini:

### 🗄️ A. Perancangan & Rekayasa Basis Data (Database Engineering)
Back-End mendesain skema relasi antar entitas sekolah sehingga tidak terjadi redundansi data (duplikasi yang sia-sia) dan data terhubung dengan integritas yang kuat:

1. **Entitas Manajemen Pengguna & Hak Akses:**
   * `users`: Menyimpan kredensial login (nama, email, hash password, status akun `is_active`).
   * `roles`: Menyimpan daftar role (Super Admin, Hubin, Koperasi, Guru, Pembina Eskul).
   * `permissions`: Izin tindakan granular (create, read, update, delete per modul).
   * `role_user` & `permission_role`: Tabel pivot untuk relasi banyak-ke-banyak (*Many-to-Many*).

2. **Entitas Akademik & Profil Sekolah:**
   * `programs`: 7 Kompetensi Keahlian resmi (RPL, TKJ, Broadcasting Perfilman, Teknik Mesin, Teknik Otomotif, Teknik Penyempurnaan Tekstil, Teknik Elektronika).
   * `classes`: Rombongan belajar / kelas yang terhubung dengan program keahlian.
   * `students`: Data siswa sekolah (NIS, nama lengkap, jenis kelamin, kelas, dan status aktif/alumni).
   * `facilities`: Fasilitas lab dan bengkel praktik kejuruan beserta foto dan lokasinya.
   * `site_settings`: Visi, misi, sejarah, kontak, dan susunan pejabat struktural organisasi sekolah.

3. **Entitas Publikasi & Komunikasi:**
   * `posts` & `post_categories`: Artikel berita sekolah, pengumuman, kategori, status *draft* atau *published*.
   * `events`: Kalender kegiatan resmi sekolah, tanggal pelaksanaan, dan lokasi acara.
   * `banners`: Banner slider visual pada halaman beranda utama.
   * `contact_messages`: Pesan atau saran yang dikirimkan oleh pengunjung melalui form kontak.

4. **Entitas Unit Bisnis Teaching Factory (TeFA) & Koperasi:**
   * `tefa_products`: Produk karya siswa kejuruan (nama barang, harga, stok, foto, deskripsi, relasi ke jurusan).
   * `tefa_orders`: Data transaksi pesanan masuk dari pembeli (nomor pesanan, nama pemesan, nomor HP, alamat pengiriman, total harga, status: *pending, processing, completed, cancelled*).
   * `tefa_order_items`: Rincian item produk yang dibeli di setiap transaksi.

5. **Entitas Kesiswaan & Ekstrakurikuler:**
   * `extracurriculars`: Daftar eskul (Robotika, Pramuka, Futsal, PMR, Seni Tari, dll.) beserta jadwal latihan dan pembimbingnya.
   * `extracurricular_registrations`: Rekap pendaftaran calon anggota baru (lengkap dengan status verifikasi: *menunggu/pending, diterima/approved, ditolak/rejected*).
   * `extracurricular_achievements`: Prestasi yang diraih oleh siswa pada cabang eskul.

6. **Entitas Hubungan Industri (Hubin) & Alumni:**
   * `dudi_partners`: Perusahaan mitra DUDI untuk magang PKL dan kelas industri.
   * `tracer_studies`: Penelusuran data keterserapan lulusan alumni (bekerja, kuliah, wirausaha).
   * `job_vacancies`: Bursa lowongan kerja khusus alumni dan siswa SMK.

7. **Entitas Bahan Ajar Guru:**
   * `teaching_materials`: Modul ajar, silabus, dan file materi pembelajaran guru untuk siswa.

---

### 🔐 B. Autentikasi & Otorisasi Berjenjang (RBAC - Role-Based Access Control)
Back-End memastikan sistem menerapkan prinsip keamanan **Zero Trust** dan membagi hak akses ke dalam 5 tingkatan pengguna (*Role*):

| Role Pengguna | Tanggung Jawab & Batas Wewenang Back-End |
|---|---|
| **Super Admin** | Akses tanpa batas ke seluruh dashboard: membuat & mengedit user, menetapkan role, aktivasi/nonaktifkan akun, serta konfigurasi global situs. |
| **Admin Hubin & BKK** | Mengelola kemitraan dunia usaha/industri (DUDI), input lowongan kerja, dan rekap data kuesioner tracer study alumni. |
| **Admin Koperasi & TeFA** | Mengelola etalase produk kejuruan, memperbarui stok barang, mengubah status pesanan pelanggan (*diproses, dikirim, selesai, dibatalkan*). |
| **Bapak/Ibu Guru** | Mengunggah dan menghapus modul ajar/bahan pembelajaran, mengakses informasi kelas yang diampu. |
| **Pembina Ekstrakurikuler** | Memantau siswa yang mendaftar eskul, memfilter status pendaftar (*terima, tolak, tunda*), dan mengunggah piala prestasi. |

Fitur keamanan yang diprogram oleh Back-End:
* **Password Hashing:** Password pengguna diacak menggunakan algoritma *Bcrypt* dengan penggaraman (*salt*), sehingga tidak terbaca bahkan jika database dibuka langsung.
* **Brute-Force Rate Limiting:** Pembatasan percobaan login (maksimal 5 kali percobaan per menit) untuk menangkal serangan bot atau peretasan paksa.
* **Token Authentication (Sanctum):** Autentikasi API yang aman menggunakan token yang terenkripsi dan dapat dicabut sewaktu-waktu saat logout.

---

### 🌐 C. Pembangunan Layanan RESTful API
Back-End merancang puluhan *endpoint* API yang terorganisasi rapi untuk menyuplai data ke seluruh halaman aplikasi:

#### 1. Public Endpoints (Dapat diakses pengunjung tanpa login):
* `GET /api/public/home` — Mengembalikan data banner aktif, berita terbaru, agenda terdekat, produk unggulan TeFA, daftar eskul, dan statistik ringkas sekolah.
* `GET /api/public/profile` — Mengembalikan visi, misi, sejarah resmi, profil 7 kompetensi keahlian, struktur organisasi, dan laboratorium sekolah.
* `GET /api/public/tefa` — Mengembalikan katalog produk TeFA dengan filter jurusan dan stok barang.
* `POST /api/public/orders` — Menerima formulir pemesanan produk TeFA dari publik, memvalidasi nomor telepon dan alamat pengiriman, serta memotong stok produk secara atomik.
* `POST /api/public/extracurricular-registrations` — Menampung pendaftaran siswa baru ke eskul pilihan.
* `GET /api/public/hubin` — Menampilkan kemitraan DUDI dan lowongan kerja terbaru.
* `POST /api/public/contact` — Menerima pesan aspirasi dan saran dari form kontak sekolah.

#### 2. Dashboard Endpoints (Dilindungi proteksi autentikasi token):
* `GET /api/dashboard/stats` — Menghitung metrik penting sekolah secara otomatis (total siswa aktif, jumlah jurusan, produk terjual, kemitraan DUDI).
* `CRUD /api/admin/users` — Pengelolaan data user (tambah, lihat, ubah role, ubah status aktif/nonaktif, hapus).
* `CRUD /api/admin/banners` & `posts` — Pengelolaan media berita dan banner beranda.
* `PATCH /api/koperasi/orders/{id}/status` — Pengubahan status pesanan produk TeFA oleh pengelola koperasi.
* `PATCH /api/eskul/registrations/{id}/status` — Keputusan penerimaan calon anggota eskul oleh pembina.
* `POST /api/guru/teaching-materials` — Pengunggahan file modul ajar oleh guru mata pelajaran.

---

### 🛡️ D. Validasi Data & Sanitasi Input (Data Integrity & Validation)
Back-End bertindak sebagai penjaga gerbang (*gatekeeper*) yang memastikan setiap data yang masuk dari form pengguna bersih dan valid sebelum menyentuh database:
* **Validasi Format:** Memeriksa format email, NISN angka, tanggal agenda yang masuk akal, dan nomor telepon seluler.
* **Pencegahan SQL Injection:** Memanfaatkan *PDO Prepared Statements* yang disediakan Eloquent ORM sehingga query SQL aman dari injeksi perintah berbahaya.
* **Pencegahan XSS (Cross-Site Scripting):** Melakukan sanitasi teks bebas (*strip_tags* / *htmlspecialchars*) agar script berbahaya tidak bisa diselipkan ke kolom berita atau komentar.

---

### 📁 E. Manajemen Unggahan Berkas (File Storage Management)
Back-End menangani siklus hidup (*lifecycle*) file media sekolah:
1. **Validasi File:** Memeriksa tipe file (MIME type: JPG, PNG, WEBP untuk gambar; PDF, DOCX untuk modul guru) dan ukuran maksimum (misal maks 2MB - 5MB) untuk mencegah pemborosan server.
2. **Pemberian Nama Unik:** File di-rename otomatis menggunakan hash unik atau timestamp agar tidak menimpa (*overwrite*) file lain dengan nama yang sama.
3. **Penyimpanan Aman:** File disimpan di direktori `storage/app/public` dan diakses publik melalui symlink aman `public/storage`.
4. **Pembersihan Otomatis:** Saat produk, modul, atau berita dihapus oleh admin, Back-End secara otomatis menghapus file fisik di harddisk server agar storage tidak membengkak (*garbage collection*).

---

### 🔄 F. Fitur Database Self-Healing & Seeder Cerdas
Pada proyek ini, Back-End menyematkan fitur canggih pada `AppServiceProvider`:
* **Skema Self-Healing:** Saat aplikasi dijalankan pertama kali atau dipindah ke server baru, Back-End otomatis mendeteksi kolom yang kurang (misal kolom `is_active` pada tabel `users`) dan menambahkannya tanpa harus mereset seluruh database.
* **Auto-Seeding Data Esensial:** Memastikan data penting seperti 7 Program Keahlian, visi-misi sekolah, susunan organisasi, dan akun Super Admin langsung tersedia jika tabel database masih kosong.
* **Normalisasi Data:** Membedakan secara akurat antara data siswa aktif di kelas dengan data alumni tracer study, sehingga angka statistik di dashboard selalu tepat dan akurat.

---

## ⚙️ 4. Penggunaan Fitur Lanjutan Basis Data: Trigger, Rollback, Commit, Function, dan Procedure

Dalam rekayasa perangkat lunak modern (*modern software engineering*) dan manajemen basis data relasional (RDBMS), terdapat 5 konsep penting yang menjaga integritas, otomasi, dan konsistensi data. 

Berikut adalah penjelasan mendalam mengenai peran kelima fitur ini, serta **bagaimana kelimanya diimplementasikan pada proyek Web Sekolah SMKN 1 Katapang**:

```
 ┌─────────────────────────────────────────────────────────────────────────────────────────┐
 │               FITUR DATABASE & BACK-END: PERAN SERTA IMPLEMENTASINYA                    │
 ├──────────────┬──────────────────────────────────────────┬───────────────────────────────┤
 │ Fitur        │ Konsep & Definisi Sederhana              │ Implementasi di Web Sekolah   │
 ├──────────────┼──────────────────────────────────────────┼───────────────────────────────┤
 │ 1. COMMIT    │ "Kukuhkan & Simpan Permanen"             │ DB::transaction / Auto-commit │
 │ 2. ROLLBACK  │ "Batalkan & Kembalikan ke Semula"        │ Exception Handler / Revert    │
 │ 3. TRIGGER   │ "Reaksi Otomatis Saat Terjadi Peristiwa" │ Foreign Key Cascade & Events  │
 │ 4. FUNCTION  │ "Rumus Perhitungan & Pengubah Nilai"     │ SQL Aggregates & Accessors    │
 │ 5. PROCEDURE │ "Rangkaian Prosedur / Alur Kerja Rumit"  │ Service Layer & Transactions  │
 └──────────────┴──────────────────────────────────────────┴───────────────────────────────┘
```

---

### 1️⃣ Database Transaction: COMMIT & ROLLBACK (Prinsip ACID)

#### A. Konsep Dasar
Bayangkan saat Anda mentransfer uang lewat ATM:
1. Saldo rekening Anda dipotong Rp 100.000.
2. Saldo rekening teman Anda ditambah Rp 100.000.

Apa jadinya jika listrik padam saat langkah 1 selesai, namun langkah 2 belum terlaksana? Uang Anda akan hilang tanpa sampai ke tujuan! 
Di sinilah peran **Database Transaction** dengan dua perintah kuncinya:
* **COMMIT:** Mengeksekusi dan menyimpan seluruh rangkaian perubahan ke database secara permanen **hanya jika semua langkah berhasil 100%**.
* **ROLLBACK:** Membatalkan seluruh langkah yang sudah sempat terjadi dan mengembalikan database ke kondisi awal persis seperti semula **jika terjadi kegagalan atau error pada langkah manapun**.

Prinsip ini dikenal sebagai **Atomicity** (satu kesatuan utuh: *semua berhasil, atau semua batal*).

#### B. Implementasi Nyata pada Proyek: Modul Transaksi Pemesanan Produk TeFA (`TefaOrderService.php`)
Pada unit Teaching Factory (TeFA) dan Koperasi sekolah, Back-End menerapkan **Database Transaction Atomik** menggunakan `DB::transaction()`:

```php
// File: app/Services/TefaOrderService.php
public function placeOrder(array $data): TefaOrder
{
    return DB::transaction(function () use ($data) {
        // 1. Kunci baris data stok (Pessimistic Locking) agar tidak terjadi bentrok
        $products = TefaProduct::whereIn('id', $productIds)->lockForUpdate()->get();

        // 2. Validasi ketersediaan stok produk
        foreach ($items as $item) {
            if ($product->stock < $item['quantity']) {
                // JIKA STOK TIDAK CUKUP: Lempar Exception -> Trigger Otomatis ROLLBACK!
                throw ValidationException::withMessages(['items' => 'Stok tidak mencukupi']);
            }
        }

        // 3. Simpan header order (TefaOrder)
        $order = TefaOrder::create([...]);

        // 4. Simpan rincian barang (TefaOrderItem) dan KURANGI STOK PRODUK
        foreach ($items as $item) {
            TefaOrderItem::create([...]);
            $product->decrement('stock', $item['quantity']); // Stok berkurang
        }

        // 5. Update total pembayaran
        $order->update(['total_price' => $totalPrice]);

        return $order; 
        // JIKA SEMUA BERHASIL SAMPAI DI SINI:
        // Database otomatis melakukan COMMIT secara permanen!
    });
}
```

* **Saat Transaksi Berhasil:** Laravel mengeksekusi `COMMIT`. Data pesanan tersimpan, rincian barang tercatat, dan stok barang di gudang otomatis terpotong secara sinkron.
* **Saat Transaksi Gagal (Misal: stok kurang, koneksi putus, server error):** Laravel otomatis mengeksekusi `ROLLBACK`. Order tidak jadi dibuat, dan stok barang **kembali utuh** ke jumlah semula. Tidak ada data sampah (*dirty data*) yang tersisa di database.

---

### 2️⃣ TRIGGER (Pemicu Otomatis)

#### A. Konsep Dasar
**Trigger** adalah blok kode khusus di database yang otomatis "terbangun dan dieksekusi" saat peristiwa tertentu terjadi pada tabel (*BEFORE / AFTER INSERT, UPDATE, DELETE*). 

Analogi sederhananya: Seperti alarm pintu toko. Ketika pintu terbuka (*event INSERT/UPDATE*), alarm otomatis berbunyi (*action trigger*) tanpa perlu ditekan manual oleh kasir.

#### B. Implementasi pada Proyek Web Sekolah:
Dalam arsitektur modern Laravel, Back-End menerapkan konsep Trigger pada dua tingkatan:

1. **Database-Level Trigger (Referential Cascade Triggers di Migrations):**
   Pada migrasi database relasional, Back-End memasang *referential actions* yang bertindak sebagai trigger otomasi:
   * **`cascadeOnDelete()`:** Jika sebuah entitas induk dihapus (misal Program Keahlian / Jurusan), database secara otomatis memicu penghapusan pada rombel kelas terkait (`classes`).
   * **`nullOnDelete()`:** Pada tabel `events` dan `posts`, jika user pembuat akun dihapus (`users`), database secara otomatis memicu pengubahan kolom `created_by` menjadi `NULL`, sehingga artikel dan agenda kegiatan sekolah **tidak ikut terhapus** dan dokumentasi sekolah tetap aman.

2. **Application-Level Triggers (Model Hooks & Observers):**
   * Saat status pendaftaran ekstrakurikuler (`ExtracurricularRegistration`) diubah menjadi `'approved'` oleh Pembina Eskul, sistem memicu pembaruan status keanggotaan siswa.
   * Saat pesanan TeFA dibatalkan (`status = 'cancelled'`), sistem memicu penambahan kembali kuota stok produk (*stock restore*).

---

### 3️⃣ FUNCTION (Fungsi Komputasi & Transformasi Nilai)

#### A. Konsep Dasar
**Function** adalah blok kode modular yang menerima satu atau beberapa nilai masukan (parameter), memproses perhitungan atau transformasi, dan **selalu mengembalikan sebuah nilai keluaran (*return value*)**.

#### B. Implementasi pada Proyek Web Sekolah:

1. **SQL Built-in Functions (Pada Query Database):**
   Back-End memanfaatkan berbagai fungsi bawaan SQL untuk efisiensi komputasi langsung di level engine database:
   * **Fungsi Agregasi:** `COUNT(id)` untuk menghitung total siswa aktif (`Student::where('status', 'aktif')->count()`), `SUM(subtotal)` untuk kalkulasi total pendapatan TeFA.
   * **Fungsi String & Tanggal:** `DATE(event_date)` untuk memfilter agenda yang akan datang, `LOWER(email)` untuk validasi unik tanpa sensitivitas huruf kapital.

2. **Eloquent Accessor & Mutator Functions (Di Level Model Back-End):**
   Back-End membuat fungsi khusus pada Model PHP untuk mengubah data mentah database menjadi data yang ramah untuk tampilan Front-End:
   * **Model User (`app/Models/User.php`):**
     ```php
     // Function Accessor untuk memastikan role selalu memiliki nama valid
     public function getRoleNameAttribute(): ?string
     {
         return $this->roles->first()?->role_name;
     }

     // Function Accessor untuk status aktif boolean
     public function getActiveAttribute(): bool
     {
         return (bool) ($this->attributes['is_active'] ?? true);
     }
     ```
   * **Model Program & Role (`app/Models/Program.php`, `Role.php`):**
     Memiliki function `getNameAttribute()` yang memetakan kolom database (`program_name`, `role_name`) ke atribut generik `name`, sehingga data tidak pernah berstatus `undefined` di antarmuka pengguna.

---

### 4️⃣ PROCEDURE (Stored Procedure / Service Action)

#### A. Konsep Dasar
Jika *Function* fokus menghasilkan nilai balik (misal rumus luas atau total harga), maka **Procedure (Stored Procedure)** adalah **kumpulan instruksi langkah-demi-langkah (*prosedur operasional*)** yang menjalankan tugas bisnis yang kompleks, melibatkan banyak tabel, percabangan logika (*if-else*), dan manipulasi data bertingkat.

#### B. Implementasi pada Proyek Web Sekolah:

1. **Pendekatan Modern (Service Class Architecture):**
   Di era pengembangan web enterprise modern berbasis Laravel, *Stored Procedure* yang biasanya ditulis manual dengan sintaks SQL murni di dalam database server (`CREATE PROCEDURE ...`) kini dialihkan ke **Service Layer / Action Class** seperti [TefaOrderService.php](file:///c:/SEKOLAH/12semester1/PROD%20%28PA%20ANGGA%29/web-sekolah/app/Services/TefaOrderService.php).

   **Mengapa dialihkan ke Service Layer Back-End?**
   * **Terkontrol Versi (Version Controlled):** Kode tersimpan rapi di Git repository bersama seluruh tim, bukan tersembunyi di dalam instalasi database server lokal.
   * **Dapat Diuji (Testable):** Mudah dibuatkan automated unit test dan feature test PHPUnit.
   * **Portabilitas Tinggi:** Jika sekolah berpindah dari database MySQL ke PostgreSQL atau SQL Server, kode alur prosedur tidak perlu ditulis ulang dari nol.

2. **Database Procedural Scripts (Database Seeder & Self-Healing Service):**
   Back-End menyusun prosedur otomatis di file [UserSeeder.php](file:///c:/SEKOLAH/12semester1/PROD%20%28PA%20ANGGA%29/web-sekolah/database/seeders/UserSeeder.php) dan [AppServiceProvider.php](file:///c:/SEKOLAH/12semester1/PROD%20%28PA%20ANGGA%29/web-sekolah/app/Providers/AppServiceProvider.php):
   * Prosedur otomatisasi pengecekan skema tabel saat server booting (`Schema::hasColumn`).
   * Prosedur migrasi data dinamis: jika tabel `users` belum memiliki kolom `is_active`, sistem secara otomatis mengeksekusi `ALTER TABLE users ADD COLUMN is_active BOOLEAN`.
   * Prosedur penyesuaian relasi siswa aktif vs alumni tracer study secara massal.

---

## 🎯 5. Kesimpulan Ringkas

Pekerjaan **Back-End Developer** pada proyek Web Sekolah SMKN 1 Katapang mencakup:
1. **Fondasi Data & Integritas Transaksi:** Merancang lebih dari 20 tabel database dan menjamin konsistensi data finansial/stok menggunakan **Database Transaction (`COMMIT` & `ROLLBACK`)**.
2. **Mesin Logika & Prosedur:** Menyediakan service layer yang berfungsi sebagai **Stored Procedure modern**, menjalankan validasi ketat, mitigasi *race condition*, dan proteksi data.
3. **Automasi & Transformasi:** Menggunakan **Trigger** pada database cascade relasi dan **Function Accessor** untuk memastikan tidak ada data bernilai `undefined`.
4. **Keamanan & Otorisasi:** Mengunci sistem dengan proteksi login Sanctum, pembatasan rate limit, hashing password, serta pemisahan wewenang 5 role pengguna.

---
*Dokumen ini dibuat sebagai panduan teknis dan laporan pertanggungjawaban pengembangan Back-End Web Sekolah SMKN 1 Katapang.*

