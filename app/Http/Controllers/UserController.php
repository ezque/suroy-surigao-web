<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Booking;

class UserController extends Controller
{
    public function addBooking(Request $request)
    {
        // validate incoming data
        $validated = $request->validate([
            'package_id'       => 'required|exists:packages,id',
            'email'            => 'required|email',
            'contact'          => 'required|string|max:20',
            'name'             => 'required|string|max:255',
            'number_of_people' => 'required|integer|min:1',
            'tourDate'         => 'required|date|after:today',
            'status'           => 'required|string|in:pending,confirmed,cancelled', // example statuses
        ]);

        $booking = Booking::create($validated);

        return response()->json([
            'message' => 'Booking successfully created!',
            'data'    => $booking
        ]);
    }

}
