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

    Route::get ('/users/{twitter_id}', 'UserController@show');

    Route::get ('/users/{twitter_id}/contributes', 'User\ContributeController@index');

    Route::get ('/timeline/public', 'TimelineController@public');

    Route::middleware('check_auth')->group(function () {
        Route::get ('/timeline/private', 'TimelineController@private');
    });
});

