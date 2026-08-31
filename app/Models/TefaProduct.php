<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * tefa_products: id, product_name, description, price, stock, image_url, program_id, timestamps
 */
class TefaProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock',
        'image_url',
        'program_id',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function orderItems()
    {
        return $this->hasMany(TefaOrderItem::class, 'product_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
