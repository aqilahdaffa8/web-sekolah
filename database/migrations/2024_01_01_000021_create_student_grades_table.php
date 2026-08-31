<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historical academic record. Per spec point (1): historical data uses
     * RESTRICT/SET NULL, never CASCADE, so a record can't silently vanish
     * because an unrelated row (student, subject, teacher) was deleted.
     */
    public function up(): void
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->restrictOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            // Nullable: if the teacher account is later removed, the grade
            // record itself must still be preserved for the student.
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('theory_score', 5, 2)->nullable();   // Teori/UTS/UAS
            $table->decimal('practice_score', 5, 2)->nullable(); // Praktik
            $table->decimal('ukk_score', 5, 2)->nullable();
            $table->decimal('pkl_score', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id'], 'grades_unique_student_subject');
            $table->index('subject_id');
            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
