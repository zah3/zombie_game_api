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
//Route::group(['middleware' => 'throttle:10,10'], function () {

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
// });

Route::prefix('verification')->group(function () {
    Route::post('resend', 'Auth\VerificationController@resend')->name('verification.resend');
});

Route::group([
    'namespace' => 'Auth',
    'prefix' => 'password/reset'
], function () {
    Route::post('/', 'PasswordResetController@store');
});
Route::group([
    'namespace' => 'Auth',
    'prefix' => 'password/change'
], function () {
    Route::post('', 'PasswordChangeController@store');
});

//});
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'API\UserController@logout');

    Route::prefix('user')->group(function () {
        Route::get('/', 'API\UserController@index');
        Route::post('/store', 'API\UserController@store');
        Route::delete('/destroy/{id}', 'API\UserController@destroy');
        Route::get('/show/{id}', 'API\UserController@show');
        Route::put('/update/{id}', 'API\UserController@update');

        Route::prefix('/characters')->group(function () {
            Route::delete('/{character}', 'API\UserCharacterController@destroy');
            Route::post('/', 'API\UserCharacterController@store');
            Route::get('/{character}', 'API\UserCharacterController@show');
            Route::put('/{character}', 'API\UserCharacterController@update');
            Route::get('/', 'API\UserCharacterController@index');
        });
    });

    Route::prefix('/game')->group(function(){
        Route::get('/{character}','API\GameController@show');
        Route::put('/{character}','API\GameController@update');
    });
});
