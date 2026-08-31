<?php

namespace Tests\Feature;

use App\Models\TefaProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates stock management during TeFA order placement:
 *  - Successful order decrements stock
 *  - Order on zero-stock product returns 422
 *  - Partial order (one item insufficient) rolls back entirely
 */
class TefaOrderStockTest extends TestCase
{
    use RefreshDatabase;

    /** Placing an order decrements product stock */
    public function test_order_decrements_stock(): void
    {
        $product = TefaProduct::factory()->inStock(10)->create(['price' => 50000]);

        $response = $this->postJson('/api/public/orders', [
            'buyer_name' => 'Budi Santoso',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['buyer_name' => 'Budi Santoso']);

        $this->assertEquals(7, $product->fresh()->stock);
    }

    /** Order on a zero-stock product returns 422 */
    public function test_order_fails_when_stock_is_zero(): void
    {
        $product = TefaProduct::factory()->outOfStock()->create();

        $response = $this->postJson('/api/public/orders', [
            'buyer_name' => 'Siti Rahayu',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422);
        // Stock should remain 0
        $this->assertEquals(0, $product->fresh()->stock);
    }

    /** Order with quantity exceeding available stock returns 422 */
    public function test_order_fails_when_quantity_exceeds_stock(): void
    {
        $product = TefaProduct::factory()->inStock(5)->create();

        $response = $this->postJson('/api/public/orders', [
            'buyer_name' => 'Ahmad Fauzi',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 10],
            ],
        ]);

        $response->assertStatus(422);
        $this->assertEquals(5, $product->fresh()->stock);
    }

    /** Multi-item order where one item fails rolls back all stock changes */
    public function test_partial_failure_rolls_back_all_stock(): void
    {
        $okProduct = TefaProduct::factory()->inStock(5)->create(['price' => 10000]);
        $oosProduct = TefaProduct::factory()->outOfStock()->create(['price' => 20000]);

        $response = $this->postJson('/api/public/orders', [
            'buyer_name' => 'Dewi Lestari',
            'items' => [
                ['product_id' => $okProduct->id,  'quantity' => 2],
                ['product_id' => $oosProduct->id, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422);
        // Stock on the valid product must NOT have changed
        $this->assertEquals(5, $okProduct->fresh()->stock);
        $this->assertEquals(0, $oosProduct->fresh()->stock);
    }
}
