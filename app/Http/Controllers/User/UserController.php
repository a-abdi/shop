<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private UserService $userService,
        private UserRepositoryInterface $userRepository,
    ){}

    public function update(Request $request)
    {
        $user = Auth::user();

        // check user authorized.
        $this->authService->checkUserAuthorized($user, $request->password);

        // Validate user data.
        $dataUpdate = $this->authService->validateUserUpdate($request);

        $this->userRepository->update($dataUpdate, $user);

        $user = Auth::user();

        return $this->successResponse($user, __('messages.updated', [
            'name' => 'user'
        ]));
    }
}
