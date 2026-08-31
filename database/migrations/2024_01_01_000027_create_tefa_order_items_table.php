<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Join table realizing the M:N relationship between tefa_orders and
     * tefa_products (with quantity/subtotal as the relationship's own data).
     */
    public function up(): void
    {
        Schema::create('tefa_order_items', function (Blueprint $table) {
            $table->id();
            // An order's line items disappear with the order itself.
            $table->foreignId('order_id')->constrained('tefa_orders')->cascadeOnDelete();
            // A product can't be deleted while it's referenced in order
            // history — protects financial/reporting integrity.
            $table->foreignId('product_id')->constrained('tefa_products')->restrictOnDelete();
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tefa_order_items');
    }
};
