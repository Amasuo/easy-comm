<?php

namespace App\Helpers;

use App\Models\User;

class AuthHelper
{
    public static function register($input)
    {
        $user = User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => bcrypt($input['password'])
        ]);

        $access_token = $user->createToken('access-token')->accessToken;
        return self::generateAuthResult($access_token, $user);
    }

    public static function login($input)
    {
        $login_credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];
        if (auth()->attempt($login_credentials)) {
            $user = auth()->user();
            $access_token = $user->createToken('access-token')->accessToken;
            return self::generateAuthResult($access_token, $user);
        } else {
            return false;
        }
    }

    private static function generateAuthResult($access_token, $user)
    {
        $res = new \stdClass();
        $res->access_token = $access_token;
        $res->user = $user;

        return $res;
    }
}
