<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Conversation;
use App\Models\User;

class Messages extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'sender_id', 'content'];

    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
