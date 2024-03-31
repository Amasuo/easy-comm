<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\AuthHelper;
use App\Helpers\GeneralHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\RegisterAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
        return $this->success(__('app.user.register-success'), $res, HTTPHeader::CREATED);
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

    public function update(UpdateAccountRequest $request)
    {
        $user = auth()->user();
        $input = $request->validated();
        //check email taken
        if (array_key_exists('email', $input)) {
            $email = $input['email'];
            if (GeneralHelper::valueTakenForClassAttribute(User::class, 'email', $email, $user->id)) {
                return $this->failure(__('app.auth.email-taken'));
            }
        }
        // @todo: this throws foreign key error if we don't disable the check, why ???? 
        Schema::disableForeignKeyConstraints();
        $user->fill($input);
        $user->save();
        Schema::enableForeignKeyConstraints();

        return $this->success(__('app.auth.updated'), $user);
    }
}
