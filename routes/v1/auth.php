<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function(){
    Route::post('register',[AuthController::class,'register'])->name('register');
    Route::post('login',[AuthController::class,'login'])->name('login');
    Route::post('logout',[AuthController::class,'logout'])->name('logout');
    Route::get('user',[AuthController::class,'user'])->name('user');
});
