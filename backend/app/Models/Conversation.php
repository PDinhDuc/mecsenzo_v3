<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Messages;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name'];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'conversation_user');
    }

    public function messages(): HasMany{
        return $this->hasMany(Messages::class);
    }
}
