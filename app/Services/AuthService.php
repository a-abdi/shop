<?php

namespace App\Services;

use Exception;
use App\Services\MainService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Auth;

class AuthService extends MainService
{
    private const USER_RULE = [
        'name'      => 'required|string|min:3|max:100',
        'email'     => 'required|email|max:255|unique:users',
        'password'  => 'required|confirmed|min:8|max:255',
    ];

    /**
     * Validate register data.
     *
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validateRegister($data)
    {
        $this->validate($data, self::USER_RULE);

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
            'email'    => 'required|email|max:255',
            'password' => 'required',
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
    public function adminPasswordResetLink($token)
    {
        return $passwordResetUrl = "http://192.168.1.138:3000/admin/reset-password/". $token;
    }

     /**
     * Create new passwordReset link.
     * 
     * @param string $token
     * @return string $link
     */
    public function userPasswordResetLink($token)
    {
        return $passwordResetUrl = "http://192.168.1.138:3000/reset-password/". $token;
    }

    /**
     * Validate password data.
     * 
     * @param array
     * @return App\Exceptions\InvalidArgumentException|true
     */
    public function validatePassword($data)
    {
        $this->validate($data, ["password" => self::USER_RULE['password']]);

        return true;
    }

    /**
     * Validate new password data.
     * 
     * @param \Illuminate\Http\Request $request
     * @return array | App\Exceptions\InvalidArgumentException
     */
    public function validateUserUpdate($request)
    {
        if ($request->name) {
            $this->validate($request->only('name'), ["name" => self::USER_RULE['name']]);
            return $request->only('name');
        }

        if ($request->email) {
            $this->validate($request->only('email'), ["email" => self::USER_RULE['email']]);
            return $request->only('email');
        }

        if ($request->new_password) {
            $this->validate(
                $request->only(['new_password', 'new_password_confirmation']), 
                ["new_password" => self::USER_RULE['password']]
            );
            $request['password'] = $this->makeHash($request->new_password);
            return $request->only('password');
        }
        
        throw new InvalidArgumentException();
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
}