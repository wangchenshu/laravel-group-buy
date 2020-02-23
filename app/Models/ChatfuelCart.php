<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatfuelCart extends Model
{
    protected $table = 'chatfuel_cart';

    protected $fillable = [
        'product_name', 'messenger_user_id', 'username', 'qty', 'price'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
