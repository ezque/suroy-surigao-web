<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function getAllReviews($spotId)
    {
        $reviews = Review::where('spot_id', $spotId)
            ->with(['user.userInfo:id,user_ID,firstName,lastName']) // include user info
            ->latest() // order by created_at descending
            ->get();

        return response()->json($reviews);
    }

    public function addReview(Request $request, $id)
    {
        $request->validate([
            'review' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'spot_id' => $id,
            'user_id' => Auth::id(),
            'review' => $request->review,
        ]);
        // Fetch spot name
        $spot = Spot::find($id);

        $review = Review::with('user.userInfo:id,user_ID,firstName,lastName')
            ->find($review->id);

        // ✅ Notify admin
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            Notification::create([
                'user_ID' => Auth::id(),
                'sender_id' => Auth::id(),
                'receiver_id' => $admin->id,
                'message' => Auth::user()->userInfo->firstName
                    . " added a new review in "
                    . $spot->spot_name . ".",
                'status' => 'unread',                 // unread
                'type' => 'review',            // notification category
            ]);
        }

        return response()->json([
            'message' => 'Review added successfully!',
            'review' => $review
        ]);
    }

}
