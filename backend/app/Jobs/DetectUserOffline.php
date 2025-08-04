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
        User::where('is_online', true)
            ->chunk(500, function ($users) {
                foreach ($users as $user) {
                    $online = Cache::get('user-is-online-' . $user->id, false);

                    if ((bool)$user->is_online !== $online) {
                        $user->update(['is_online' => $online]);
                        broadcast(new UserOnlineStatusUpdated($user));
                    }
                }
        });
    }
}
