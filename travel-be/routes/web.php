<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
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
Route::delete('/{user}/locations/{id}', 'LocationController@destroy');

Auth::routes();


