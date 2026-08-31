<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('name');
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->enum('status', ['aktif', 'lulus'])->default('aktif');
            $table->timestamps();

            $table->index('nis');
            $table->index('class_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
