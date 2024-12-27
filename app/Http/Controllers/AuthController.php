<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

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


        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
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

}
