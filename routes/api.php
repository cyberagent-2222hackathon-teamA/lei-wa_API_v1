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
    Route::get ('/twitter/login', 'Auth\Login\TwitterController@getRedirectUrl');
    Route::get ('/twitter/callback', 'Auth\Login\TwitterController@twitterCallback');

    Route::get ('/users/{name}', 'UserController@show');

    Route::get ('/users/{name}/contributes', 'User\ContributeController@index');

    Route::get ('/timeline/public', 'TimelineController@public');

    Route::middleware('check_auth')->group(function () {
        Route::get ('/timeline/private', 'TimelineController@private');
//        Route::get ('/slack/users', 'SlackController@getUsers');
    });
});

