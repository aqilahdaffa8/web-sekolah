<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('schedule')->nullable();
            // Nullable: the club can exist before a coach is assigned.
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('coach_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurriculars');
    }
};
