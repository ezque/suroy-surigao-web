<?php

namespace App\Services;
use App\Models\User;
use App\Models\IsSave;
use App\Models\UserInfo;
use App\Models\Spot;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    public function getUserInformation()
    {
        $userInformation = Auth::user()->load('userInfo');

        return $userInformation;
    }
    public function getAgencyInformation()
    {
        $agencyInformation = Auth::user()->load('agency');

        return $agencyInformation;
    }
    public function getUserTotal()
    {
        $usersTotal = User::where('role', 'user')->count();

        return $usersTotal;
    }
    public function getAgencyTotal()
    {
        $agencyTotal = User::where('role', 'agency')->count();

        return $agencyTotal;
    }
    public function getTotalSpot()
    {
        $spotTotal = Spot::where('status', 'active')->count();

        return $spotTotal;
    }

    public function getAgencies()
    {
        $agencies = User::where('role', '2')
            ->get();
        return $agencies;
    }
    public function getAllBlogUrlsRandom()
    {
        return Blog::inRandomOrder()->pluck('url');
    }

}
