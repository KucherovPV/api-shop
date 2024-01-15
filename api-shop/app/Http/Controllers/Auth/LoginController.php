<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $cred = $request->only('email','password');

        if (!$token = auth()->attempt($cred)) {
            $errors = [];

            if (!auth()->validate(['email' => $cred['email'], 'password' => $cred['password']])) {
                $errors['password'] = 'Auth failed';
            } else {
                $errors['email'] = 'Auth failed';
            }

            return response()->json($errors, 401);
        }
        return response()->json(['user_token' => $token],201);
    }
}
