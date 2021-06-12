<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnauthorizedException;

class AuthService
{
    /**
     * check user authorized.
     * 
     * @param object $user
     * @param string $password
     * @return App\Exceptions\UnauthorizedException|true
     */
    public function check_user_authorized($user, $password) 
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw new UnauthorizedException(__('messages.unauthorized'));
        }

        return true;
    }
}