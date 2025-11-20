<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyRating extends Model
{
    protected $table = 'agency_rating';

    protected $fillable = [
        'user_id',
        'agency_id',
        'rating',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_id', 'id');
    }
}
