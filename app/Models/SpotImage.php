<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotImage extends Model
{
    protected $table = 'spot_images';
    protected $fillable = [
        'spot_id',
        'spot_image'
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id');
    }
}
