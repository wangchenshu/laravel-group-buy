<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user));
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        return $this->setStatusCode(201)->success($user);
    }

    public function update(User $user, UserRequest $request)
    {
        $user->update($request->all());
        return $this->setStatusCode(201)->success(new UserResource($user));
    }

    public function login(Request $request)
    {
        $token = Auth::guard('api')->attempt(
            [
                'name' => $request->name,
                'password' => $request->password
            ]
        );

        if ($token) {
            return $this->setStatusCode(201)->success(['token' => 'Bearer ' . $token]);
        }
        return $this->failed('帳號或密碼錯誤', 400);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->success('登出成功');
    }
}
