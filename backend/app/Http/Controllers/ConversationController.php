<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Messages;

class ConversationController extends Controller
{
    public function getConversationOfUser(){
        $user = Auth::user();
        $conversations = $user->conversations()
            ->with(['users:id,name,avatar', 'messages' => function ($q) {
                $q->latest(); // lấy tin nhắn cuối cùng
            }])
            ->get();
        return response()->json($conversations);
    }

    public function getConversationWithUser($userId)
    {
        $authId = Auth::id();

        $conversation = Conversation::whereHas('users', function ($q) use ($authId) {
            $q->where('user_id', $authId);
        })->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('type', 'private')->first();

        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'private']);
            $conversation->users()->attach([$authId, $userId]);
        }

        return response()->json($conversation);
    }

    public function loadMessages(Request $request, $conversationId)
    {
        $messages = Messages::with('user')
            ->where('conversation_id', $conversationId)
            ->orderByDesc('created_at')
            ->paginate(10);
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $message = Messages::create([
            'user_id' => auth()->id(),
            'conversation_id' => $conversationId,
            'content' => $request->content
        ]);
        broadcast(new \App\Events\MessageSent($message))->toOthers();
        return response()->json([
            'message' => $message
        ], 201);
    }
}
