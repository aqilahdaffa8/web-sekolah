<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracurricular_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('extracurricular_id')->constrained('extracurriculars')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();

            $table->unique(['student_id', 'extracurricular_id'], 'reg_unique_student_eskul');
            $table->index('extracurricular_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurricular_registrations');
    }
};
