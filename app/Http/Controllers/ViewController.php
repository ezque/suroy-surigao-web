<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\UserServices;

class ViewController extends Controller
{
    public function adminDashboard(UserServices $userServices): \Inertia\Response
    {
        $userInformation =$userServices->getUserInformation();

        return Inertia::render('Admin/index',
            ['userInformation'=>$userInformation]
        );
    }
    public function userDashboard(): \Inertia\Response
    {
        return Inertia::render('User/index');
    }

}
