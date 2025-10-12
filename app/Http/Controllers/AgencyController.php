<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Service;

class AgencyController extends Controller
{
    public function addPackage(Request $request)
    {
        $validated = $request->validate([
            'package_name'    => 'required|string|max:255',
            'description'    => 'required|string',
            'duration'       => 'required|string|max:255',
            'capacity'       => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'available_On' => 'required|string|max:255',
            'services'       => 'required|array|min:1',
            'services.*'     => 'required|string|max:255',
        ]);

        // Create package
        $package = Package::create([
            'package_name'   => $validated['package_name'],
            'description'    => $validated['description'],
            'duration'       => $validated['duration'],
            'price'          => $validated['price'],
            'capacity'       => $validated['capacity'],
            'available_On' => $validated['available_On'],
        ]);

        // Create related services
        foreach ($validated['services'] as $serviceName) {
            Service::create([
                'package_id'   => $package->id,
                'agency_ID'    => auth()->id() ?? null,
                'service_name' => $serviceName,
            ]);
        }

        return response()->json([
            'message' => 'Package and services added successfully',
            'package' => $package->load('services'),
        ], 201);
    }

}
