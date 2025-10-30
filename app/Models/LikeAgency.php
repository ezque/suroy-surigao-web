<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeAgency extends Model
{
    protected $table = 'like_agency';

    protected $fillable = [
        'userID',
        'agencyID',
        'status',
    ];

}
