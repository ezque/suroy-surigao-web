<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'agency_ID',
        'package_id',
        'service_name',
    ];
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

}
