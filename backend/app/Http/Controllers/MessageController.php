<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Messages::query()->with('user');
        
        if($request->has('conversation_id')){
            $query->where('conversation_id', $request->conversation_id);
        }

        return response()->json($query->latest()->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:1000'
        ]);

        $message = Messages::create([
            'user_id' => auth()->id(),
            'conversation_id' => $request->conversation_id,
            'content' => $request->content
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json([
            'message' => $message->load('user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
       return response()->json($message->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message); // nếu dùng policy

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message->update([
            'content' => $request->content,
        ]);

        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $message); // nếu dùng policy

        $message->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
