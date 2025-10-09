<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'package_id',
        'email',
        'contact',
        'name',
        'number_of_people',
        'tourDate',
        'status',
    ];

}
