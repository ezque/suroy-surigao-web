<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;



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
    Route::post('/update-spot/{id}', [AdminController::class, 'updateSpot']);

    Route::post('/add-package', [AgencyController::class, 'addPackage']);
    Route::post('/update-package/{id}', [AgencyController::class, 'updatePackage']);

    Route::post('/update-user-status', [AdminController::class, 'updateUserStatus']);

    Route::get('/agencies-by-spot/{spotId}', [UserController::class, 'getAgencyInSpots']);


    Route::post('/saved-unsave-spot', [UserController::class, 'saveUnsaveSpot']);
    Route::get('/check-saved-unsave-spot/{id}', [UserController::class, 'getSpotSavedStatus']);
    Route::get('/agencies-by-spot/{spotId}', [UserController::class, 'getAgenciesByPackageAndSpot']);
    Route::get('/check-if-agency-is-liked', [UserController::class, 'checkIfAgencyIsLiked']);
    Route::post('/like-unlike-agency', [UserController::class, 'likeUnlikeAgency']);
    Route::get('/get-spots', [UserController::class, 'getSpots']);
    Route::post('/add-reservation', [UserController::class, 'addReservation']);
});
