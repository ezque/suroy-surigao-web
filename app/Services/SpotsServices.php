<?php

namespace App\Services;
use App\Models\Spot;
use App\Models\IsSave;
use Illuminate\Support\Facades\Auth;
class SpotsServices
{
    /**
     * Create a new class instance.
     */
    public function getAllSpots()
    {
        return Spot::withCount('reviews')->with('images')->get();
    }
    public function getAllSaveSpots()
    {
        $user = Auth::user();

        return IsSave::where('user_id', $user->id)
            ->where('is_save', true)
            ->with(['spot.images', 'spot.ratings', 'spot.reviews'])
            ->get()
            ->pluck('spot');
    }

}
