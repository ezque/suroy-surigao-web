<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
class AgencyServices
{
    public function getAllReservation()
    {
        $user = Auth::user();

        // Get reservations for packages owned by the current user (agency)
        $reservations = Reservation::whereHas('package', function ($query) use ($user) {
            $query->where('userID', $user->id); // userID = package owner
        })
            ->with(['package', 'package.user']) // eager load package and its owner if needed
            ->get();

        $totalReservations = $reservations->count();

        return [
            'reservations' => $reservations,
            'totalReservations' => $totalReservations,
        ];
    }
}
