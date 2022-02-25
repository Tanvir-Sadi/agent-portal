<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\MediaTypeResource;

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

    public function logout()
    {
        auth()->user()->tokens()->delete();
        $response = [
            'messege' =>'Logout Successfull'
        ];
        
        return response($response, 201);

    }

    public function agent()
    {
        return UserResource::collection(User::where('status', 'onhold')->orderBy('updated_at','desc')->paginate(6));
    }

    public function agentVerified()
    {
        return UserResource::collection(User::where('status', 'verified')->orderBy('updated_at','desc')->paginate(6));
    }

    public function verify(Request $request, $user)
    {
        $user = User::find($user);
        $user->status = $request->status;
        $user->save();
        return new UserResource($user,null);
    }
    public function deleteAgent($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json('Successfully Deleted', 200);
    }

    public function profile()
    {
        return new UserResource(auth()->user(),null);
    }

    public function getMedia($id)
    {
        $user = User::find($id);
        return MediaTypeResource::collection($user->getMedia());
    }

    public function uploadMediaAsAgent(Request $request)
    {
        if ($request->hasFile('document')) {
            $user = auth()->user();
            $user->addMedia($request->document)->toMediaCollection();
            return response()->json('Uploaded Successfully',200);
        }else{
            return response()->json('File Not Found',404);
        }
    }

    public function getMediaAsAgent()
    {
        $user = auth()->user();
        return MediaTypeResource::collection($user->getMedia());
    }
}
