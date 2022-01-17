<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
         
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            // 'phone' => 'required|integer|max:255',
            // 'roles' => 'string|max:255',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            // 'status' => $request->status,
            'roles' => $request->roles
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;
        return new UserResource($user,$token);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
                'messege' =>'Your Email or Password does not Match. Please Try Again'
            ];
            return response($response, 403);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        
        return new UserResource($user,$token);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $response = [
            'messege' =>'Logout Successfull'
        ];
        
        return response($response, 201);

    }

    public function agent()
    {
        return UserResource::collection(User::where('status', 'onhold')->paginate());
    }

    public function verify(Request $request, $user)
    {
        $user = User::find($user);
        $user->status = $request->status;
        $user->save();
        return new UserResource($user,null);
    }
}
