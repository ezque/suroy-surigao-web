<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'package';

    protected $fillable = [
        'userID',
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
    protected $appends = ['duration'];

    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_date . ' ' . $this->start_time);
        $end = Carbon::parse($this->end_date . ' ' . $this->end_time);

        $days = $start->diffInDays($end);
        $hours = $start->diffInHours($end) % 24;

        if ($days > 0 && $hours > 0) {
            return "{$days} day(s) {$hours} hour(s)";
        } elseif ($days > 0) {
            return "{$days} day(s)";
        } else {
            return "{$hours} hour(s)";
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }


}
