<?php

namespace App\Http\Controllers\Auth\Login;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\Login\Twitter\CallbackRequest;

use App\Services\Login\TwitterService;
use Abraham\TwitterOAuth\TwitterOAuth;


class TwitterController extends Controller
{

    private $twitter_service;

    public function __construct(TwitterService $twitter_service)
    {
        $this->twitter_service = $twitter_service;
    }

    public function getRedirectUrl(Request $request)
    {
        $res = $this->twitter_service->getRedirectUrl();
        return $res;
    }

    public function twitterCallback(CallbackRequest $request)
    {
        $res = $this->twitter_service->twitterUserVerification($request->query('oauth_token'), $request->query('oauth_verifier'));
        return $res;
    }
}
