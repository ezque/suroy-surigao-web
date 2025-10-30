<?php

namespace App\Services;
use App\Models\Package;
class PackageServices
{

    public function getAllOwnedPackages()
    {
        $userId = auth()->id();

        $packages = Package::where('userID', $userId)->get();

        return $packages;
    }

    public function getActivePackages()
    {
        $activePackage = Package::orderBy('created_at', 'desc')->get();

        return $activePackage;
    }
}
