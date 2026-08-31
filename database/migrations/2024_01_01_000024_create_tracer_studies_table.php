<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Alumni tracer record — historical, so RESTRICT rather than CASCADE. */
    public function up(): void
    {
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->restrictOnDelete();
            $table->year('graduation_year');
            $table->enum('current_status', ['Kerja', 'Kuliah', 'Wirausaha']);
            $table->string('company_or_campus_name')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('graduation_year');
            $table->index('current_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
