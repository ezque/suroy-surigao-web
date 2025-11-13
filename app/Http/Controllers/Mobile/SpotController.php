<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Spot;
use App\Models\IsSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function getSpotCards()
    {
        $userId = auth()->id();

        // Get all spots with related data (images, ratings, reviews, etc.)
        $spots = Spot::withCount('reviews')
            ->with([
                'images',
                'ratings',
                'reviews.user.userInfo',
                'isSavedByUser',
            ])
            ->addSelect([
                'average_rating' => Rating::selectRaw(
                    'CAST(ROUND(COALESCE(AVG(rating), 0), 1) AS DECIMAL(3,1))'
                )
                    ->whereColumn('spot_id', 'spots.id')
                    ->limit(1)
            ])
            ->get();

        // Attach agencies dynamically
        return $spots->map(function ($spot) {
            $spot->is_saved_by_user = $spot->isSavedByUser ? true : false;
            unset($spot->isSavedByUser);

            $spot->agencies = $spot->agencies();

            return $spot;
        });
    }


    public function saveUnsaveSpot(Request $request){
        $userId = auth()->id();
        $spotId = $request->input('spot_id');

        $save = IsSave::where('user_id', $userId)
            ->where('spot_id', $spotId)
            ->first();

        if (!$save) {
            $save = IsSave::create([
                'user_id' => $userId,
                'spot_id' => $spotId,
                'is_save' => '1',
            ]);
        } else {
            $save->is_save = $save->is_save === '1' ? '2' : '1';
            $save->save();
        }

        return response()->json([
            'spot_id' => $spotId,
            'is_saved' => $save->is_save,
        ]);
    }
}
