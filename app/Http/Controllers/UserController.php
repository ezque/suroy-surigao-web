<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IsSave;
use App\Models\User;
use App\Models\LikeAgency;
use App\Models\Spot;
use App\Models\Reservation;
use App\Models\Package;


class UserController extends Controller
{
    public function saveUnsaveSpot(Request $request)
    {
        $userId = auth()->id();
        $spotId = $request->spot_id;

        // Find existing save record
        $save = IsSave::where('user_id', $userId)
            ->where('spot_id', $spotId)
            ->first();

        if ($save) {
            $save->is_save = $save->is_save === '1' ? '2' : '1';
            $save->save();
        } else {
            // Create new save record as '1' (saved)
            $save = IsSave::create([
                'user_id' => $userId,
                'spot_id' => $spotId,
                'is_save' => '1',
            ]);
        }

        return response()->json([
            'success' => true,
            'is_saved' => $save->is_save
        ]);
    }

    public function likeUnlikeAgency(Request $request)
    {
        $userID = auth()->id();
        $agencyID = $request->agencyID;

        if (!$userID) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if a like record exists
        $like = LikeAgency::where('userID', $userID)
            ->where('agencyID', $agencyID)
            ->first();

        if ($like) {
            // Toggle status between 'liked' and 'unliked'
            $like->status = $like->status === 'liked' ? 'unliked' : 'liked';
            $like->save();
        } else {
            // Create new like with status = 'liked'
            $like = LikeAgency::create([
                'userID' => $userID,
                'agencyID' => $agencyID,
                'status' => 'liked',
            ]);
        }

        // Optional: total liked count for the agency
        $totalLikes = LikeAgency::where('agencyID', $agencyID)
            ->where('status', 'liked')
            ->count();

        return response()->json([
            'status' => $like->status,
            'totalLikes' => $totalLikes,
        ]);
    }

    public function checkIfAgencyIsLiked(Request $request)
    {
        $userID = auth()->id();

        if (!$userID) {
            return response()->json([]);
        }

        $likedAgencies = LikeAgency::where('userID', $userID)
            ->where('status', 'liked')
            ->pluck('agencyID') // get array of agency IDs
            ->toArray();

        return response()->json($likedAgencies);
    }




    public function getSpotSavedStatus($id)
    {
        $userId = auth()->id();
        $save = IsSave::where('user_id', $userId)
            ->where('spot_id', $id)
            ->first();

        return response()->json([
            'spot_id' => $id,
            'is_saved' => $save?->is_save ?? '2'
        ]);
    }

    public function getAgenciesByPackageAndSpot($spotId)
    {
        $agencies = User::where('role', '2')
            ->whereHas('packages', function ($query) use ($spotId) {
                $query->whereJsonContains('tour_destination', (int) $spotId)
                    ->where('status', '1');
            })
            ->with([
                'userInfo',
                'agency',
                'packages' => function ($query) use ($spotId) {
                    $query->whereJsonContains('tour_destination', (int) $spotId)
                        ->where('status', '1');
                }
            ])
            ->withCount(['packages as available_package' => function ($query) use ($spotId) {
                $query->whereJsonContains('tour_destination', (int) $spotId)
                    ->where('status', '1');
            }])
            ->get();

        return response()->json($agencies);
    }
    public function getSpots()
    {
        $spots = Spot::all();

        return response()->json($spots);
    }
    public function addReservation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|integer|exists:package,id',
            'number_of_people' => 'required|integer|min:1',
            'special_req' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:50',
            'complete_address' => 'nullable|string|max:500',
            'e_full_name' => 'required|string|max:255',
            'e_contact' => 'required|string|max:50',
            'e_relationship' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:pending,confirmed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Fetch the package
        $package = Package::find($request->package_id);

        // Check if enough slots are available
        if ($package->available_slot < $request->number_of_people) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough available slots for this package.'
            ], 400);
        }

        // Use authenticated user's ID
        $userId = Auth::id();

        // Begin transaction
        DB::beginTransaction();
        try {
            // Create reservation
            $reservation = Reservation::create([
                'user_id' => $userId,
                'package_id' => $request->package_id,
                'number_of_people' => $request->number_of_people,
                'special_req' => $request->special_req,
                'total_amount' => $request->total_amount,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'complete_address' => $request->complete_address,
                'e_full_name' => $request->e_full_name,
                'e_contact' => $request->e_contact,
                'e_relationship' => $request->e_relationship,
                'status' => $request->status ?? 'pending',
            ]);

            // Deduct available slots
            $package->available_slot -= $request->number_of_people;
            $package->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully',
                'reservation' => $reservation
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the reservation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
