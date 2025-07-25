<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Messages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationUserAndMessagesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $conversations = Conversation::all();

        foreach ($conversations as $conversation) {
            if ($conversation->type === 'private') {
                // Lấy 2 user khác nhau cho private chat
                $pair = collect($users)->random(2);

                foreach ($pair as $userId) {
                    DB::table('conversation_user')->insert([
                        'conversation_id' => $conversation->id,
                        'user_id' => $userId,
                    ]);
                }

                // Tạo khoảng 10 tin nhắn xen kẽ giữa 2 người
                for ($i = 0; $i < 10; $i++) {
                    Messages::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $pair[$i % 2], // xen kẽ giữa 2 người
                        'content' => fake()->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

            } else {
                // Group conversation: 3-5 người
                $groupUserIds = collect($users)->random(rand(3, 5))->values();

                foreach ($groupUserIds as $userId) {
                    DB::table('conversation_user')->insert([
                        'conversation_id' => $conversation->id,
                        'user_id' => $userId,
                    ]);
                }

                // Tạo 15–25 tin nhắn từ random user trong nhóm
                $messageCount = rand(15, 25);
                for ($i = 0; $i < $messageCount; $i++) {
                    Messages::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $groupUserIds->random(),
                        'content' => fake()->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
