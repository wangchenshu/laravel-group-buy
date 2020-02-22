<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = [
        'product_id', 'line_user_id', 'qty'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
