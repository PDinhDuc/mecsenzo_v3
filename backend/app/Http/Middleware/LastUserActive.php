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
        if (Auth::check() && !in_array($request->route()->getName(), ['logout'])) {
            $user = Auth::user();
            $wasOnline = Cache::has('user-is-online-' . $user->id);
            $expiresAt = now()->addMinutes(3);
            Cache::put('user-is-online-' . Auth::id(), true, $expiresAt);
            if(!$wasOnline){
                broadcast(new UserOnlineStatusUpdated($user, true));
            }
        }
        return $next($request);
    }
}
