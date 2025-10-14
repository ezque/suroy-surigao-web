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
        'available_slot',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'pickup_point',
        'tour_destination',
        'inclusions',
        'exclusions',
        'status',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'package_id');
    }
}
