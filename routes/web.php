<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\UserSettings;



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
    Route::post('/add-blog', [AdminController::class, 'addBlog']);
    Route::delete('/delete-spot/{id}', [AdminController::class, 'deleteSpot']);
    Route::get('/agencies-by-spot/{spotId}', [UserController::class, 'getAgencyInSpots']);


    Route::post('/saved-unsave-spot', [UserController::class, 'saveUnsaveSpot']);
    Route::get('/check-saved-unsave-spot/{id}', [UserController::class, 'getSpotSavedStatus']);
    Route::get('/agencies-by-spot/{spotId}', [UserController::class, 'getAgenciesByPackageAndSpot']);
    Route::get('/check-if-agency-is-liked', [UserController::class, 'checkIfAgencyIsLiked']);
    Route::post('/like-unlike-agency', [UserController::class, 'likeUnlikeAgency']);
    Route::get('/get-spots', [UserController::class, 'getSpots']);
    Route::post('/add-reservation', [UserController::class, 'addReservation']);
    Route::post('/update-reservation/{id}', [AgencyController::class, 'updateReservation']);

    Route::post('/spots/{id}/rate', [UserController::class, 'rating']);
    Route::get('/spots/{id}/rating', [UserController::class, 'getRatings']);

    Route::get('/spots/{id}/reviews', [UserController::class, 'getReviews']);
    Route::post('/spots/{id}/reviews', [UserController::class, 'addReview']);

    Route::get('/user-information', [UserSettings::class , 'getPersonalInformation']);
    Route::put('/user-information', [UserSettings::class, 'updatePersonalInformation']);
    Route::put('/user-password', [UserSettings::class, 'updatePassword']);
    Route::post('/agency-information', [UserSettings::class, 'updateAgencyInformation']);
    Route::get('/agency-information', [UserSettings::class , 'getAgencyInformation']);
    Route::get('/notifications', [UserSettings::class , 'getUserNotifications']);
    Route::delete('/packages/{id}', [AgencyController::class, 'deletePackage']);

});
