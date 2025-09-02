<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    public function adminDashboard(): \Inertia\Response
    {
        return Inertia::render('Admin/index');
    }
    public function userDashboard(): \Inertia\Response
    {
        return Inertia::render('User/index');
    }

}
