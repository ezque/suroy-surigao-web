<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
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

        $package = Package::find($request->package_id);

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found.'
            ], 404);
        }

        if ($package->available_slot < $request->number_of_people) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough available slots for this package.'
            ], 400);
        }

        $userId = Auth::id();

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

            // Reduce available slots
            $package->available_slot -= $request->number_of_people;
            $package->save();

            // Notify package owner
            $receiverId = $package->user?->id; // get owner of the package

            if ($receiverId) {
                Notification::create([
                    'user_ID' => $userId,
                    'sender_id' => $userId,
                    'receiver_id' => $receiverId,
                    'message' => 'A new reservation has been made for your package: ' . $package->package_name,
                    'status' => 'unread',
                    'type' => 'reservationAdded',
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully and agency notified.',
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
