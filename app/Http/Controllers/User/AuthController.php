<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService;
use App\Contracts\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(
        private Authservice $authService,
        private UserRepositoryInterface $userRepository,
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

        $request['password'] = Hash::make($request->password);

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
        // validate login data
        $this->authService->validateLogin($request->only(['email', 'password']));

        // get user with email
        $user = $this->userRepository->where('email', $request->email);

        // check admin authorized
        $this->authService->checkUserAuthorized($user, $request->password);

        // create access token
        $token = $user->createToken('user')->accessToken;
        
        return $this->successResponse($token);
    }
}
