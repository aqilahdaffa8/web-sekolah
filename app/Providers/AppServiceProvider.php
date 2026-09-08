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
        } catch (\Throwable $e) {
            // Graceful fallback: catch database connection or permission errors
        }
    }
}
