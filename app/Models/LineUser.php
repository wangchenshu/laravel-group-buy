<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineUser extends Model
{
    protected $table = 'line_user';

    protected $fillable = [
        'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
