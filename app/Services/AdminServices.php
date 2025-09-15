<?php

namespace App\Services;
use App\Models\User;
use App\Models\Spot;

class AdminServices
{
    /**
     * Create a new class instance.
     */
    public function getAgencyDetails()
    {
        return User::with(['userInfo', 'agency'])
            ->whereHas('agency')
            ->get();
    }
    public function getAllUsers()
    {
        return User::with(['userInfo', 'agency'])
            ->where('role', 3)
            ->get();
    }


}
