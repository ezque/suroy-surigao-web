<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Reservation;


class AgencyController extends Controller
{
    public function addPackage(Request $request)
    {

        $validated = $request->validate([
            'package_name'   => 'required|string|max:255',
            'shortDesc'      => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'available_slot'   => 'nullable|integer|min:0',
            'startDate'      => 'nullable|date',
            'endDate'        => 'nullable|date|after_or_equal:startDate',
            'startTime'      => 'nullable|date_format:H:i',
            'endTime'        => 'nullable|date_format:H:i',
            'pickupPoint'    => 'nullable|string|max:255',
            'destinations'   => 'nullable|array',
            'inclusions'     => 'nullable|string',
            'exclusions'     => 'nullable|string',
        ]);

        $package = Package::create([
            'userID' => auth()->id(),
            'package_name'    => $validated['package_name'],
            'description'     => $validated['shortDesc'] ?? null,
            'price'           => $validated['price'],
            'capacity'        => $validated['capacity'],
            'available_slot'  => $validated['available_slot'] ?? null,
            'start_date'      => $validated['startDate'] ?? null,
            'end_date'        => $validated['endDate'] ?? null,
            'start_time'      => $validated['startTime'] ?? null,
            'end_time'        => $validated['endTime'] ?? null,
            'pickup_point'    => $validated['pickupPoint'] ?? null,
            'tour_destination'=> json_encode($validated['destinations'] ?? []),
            'inclusions'      => $validated['inclusions'] ?? null,
            'exclusions'      => $validated['exclusions'] ?? null,
            'status'          => '1',
        ]);

        return response()->json([
            'message' => 'Package added successfully!',
            'package' => $package,
        ], 201);
    }

    public function updatePackage(Request $request, $id)
    {
        // Validate inputs
        $validated = $request->validate([
            'package_name'   => 'required|string|max:255',
            'shortDesc'      => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'available_slot' => 'nullable|integer|min:0',
            'startDate'      => 'nullable|date',
            'endDate'        => 'nullable|date|after_or_equal:startDate',
            'startTime'      => 'nullable|date_format:H:i',
            'endTime'        => 'nullable|date_format:H:i',
            'pickupPoint'    => 'nullable|string|max:255',
            'destinations'   => 'nullable|array',
            'inclusions'     => 'nullable|string',
            'exclusions'     => 'nullable|string',
            'status'         => 'nullable|string|in:0,1,active,inactive',
        ]);

        // Find package
        $package = Package::findOrFail($id);

        // Update only fields provided
        $package->update(array_filter([
            'package_name'     => $validated['package_name'] ?? $package->package_name,
            'description'      => $validated['shortDesc'] ?? $package->description,
            'price'            => $validated['price'] ?? $package->price,
            'capacity'         => $validated['capacity'] ?? $package->capacity,
            'available_slot'   => $validated['available_slot'] ?? $package->available_slot,
            'start_date'       => $validated['startDate'] ?? $package->start_date,
            'end_date'         => $validated['endDate'] ?? $package->end_date,
            'start_time'       => $validated['startTime'] ?? $package->start_time,
            'end_time'         => $validated['endTime'] ?? $package->end_time,
            'pickup_point'     => $validated['pickupPoint'] ?? $package->pickup_point,
            'tour_destination' => isset($validated['destinations'])
                ? json_encode($validated['destinations'])
                : $package->tour_destination,
            'inclusions'       => $validated['inclusions'] ?? $package->inclusions,
            'exclusions'       => $validated['exclusions'] ?? $package->exclusions,
            'status'           => $validated['status'] ?? $package->status,
        ], fn($v) => !is_null($v)));

        return response()->json([
            'message' => 'Package updated successfully!',
            'package' => $package,
        ], 200);
    }

    public function updateReservation(Request $request, $id)
    {
        try {
            // Validate the incoming status
            $request->validate([
                'status' => 'required|string|in:pending,confirmed,rejected',
            ]);

            $reservation = Reservation::findOrFail($id);

            $reservation->update([
                'status' => $request->status,
            ]);



            return response()->json([
                'message' => 'Reservation updated successfully',
                'data' => $reservation,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update reservation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function deletePackage($id)
    {
        try {
            $package = Package::find($id);

            if (!$package) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found.'
                ], 404);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the package.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




}
