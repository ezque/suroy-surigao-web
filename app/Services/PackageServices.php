<?php

namespace App\Services;
use App\Models\Package;
class PackageServices
{

    public function getAllPackages()
    {
        $packages = Package::all();

        return $packages;
    }
}
