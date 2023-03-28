<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // Validation
        $validate = $this->validateForm($request);

        // Login Form
        $email = $request->input('email');
        $password = $request->input('password');

        // Credentials for make JWT Token
        $credentials = ['email' => $email, 'password' => $password];

        // Check User
        $check = User::where('email', '=', $email)->first();

        // Checking if user exists
        if($check)
        {
            // Checking if password is matches from database
            if(Hash::check($password, $check['password']))
            {
                $token = auth()->attempt($credentials);
                // dd($token);
                return $this->createToken($token, $check);
            }else{ // If password is not match
                $response = [
                    'code' => 401,
                    'message' => 'Password is not valid',
                ];
                return response()->json($response, 401);
            }
        }else // If account isn't exist
        {
            $response = [
                'code' => 400,
                'message' => 'Account is not found',
            ];
            return response()->json($response, 400);
        }
    }

    private function validateForm($request)
    {
        // Validate Will Return to Login Functions
        return $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }

    private function createToken($token, $check)
    {
        // Response Login with Token and will return to Login Function
        return response()->json([
            'code' => 200,
            'message' => 'Login Success',
            'data' => [
                'id' => $check['id'],
                'name' => $check['name'],
                'email' => $check['email'],
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ], 200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
