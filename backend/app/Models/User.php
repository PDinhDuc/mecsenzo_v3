<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Friendships;
use App\Models\Conversation;
use App\Models\Messages;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_online', 
        'last_active_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function friends(): BelongsToMany{
        return $this->belongsToMany(User::class, 'friendships',
         'user_id','friend_id')->wherePivot('status','accepted');
    }

    public function friendRequest():BelongsToMany {
        return $this->belongsToMany(User::class,'friendships',
        'friend_id', 'user_id')->wherePivot('status','pending');
    }

    public function conversations(): BelongsToMany{
        return $this->belongsToMany(Conversation::class, 'conversation_user');
    }

    public function message(): HasMany{
        return $this->hasMany(Messages::class);
    }

    public function getIsOnlineAttribute()
{
    return Cache::has('user-is-online-' . $this->id);
}
}
