<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';
    protected $fillable = [
        'user_ID',
        'firstName',
        'lastName',
        'phone_num',
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'id');
    }
}

