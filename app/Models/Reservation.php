<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $fillable = [
        'user_id',
        'package_id',
        'number_of_people',
        'special_req',
        'total_amount',
        'full_name',
        'email',
        'phone_number',
        'complete_address',
        'e_full_name',
        'e_contact',
        'e_relationship',
        'status',
    ];
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
}
