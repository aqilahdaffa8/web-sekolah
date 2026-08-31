<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tefa_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('buyer_name');
            $table->string('buyer_contact')->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'completed'])->default('pending');
            $table->timestamps();

            $table->index('order_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tefa_orders');
    }
};
