<?php

namespace App\Services;
use App\Models\Spot;
class SpotsServices
{
    /**
     * Create a new class instance.
     */
    public function getAllSpots()
    {
        return Spot::with('images')->get();
    }
}
