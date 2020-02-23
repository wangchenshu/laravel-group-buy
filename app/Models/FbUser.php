<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbUser extends Model
{
    protected $table = 'fb_user';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'messenger_user_id',
        'first_name',
        'last_name',
        'gender',
        'profile_pic_url',
        'timezone',
        'locale'
    ];
}
