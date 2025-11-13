<?php

namespace App\Services;
use App\Models\Spot;
use App\Models\IsSave;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
class SpotsServices
{
    /**
     * Create a new class instance.
     */
    public function getAllSpots()
    {
        return Spot::withCount('reviews')
            ->with(['images', 'ratings', 'reviews.user.userInfo'])

            ->addSelect([
                'average_rating' => Rating::selectRaw('CAST(ROUND(COALESCE(AVG(rating), 0), 1) AS DECIMAL(3,1))')
                    ->whereColumn('spot_id', 'spots.id')
                    ->limit(1)
            ])
            ->get();
    }

    public function getAllSaveSpots()
    {
        $user = Auth::user();

        return IsSave::where('user_id', $user->id)
            ->where('is_save', true)
            ->with([
                'spot' => function ($query) {
                    $query->with(['images', 'ratings', 'reviews.user'])
                        ->withCount('reviews')
                        ->addSelect([
                            'average_rating' => Rating::selectRaw('ROUND(COALESCE(AVG(rating), 0), 1)')
                                ->whereColumn('spot_id', 'spots.id')
                                ->limit(1)
                        ]);
                }
            ])
            ->get()
            ->pluck('spot')
            ->filter()
            ->map(function ($spot) {
                $spot->total_reviews = $spot->reviews_count ?? 0;
                unset($spot->reviews_count);
                return $spot;
            })
            ->values(); // ✅ Reindex numeric keys so it becomes a proper array

    }



}
