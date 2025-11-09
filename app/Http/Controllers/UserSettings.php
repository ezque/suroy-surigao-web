<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInfo;

class UserSettings extends Controller
{
    public function getPersonalInformation()
    {
        return Auth::user()->load('userInfo');
    }
    public function updatePersonalInformation(Request $request)
    {
        $request->validate([
            'firstName' => 'sometimes|string|max:255',
            'lastName' => 'sometimes|string|max:255',
            'phone_num' => 'sometimes|string|max:20',
            'gender' => 'sometimes|string|in:male,female,other,prefer-not-to-say',
        ]);

        $user = Auth::user();

        $userInfo = UserInfo::updateOrCreate(
            ['user_ID' => $user->id],
            $request->only(['firstName', 'lastName', 'phone_num', 'gender'])
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'user_info' => $userInfo,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }

}
