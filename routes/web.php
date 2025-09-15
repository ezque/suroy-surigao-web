<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/register', [AuthController::class, 'registerPost']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin-dashboard', [ViewController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/user-dashboard', [ViewController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/agency-dashboard', [ViewController::class, 'agencyDashboard'])->name('agency.dashboard');

    Route::post('/add-agency', [AdminController::class, 'addAgency'])->name('add.agency');
    Route::post('/add-new-spot', [AdminController::class, 'addSpot']);

});
