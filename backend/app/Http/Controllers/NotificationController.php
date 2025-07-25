<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications
        ],200);
    }

    public function unread()
    {
        $unread = Auth::user()->unreadNotifications()->latest()->get();

        return response()->json([
            'status' => true,
            'notifications' => $unread
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Đã đánh dấu là đã đọc.'
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Đã đánh dấu tất cả là đã đọc.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đã xoá thông báo.'
        ]);
    }

     public function clearAll()
    {
        Auth::user()->notifications()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đã xoá tất cả thông báo.'
        ]);
    }
}
