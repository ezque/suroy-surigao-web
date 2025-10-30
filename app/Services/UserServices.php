<?php

namespace App\Services;
use App\Models\User;
use App\Models\IsSave;
use App\Models\UserInfo;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    public function getUserInformation()
    {
        $userInformation = Auth::user()->load('userInfo');

        return $userInformation;
    }
    public function getUserTotal()
    {
        $usersTotal = User::where('role', '3')->count();

        return $usersTotal;
    }
    public function getAgencyTotal()
    {
        $agencyTotal = User::where('role', '2')->count();

        return $agencyTotal;
    }

    public function getAgencies()
    {
        $agencies = User::where('role', '2')
            ->get();
        return $agencies;
    }
}
