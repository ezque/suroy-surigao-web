<?php

namespace App\Services;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    public function getUserInformation()
    {
        $userInformation = Auth::user()->load('userInfo');

        return $userInformation;
    }
}
