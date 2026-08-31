<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            // A program with existing classes cannot be deleted outright —
            // protects academic structure from accidental cascading loss.
            $table->foreignId('program_id')->constrained('programs')->restrictOnDelete();
            $table->timestamps();

            $table->index('program_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
