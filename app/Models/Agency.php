<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agencies';

    protected $fillable = [
        'user_id',
        'agency_name',
        'description',
        'image_path',
        'location_address',
        'contact_number',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'id');
    }


}
