<?php

use App\Http\Controllers\Api\V1\AnswerController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\ChannelController;
use App\Http\Controllers\Api\V1\SubscribeController;
use App\Http\Controllers\Api\V1\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

include __DIR__ . '/v1/auth.php';

Route::apiResource('channels', ChannelController::class);
Route::apiResource('threads', ThreadController::class);
Route::prefix('threads')->group(function (){
    Route::apiResource('answers', AnswerController::class);
});

Route::prefix('/threads')->group(function (){
    Route::post('{thread}/subscribe',[SubscribeController::class,'Subscribe'])->name('subscribe');
    Route::post('{thread}/unsubscribe',[SubscribeController::class,'unSubscribe'])->name('unsubscribe');
});

