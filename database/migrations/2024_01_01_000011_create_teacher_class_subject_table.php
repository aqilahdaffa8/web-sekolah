<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Assignment table: which teacher is authorized to teach a given
     * subject in a given class. This is the SOURCE OF TRUTH used by the
     * backend (not just the frontend) to gate grade-input access for the
     * "Admin Guru & Akademik" role — every write to student_grades must be
     * checked against a matching row here (teacher_id + class_id via the
     * student's class + subject_id).
     */
    public function up(): void
    {
        Schema::create('teacher_class_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();

            // Prevent duplicate assignment of the same teacher/class/subject
            $table->unique(['teacher_id', 'class_id', 'subject_id'], 'tcs_unique_assignment');
            $table->index('class_id');
            $table->index('subject_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_class_subject');
    }
};
