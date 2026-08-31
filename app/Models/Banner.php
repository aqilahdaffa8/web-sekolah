<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * banners: id, image_url, link_url, sort_order, timestamps
 */
class Banner extends Model
{
    protected $fillable = ['image_url', 'link_url', 'sort_order'];
}
