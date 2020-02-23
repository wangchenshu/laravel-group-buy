<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = [
        'name', 'price', 'link', 'pic_url'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', '=', 1);
    }
}
