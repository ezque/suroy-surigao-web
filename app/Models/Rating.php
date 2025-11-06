<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'spot_id',
        'user_id',
        'rating',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id');
    }
}
