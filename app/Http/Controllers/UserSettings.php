<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Notification;

class UserSettings extends Controller
{
    public function getPersonalInformation()
    {
        return Auth::user()->load('userInfo');
    }
    public function getAgencyInformation()
    {
        return Auth::user()->load('agency');
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
    public function updateAgencyInformation(Request $request)
    {
        $request->validate([
            'agency_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'image_path' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'location_address' => 'sometimes|string|max:255',
            'contact_number' => 'sometimes|string|max:20',
        ]);

        $user = Auth::user();

        $data = $request->only(['agency_name', 'description', 'location_address', 'contact_number']);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $path = $image->store('agency_images', 'public'); // storage/app/public/agency_images
            $data['image_path'] = $path; // save path in DB
        }

        // Update or create agency info
        $agencyInfo = Agency::updateOrCreate(
            ['user_ID' => $user->id], // lowercase
            $data
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'user_info' => $agencyInfo,
            'image_url' => $agencyInfo->image_path ? asset("storage/{$agencyInfo->image_path}") : null,
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
    public function getUserNotifications(Request $request)
    {
        $userId = $request->user()->id;

        // Fetch notifications where the user is the receiver
        $notifications = Notification::where('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'sender_id', 'message', 'status', 'type', 'created_at']);

        // Format for frontend
        $formatted = $notifications->map(function ($notif) {
            return [
                'id' => $notif->id,
                'message' => $notif->message,
                'unread' => $notif->status === 'unread', // true if unread
                'type' => $notif->type,
                'time' => $notif->created_at->diffForHumans(),
            ];
        });

        return response()->json($formatted);
    }

}
