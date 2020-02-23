<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\FbUserRequest;
use App\Http\Resources\Api\FbUserResource;
use App\Models\FbUser;

class FbUserController extends Controller
{
    public function index()
    {
        $fbUsers = FbUser::all();
        return $this->success(FbUserResource::collection($fbUsers));
    }

    public function show(FbUser $fb_user)
    {
        return $this->success(new FbUserResource($fb_user));
    }

    public function store(FbUserRequest $request)
    {
        $fbUser = FbUser::create($request->all());
        return $this->setStatusCode(201)->success(new FbUserResource($fbUser));
    }

    public function join(FbUserRequest $request)
    {
        $fbUser = FbUser::create($request->all());
        $response = [
            'messages' => [['text' => '歡迎您, ' . $fbUser->first_name]]
        ];
        return $this->success($response);
    }
}
