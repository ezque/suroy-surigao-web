<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;


class AgencyController extends Controller
{
    public function addPackage(Request $request)
    {
        // ✅ validate inputs
        $validated = $request->validate([
            'package_name'   => 'required|string|max:255',
            'shortDesc'      => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'available_On'   => 'nullable|integer|min:0',
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
            'package_name'    => $validated['package_name'],
            'description'     => $validated['shortDesc'] ?? null,
            'price'           => $validated['price'],
            'capacity'        => $validated['capacity'],
            'available_slot'  => $validated['available_On'] ?? null,
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

}
