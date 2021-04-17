<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/sanctum/token', ['App\Http\Controllers\LoginController', 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sendinvitationlink', ['App\Http\Controllers\SendInvitationController', 'sendinvitationlink']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/update', ['App\Http\Controllers\UserController', 'update']);
});

Route::post('/signup', ['App\Http\Controllers\UserController', 'signup']);
Route::get('/verify', ['App\Http\Controllers\VerifyController', 'verify']);
