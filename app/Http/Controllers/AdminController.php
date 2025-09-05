<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Agency;

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


}
