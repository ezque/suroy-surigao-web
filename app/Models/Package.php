<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'package';

    protected $fillable = [
        'package_name',
        'description',
        'price',
        'capacity',
        'duration',
        'available_from',
        'available_to',
    ];
}
