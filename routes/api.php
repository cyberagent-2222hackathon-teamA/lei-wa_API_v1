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
Route::group(['prefix' => 'v1'], function() {
    Route::get ('/test', 'TestController@index');
    Route::get ('/twitter/login', 'Auth\Login\TwitterController@getRedirectUrl');
    Route::get ('/twitter/callback', 'Auth\Login\TwitterController@twitterCallback');

    Route::get ('/users/{user_id}', 'UserController@show');
});

