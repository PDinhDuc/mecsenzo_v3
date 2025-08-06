<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return true; // hoặc xác thực quyền user với cuộc trò chuyện
});

Broadcast::channel('friend-request.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation-user-status', function ($user) {
    return true;
});