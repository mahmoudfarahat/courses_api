<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use  App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ApiResource;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $token = $user->createToken('myapptoken')->plainTextToken;
        return  (new ApiResource($user))->additional(['token' =>$token]);;

    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed'
        ]);

        $user =User::where('email',$request->email)->first();



        if (! $user || ! Hash::check($request->password, $user->password))
        {
            return response()->json(['message' => 'check your password or your email'] , 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        return  (new ApiResource($user))->additional(['token' =>$token, 'message' =>'login success']);


    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);

    }

}
