<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\UserServices;
use App\Services\AdminServices;

class ViewController extends Controller
{
    public function adminDashboard(UserServices $userServices, AdminServices $adminServices): \Inertia\Response
    {
        $userInformation =$userServices->getUserInformation();
        $agencyDetails = $adminServices->getAgencyDetails();

        return Inertia::render('Admin/index',
            [
                'userInformation'=>$userInformation,
                'agencyDetails'=>$agencyDetails
            ]
        );
    }
    public function userDashboard(): \Inertia\Response
    {
        return Inertia::render('User/index');
    }

}
