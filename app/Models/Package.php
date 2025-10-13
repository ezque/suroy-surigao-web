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
        'available_On',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'package_id');
    }
}
