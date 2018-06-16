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

/**
 * Routes for resetting password.
 */

//send email to received mail address for password reset
Route::post('/forgotpassword', 'ForgotPasswordController@forgot');

//check received token and id
Route::post('/forgotpassword/check', 'ForgotPasswordController@check');

//reset password for user
Route::post('/forgotpassword/new', 'ForgotPasswordController@newPassword');

/**
 * Routes for user CRUD.
 */

//get specific user
Route::get('/user', 'UserController@index')->middleware('auth:api');

//update user
Route::put('/user', 'UserController@update')->middleware('auth:api');

//register user
Route::post('/user', 'UserController@create');

/**
 * Routes for location CRUD.
 */

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

Route::get('/{user}/locations/visited/count', 'LocationController@visitedCount');
Route::get('/{user}/locations/planned/count', 'LocationController@plannedCount');


/**
 * Routes for picture CRUD
 */

Route::post('/{user}/avatar', 'AvatarController@store');
Route::get('/{user}/avatar', 'AvatarController@show');