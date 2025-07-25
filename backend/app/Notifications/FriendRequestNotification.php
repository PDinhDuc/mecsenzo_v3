<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class FriendRequestNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $fromUser;
    protected $type;

    public function __construct($fromUser, $type = 'pending')
    {
        $this->fromUser = $fromUser;
        $this->type = $type;
    }


    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }


    public function toDatabase(object $notifiable): array
    {
        $title = match ($this->type) {
            'pending'  => 'Lời mời kết bạn mới',
            'accepted' => 'Lời mời kết bạn đã được chấp nhận',
            'declined' => 'Lời mời kết bạn đã bị từ chối',
            default    => 'Thông báo kết bạn',
        };

        $message = match ($this->type) {
            'pending'  => "{$this->fromUser->name} đã gửi lời mời kết bạn.",
            'accepted' => "{$this->fromUser->name} đã chấp nhận lời mời kết bạn của bạn.",
            'declined' => "{$this->fromUser->name} đã từ chối lời mời kết bạn.",
            default    => '',
        };

        return [
            'type'           => $this->type,
            'from_user_id'   => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'title'          => $title,
            'message'        => $message,
            'url'            => '/friends',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type'           => $this->type,
            'from_user_id'   => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'title'          => 'Lời mời kết bạn',
            'message'        => "{$this->fromUser->name} đã gửi lời mời kết bạn.",
            'url'            => '/friends',
        ]);
    }


    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable); // dùng cùng data
    }
}
