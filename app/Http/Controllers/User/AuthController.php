<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Jobs\SendMailPasswordReset;
use App\Jobs\ClearTokenPasswordReset;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Repositories\PasswordResetRepositoryInterface;
use App\Contracts\Repositories\PersonalInformationRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(
        private Authservice $authService,
        private UserRepositoryInterface $userRepository,
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private PersonalInformationRepositoryInterface $personalInformationRepository,
    ){}

    /**
     * Get the information user created new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function register(Request $request)
    {
        $this->authService->validateRegister($request->all());

        $request['password'] = $this->authService->makeHash($request->password);

        $this->userRepository->create($request->all());

        return $this->successResponse(message: __('messages.created', [
            'name' => 'user'
        ]), code: 201);
    }
    
    /**
     * Get the credential.
     * Return access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function login(Request $request)
    {
        // Validate login data.
        $this->authService->validateLogin($request->only(['email', 'password']));

        // Get user with email.
        $user = $this->userRepository->where('email', $request->email);

        // check user authorized.
        $this->authService->checkUserAuthorized($user, $request->password);

        // Create access token.
        $user->token = $user->createToken('user')->accessToken;

        // Get user personal information.
        $user->information = $this->personalInformationRepository->where('user_id', $user->id);

        return $this->successResponse($user);
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
        $user = $this->userRepository->where('email', $request->email);
        
        $this->authService->checkExist($user, __('messages.not_found', [
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

        $email = $request->email;

        $link = $this->authService->userPasswordResetLink($token);
        
        ClearTokenPasswordReset::dispatch($email)->delay(now()->addMinutes(5));
        
        SendMailPasswordReset::dispatch($email, $link);

        return $this->successResponse(message: __('messages.reset_password'));
    }

     /**
     * Find email then change password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function resetPassword(Request $request)
    {
        $this->authService->validateResetPassword($request->only([
            'password', 'password_confirmation'
        ]));

        $email = $this->passwordResetRepository->getEmail($request->token);

        $this->authService->checkExist($email, __('messages.not_found', [
            'name' => 'token'
        ]));

        $user = $this->userRepository->where('email', $email);

        $password = $this->authService->makeHash($request->password);

        $this->userRepository->update(['password' => $password], $user);

        $this->passwordResetRepository->deleteToken($email);

        return $this->successResponse(message: __('messages.changed', [
            'name' => 'password'
        ]));
    }
}
