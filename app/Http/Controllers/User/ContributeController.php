<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Controller;

use App\Services\User\ContributeService;
use App\Http\Requests\User\Contribute\IndexRequest;

class ContributeController extends Controller
{

    private $contribute_service;

    public function __construct(ContributeService $contribute_service)
    {
        $this->contribute_service = $contribute_service;
    }

    public function index(IndexRequest $request)
    {
        $res = $this->contribute_service->getUserContributesById($request->route('user_id'), $request->validated());
        return $res;
    }
}
