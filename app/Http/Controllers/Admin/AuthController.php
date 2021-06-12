<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
        $admin = Admin::where('email', $request->email)->first();

        if(!$admin) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        if(!Hash::check($request->password, $admin->password)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $token = $admin->createToken('admin')->accessToken;

        return response()->json([
            'access_token' => $token
        ], 200);
    }
}
