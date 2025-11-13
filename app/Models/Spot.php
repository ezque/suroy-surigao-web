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
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'spot_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'spot_id');
    }
    public function isSavedByUser()
    {
        return $this->hasOne(IsSave::class, 'spot_id')->where('user_id', auth()->id())->where('is_save', '1');
    }
    public function agencies()
    {
        return User::where('role', 'agency')
            ->whereHas('packages', function ($query) {
                $query->whereJsonContains('tour_destination', $this->id)
                    ->where('status', '1');
            })
            ->with('agency') // eager load agency info
            ->with(['packages' => function ($query) {
                $query->where('status', '1')
                    ->select([
                        'id', 'userID', 'package_name', 'description',
                        'price', 'capacity', 'available_slot',
                        'start_date', 'end_date', 'start_time', 'end_time',
                        'pickup_point', 'tour_destination', 'status'
                    ]);
            }])
            ->withCount(['packages' => function ($query) {
                $query->where('status', '1');
            }])
            ->get()
            ->map(function ($agency) {
                $agency->packages->transform(function ($pkg) {
                    $spotIds = json_decode($pkg->tour_destination, true) ?? [];

                    $pkg->tour_destinations = Spot::whereIn('id', $spotIds)
                        ->get(['id', 'spot_name']);

                    return $pkg;
                });

                return $agency;
            });
    }

    protected static function booted()
    {
        static::deleting(function ($spot) {
            // Delete images
            $spot->images()->each(function ($img) {
                if (file_exists(storage_path('app/public/' . $img->spot_image))) {
                    unlink(storage_path('app/public/' . $img->spot_image));
                }
                $img->delete();
            });

            // Delete ratings
            $spot->ratings()->delete();

            // Delete reviews
            $spot->reviews()->delete();

            // Delete saved entries
            $spot->isSavedByUser()->delete();
        });
    }

}
