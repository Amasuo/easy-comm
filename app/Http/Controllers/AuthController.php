<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\AuthHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $res = AuthHelper::register($input);
        return $this->success(__('app.user.register-success'), $res);
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();
        $res = AuthHelper::login($input);
        if ($res) {
            return $this->success(__('app.user.login-success'), $res);
        } else {
            return $this->failure(__('app.user.login-failure'), HTTPHeader::NOT_FOUND);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return $this->success(__('app.user.logout-success'));
    }
}
