<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * tefa_order_items: id, order_id, product_id, quantity, subtotal, timestamps
 */
class TefaOrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'subtotal' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(TefaOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(TefaProduct::class, 'product_id');
    }
}
