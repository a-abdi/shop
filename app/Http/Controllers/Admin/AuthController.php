<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Services\AuthService;
use App\Contracts\Repositories\AdminRepositoryInterface;


class AuthController extends Controller
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private Authservice $authService,
    ){}

    /**
     * Get the information admin created new admin.
     * This function is only accessible to the admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function register(Request $request)
    {
       
    }

    /**
     * Get the credential.
     * This function is only accessible to the admin user.
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
        $user = $this->adminRepository->where('email', $request->email);

        // check admin authorized
        $this->authService->checkUserAuthorized($user, $request->password);

        // create access token
        $token = $user->createToken('admin')->accessToken;
        
        return $this->successResponse($token);
    }
}
