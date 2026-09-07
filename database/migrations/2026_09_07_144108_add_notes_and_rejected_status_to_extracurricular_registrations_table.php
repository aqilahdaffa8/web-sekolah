<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('extracurricular_registrations', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE extracurricular_registrations MODIFY status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('extracurricular_registrations')->where('status', 'rejected')->update(['status' => 'pending']);
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE extracurricular_registrations MODIFY status ENUM('pending', 'approved') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('extracurricular_registrations', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
