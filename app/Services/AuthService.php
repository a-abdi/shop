<?php

namespace App\Services;

use Exception;
use App\Services\MainService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnauthorizedException;

class AuthService extends MainService
{
     /**
     * Validate login data.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validate_login($data)
    {
        $rule = [
            'email'    => 'required|email|',
            'password' => 'required',
        ];

        $this->validate($data, $rule);

        return true;
    }

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