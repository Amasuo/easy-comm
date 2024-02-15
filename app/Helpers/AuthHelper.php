<?php

namespace App\Helpers;

use App\Models\RegisterAttempt;
use App\Models\Store;
use App\Models\User;

class AuthHelper
{
    public static function generateAuthResult($access_token, $user)
    {
        $res = new \stdClass();
        $res->access_token = $access_token;
        $res->user = $user;

        return $res;
    }
}
