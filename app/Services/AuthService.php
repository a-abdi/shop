<?php

namespace App\Services;

use Exception;
use App\Services\MainService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnauthorizedException;

class AuthService extends MainService
{
    /**
     * Validate register data.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateRegister($data)
    {
        $rule = [
            'name'                  => 'required|string|min:3|max:100',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|confirmed|min:8|max:255',
            'password_confirmation' => 'required'
        ];

        $this->validate($data, $rule);

        return true;
    }

     /**
     * Validate login data.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateLogin($data)
    {
        $rule = [
            'email'    => 'required|email|',
            'password' => 'required',
        ];

        $this->validate($data, $rule);

        return true;
    }

    /**
     * Validate reset password data.
     * 
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateResetPassword($data)
    {
        $rule = [
            'password'              => 'required|confirmed|min:8|max:255',
        ];

        $this->validate($data, $rule);

        return true;
    }

    /**
     * Check user authorized.
     * 
     * @param object $user
     * @param string $password
     * @return App\Exceptions\UnauthorizedException|true
     */
    public function checkUserAuthorized($user, $password) 
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw new UnauthorizedException(__('messages.unauthorized'));
        }

        return true;
    }

     /**
     * Create new passwordReset link.
     * 
     * @param string $token
     * @return string $link
     */
    public function passwordResetLink($token)
    {
        return $passwordResetUrl = "http://192.168.1.136:3000/admin/reset-password/". $token;
    }
}