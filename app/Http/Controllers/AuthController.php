<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Auth/index');
    }
    public function loginPost(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'errors' => ['email' => ['Invalid credentials.']],
            ], 422);
        }

        if ($user->status === '2') {
            return response()->json([
                'errors' => ['email' => ['Your account is blocked. Please contact support.']],
            ], 423);
        }

        // Validate password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'errors' => ['email' => ['Invalid credentials.']],
            ], 422);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        $redirect = match ($user->role ?? 'user') {
            'admin' => '/admin-dashboard',
            'agency' => '/agency-dashboard',
            default => '/user-dashboard',
        };

        return response()->json(['redirect' => $redirect]);
    }

    public function registerPost(Request $request)
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
            DB::beginTransaction();

            $user = User::create([
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'user',
                'status'   => 1,
            ]);

            UserInfo::create([
                'user_ID'   => $user->id,
                'firstName' => $request->firstName,
                'lastName'  => $request->lastName,
                'phone_num' => $request->phone_num,
                'gender'    => $request->gender,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'User registered successfully.',
            ]);

        } catch (\Exception $e) {
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
        \Log::info('Logout route hit.');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
