<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\AuthHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\RegisterAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $user = User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
            'is_active'=> false,
        ]);

        $registerAttempt = new RegisterAttempt();
        $registerAttempt->user_id = $user->id;
        unset($input['password']);
        $registerAttempt->body = json_encode($input);
        $registerAttempt->save();

        $access_token = $user->createToken('access-token')->accessToken;
        $res = AuthHelper::generateAuthResult($access_token, $user);
        return $this->success(__('app.user.register-success'), $res);
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();
        $login_credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];
        if (!auth()->attempt($login_credentials)) {
            return $this->failure(__('app.user.login-failure'), HTTPHeader::NOT_FOUND);
        }
        $user = auth()->user();
        if (!$user->is_active) {
            return $this->failure(__('app.user.login-failure.inactive'), HTTPHeader::FORBIDDEN);
        }
        $access_token = $user->createToken('access-token')->accessToken;
        $res = AuthHelper::generateAuthResult($access_token, $user);
        return $this->success(__('app.user.login-success'), $res);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return $this->success(__('app.user.logout-success'));
    }
}
