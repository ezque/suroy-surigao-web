<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\SpotController;
use App\Http\Controllers\Mobile\ReservationController;
use App\Http\Controllers\Mobile\UserSettings;
use App\Http\Controllers\Mobile\NotificationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/spot-card', [SpotController::class, 'getSpotCards']);
Route::middleware('auth:sanctum')->get('/user-profile', [UserSettings::class, 'getUserProfile']);
Route::middleware('auth:sanctum')->get('/notification', [NotificationController::class, 'getUserNotifications']);

Route::middleware('auth:sanctum')->post('/save-unsave-spot', [SpotController::class, 'saveUnsaveSpot']);
Route::middleware('auth:sanctum')->post('/add-reservation', [ReservationController::class, 'addReservation']);
Route::middleware('auth:sanctum')->put('/update-profile', [UserSettings::class, 'updatePersonalInformation']);

