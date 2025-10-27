<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\UserServices;
use App\Services\AdminServices;
use App\Services\SpotsServices;
use App\Services\PackageServices;

class ViewController extends Controller
{
    public function adminDashboard(UserServices $userServices, AdminServices $adminServices, SpotsServices $spotsServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();
        $allUsers = $adminServices->getAllUsers();
        $agencyDetails = $adminServices->getAgencyDetails();
        $allSpots = $spotsServices->getAllSpots();
        $totalUsers = $userServices->getUserTotal();
        $agencyTotal = $userServices->getAgencyTotal();

        return Inertia::render('Admin/index', [
            'userInformation' => $userInformation,
            'agencyDetails'   => $agencyDetails,
            'allUsers'        => $allUsers,
            'allSpots'     => $allSpots,
            'totalUsers' => $totalUsers,
            'agencyTotal' => $agencyTotal
        ]);
    }

    public function userDashboard(UserServices $userServices, SpotsServices $spotsServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();
        $allSpots = $spotsServices->getAllSpots();

        return Inertia::render('User/index', [
            'userInformation' => $userInformation,
            'allSpots'     => $allSpots,
        ]);
    }
    public function agencyDashboard(UserServices $userServices, SpotsServices $spotsServices, PackageServices $packageServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();
        $allSpots = $spotsServices->getAllSpots();
        $allPackages = $packageServices->getAllOwnedPackages();

        return Inertia::render('Agency/index', [
            'userInformation' => $userInformation,
            'allSpots'     => $allSpots,
            'allPackages'  => $allPackages,
        ]);
    }

}
