<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserService $userService,
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->all();

        return $this->successResponse($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        // Check exist user.
        $this->userService->checkExist($user, __('messages.not_found', [
            'name' => 'user'
        ]));

        return $this->successResponse($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        // Check exist user.
        $this->userService->checkExist($user, __('messages.not_found', [
            'name' => 'user'
        ]));

        $this->userRepository->destroy($user->id);

        return $this->successResponse(message: __('messages.deleted', [
            'name' => 'user'
        ]));
    }
}
