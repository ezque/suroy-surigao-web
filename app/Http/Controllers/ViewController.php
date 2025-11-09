<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\UserServices;
use App\Services\AdminServices;
use App\Services\SpotsServices;
use App\Services\PackageServices;
use App\Services\AgencyServices;

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
        $allBlogs = $adminServices->getAllBlogs();

        return Inertia::render('Admin/index', [
            'userInformation' => $userInformation,
            'agencyDetails'   => $agencyDetails,
            'allUsers'        => $allUsers,
            'allSpots'     => $allSpots,
            'totalUsers' => $totalUsers,
            'agencyTotal' => $agencyTotal,
            'allBlogs' => $allBlogs,
        ]);
    }

    public function userDashboard(UserServices $userServices, SpotsServices $spotsServices, PackageServices $packageServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();
        $allSpots = $spotsServices->getAllSpots();
        $agencies = $userServices->getAgencies();
        $activePackages = $packageServices->getActivePackages();
        $blogsURL = $userServices->getAllBlogUrlsRandom();
        $getAllSaveSpots = $spotsServices->getAllSaveSpots();

        return Inertia::render('User/index', [
            'userInformation' => $userInformation,
            'allSpots'     => $allSpots,
            'agencies' => $agencies,
            'activePackages' => $activePackages,
            'blogsURL' => $blogsURL,
            'saveSpots' => $getAllSaveSpots,
        ]);
    }
    public function agencyDashboard(UserServices $userServices, SpotsServices $spotsServices, PackageServices $packageServices, AgencyServices $agencyServices): \Inertia\Response
    {
        $userInformation = $userServices->getUserInformation();
        $getAgencyInformation = $userServices->getAgencyInformation();
        $allSpots = $spotsServices->getAllSpots();
        $allPackages = $packageServices->getAllOwnedPackages();
        $reservationData = $agencyServices->getAllReservation();

        return Inertia::render('Agency/index', [
            'userInformation' => $userInformation,
            'agencyDetails'   => $getAgencyInformation,
            'allSpots'     => $allSpots,
            'allPackages'  => $allPackages,
            'allReservations' => $reservationData['reservations'],
            'totalReservations' => $reservationData['totalReservations'],
        ]);
    }

}
