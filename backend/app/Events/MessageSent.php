<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Messages;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Messages $message;
    public function __construct(Messages $message)
    {
        $this->message = $message->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' .$this->message->conversation_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'user_id' => $this->message->user->id,
                'conversation_id' => $this->message->conversation_id,
                'content' => $this->message->content,
                'user' => [
                    'name' => $this->message->user->name,
                    'age' => $this->message->user->age,
                    'avatar' => $this->message->user->avatar,
                    'email' => $this->message->user->email,
                    'id' => $this->message->user->id,
                    'name' => $this->message->user->name,
                ],
                'created_at' => $this->message->created_at->toDateTimeString(),
            ]
        ];
    }
}
