<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $table = 'spots';
    protected $fillable = [
        'spot_name',
        'description',
        'location',
        'category',
        'status',
    ];

    public function images()
    {
        return $this->hasMany(SpotImage::class, 'spot_id');
    }
}
