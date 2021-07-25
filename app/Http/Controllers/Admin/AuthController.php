<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\DB;
use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Contracts\Repositories\PasswordResetRepositoryInterface;


class AuthController extends Controller
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private Authservice $authService,
    ){}

    /**
     * Get the information admin created new admin.
     * This function is only accessible to the admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function register(Request $request)
    {
       
    }

    /**
     * Get the credential.
     * This function is only accessible to the admim.
     * Return access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function login(Request $request)
    {
        // validate login data
        $this->authService->validateLogin($request->only(['email', 'password']));

        // get admin with email
        $admin = $this->adminRepository->where('email', $request->email);

        // check admin authorized
        $this->authService->checkUserAuthorized($admin, $request->password);
        
        // create access token
        $token = $admin->createToken('admin')->accessToken;

        return $this->successResponse($token);
    }

     /**
     * Create token for password reset.
     * Emails the password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function forgotPassword(Request $request)
    {
        $admin = $this->adminRepository->where('email', $request->email);
        
        $this->authService->checkExist($admin, __('messages.not_found', [
            'name' => 'email'
        ]));
        
        // If for this email token exist remove record.
        $this->passwordResetRepository->deleteToken($request->email);
        
        $token = $this->authService->createToken(60);
        
        // create new token for email
        $this->passwordResetRepository->create([
            'email' => $request->email,
            'token' => $token,
        ]);
        
        // Create link for password reset
        $link = $this->authService->passwordResetLink($token);

        $this->authService->sendMail($request->email, new PasswordReset($link));

        return $this->successResponse(message: __('messages.reset_password'));
    }

    public function resetPassword(Request $request)
    {
        $this->authService->validateResetPassword($request->only([
            'password', 'password_confirmation'
        ]));

        $email = $this->passwordResetRepository->getEmail($request->token);

        $this->authService->checkExist($email, __('messages.not_found', [
            'name' => 'token'
        ]));

        $admin = $this->adminRepository->where('email', $email);

        $password = $this->authService->makeHash($request->password);

        $this->adminRepository->update(['password' => $password], $admin);

        $this->passwordResetRepository->deleteToken($email);

        return $this->successResponse(message: __('messages.changed', [
            'name' => 'password'
        ]));
    }
}
