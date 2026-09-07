<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->text('description')->nullable()->after('facility_name');
            $table->string('location')->nullable()->after('description');
            $table->integer('capacity')->nullable()->after('location');
        });

        Schema::table('learning_modules', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete()->after('description');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete()->after('class_id');
            $table->string('file_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_modules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_id');
            $table->dropConstrainedForeignId('subject_id');
            $table->dropColumn('description');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['description', 'location', 'capacity']);
        });
    }
};
