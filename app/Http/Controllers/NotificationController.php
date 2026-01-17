<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $notifications = $user->unreadNotifications()->take(10)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'data' => $n->data,
                'created_at' => $n->created_at->diffForHumans()
            ];
        });

        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $notifications
        ]);
    }

    public function markRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Return URL to redirect
        return response()->json(['url' => $notification->data['url'] ?? '#']);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
