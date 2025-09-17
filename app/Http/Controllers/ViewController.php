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
        $userInformation = $userServices->getUserInformation();
        $allUsers = $adminServices->getAllUsers();
        $agencyDetails = $adminServices->getAgencyDetails();

        return Inertia::render('Admin/index', [
            'userInformation' => $userInformation,
            'agencyDetails'   => $agencyDetails,
            'allUsers'        => $allUsers,
        ]);
    }

    public function userDashboard(): \Inertia\Response
    {
        return Inertia::render('User/index');
    }
    public function agencyDashboard(UserServices $userServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();

        return Inertia::render('Agency/index', [
            'userInformation' => $userInformation,
        ]);
    }

}
