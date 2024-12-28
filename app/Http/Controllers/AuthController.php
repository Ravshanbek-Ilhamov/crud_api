<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Jobs\SendVarificationCode;
use App\Mail\ValidationMail;
use App\Models\User;
use App\Models\ValidationPassword;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        FacadesAuth::attempt($request->only('email','password'));

        if (FacadesAuth::check()) {
            
            $token = FacadesAuth::user()->createToken('auth_token')->plainTextToken;

            $data = [
                'user' => FacadesAuth::user(),
                'token' => $token
            ];

            return response()->json($data, 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function register(Request $request) {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user  = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // $token = $user->createToken('auth_token')->plainTextToken;

        $randNumber = rand(100000, 999999);

        ValidationPassword::create([
            'user_id' => $user->id,
            'password' => $randNumber
        ]);

        $data = [
            'password' => $randNumber,
            'name' => $user->name,
            'email' => $user->email,
        ];

        SendVarificationCode::dispatch($data);

        $data = [
            'message' => 'Varification code has been sent to your email',
        ];

        return response()->json($data, 201);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();

        $response = [
            'status' => 'success',
            'message' => 'Logout successfully'
        ];
        return response()->json($response, 200);

    }

    public function takeToken(Request $request) {

        $request->validate([
            'code' => 'required',
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        $sentCode = ValidationPassword::where('user_id', $user->id)->first();

        if ( $sentCode && ($sentCode->password == $request->code)) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = [
                'message' => 'Welcome!',
                'user' => new UserResource($user),
                'token' => $token
            ];
            return response()->json($data, 200);
        }else{
            return response()->json(['message' => 'Code is not correct or Register Again'], 401);
        }
    }

    public function forgetPassword(Request $request) {

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $randNumber = rand(100000, 999999);

            $data = [
                'password' => $randNumber,
                'name' => $user->name,
                'email' => $user->email,
            ];

            SendVarificationCode::dispatch($data);

            $user->update([
                'password' => Hash::make($randNumber)
            ]);

            $response = [
                'status' => 'success',
                'message' => 'Your new password has been sent to your email'
            ];
            return response()->json($response, 200);
        }else{
            return response()->json(['message' => 'User not found'], 401);
        }
    }

    public function authUser(){
        return response()->json(FacadesAuth::user());
    }

    
}
