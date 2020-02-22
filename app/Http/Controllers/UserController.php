<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateSlackInfoRequest;
use Illuminate\Routing\Controller;
use App\Http\Requests\User\ShowRequest;

use App\Services\UserService;


class UserController extends Controller
{

    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function show(ShowRequest $request)
    {
        $res = $this->user_service->getUserByName($request->route('name'));
        return $res;
    }

    public function updateSlackInfo(UpdateSlackInfoRequest $request)
    {
        $res = $this->user_service->updateSlackInfo($request->input('user_id'), $request->validated());
        return $res;
    }

    public function updateFollowStatus(UpdateSlackInfoRequest $request)
    {
        $res = $this->user_service->updateSlackInfo($request->input('user_id'), $request->all());
        return $res;
    }
}
