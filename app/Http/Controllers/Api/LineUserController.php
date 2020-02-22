<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class LineUserController extends Controller
{
    public function index()
    {
        return $this->success(['message' => 'OK']);
    }
}
