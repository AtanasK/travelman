<?php

use Illuminate\Http\Request;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//get all locations for user
Route::get('/{user}/locations', 'LocationController@index');

//get selected location info for user
Route::get('/{user}/locations/{location}', 'LocationController@show');

//add new location
Route::post('/{user}/locations', 'LocationController@store');

//edit location with id for user
Route::put('/{user}/locations/{location}', 'LocationController@update');

//delete location with id for user
Route::delete('/{user}/locations/{location}', 'LocationController@destroy');

