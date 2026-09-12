<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensureDatabaseSchemaUpToDate();
    }

    /**
     * Self-healing migration check to ensure columns exist even if migrations haven't run yet.
     */
    private function ensureDatabaseSchemaUpToDate(): void
    {
        try {
            // 1. extracurricular_registrations
            if (Schema::hasTable('extracurricular_registrations')) {
                if (! Schema::hasColumn('extracurricular_registrations', 'notes')) {
                    Schema::table('extracurricular_registrations', function (Blueprint $table) {
                        $table->text('notes')->nullable()->after('status');
                    });
                }
                if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                    DB::statement("ALTER TABLE extracurricular_registrations MODIFY status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
                }
            }

            // 2. facilities
            if (Schema::hasTable('facilities')) {
                Schema::table('facilities', function (Blueprint $table) {
                    if (! Schema::hasColumn('facilities', 'description')) {
                        $table->text('description')->nullable()->after('facility_name');
                    }
                    if (! Schema::hasColumn('facilities', 'location')) {
                        $table->string('location')->nullable()->after('description');
                    }
                    if (! Schema::hasColumn('facilities', 'capacity')) {
                        $table->integer('capacity')->nullable()->after('location');
                    }
                });
            }

            // 3. learning_modules
            if (Schema::hasTable('learning_modules')) {
                Schema::table('learning_modules', function (Blueprint $table) {
                    if (! Schema::hasColumn('learning_modules', 'description')) {
                        $table->text('description')->nullable()->after('title');
                    }
                    if (! Schema::hasColumn('learning_modules', 'class_id')) {
                        $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete()->after('description');
                    }
                    if (! Schema::hasColumn('learning_modules', 'subject_id')) {
                        $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete()->after('class_id');
                    }
                    if (! Schema::hasColumn('learning_modules', 'file_path')) {
                        $table->string('file_path')->nullable()->after('subject_id');
                    }
                });

                if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                    DB::statement("ALTER TABLE learning_modules MODIFY file_url VARCHAR(255) NULL");
                    DB::statement("ALTER TABLE learning_modules MODIFY program_id BIGINT UNSIGNED NULL");
                }
            }

            // 4. tefa_orders (delivery_address and notes)
            if (Schema::hasTable('tefa_orders')) {
                Schema::table('tefa_orders', function (Blueprint $table) {
                    if (! Schema::hasColumn('tefa_orders', 'delivery_address')) {
                        $table->text('delivery_address')->nullable()->after('buyer_contact');
                    }
                    if (! Schema::hasColumn('tefa_orders', 'notes')) {
                        $table->text('notes')->nullable()->after('delivery_address');
                    }
                });

                if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                    DB::statement("ALTER TABLE tefa_orders MODIFY status VARCHAR(50) NOT NULL DEFAULT 'pending'");
                }

                // Populate delivery_address for existing orders if empty
                if (Schema::hasColumn('tefa_orders', 'delivery_address')) {
                    DB::table('tefa_orders')
                        ->whereNull('delivery_address')
                        ->orWhere('delivery_address', '')
                        ->update([
                            'delivery_address' => 'Jl. Terusan Kopo No. 45, RT 02/RW 05, Katapang, Kab. Bandung, Jawa Barat 40921',
                        ]);
                }
            }

            // 5. users (is_active)
            if (Schema::hasTable('users')) {
                if (! Schema::hasColumn('users', 'is_active')) {
                    Schema::table('users', function (Blueprint $table) {
                        $table->boolean('is_active')->default(true)->after('password');
                    });
                }
                DB::table('users')->whereNull('is_active')->update(['is_active' => true]);
            }

            // 6. Ensure 7 Programs (Kompetensi Keahlian)
            if (Schema::hasTable('programs')) {
                $programsList = [
                    [
                        'program_name' => 'Rekayasa Perangkat Lunak',
                        'description'  => 'Mempelajari pengembangan perangkat lunak, aplikasi web & mobile, basis data, dan solusi kecerdasan buatan berbasis industri.',
                        'image'        => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80',
                    ],
                    [
                        'program_name' => 'Teknik Komputer dan Jaringan',
                        'description'  => 'Mempelajari instalasi jaringan komputer, administrasi server, fiber optic, cloud computing, dan keamanan siber industri.',
                        'image'        => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                    ],
                    [
                        'program_name' => 'Broadcasting Perfilman',
                        'description'  => 'Mempelajari produksi konten audio-visual, pertelevisian, sinematografi, teknik tata kamera, editing video, dan live broadcasting.',
                        'image'        => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80',
                    ],
                    [
                        'program_name' => 'Teknik Mesin',
                        'description'  => 'Mempelajari permesinan presisi, pengoperasian mesin bubut, milling, fabrikasi logam, CAD/CAM, dan CNC manufaktur modern.',
                        'image'        => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=600&auto=format&fit=crop&q=80',
                    ],
                    [
                        'program_name' => 'Teknik Otomotif',
                        'description'  => 'Mempelajari pemeliharaan mesin kendaraan ringan, sistem EFI/injeksi modern, kelistrikan bodi otomotif, dan diagnosis komputer kendaraan.',
                        'image'        => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=600&auto=format&fit=crop&q=80',
                    ],
                    [
                        'program_name' => 'Teknik Penyempurnaan Tekstil',
                        'description'  => 'Mempelajari teknologi pencelupan, pencapan, kontrol kualitas serat kain, dan proses kimia penyempurnaan tekstil modern.',
                        'image'        => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=600&auto=format&fit=crop&q=80',
                    ],
                    [
                        'program_name' => 'Teknik Elektronika',
                        'description'  => 'Mempelajari sistem kontrol otomatis, mikrokontroler & IoT industri, mekatronika, instrumentasi, dan perbaikan perangkat elektronika.',
                        'image'        => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&auto=format&fit=crop&q=80',
                    ],
                ];

                foreach ($programsList as $prog) {
                    \App\Models\Program::updateOrCreate(
                        ['program_name' => $prog['program_name']],
                        [
                            'description' => $prog['description'],
                            'image'       => $prog['image'],
                        ]
                    );
                }
            }

            // 7. Site Settings (Visi, Misi, Sejarah, Struktur Organisasi)
            if (Schema::hasTable('site_settings')) {
                $defaultSettings = [
                    'vision' => 'Menjadi sekolah kejuruan unggulan yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.',
                    'missions' => json_encode([
                        'Menyelenggarakan pendidikan bermutu berbasis kompetensi industri 4.0.',
                        'Mengembangkan karakter, budi pekerti luhur, dan jiwa wirausaha peserta didik.',
                        'Membangun kemitraan strategis dengan dunia usaha dan industri (DUDI) bereputasi.',
                        'Mengembangkan sarana prasarana dan unit Teaching Factory berstandar internasional.',
                    ]),
                    'history' => 'SMKN 1 Katapang berdiri sejak tahun 1999 dengan komitmen mencetak generasi vokasi unggul dan berkarakter di bidang teknologi dan industri. Selama lebih dari dua dekade, sekolah terus berkembang pesat hingga memiliki 7 kompetensi keahlian unggulan dengan ribuan alumni yang telah sukses berkarier di berbagai industri nasional maupun multinasional.',
                    'organization_members' => json_encode([
                        ['name' => 'Drs. H. Agus Ruswandi, M.Pd.', 'position' => 'Kepala Sekolah', 'photo' => null],
                        ['name' => 'Dra. Hj. Nunung Maryati', 'position' => 'Wakasek Kurikulum', 'photo' => null],
                        ['name' => 'Ir. Bambang Sugianto', 'position' => 'Wakasek Kesiswaan', 'photo' => null],
                        ['name' => 'Asep Saepudin, S.T., M.Kom.', 'position' => 'Wakasek Hubin & Humas', 'photo' => null],
                        ['name' => 'Dedi Junaedi, S.Pd.', 'position' => 'Wakasek Sarana Prasarana', 'photo' => null],
                        ['name' => 'Rina Marlina, S.Kom.', 'position' => 'Ketua Program Keahlian RPL', 'photo' => null],
                    ]),
                ];

                foreach ($defaultSettings as $k => $v) {
                    \App\Models\SiteSetting::firstOrCreate(['key' => $k], ['value' => $v, 'group' => 'school']);
                }
            }

            // 8. Students status (aktif vs alumni)
            if (Schema::hasTable('students')) {
                // Ensure active students have status 'aktif'
                DB::table('students')
                    ->where('nis', 'like', '2024%')
                    ->where(function ($q) {
                        $q->whereNull('status')->orWhere('status', '');
                    })
                    ->update(['status' => 'aktif']);

                // Ensure alumni have status 'alumni'
                DB::table('students')
                    ->where('nis', 'like', 'ALUMNI%')
                    ->update(['status' => 'alumni']);
            }
        } catch (\Throwable $e) {
            // Graceful fallback: catch database connection or permission errors
        }
    }
}
