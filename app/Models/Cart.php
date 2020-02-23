<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'product_name', 'line_user_id', 'username', 'qty', 'price'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
