<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Events\UserOnlineStatusUpdated;

class LastUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(10));
            if (is_null($user->last_active_at) || now()->diffInMinutes($user->last_active_at) >= 5) {
                $user->forceFill(['last_active_at' => now()])->save();
            }
            if(!$user->is_online){
                $user->update(['is_online' => true]);
                broadcast(new UserOnlineStatusUpdated($user));
            }
        }
        return $next($request);
    }
}
