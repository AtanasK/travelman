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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::get('/user', 'UserController@index')->middleware('auth:api');

Route::put('/user', 'UserController@update')->middleware('auth:api');

Route::post('/user', 'UserController@create');

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
