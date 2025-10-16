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
}
