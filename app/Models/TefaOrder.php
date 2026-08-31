<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * tefa_orders: id, order_code, buyer_name, buyer_contact, total_price, status, timestamps
 */
class TefaOrder extends Model
{
    protected $fillable = [
        'order_code',
        'buyer_name',
        'buyer_contact',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(TefaOrderItem::class, 'order_id');
    }
}
