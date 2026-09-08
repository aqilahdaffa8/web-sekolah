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
        'delivery_address',
        'notes',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'float',
    ];

    protected $appends = [
        'customer_name',
    ];

    public function getCustomerNameAttribute(): ?string
    {
        return $this->attributes['buyer_name'] ?? null;
    }

    public function items()
    {
        return $this->hasMany(TefaOrderItem::class, 'order_id');
    }
}
