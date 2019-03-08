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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::group(['middleware' => 'throttle:10,10'], function () {

    Route::post('login', 'API\UserController@login');

    Route::post('register', 'API\UserController@register');

//});
Route::group(['middleware' => 'auth:api'], function() {

    Route::post('logout','API\UserController@logout');

});


Route::prefix('user')->group(function () {

    Route::get('/','API\UserController@index')->middleware('auth:api');

    Route::post('/store','API\UserController@store')->middleware('auth:api');

    Route::delete('/destroy/{id}','API\UserController@destroy')->middleware('auth:api');

    Route::get('/show/{id}','API\UserController@show')->middleware('auth:api');

    Route::put('/update/{id}','API\UserController@update')->middleware('auth:api');

});
