<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Agency;
use App\Models\Spot;
use App\Models\SpotImage;

class AdminController extends Controller
{
    public function addAgency(Request $request)
    {
        $request->validate([
            'agency_name'      => 'required|string|max:255|unique:agencies,agency_name',
            'description'      => 'nullable|string',
            'contact_number'   => 'required|string|max:15',
            'location_address' => 'nullable|string|max:255',
        ]);

        [$agency, $user] = DB::transaction(function () use ($request) {
            $agencyNameSlug = strtolower(preg_replace('/\s+/', '', $request->agency_name));
            $generatedEmail = $agencyNameSlug . '.surigao@gmail.com';

            $user = User::create([
                'email'    => $generatedEmail,
                'password' => Hash::make($agencyNameSlug),
                'role'     => 2,
                'status'   => 1,
            ]);

            $agency = Agency::create([
                'user_id'          => $user->id,
                'agency_name'      => $request->agency_name,
                'description'      => $request->description,
                'contact_number'   => $request->contact_number,
                'location_address' => $request->location_address,
                'image_path'       => null,
            ]);

            return [$agency, $user];
        });

        return response()->json([
            'message' => 'Agency and user created successfully',
            'agency'  => $agency,
            'user'    => $user,
        ], 201);
    }

    public function addSpot(Request $request)
    {
        \Log::info('Incoming request data:', $request->all());

        try {
            $request->validate([
                'spot_name'   => 'required|string|max:255',
                'description' => 'nullable|string',
                'location'    => 'nullable|string|max:255',
                'category'    => 'nullable|string|max:255',
                'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:40000'
            ]);
            \Log::info('Validation passed.');

            // Create spot
            $spot = Spot::create([
                'spot_name'   => $request->spot_name,
                'description' => $request->description,
                'location'    => $request->location,
                'category'    => $request->category,
                'status'      => 1
            ]);
            \Log::info('Spot created successfully:', $spot->toArray());

            // Save images if available
            if ($request->hasFile('images')) {
                \Log::info('Images found:', ['count' => count($request->file('images'))]);

                foreach ($request->file('images') as $image) {
                    $path = $image->store('spots', 'public');
                    \Log::info('Image stored:', ['path' => $path]);

                    SpotImage::create([
                        'spot_id'     => $spot->id,
                        'spot_image' => $path
                    ]);
                }
            } else {
                \Log::info('No images uploaded.');
            }

            return response()->json(['message' => 'Spot created successfully!'], 201);

        } catch (\Exception $e) {
            \Log::error('Error adding spot: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Something went wrong. Check logs for details.'], 500);
        }
    }

    public function updateUserStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'status' => 'required|integer|in:1,2,3',
        ]);

        $user = User::findOrFail($request->id);

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'user' => $user
        ]);
    }





}
