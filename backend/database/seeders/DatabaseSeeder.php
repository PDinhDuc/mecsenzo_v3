<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FriendShips;
use App\Models\Conversation;
use App\Models\Messages;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call(FriendshipSeeder::class);
        Conversation::factory(10)->create();
        $this->call([ConversationUserAndMessagesSeeder::class,]);
        $this->call(UserSeeder::class);
    }
}
