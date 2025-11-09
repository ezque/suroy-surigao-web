<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsSave extends Model
{
    protected $table = 'is_save';

    protected $fillable = [
        'user_id',
        'spot_id',
        'is_save',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id');
    }

}
