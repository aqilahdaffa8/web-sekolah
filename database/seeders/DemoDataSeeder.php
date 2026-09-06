<?php

namespace Database\Seeders;

use App\Models\DudiPartner;
use App\Models\JobVacancy;
use App\Models\Student;
use App\Models\TracerStudy;
use App\Models\Post;
use App\Models\Event;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Achievement;
use App\Models\TefaProduct;
use App\Models\TefaOrder;
use App\Models\TefaOrderItem;
use App\Models\Program;
use App\Models\Category;
use App\Models\Banner;
use App\Models\LearningModule;
use App\Models\Facility;
use App\Models\SiteSetting;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSiteSettings();
        $this->seedBanners();
        $this->seedCategories();
        $this->seedStudents();
        $this->seedDudiPartners();
        $this->seedJobVacancies();
        $this->seedTracerStudies();
        $this->seedPosts();
        $this->seedEvents();
        $this->seedExtracurriculars();
        $this->seedExtracurricularRegistrations();
        $this->seedAchievements();
        $this->seedTefaProducts();
        $this->seedTefaOrders();
        $this->seedLearningModules();
        $this->seedFacilities();
    }

    // ── Site Settings ─────────────────────────────────────────
    private function seedSiteSettings(): void
    {
        $settings = [
            'school_name'     => 'SMKN 1 Katapang',
            'school_tagline'  => 'Mencetak Generasi Unggul, Berkarakter & Siap Kerja',
            'school_address'  => 'Jl. Ceuri Terusan Kopo No.KM 13.5, Katapang, Kec. Katapang, Kabupaten Bandung, Jawa Barat 40921',
            'school_phone'    => '(022) 589-3737',
            'school_email'    => 'info@smkn1katapang.sch.id',
            'school_whatsapp' => '081234567890',
        ];

        foreach ($settings as $k => $v) {
            SiteSetting::updateOrCreate(['key' => $k], ['value' => $v]);
        }
        $this->command->info('✅ Site Settings seeded');
    }

    // ── Banners ───────────────────────────────────────────────
    private function seedBanners(): void
    {
        $banners = [
            [
                'image_url'  => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600&auto=format&fit=crop&q=80',
                'link_url'   => '/profil',
                'sort_order' => 1,
            ],
            [
                'image_url'  => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=1600&auto=format&fit=crop&q=80',
                'link_url'   => '/katalog',
                'sort_order' => 2,
            ],
            [
                'image_url'  => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=1600&auto=format&fit=crop&q=80',
                'link_url'   => '/hubin',
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $b) {
            Banner::firstOrCreate(['image_url' => $b['image_url']], $b);
        }
        $this->command->info('✅ Banners seeded (' . count($banners) . ')');
    }

    // ── Categories ────────────────────────────────────────────
    private function seedCategories(): void
    {
        $cats = [
            'Berita & Pengumuman',
            'Prestasi Siswa',
            'Kegiatan Sekolah',
            'Industri & Vokasi',
            'TeFA & Kewirausahaan',
        ];

        foreach ($cats as $c) {
            Category::firstOrCreate(
                ['slug' => Str::slug($c)],
                ['category_name' => $c, 'slug' => Str::slug($c)]
            );
        }
        $this->command->info('✅ Categories seeded');
    }

    // ── Students ──────────────────────────────────────────────
    private function seedStudents(): void
    {
        $classId = ClassRoom::value('id') ?? 1;

        $studentNames = [
            'Aditya Pratama', 'Alya Nuraini', 'Bayu Firmansyah', 'Citra Lestari',
            'Dimas Saputra', 'Elsa Maulida', 'Fajar Ramadhan', 'Gita Permata',
            'Hafiz Maulana', 'Indah Kusuma', 'Joko Triyono', 'Kurnia Dewi',
            'Luthfi Hakim', 'Mega Utami', 'Naufal Rizky', 'Olivia Safitri',
            'Panji Asmara', 'Qonita Zahra', 'Rafi Setiawan', 'Siti Rahmawati',
            'Taufik Hidayat', 'Ulfa Nurjanah', 'Vicky Prasetyo', 'Wulan Dari',
            'Yusuf Arifin', 'Zahra Amalia', 'Budi Santoso', 'Sari Wulandari',
            'Ahmad Fauzi', 'Dewi Rahayu', 'Rizky Pratama', 'Fitria Anggraini',
        ];

        foreach ($studentNames as $idx => $name) {
            $nis = '2024' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            Student::firstOrCreate(
                ['nis' => $nis],
                [
                    'name'     => $name,
                    'nis'      => $nis,
                    'class_id' => $classId,
                    'status'   => 'aktif',
                ]
            );
        }
        $this->command->info('✅ Active Students seeded (' . count($studentNames) . ')');
    }

    // ── DUDI Partners ─────────────────────────────────────────
    private function seedDudiPartners(): void
    {
        $partners = [
            ['company_name' => 'PT. Telkom Indonesia (Persero) Tbk', 'industry_field' => 'Teknologi & Telekomunikasi'],
            ['company_name' => 'PT. Astra Honda Motor',              'industry_field' => 'Otomotif & Manufaktur'],
            ['company_name' => 'PT. Bank Rakyat Indonesia Tbk',      'industry_field' => 'Perbankan & Keuangan'],
            ['company_name' => 'PT. Pertamina (Persero)',           'industry_field' => 'Energi & Migas'],
            ['company_name' => 'PT. Unilever Indonesia Tbk',         'industry_field' => 'Konsumsi & FMCG'],
            ['company_name' => 'PT. Indofood CBP Sukses Makmur',     'industry_field' => 'Industri Pangan & Pengolahan'],
            ['company_name' => 'PT. Len Industri (Persero)',         'industry_field' => 'Elektronika & Pertahanan'],
            ['company_name' => 'PT. PLN (Persero)',                 'industry_field' => 'Kelistrikan & Energi Baru'],
            ['company_name' => 'PT. Dirgantara Indonesia',          'industry_field' => 'Dirgantara & Manufaktur Presisi'],
            ['company_name' => 'PT. Kimia Farma Tbk',                'industry_field' => 'Farmasi & Kesehatan'],
            ['company_name' => 'PT. Wijaya Karya (Persero) Tbk',     'industry_field' => 'Konstruksi & Infrastruktur'],
            ['company_name' => 'CV. Mitra Digital Nusantara',       'industry_field' => 'Software & Pemasaran Digital'],
            ['company_name' => 'PT. Tokopedia & GoTo Group',         'industry_field' => 'E-Commerce & Startup'],
            ['company_name' => 'PT. Pindad (Persero)',               'industry_field' => 'Industri Manufaktur & Alutsista'],
            ['company_name' => 'PT. Bank Central Asia Tbk',          'industry_field' => 'Perbankan & Finansial Digital'],
        ];

        foreach ($partners as $p) {
            DudiPartner::firstOrCreate(['company_name' => $p['company_name']], $p);
        }
        $this->command->info('✅ DUDI Partners seeded (' . count($partners) . ')');
    }

    // ── Job Vacancies ─────────────────────────────────────────
    private function seedJobVacancies(): void
    {
        $dudiMap = DudiPartner::pluck('id', 'company_name')->toArray();

        $vacancies = [
            ['company' => 'PT. Telkom Indonesia (Persero) Tbk', 'job_title' => 'Junior Network & Fiber Optic Engineer', 'status' => 'open',   'location' => 'Bandung, Jawa Barat', 'salary' => 'Rp 4.500.000 – Rp 6.000.000'],
            ['company' => 'PT. Astra Honda Motor',              'job_title' => 'Teknisi Mekanik & Assembler Motor',      'status' => 'open',   'location' => 'Karawang, Jawa Barat', 'salary' => 'Rp 5.200.000 – Rp 7.500.000'],
            ['company' => 'PT. Bank Rakyat Indonesia Tbk',      'job_title' => 'Customer Service & Digital Banking Staff', 'status' => 'open', 'location' => 'Wilayah Jawa Barat',  'salary' => 'Rp 4.200.000 – Rp 5.800.000'],
            ['company' => 'CV. Mitra Digital Nusantara',       'job_title' => 'Junior Fullstack Web Developer (Laravel)', 'status' => 'open',  'location' => 'Bandung / Hybrid',    'salary' => 'Rp 4.500.000 – Rp 6.500.000'],
            ['company' => 'PT. PLN (Persero)',                 'job_title' => 'Operator Distribusi Listrik Tegangan Rendah', 'status' => 'open', 'location' => 'Bandung Raya',      'salary' => 'Rp 5.000.000 – Rp 7.000.000'],
            ['company' => 'PT. Unilever Indonesia Tbk',         'job_title' => 'Operator Packaging & Quality Inspector', 'status' => 'open',   'location' => 'Cikarang, Bekasi',    'salary' => 'Rp 4.800.000 – Rp 6.200.000'],
            ['company' => 'PT. Len Industri (Persero)',         'job_title' => 'Teknisi Instalasi Sistem Elektronika',  'status' => 'open',   'location' => 'Bandung, Jawa Barat', 'salary' => 'Rp 4.500.000 – Rp 6.000.000'],
            ['company' => 'PT. Tokopedia & GoTo Group',         'job_title' => 'Quality Assurance Support & IT Helpdesk', 'status' => 'open',  'location' => 'Jakarta / Remote',    'salary' => 'Rp 5.500.000 – Rp 7.500.000'],
            ['company' => 'PT. Kimia Farma Tbk',                'job_title' => 'Staf Gudang Logistik & Asisten Farmasi', 'status' => 'open',   'location' => 'Bandung Selatan',     'salary' => 'Rp 4.000.000 – Rp 5.200.000'],
            ['company' => 'PT. Indofood CBP Sukses Makmur',     'job_title' => 'Operator Mesin Produksi Makanan',        'status' => 'closed', 'location' => 'Cimahi, Jawa Barat',  'salary' => 'Rp 4.300.000 – Rp 5.500.000'],
        ];

        foreach ($vacancies as $v) {
            $dudiId = $dudiMap[$v['company']] ?? DudiPartner::value('id') ?? 1;

            JobVacancy::firstOrCreate(
                ['job_title' => $v['job_title'], 'dudi_id' => $dudiId],
                [
                    'dudi_id'     => $dudiId,
                    'job_title'   => $v['job_title'],
                    'description' => "📍 Lokasi: {$v['location']}\n💰 Kisaran Gaji: {$v['salary']}\n\nKualifikasi: Lulusan SMK/SMKN jurusan terkait, berdisiplin tinggi, memiliki sertifikat kompetensi keahlian. Silakan ajukan lamaran melalui BKK SMKN 1 Katapang.",
                    'status'      => $v['status'],
                ]
            );
        }
        $this->command->info('✅ Job Vacancies seeded (' . count($vacancies) . ')');
    }

    // ── Tracer Studies ────────────────────────────────────────
    private function seedTracerStudies(): void
    {
        $students = Student::pluck('id')->toArray();
        $classId  = ClassRoom::value('id') ?? 1;

        $alumni = [
            ['Budi Santoso',      2022, 'Kerja',     'PT. Telkom Indonesia (Persero) Tbk'],
            ['Sari Wulandari',    2022, 'Kuliah',    'Universitas Pendidikan Indonesia (UPI)'],
            ['Ahmad Fauzi',       2021, 'Kerja',     'PT. Astra Honda Motor'],
            ['Dewi Rahayu',       2023, 'Kuliah',    'Politeknik Negeri Bandung (POLBAN)'],
            ['Rizky Pratama',     2021, 'Kerja',     'CV. Mitra Digital Nusantara'],
            ['Fitria Anggraini',  2022, 'Wirausaha', 'Usaha Kuliner Mandiri "Dapur Rasa Katapang"'],
            ['Hendra Kusuma',     2020, 'Kerja',     'PT. PLN (Persero)'],
            ['Yuni Astuti',       2023, 'Kuliah',    'Universitas Padjadjaran (UNPAD)'],
            ['Dani Setiawan',     2022, 'Kerja',     'PT. Bank Rakyat Indonesia Tbk'],
            ['Maya Putri',        2021, 'Wirausaha', 'Fashion Craft "Maya Boutique"'],
            ['Rendi Firmansyah',  2020, 'Kerja',     'PT. Pertamina (Persero)'],
            ['Lestari Nurhayati', 2023, 'Kuliah',    'Institut Teknologi Bandung (ITB)'],
            ['Agus Wibowo',       2022, 'Kerja',     'PT. Unilever Indonesia Tbk'],
            ['Sinta Ramadhani',   2021, 'Kerja',     'PT. Len Industri (Persero)'],
            ['Fajar Nugroho',     2020, 'Kerja',     'PT. Dirgantara Indonesia'],
        ];

        foreach ($alumni as $idx => [$name, $year, $status, $company]) {
            $studentId = $students[$idx] ?? Student::firstOrCreate(
                ['name' => $name],
                ['name' => $name, 'nis' => 'ALUMNI' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT), 'class_id' => $classId]
            )->id;

            TracerStudy::firstOrCreate(
                ['student_id' => $studentId, 'graduation_year' => $year],
                [
                    'student_id'             => $studentId,
                    'graduation_year'        => $year,
                    'current_status'         => $status,
                    'company_or_campus_name' => $company,
                ]
            );
        }
        $this->command->info('✅ Tracer Studies seeded (' . count($alumni) . ')');
    }

    // ── Posts (Berita) ────────────────────────────────────────
    private function seedPosts(): void
    {
        $authorId = User::where('email', 'superadmin@smk.sch.id')->value('id') ?? 1;
        $catPrestasi = Category::where('slug', 'prestasi-siswa')->value('id') ?? 1;
        $catKegiatan = Category::where('slug', 'kegiatan-sekolah')->value('id') ?? 1;
        $catIndustri = Category::where('slug', 'industri-vokasi')->value('id') ?? 1;

        $posts = [
            [
                'title'       => 'Siswa SMKN 1 Katapang Raih Medali Emas LKS Tingkat Provinsi Jawa Barat',
                'category_id' => $catPrestasi,
                'image_url'   => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop&q=80',
                'content'     => 'Kabar membanggakan datang dari kontingen LKS SMKN 1 Katapang. Siswa jurusan Rekayasa Perangkat Lunak berhasil meraih juara 1 dan medali emas dalam cabang lomba Web Technologies tingkat Provinsi Jawa Barat. Prestasi ini mengantarkan sekolah menuju ajang LKS Nasional tahun 2026. Kepala Sekolah mengapresiasi dedikasi para guru pembimbing serta ketekunan siswa dalam mempersiapkan diri.',
                'status'      => 'published',
            ],
            [
                'title'       => 'SMKN 1 Katapang Resmikan Lab Komputer Modern Berstandar Industri 4.0',
                'category_id' => $catKegiatan,
                'image_url'   => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80',
                'content'     => 'Untuk memperkuat pembelajaran vokasi berbasis kompetensi industri, SMKN 1 Katapang meresmikan laboratorium komputer baru berstandar industri dengan 72 unit workstation berkecepatan tinggi, konektivitas serat optik gigabit, dan ruang kolaborasi agile. Fasilitas ini didukung oleh kemitraan strategis dengan BUMN teknologi terkemuka di Bandung.',
                'status'      => 'published',
            ],
            [
                'title'       => 'Penandatanganan MoU Kerjasama Kelas Industri dengan 15 Perusahaan Nasional',
                'category_id' => $catIndustri,
                'image_url'   => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                'content'     => 'SMKN 1 Katapang memperluas jejaring kemitraan DUDI dengan menandatangani nota kesepahaman (MoU) bersama 15 perusahaan manufaktur, perbankan, dan teknologi. Kerjasama ini meliputi sinkronisasi kurikulum merdeka vokasi, program guru tamu dari praktisi industri, magang kerja bersertifikat, serta rekrutmen prioritas bagi alumni lulusan.',
                'status'      => 'published',
            ],
            [
                'title'       => 'Produk TeFA Siswa Tembus Pasar Retail dan Pameran Vokasi Nasional',
                'category_id' => $catPrestasi,
                'image_url'   => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
                'content'     => 'Unit Teaching Factory (TeFA) SMKN 1 Katapang berhasil memamerkan produk kriya kulit handmade, kue lava brownies produksi siswa boga, dan paket aplikasi manajemen sekolah di ajang Vokasi Land Expo. Pesanan terus mengalir dari berbagai instansi mitra dan konsumen retail.',
                'status'      => 'published',
            ],
            [
                'title'       => 'Workshop Kewirausahaan Vokasi: Membangun Startup Mandiri di Kalangan Pelajar',
                'category_id' => $catKegiatan,
                'image_url'   => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&auto=format&fit=crop&q=80',
                'content'     => 'Lebih dari 300 siswa kelas XII mengikuti pelatihan kewirausahaan digital dan inkubasi bisnis mini yang diselenggarakan oleh BKK dan Koperasi SMKN 1 Katapang bersama HIPMI. Pelatihan ini melatih keterampilan pitching, validasi produk, serta pengelolaan kas usaha.',
                'status'      => 'published',
            ],
            [
                'title'       => 'Kunjungan Industri Siswa TKJ & Otomotif ke Pabrik Manufaktur Cikarang',
                'category_id' => $catIndustri,
                'image_url'   => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&auto=format&fit=crop&q=80',
                'content'     => 'Sebanyak 180 siswa didampingi guru kejuruan berkunjung langsung ke fasilitas perakitan modern untuk melihat proses otomatisasi robotik, manajemen K3 industri, dan standar kualitas Six Sigma di fasilitas pabrik perakitan berskala multinasional.',
                'status'      => 'published',
            ],
        ];

        foreach ($posts as $p) {
            Post::firstOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'title'       => $p['title'],
                    'slug'        => Str::slug($p['title']),
                    'content'     => $p['content'],
                    'image_url'   => $p['image_url'],
                    'category_id' => $p['category_id'],
                    'status'      => $p['status'],
                    'author_id'   => $authorId,
                ]
            );
        }
        $this->command->info('✅ Posts seeded (' . count($posts) . ')');
    }

    // ── Events / Agenda ────────────────────────────────────────
    private function seedEvents(): void
    {
        $events = [
            ['title' => 'Asesmen Bakat Minat & Uji Sertifikasi Kompetensi Keahlian', 'days' => 3,  'desc' => 'Uji kompetensi resmi LSP-P1 bagi seluruh siswa tingkat akhir SMKN 1 Katapang.'],
            ['title' => 'Gelar Karya TeFA & Pameran Inovasi Vokasi 2026',            'days' => 10, 'desc' => 'Pameran terbuka produk dan hasil riset terapan seluruh kompetensi keahlian.'],
            ['title' => 'Job Fair & Industry Career Expo SMKN 1 Katapang',          'days' => 18, 'desc' => 'Bursa kerja terbuka menghadirkan 25+ perusahaan mitra untuk rekrutmen alumni.'],
            ['title' => 'Lomba Olahraga & Seni Antar Kelas (Classmeeting)',           'days' => 25, 'desc' => 'Kompetisi futsal, basket, tari tradisional, dan e-sport antar jurusan.'],
            ['title' => 'Sosialisasi Magang PKL Gelombang II Tahun Pelajaran 2026',   'days' => 35, 'desc' => 'Pembekalan teknis dan budaya kerja industri bagi siswa kelas XI.'],
        ];

        foreach ($events as $ev) {
            $date = now()->addDays($ev['days'])->toDateString();
            Event::firstOrCreate(
                ['title' => $ev['title']],
                [
                    'title'       => $ev['title'],
                    'description' => $ev['desc'] . ' Lokasi: Kampus SMKN 1 Katapang.',
                    'event_date'  => $date,
                ]
            );
        }
        $this->command->info('✅ Events seeded (' . count($events) . ')');
    }

    // ── Extracurriculars ───────────────────────────────────────
    private function seedExtracurriculars(): void
    {
        $coachId = User::where('email', 'eskul@smk.sch.id')->value('id');

        $eskuls = [
            ['name' => 'Robotika & IoT',             'schedule' => 'Sabtu, 08.00–12.00', 'desc' => 'Riset dan perancangan robotika industri, mikrokontroler Arduino/ESP32, dan automasi cerdas.'],
            ['name' => 'Pramuka Ambalan Katapang',   'schedule' => 'Jumat, 13.30–16.30', 'desc' => 'Kepramukaan wajib sebagai wadah pembentukan disiplin, karakter tangguh, dan kepemimpinan.'],
            ['name' => 'Futsal & Sepak Bola',        'schedule' => 'Rabu & Sabtu, 15.30', 'desc' => 'Latihan fisik, taktik sepak bola, dan persiapan kejuaraan turnamen antar SMK se-Jawa Barat.'],
            ['name' => 'PMR (Palang Merah Remaja)',  'schedule' => 'Sabtu, 08.30–10.30', 'desc' => 'Pertolongan pertama, donor darah berkala, edukasi kesehatan, dan tanggap darurat bencana.'],
            ['name' => 'English Club & Debating',    'schedule' => 'Selasa, 14.30–16.00', 'desc' => 'Pengembangan kemampuan bahasa Inggris aktif, public speaking, dan kompetisi debat vokasi.'],
            ['name' => 'Seni Tari Tradisional',      'schedule' => 'Kamis, 14.00–16.00', 'desc' => 'Pelestarian kebudayaan tari Sunda dan nusantara untuk penampilan festival dan perpisahan.'],
            ['name' => 'Paduan Suara & Musik',       'schedule' => 'Rabu, 14.00–16.00',  'desc' => 'Olah vokal, harmoni paduan suara sekolah, dan ansambel musik akustik modern.'],
            ['name' => 'Desain Grafis & Multimedia', 'schedule' => 'Sabtu, 09.00–12.00', 'desc' => 'Pelatihan UI/UX design, visual branding, fotografi kamera mirrorless, dan video editing.'],
            ['name' => 'Paskibraka Satuan Katapang', 'schedule' => 'Senin & Kamis, 15.30', 'desc' => 'Latihan baris-berbaris presisi, upacara bendera kenegaraan, dan pembinaan kedisiplinan.'],
            ['name' => 'Rohis & Tahfidz Quran',      'schedule' => 'Jumat, 11.30–13.00', 'desc' => 'Pembinaan akhlak, kajian keislaman pelajar, tahsin tilawah, dan tahfidz Al-Quran.'],
        ];

        foreach ($eskuls as $e) {
            Extracurricular::firstOrCreate(
                ['name' => $e['name']],
                [
                    'name'        => $e['name'],
                    'description' => $e['desc'],
                    'schedule'    => $e['schedule'],
                    'coach_id'    => $coachId,
                ]
            );
        }
        $this->command->info('✅ Extracurriculars seeded (' . count($eskuls) . ')');
    }

    // ── Extracurricular Registrations ──────────────────────────
    private function seedExtracurricularRegistrations(): void
    {
        $students = Student::pluck('id')->toArray();
        $eskuls   = Extracurricular::pluck('id')->toArray();

        if (empty($students) || empty($eskuls)) return;

        $statuses = ['approved', 'approved', 'pending', 'approved', 'pending', 'approved'];

        foreach ($students as $idx => $studentId) {
            if ($idx >= 15) break;
            $eskulId = $eskuls[$idx % count($eskuls)];
            $status  = $statuses[$idx % count($statuses)];

            ExtracurricularRegistration::firstOrCreate(
                ['student_id' => $studentId, 'extracurricular_id' => $eskulId],
                [
                    'student_id'         => $studentId,
                    'extracurricular_id' => $eskulId,
                    'status'             => $status,
                ]
            );
        }
        $this->command->info('✅ Extracurricular Registrations seeded (15)');
    }

    // ── Achievements ──────────────────────────────────────────
    private function seedAchievements(): void
    {
        $eskulMap = Extracurricular::pluck('id', 'name')->toArray();

        $achievements = [
            ['title' => 'Juara 1 Lomba Kompetensi Siswa (LKS) Web Technologies', 'desc' => 'Meraih medali emas LKS tingkat Provinsi Jawa Barat 2025 dengan nilai tertinggi di modul backend & frontend.', 'eskul' => 'Robotika & IoT'],
            ['title' => 'Juara 1 Turnamen Futsal Pelajar Piala Gubernur Jabar', 'desc' => 'Tim futsal SMKN 1 Katapang menjuarai turnamen futsal antar SMK/SMA se-Bandung Raya.', 'eskul' => 'Futsal & Sepak Bola'],
            ['title' => 'Medali Perak Olimpiade Robotika Nasional (Kategori IoT)', 'desc' => 'Inovasi alat smart farming otomatis berbasis panel surya dan sensor kelembaban tanah.', 'eskul' => 'Robotika & IoT'],
            ['title' => 'Juara 2 Lomba Debat Bahasa Inggris Tingkat Wilayah VII', 'desc' => 'Membuktikan kompetensi komunikasi global siswa vokasi di ajang English Speech & Debate Fest.', 'eskul' => 'English Club & Debating'],
            ['title' => 'Penghargaan Tata Kelola Unit Produksi TeFA Terbaik', 'desc' => 'Diberikan oleh Kemendikbudristek RI atas inovasi hilirisasi produk kriya dan boga siswa.', 'eskul' => null],
            ['title' => 'Juara 1 Festival Tari Tradisional Kreasi Daerah', 'desc' => 'Penampilan memukau tari Jaipong kreasi baru di Teater Tertutup Dago Tea House Bandung.', 'eskul' => 'Seni Tari Tradisional'],
        ];

        foreach ($achievements as $a) {
            $eskulId = $a['eskul'] ? ($eskulMap[$a['eskul']] ?? null) : null;
            Achievement::firstOrCreate(
                ['title' => $a['title']],
                [
                    'title'              => $a['title'],
                    'description'        => $a['desc'],
                    'extracurricular_id' => $eskulId,
                ]
            );
        }
        $this->command->info('✅ Achievements seeded (' . count($achievements) . ')');
    }

    // ── TeFA Products ──────────────────────────────────────────
    private function seedTefaProducts(): void
    {
        $programId = Program::value('id') ?? 1;

        $products = [
            [
                'product_name' => 'Tas Kulit Handmade Premium Katapang',
                'price'        => 350000,
                'stock'        => 18,
                'image_url'    => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Tas selempang kulit sapi asli dibuat manual dengan teknik jahit tangan presisi oleh siswa jurusan Kriya Kulit.',
            ],
            [
                'product_name' => 'Kue Brownies Coklat Lava Lumer',
                'price'        => 45000,
                'stock'        => 35,
                'image_url'    => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Brownies panggang lembut dengan coklat leleh di dalamnya. 100% bahan premium halal buatan siswa Tata Boga.',
            ],
            [
                'product_name' => 'Paket Web Profil Perusahaan & Sekolah',
                'price'        => 1750000,
                'stock'        => 10,
                'image_url'    => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Layanan pengembangan website responsive, SEO-ready, dan dashboard admin dinamis garapan siswa Rekayasa Perangkat Lunak.',
            ],
            [
                'product_name' => 'Kain Batik Tulis Motif Khas Parahyangan',
                'price'        => 280000,
                'stock'        => 20,
                'image_url'    => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Kain batik tulis motif daun teh dan pegunungan khas Jawa Barat, dibuat dengan canting tembaga manual.',
            ],
            [
                'product_name' => 'Jasa Desain Identitas Visual & Logo Brand',
                'price'        => 350000,
                'stock'        => 25,
                'image_url'    => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Paket lengkap desain logo vector, guideline warna, mock-up kartu nama, dan feed media sosial dari siswa DKV.',
            ],
            [
                'product_name' => 'Jasa Servis Ringan & Tune-Up Motor Injeksi',
                'price'        => 65000,
                'stock'        => 50,
                'image_url'    => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Layanan perawatan berkala motor matic dan bebek oleh teknisi siswa Otomotif dengan panduan SOP bengkel resmi Astra.',
            ],
            [
                'product_name' => 'Instalasi Jaringan LAN & Hotspot WiFi Mikrotik',
                'price'        => 450000,
                'stock'        => 12,
                'image_url'    => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Jasa penarikan kabel UTP Cat6, crimping, konfigurasi router Mikrotik, dan manajemen bandwidth kantor/sekolah.',
            ],
            [
                'product_name' => 'Tempe Krispi Aneka Rasa Gurih Renyah',
                'price'        => 15000,
                'stock'        => 80,
                'image_url'    => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&auto=format&fit=crop&q=80',
                'desc'         => 'Snack tempe kedelai lokal renyah dengan bumbu rempah alami tanpa pengawet. Tersedia rasa original, balado, dan keju.',
            ],
        ];

        foreach ($products as $p) {
            TefaProduct::firstOrCreate(
                ['product_name' => $p['product_name']],
                [
                    'product_name' => $p['product_name'],
                    'description'  => $p['desc'],
                    'price'        => $p['price'],
                    'stock'        => $p['stock'],
                    'image_url'    => $p['image_url'],
                    'program_id'   => $programId,
                ]
            );
        }
        $this->command->info('✅ TeFA Products seeded (' . count($products) . ')');
    }

    // ── TeFA Orders ───────────────────────────────────────────
    private function seedTefaOrders(): void
    {
        $products = TefaProduct::all();
        if ($products->isEmpty()) return;

        $orders = [
            [
                'order_code'    => 'ORD-20260905-001',
                'buyer_name'    => 'Drs. H. Mulyana (SMKN 2 Baleendah)',
                'buyer_contact' => '081234567890',
                'status'        => 'completed',
                'items'         => [
                    ['product_idx' => 0, 'qty' => 2],
                    ['product_idx' => 1, 'qty' => 5],
                ],
            ],
            [
                'order_code'    => 'ORD-20260905-002',
                'buyer_name'    => 'Ibu Hj. Kartika Sari',
                'buyer_contact' => '082198765432',
                'status'        => 'paid',
                'items'         => [
                    ['product_idx' => 1, 'qty' => 4],
                    ['product_idx' => 3, 'qty' => 1],
                ],
            ],
            [
                'order_code'    => 'ORD-20260905-003',
                'buyer_name'    => 'PT Daya Adicipta Motora',
                'buyer_contact' => '085711223344',
                'status'        => 'pending',
                'items'         => [
                    ['product_idx' => 2, 'qty' => 1],
                ],
            ],
            [
                'order_code'    => 'ORD-20260905-004',
                'buyer_name'    => 'Bpk. Ridwan Fauzi',
                'buyer_contact' => '087812349900',
                'status'        => 'completed',
                'items'         => [
                    ['product_idx' => 5, 'qty' => 3],
                ],
            ],
            [
                'order_code'    => 'ORD-20260905-005',
                'buyer_name'    => 'Koperasi Guru SMKN 1 Katapang',
                'buyer_contact' => '081399887766',
                'status'        => 'paid',
                'items'         => [
                    ['product_idx' => 7, 'qty' => 20],
                ],
            ],
        ];

        foreach ($orders as $o) {
            $total = 0;
            $itemsData = [];

            foreach ($o['items'] as $item) {
                $prod = $products[$item['product_idx'] % $products->count()];
                $subtotal = $prod->price * $item['qty'];
                $total += $subtotal;
                $itemsData[] = [
                    'product_id' => $prod->id,
                    'quantity'   => $item['qty'],
                    'subtotal'   => $subtotal,
                ];
            }

            $order = TefaOrder::firstOrCreate(
                ['order_code' => $o['order_code']],
                [
                    'order_code'    => $o['order_code'],
                    'buyer_name'    => $o['buyer_name'],
                    'buyer_contact' => $o['buyer_contact'],
                    'total_price'   => $total,
                    'status'        => $o['status'],
                ]
            );

            foreach ($itemsData as $it) {
                TefaOrderItem::firstOrCreate(
                    ['order_id' => $order->id, 'product_id' => $it['product_id']],
                    [
                        'order_id'   => $order->id,
                        'product_id' => $it['product_id'],
                        'quantity'   => $it['quantity'],
                        'subtotal'   => $it['subtotal'],
                    ]
                );
            }
        }
        $this->command->info('✅ TeFA Orders seeded (' . count($orders) . ')');
    }

    // ── Learning Modules ───────────────────────────────────────
    private function seedLearningModules(): void
    {
        $teacherId = User::where('email', 'guru@smk.sch.id')->value('id') ?? 1;
        $programId = Program::value('id') ?? 1;

        $modules = [
            ['title' => 'Modul Ajar Pemrograman Web & Perangkat Bergerak (Laravel 11)', 'file_url' => '/documents/modul-laravel-11.pdf'],
            ['title' => 'Modul Desain Basis Data Relasional & Normalisasi MySQL',         'file_url' => '/documents/modul-basis-data.pdf'],
            ['title' => 'Modul Konfigurasi Routing & Switching Jaringan Komputer',       'file_url' => '/documents/modul-routing-jaringan.pdf'],
            ['title' => 'Modul Pemeliharaan Mesin Otomotif & Diagnosa EFI Modern',        'file_url' => '/documents/modul-mesin-otomotif.pdf'],
            ['title' => 'Modul Teknik Pengolahan Audio Visual & Tata Kamera Broadcasting', 'file_url' => '/documents/modul-broadcasting.pdf'],
        ];

        foreach ($modules as $m) {
            LearningModule::firstOrCreate(
                ['title' => $m['title']],
                [
                    'title'      => $m['title'],
                    'file_url'   => $m['file_url'],
                    'program_id' => $programId,
                    'teacher_id' => $teacherId,
                ]
            );
        }
        $this->command->info('✅ Learning Modules seeded (' . count($modules) . ')');
    }

    // ── Facilities ────────────────────────────────────────────
    private function seedFacilities(): void
    {
        $programId = Program::value('id') ?? 1;

        $facilities = [
            [
                'facility_name' => 'Laboratorium Komputer Software Engineering',
                'image_url'     => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'facility_name' => 'Bengkel Praktik Otomotif & Mesin Presisi',
                'image_url'     => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'facility_name' => 'Studio Penyiaran & Multimedia Katapang TV',
                'image_url'     => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'facility_name' => 'Ruang Workshop Teaching Factory (TeFA) Kriya Kulit',
                'image_url'     => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'facility_name' => 'Gedung Olahraga & Lapangan Futsal Indoor',
                'image_url'     => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'facility_name' => 'Perpustakaan Digital & Ruang Riset Pelajar',
                'image_url'     => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=600&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($facilities as $f) {
            Facility::firstOrCreate(
                ['facility_name' => $f['facility_name']],
                [
                    'facility_name' => $f['facility_name'],
                    'image_url'     => $f['image_url'],
                    'program_id'    => $programId,
                ]
            );
        }
        $this->command->info('✅ Facilities seeded (' . count($facilities) . ')');
    }
}
