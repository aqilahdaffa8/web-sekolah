<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->text('description')->nullable();
            // If a DUDI partner is removed, its postings go with it.
            $table->foreignId('dudi_id')->constrained('dudi_partners')->cascadeOnDelete();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->index('dudi_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
