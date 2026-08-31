<?php

namespace App\Services;

use App\Models\TefaOrder;
use App\Models\TefaOrderItem;
use App\Models\TefaProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TefaOrderService
{
    /**
     * Atomically create an order and decrement product stock.
     *
     * @param array $data  { buyer_name, buyer_contact?, items: [{product_id, quantity}] }
     * @throws ValidationException when any product has insufficient stock
     */
    public function placeOrder(array $data): TefaOrder
    {
        return DB::transaction(function () use ($data) {
            $items      = $data['items'];
            $totalPrice = 0;

            // 1. Lock rows & validate stock
            $productIds = array_column($items, 'product_id');
            $products   = TefaProduct::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => ["Product ID {$item['product_id']} not found."],
                    ]);
                }

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => [
                            "Insufficient stock for \"{$product->product_name}\". "
                            . "Available: {$product->stock}, requested: {$item['quantity']}.",
                        ],
                    ]);
                }
            }

            // 2. Create order header
            $order = TefaOrder::create([
                'order_code'    => 'ORD-' . strtoupper(uniqid()),
                'buyer_name'    => $data['buyer_name'],
                'buyer_contact' => $data['buyer_contact'] ?? null,
                'total_price'   => 0,
                'status'        => 'pending',
            ]);

            // 3. Create items & decrement stock
            foreach ($items as $item) {
                $product  = $products->get($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                TefaOrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'subtotal'   => $subtotal,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // 4. Update total
            $order->update(['total_price' => $totalPrice]);

            return $order->load('items.product');
        });
    }
}
