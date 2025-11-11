<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 422);
        }

        $user = Auth::user();

        // Create token for mobile access
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user'    => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }
}
