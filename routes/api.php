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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:google'], function () {
    Route::get('current-user', [\App\Http\Controllers\Server\AuthController::class, 'currentUser']);
});

Route::group(['controller' => \App\Http\Controllers\Server\AuthController::class, 'prefix' => 'auth'], function () {
    Route::post('/sign-in', 'signIn');

});
