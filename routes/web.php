<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/register', [AuthController::class, 'registerPost']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin-dashboard', [ViewController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/user-dashboard', [ViewController::class, 'userDashboard'])->name('user.dashboard');

});
