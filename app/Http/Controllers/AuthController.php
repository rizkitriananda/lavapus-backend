<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

      public function register(Request $request)
    {
        $validation = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'image|mimes:jpg,png,svg',
            'role_id' => 'required|in:1,2',
            'gender' => 'required|in:Male,Female',
            'cover' => 'image|mimes:jpg,png,svg|max:2048',
        ]);

        $validation['password'] = Hash::make($validation['password']);

        if($request->hasFile('cover')){
            $file = $request->cover;

            $validation['cover'] = $file->store('UserProfile', 'public');
        }

        $user = User::create($validation);


        return response()->json([
            'status' => 'success',
            'message' => 'User registration successfully',
            'data' => $user->makeHidden('email')
        ], 201);
    }

    public function login(Request $request)
    {
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    

    if (!Auth::once($validated)) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah',
        ], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'user' => new UserResource($user),
            'token' => $token,
        ]
    ], 200);
    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }


}
