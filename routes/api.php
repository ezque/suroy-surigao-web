<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\SpotController;
use App\Http\Controllers\Mobile\ReservationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/spot-card', [SpotController::class, 'getSpotCards']);
Route::middleware('auth:sanctum')->post('/save-unsave-spot', [SpotController::class, 'saveUnsaveSpot']);
Route::middleware('auth:sanctum')->post('/add-reservation', [ReservationController::class, 'addReservation']);
