<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Events\UserOnlineStatusUpdated;

class DetectUserOffline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::all()->each(function ($user) {
            $isOnline = Cache::has('user-is-online-' . $user->id);
            if (!$isOnline) {
                broadcast(new UserOnlineStatusUpdated($user, false));
            }
        });
    }
}
