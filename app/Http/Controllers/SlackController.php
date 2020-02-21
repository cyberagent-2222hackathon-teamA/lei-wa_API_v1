<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Slack\GetUsersRequest;
use App\Services\SlackService;


class SlackController extends Controller
{

    private $slack_service;

    public function __construct(SlackService $slack_service)
    {
        $this->slack_service = $slack_service;
    }

    public function getUsers(GetUsersRequest $request)
    {
        $res = $this->slack_service->getSameWorkSpaceUsers($request->input('user_id'));
        return $res;
    }
}
