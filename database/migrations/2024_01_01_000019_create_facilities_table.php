<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('facility_name');
            $table->string('image_url')->nullable();
            // Nullable: some facilities (library, hall, etc.) are shared
            // across programs rather than tied to just one.
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->timestamps();

            $table->index('program_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
