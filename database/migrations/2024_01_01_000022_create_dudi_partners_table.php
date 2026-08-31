<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dudi_partners', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('industry_field')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('mou_document')->nullable(); // path/URL to uploaded MoU file
            $table->timestamps();

            $table->index('industry_field');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dudi_partners');
    }
};
