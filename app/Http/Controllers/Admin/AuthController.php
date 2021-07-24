<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRecovery;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Contracts\Repositories\AdminRepositoryInterface;


class AuthController extends Controller
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
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

    public function forgotPassword(Request $request)
    {
        $admin = $this->adminRepository->where('email', $request->email);

        $this->authService->checkExist($admin, __('messages.not_found', [
            'name' => 'email'
        ]));

        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
            
        $passwordResetUrl = "http://192.168.1.136:3000/admin/reset-password/". $token;

        Mail::to($request->email)->send(new PasswordRecovery($passwordResetUrl));

        return $this->successResponse(message: __('messages.reset_password'));
    }

    public function resetPassword(Request $request)
    {
        $this->authService->validateResetPassword($request->only([
            'password', 'password_confirmation'
        ]));

        $resetPasswordToken = $request->token;

        $email =  DB::table('password_resets')
                    ->where('token', $resetPasswordToken)
                    ->value('email');

        $this->authService->checkExist($email, __('messages.not_found', [
            'name' => 'token'
        ]));

        $admin = $this->adminRepository->where('email', $email);

        $request['password'] = Hash::make($request->password);

        $this->adminRepository->update($request->only(['password']), $admin);

        return $this->successResponse(message: __('messages.changed', [
            'name' => 'password'
        ]));
    }
}
