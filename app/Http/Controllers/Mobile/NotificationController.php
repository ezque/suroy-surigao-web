<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUserNotifications(Request $request)
    {
        // Get the currently authenticated user's ID
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
                'type' => $notif->type ?? 'general',
                'time' => $notif->created_at->diffForHumans(), // human-readable time
            ];
        });

        return response()->json($formatted);
    }
}
