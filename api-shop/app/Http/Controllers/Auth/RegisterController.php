<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CartsModels\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
class RegisterController extends Controller
{
    public function makeUserCart($id){

        $cart = new Cart();
        $cart->user_id = $id;
        $cart->save();
    }


    public function register(Request $request)
    {

        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json( $validator->errors(), 400);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $role = Role::findByName('customer');

        $user->assignRole($role);
        $user->save();

        $this->makeUserCart($user->id);

        $token = JWTAuth::fromUser($user);

        return response()->json(['user_token'=>compact('token')],201);
    }
}

