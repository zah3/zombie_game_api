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

Auth::routes();

Route::middleware('web')->get('verify', 'Auth\VerificationController@verify')->name('verification.verify');

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'web',
    'prefix' => 'password'
], function () {
    Route::get('{token}', 'PasswordController@show');
});