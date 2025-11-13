<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    public function register(Request $request)
    {
        $request->validate([
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'firstName'  => 'required|string|max:100',
            'lastName'   => 'required|string|max:100',
            'phone_num'  => 'nullable|string|max:20',
            'gender'     => 'nullable|in:male,female',
        ]);

        try {
            // Start transaction
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'user',
                'status'   => 1,
            ]);

            // Create user info
            UserInfo::create([
                'user_ID'   => $user->id,
                'firstName' => $request->firstName,
                'lastName'  => $request->lastName,
                'phone_num' => $request->phone_num,
                'gender'    => $request->gender,
            ]);

            // Commit both if successful
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'User registered successfully.',
            ]);

        } catch (\Exception $e) {
            // Rollback everything if any error occurs
            DB::rollBack();
            Log::error("Registration failed: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong while registering the user.',
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

}
