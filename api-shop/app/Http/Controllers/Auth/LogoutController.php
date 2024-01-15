<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class LogoutController extends Controller
{
    public function logout()
    {
        try {
            $user = auth()->userOrFail();
            if (!$user->hasRole('customer') ) {
                return response()->json(['message' => 'Forbidden for you'], 403);
            }
        } catch (UserNotDefinedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
        Auth::logout();

        return response()->json(['message' => 'logout'], 200);
    }
}
