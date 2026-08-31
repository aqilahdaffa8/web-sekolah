<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tefa_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->string('image_url')->nullable();
            $table->foreignId('program_id')->constrained('programs')->restrictOnDelete();
            $table->timestamps();

            $table->index('program_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tefa_products');
    }
};
