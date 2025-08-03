<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Messages;

class ConversationController extends Controller
{
    public function getConversationIndividual(){
        $user = Auth::user();
        $conversations = $user->conversations()->where('type','private')
            ->with(['users:id,name,avatar', 'messages' => function ($q) {
                $q->latest(); // lấy tin nhắn cuối cùng
            }])->paginate(2);
        $mapped = $conversations->map(function ($conversation) use ($user) {
            $partner = $conversation->users->where('id', '!=', $user->id)->values();

            return [
                'id' => $conversation->id,
                'type' => $conversation->type,
                'last_message' => $conversation->messages->first(),
                'partner' => $partner[0],
            ];
        });
        return response()->json($mapped);
    }

    public function getConversationSpace(){
        $user = Auth::user();
        $conversations = $user->conversations()->where('type','group')
            ->with(['users:id,name,avatar', 'messages' => function ($q) {
                $q->latest();
            }])->paginate(1);

        $mapped = $conversations->map(function ($conversation) use ($user) {
            $partner = $conversation->users->where('id', '!=', $user->id)->values();
            return [
                'id' => $conversation->id,
                'type' => $conversation->type,
                'last_message' => $conversation->messages->first(),
                'partner' => $partner,
                'name' => $conversation->name,
            ];
        });
        return response()->json($mapped);
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

    public function getInforConversation($conversationId){
        $conversation = Conversation::with('users')->find($conversationId);
        if(!$conversation){
            return response()->json([
                'message' => 'Conversatin not found'
            ], 404);
        }
        $conversation->users->transform(function ($user) {
            $user->is_online = cache()->has('user-is-online-' . $user->id);
            return $user;
        });
        return response()->json($conversation,200);
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
            'sender_id' => Auth::id(),
            'conversation_id' => $conversationId,
            'content' => $request->content
        ]);
        broadcast(new \App\Events\MessageSent($message))->toOthers();
        return response()->json([
            'message' => $message
        ], 201);
    }
}
