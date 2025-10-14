<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\UserServices;
use App\Services\AdminServices;
use App\Services\SpotsServices;

class ViewController extends Controller
{
    public function adminDashboard(UserServices $userServices, AdminServices $adminServices, SpotsServices $spotsServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();
        $allUsers = $adminServices->getAllUsers();
        $agencyDetails = $adminServices->getAgencyDetails();
        $allSpots = $spotsServices->getAllSpots();

        return Inertia::render('Admin/index', [
            'userInformation' => $userInformation,
            'agencyDetails'   => $agencyDetails,
            'allUsers'        => $allUsers,
            'allSpots'     => $allSpots,
        ]);
    }

    public function userDashboard(UserServices $userServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();

        return Inertia::render('User/index', [
            'userInformation' => $userInformation,
        ]);
    }
    public function agencyDashboard(UserServices $userServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();

        return Inertia::render('Agency/index', [
            'userInformation' => $userInformation,
        ]);
    }

}
