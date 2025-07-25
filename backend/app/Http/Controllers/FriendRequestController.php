<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Friendships;
use App\Models\Conversation;
use App\Events\FriendRequestSent;
use App\Notifications\FriendRequestNotification;

class FriendRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function sendRequest(Request $request){
        
        $user = Auth::user();
        $toUser = User::findOrFail($request->friend_id);
        event(new FriendRequestSent($user, $request->friend_id));
    
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        if($user->id === (int)$request->friend_id){
            return response().json([
                'message' => 'Bạn không thể kết bạn với chính mình.'
            ],400);
        }

        $existing = Friendships::where(function ($query) use ($user, $request){
            $query->where('user_id', $user->id)
                ->where('friend_id', $request->friend_id);    
        })->orWhere(function ($query) use ($user, $request){
            $query->where('user_id', $request->friend_id)
                ->where('friend_id',$user->id);
        })->first();

        if($existing){
            return response()->json([
                'message' => 'Đã tồn tại lời mời kết bạn.'
            ],400);
        }

        $toUser->notify(new FriendRequestNotification($user, 'request'));

        $friendship = Friendships::create([
            'user_id' => $user->id,
            'friend_id' => $request->friend_id,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Đã gửi lời mời kết bạn.',
            'friendship' => $friendship
        ]);
    }

    public function acceptRequest($id)
    {
        $friendship = Friendships::where(function ($query) use ($id) {
            $query->where('user_id', $id)
              ->where('friend_id', Auth::id());
        })
        ->orWhere(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                ->where('friend_id', $id);
        })->where('status', 'pending')->firstOrFail();

        $friendship->update(['status' => 'accepted']);

        $userId = Auth::id();
        $friendId = $id;

        $existingConversation = Conversation::where('type', 'private')
            ->whereHas('users', function ($q) use ($userId){
                $q->where('user_id', $userId);
            })->whereHas('users', function ($q) use ($friendId){
                $q->where('user_id', $friendId);
            })->first();

        if(!$existingConversation){
            $conversation = Conversation::create([
                'type' => 'private'
            ]);
            DB::table('conversation_user')->insert([
                [
                    'conversation_id' => $conversation->id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conversation_id' => $conversation->id,
                    'user_id' => $friendId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }else{
            $conversation = $existingConversation;
        }

        $fromUser = User::findOrFail($friendship->user_id);
        $acceptedBy = Auth::user();
        $fromUser->notify(new FriendRequestNotification($acceptedBy, 'accepted'));

        return response()->json(['message' => 'Đã chấp nhận lời mời kết bạn.']);
    }

    public function declineRequest($id)
    {
        $friendship = Friendships::where('id', $id)
                                ->where('friend_id', Auth::id())
                                ->where('status', 'pending')
                                ->firstOrFail();

        $fromUser = User::findOrFail($friendship->user_id); // người gửi lời mời
        $acceptedBy = Auth::user(); // người đồng ý lời mời
        $fromUser->notify(new FriendRequestNotification($acceptedBy, 'declined'));

        $friendship->update(['status' => 'declined']);

        return response()->json(['message' => 'Đã từ chối lời mời kết bạn.']);
    }

    public function receivedRequests()
    {
        $requests = Friendships::with('user')
                              ->where('friend_id', Auth::id())
                              ->where('status', 'pending')
                              ->get();

        return response()->json($requests);
    }
}
