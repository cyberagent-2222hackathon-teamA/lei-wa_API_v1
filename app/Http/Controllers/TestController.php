<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return [
            'status' => 'ok'
        ];
    }
}
